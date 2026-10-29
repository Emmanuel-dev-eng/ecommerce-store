<?php
// ============================================
// order-confirmation.php
// Shows a real order, read from the database.
// ============================================

require 'config/auth_check.php';
require 'config/db.php';
require 'config/helpers.php';

$userId = (int)$_SESSION['user_id'];
$orderNumber = trim($_GET['order'] ?? '');

// Find this order, but ONLY if it belongs to the logged-in user
$orderStmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
$orderStmt->execute([$orderNumber, $userId]);
$order = $orderStmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: 404.php");
    exit;
}

// The items in this order
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER ===================== -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight">ShopName</a>
            <a href="account.php" class="text-gray-700 hover:text-black">
                <i data-lucide="user" class="w-5 h-5"></i>
            </a>
        </div>
    </div>
</header>

<main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <!-- ===================== SUCCESS BANNER ===================== -->
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <i data-lucide="check" class="w-8 h-8 text-green-600"></i>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Order Placed!</h1>
        <p class="text-sm text-gray-500">
            Thank you, <?= htmlspecialchars($order['customer_name']) ?>. We'll call
            <span class="font-medium text-gray-900"><?= htmlspecialchars($order['phone']) ?></span>
            to confirm your order before it ships.
        </p>
    </div>

    <!-- ===================== STEP PROGRESS ===================== -->
    <div class="flex items-center justify-center gap-2 mb-10 text-xs font-medium">
        <div class="flex items-center gap-2 text-gray-900">
            <span class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></span>
            Cart
        </div>
        <div class="w-8 h-px bg-gray-300"></div>
        <div class="flex items-center gap-2 text-gray-900">
            <span class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></span>
            Checkout
        </div>
        <div class="w-8 h-px bg-gray-300"></div>
        <div class="flex items-center gap-2 text-gray-900">
            <span class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></span>
            Confirmation
        </div>
    </div>

    <!-- ===================== ORDER DETAILS ===================== -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-5 pb-5 border-b border-gray-100">
            <div>
                <p class="text-xs text-gray-500">Order Number</p>
                <p class="text-sm font-bold text-gray-900">#<?= htmlspecialchars($order['order_number']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Order Date</p>
                <p class="text-sm font-bold text-gray-900"><?= date('F j, Y', strtotime($order['created_at'])) ?></p>
            </div>
        </div>

        <div class="space-y-4 mb-5 pb-5 border-b border-gray-100">
            <?php foreach ($items as $item): ?>
                <?php
                $label = isset($labels[$item['variant_id']])
                    ? implode(', ', $labels[$item['variant_id']])
                    : $item['sku'];
                ?>
                <div class="flex items-center gap-4">
                    <img src="<?= htmlspecialchars($item['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($item['name']) ?>"
                         class="w-14 h-14 rounded-lg object-cover flex-shrink-0 bg-gray-100">
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

        <div class="space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>$<?= number_format($order['subtotal'], 2) ?></span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span><?= $order['shipping_cost'] == 0 ? 'Free' : '$' . number_format($order['shipping_cost'], 2) ?></span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Tax</span>
                <span>$<?= number_format($order['tax'], 2) ?></span>
            </div>
            <div class="flex justify-between font-bold text-gray-900 text-base pt-2 border-t border-gray-100">
                <span><?= $order['payment_method'] === 'cod' ? 'Total to pay on delivery' : 'Total paid' ?></span>
                <span>$<?= number_format($order['total_amount'], 2) ?></span>
            </div>
        </div>
    </div>

    <!-- ===================== SHIPPING + PAYMENT ===================== -->
    <div class="grid sm:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                <i data-lucide="truck" class="w-4 h-4"></i> Shipping To
            </h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                <?= htmlspecialchars($order['customer_name']) ?><br>
                <?= htmlspecialchars($order['shipping_address']) ?>
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                <i data-lucide="wallet" class="w-4 h-4"></i> Payment
            </h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                <?= $order['payment_method'] === 'cod' ? 'Cash on Delivery' : 'Paystack' ?><br>
                <span class="text-xs text-gray-400">Status: <?= ucfirst(htmlspecialchars($order['payment_status'])) ?></span>
            </p>
        </div>
    </div>

    <!-- ===================== ACTIONS ===================== -->
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="orders.php" class="flex-1 text-center border border-gray-300 text-gray-900 font-semibold py-3 rounded-lg hover:bg-gray-50">
            View My Orders
        </a>
        <a href="shop.php" class="flex-1 text-center bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800">
            Continue Shopping
        </a>
    </div>

</main>

<script>
    lucide.createIcons();
</script>

</body>
</html>