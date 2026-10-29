<?php
// ============================================
// order-detail.php
// One real order, plus the ability to cancel it while it's pending.
// ============================================

require 'config/auth_check.php';
require 'config/db.php';
require 'config/cart_count.php';
require 'config/helpers.php';

$userId = (int)$_SESSION['user_id'];
$orderNumber = trim($_GET['order'] ?? '');

// ---- Handle "Cancel Order" ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'cancel') {

    try {
        $pdo->beginTransaction();

        // Lock this order row so nothing else can change it mid-cancel.
        // "AND user_id = ?" means customers can only cancel THEIR OWN orders.
        $lockStmt = $pdo->prepare("SELECT id, status FROM orders WHERE order_number = ? AND user_id = ? FOR UPDATE");
        $lockStmt->execute([$orderNumber, $userId]);
        $target = $lockStmt->fetch(PDO::FETCH_ASSOC);

        // Only a pending order can be cancelled by the customer.
        if ($target && $target['status'] === 'pending') {

            $cancelStmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $cancelStmt->execute([$target['id']]);

            // Put the stock back: add each cancelled quantity back to its variant.
            // This is a multi-table UPDATE (it joins order_items to product_variants).
            $restoreStmt = $pdo->prepare("
                UPDATE product_variants pv
                JOIN order_items oi ON oi.variant_id = pv.id
                SET pv.stock_quantity = pv.stock_quantity + oi.quantity
                WHERE oi.order_id = ?
            ");
            $restoreStmt->execute([$target['id']]);
        }

        $pdo->commit();

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log($e->getMessage());
    }

    // Post/Redirect/Get, with a flag so we can show a confirmation banner
    header("Location: order-detail.php?order=" . urlencode($orderNumber) . "&cancelled=1");
    exit;
}

// ---- Load the order (only if it belongs to this user) ----
$orderStmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
$orderStmt->execute([$orderNumber, $userId]);
$order = $orderStmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: 404.php");
    exit;
}

// ---- Load its items ----
$itemsStmt = $pdo->prepare("
    SELECT oi.variant_id, oi.quantity, oi.price_at_purchase, pv.sku, p.name, pi.image_url
    FROM order_items oi
    JOIN product_variants pv ON oi.variant_id = pv.id
    JOIN products p ON pv.product_id = p.id
    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE oi.order_id = ?
");
$itemsStmt->execute([$order['id']]);
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

$labels = variantLabels($pdo, array_column($items, 'variant_id'));

$statusStyles = [
    'pending'    => 'bg-gray-100 text-gray-600',
    'processing' => 'bg-yellow-50 text-yellow-700',
    'shipped'    => 'bg-blue-50 text-blue-600',
    'delivered'  => 'bg-green-50 text-green-600',
    'cancelled'  => 'bg-red-50 text-red-600',
];

// The tracking steps, in order
$steps = [
    'pending'    => ['Order Placed', 'circle-check'],
    'processing' => ['Processing', 'package'],
    'shipped'    => ['Shipped', 'truck'],
    'delivered'  => ['Delivered', 'home'],
];
$stepKeys = array_keys($steps);
$currentIndex = array_search($order['status'], $stepKeys, true); // false if cancelled
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order <?= htmlspecialchars($order['order_number']) ?> | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER ===================== -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight">ShopName</a>
            <div class="flex items-center gap-4">
                <a href="cart.php" class="relative text-gray-700 hover:text-black">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-black text-white text-[10px] font-bold rounded-full min-w-4 h-4 px-1 flex items-center justify-center"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="account.php" class="text-gray-700 hover:text-black">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a href="orders.php" class="text-xs text-gray-500 hover:text-black flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Orders
        </a>
    </div>

    <?php if (isset($_GET['cancelled'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3 mb-6">
            Your request was processed. See the order status below.
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Order #<?= htmlspecialchars($order['order_number']) ?></h1>
            <p class="text-sm text-gray-500 mt-1">
                Placed on <?= date('F j, Y', strtotime($order['created_at'])) ?> ·
                <?= count($items) ?> product<?= count($items) == 1 ? '' : 's' ?>
            </p>
        </div>
        <span class="text-xs font-semibold px-3 py-1.5 rounded-full flex-shrink-0 <?= $statusStyles[$order['status']] ?? '' ?>">
            <?= ucfirst($order['status']) ?>
        </span>
    </div>

    <!-- ===================== TRACKING ===================== -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="text-sm font-bold text-gray-900 mb-6">Order Tracking</h2>

        <?php if ($order['status'] === 'cancelled'): ?>
            <div class="flex items-center gap-3 text-red-600">
                <i data-lucide="x-circle" class="w-5 h-5"></i>
                <p class="text-sm font-medium">This order was cancelled.</p>
            </div>
        <?php else: ?>
            <div class="relative">
                <?php $i = 0; foreach ($steps as $key => [$label, $icon]): $done = $i <= $currentIndex; ?>
                    <div class="flex items-start gap-4 pb-6 last:pb-0 relative">
                        <?php if ($i < count($steps) - 1): ?>
                            <span class="absolute left-4 top-8 w-px h-full <?= $done ? 'bg-black' : 'bg-gray-200' ?>"></span>
                        <?php endif; ?>
                        <span class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 z-10 <?= $done ? 'bg-black text-white' : 'bg-gray-100 text-gray-400' ?>">
                            <i data-lucide="<?= $icon ?>" class="w-4 h-4"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold <?= $done ? 'text-gray-900' : 'text-gray-400' ?>"><?= $label ?></p>
                            <?php if ($i === $currentIndex): ?>
                                <p class="text-xs text-gray-500 mt-0.5">Current status</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        <!-- ===================== ITEMS ===================== -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-sm font-bold text-gray-900 mb-4">Items</h2>
                <div class="space-y-4">
                    <?php foreach ($items as $item): ?>
                        <?php
                        $label = isset($labels[$item['variant_id']])
                            ? implode(', ', $labels[$item['variant_id']])
                            : $item['sku'];
                        ?>
                        <div class="flex items-center gap-4">
                            <img src="<?= htmlspecialchars($item['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($item['name']) ?>"
                                 class="w-16 h-16 rounded-xl object-cover flex-shrink-0 bg-gray-100">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($item['name']) ?></p>
                                <p class="text-xs text-gray-500"><?= htmlspecialchars($label) ?> · Qty <?= (int)$item['quantity'] ?></p>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 flex-shrink-0">
                                $<?= number_format($item['price_at_purchase'] * $item['quantity'], 2) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ===================== SUMMARY + ADDRESS ===================== -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-sm font-bold text-gray-900 mb-4">Summary</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span><span>$<?= number_format($order['subtotal'], 2) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span><?= $order['shipping_cost'] == 0 ? 'Free' : '$' . number_format($order['shipping_cost'], 2) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Tax</span><span>$<?= number_format($order['tax'], 2) ?></span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-900 pt-2 border-t border-gray-100">
                        <span>Total</span><span>$<?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">
                    <?= $order['payment_method'] === 'cod' ? 'Cash on Delivery' : 'Paystack' ?> ·
                    <?= ucfirst(htmlspecialchars($order['payment_status'])) ?>
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> Shipping Address
                </h2>
                <p class="text-sm text-gray-600 leading-relaxed">
                    <?= htmlspecialchars($order['customer_name']) ?><br>
                    <?= htmlspecialchars($order['shipping_address']) ?><br>
                    <?= htmlspecialchars($order['phone']) ?>
                </p>
            </div>

            <?php if ($order['status'] === 'pending'): ?>
                <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                    <input type="hidden" name="action" value="cancel">
                    <button type="submit" class="w-full border border-red-200 text-red-600 text-sm font-semibold py-3 rounded-lg hover:bg-red-50">
                        Cancel Order
                    </button>
                </form>
            <?php elseif ($order['status'] !== 'cancelled'): ?>
                <p class="text-xs text-gray-400 text-center">
                    This order can no longer be cancelled online. Please <a href="contact.php" class="underline">contact us</a>.
                </p>
            <?php endif; ?>
        </div>
    </div>

</main>

<script>
    lucide.createIcons();
</script>

</body>
</html>