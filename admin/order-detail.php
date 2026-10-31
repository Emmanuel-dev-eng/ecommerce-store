<?php
// ============================================
// admin/order-detail.php
// One order in full: customer, items, payment, status, private notes.
// ============================================

require '../config/admin_auth_check.php';
require '../config/db.php';
require '../config/helpers.php';

$orderNumber = is_string($_GET['order'] ?? null) ? trim($_GET['order']) : '';

// Admins can open any order, so there's no user_id condition here
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ?");
$stmt->execute([$orderNumber]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: orders.php");
    exit;
}

$selfUrl = "order-detail.php?order=" . urlencode($orderNumber);

// ---- Handle form submissions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_status') {
        $newStatus = is_string($_POST['status'] ?? null) ? $_POST['status'] : '';
        // We use the ID we loaded from the URL, never one sent by the form
        $error = updateOrderStatus($pdo, (int)$order['id'], $newStatus);

        $_SESSION['flash'] = ($error === '')
            ? ['type' => 'success', 'text' => 'Order status updated.']
            : ['type' => 'error',   'text' => $error];

    } elseif ($action === 'save_note') {
        $note = is_string($_POST['notes'] ?? null) ? trim($_POST['notes']) : '';
        $note = mb_substr($note, 0, 2000); // cap the length

        $noteStmt = $pdo->prepare("UPDATE orders SET admin_notes = ? WHERE id = ?");
        $noteStmt->execute([$note, $order['id']]);

        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Note saved.'];
    }

    header("Location: " . $selfUrl);
    exit;
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// ---- Items in this order ----
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

$next = allowedNextStatuses($order['status']);

// Keep only digits and "+" so the phone works as a tel: link
$telNumber = preg_replace('/[^0-9+]/', '', $order['phone']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order <?= htmlspecialchars($order['order_number']) ?> | ShopName Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<div class="flex min-h-screen">

    <?php $active = 'orders'; require __DIR__ . '/partials/sidebar.php'; ?>

    <!-- ===================== MAIN AREA ===================== -->
    <div class="flex-1 flex flex-col min-w-0">

        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <button id="adminMenuToggle" class="lg:hidden text-gray-700">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <a href="orders.php" class="text-gray-500 hover:text-black flex items-center gap-1 text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                </a>
                <h1 class="text-lg font-bold text-gray-900">Order #<?= htmlspecialchars($order['order_number']) ?></h1>
            </div>
            <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6">

            <?php if ($flash): ?>
                <div class="mb-5 text-sm rounded-lg px-4 py-3 border <?= $flash['type'] === 'success'
                        ? 'bg-green-50 border-green-200 text-green-700'
                        : 'bg-red-50 border-red-200 text-red-600' ?>">
                    <?= htmlspecialchars($flash['text']) ?>
                </div>
            <?php endif; ?>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <p class="text-sm text-gray-500">
                    Placed on <?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?> ·
                    <?= count($items) ?> product<?= count($items) == 1 ? '' : 's' ?>
                </p>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full <?= $statusStyles[$order['status']] ?? '' ?>">
                        <?= ucfirst($order['status']) ?>
                    </span>

                    <?php if (!empty($next)): ?>
                        <form method="POST" class="flex items-center gap-2" onsubmit="return confirmStatus(this);">
                            <input type="hidden" name="action" value="update_status">
                            <select name="status" required
                                class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-black">
                                <option value="" selected disabled>Change to…</option>
                                <?php foreach ($next as $s): ?>
                                    <option value="<?= $s ?>"><?= ucfirst($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="bg-gray-900 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-black">Save</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">

                <!-- LEFT: items, payment, notes -->
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-4">Items</h2>
                        <div class="space-y-4 mb-4 pb-4 border-b border-gray-100">
                            <?php foreach ($items as $item): ?>
                                <?php
                                $label = isset($labels[$item['variant_id']])
                                    ? implode(', ', $labels[$item['variant_id']])
                                    : $item['sku'];
                                ?>
                                <div class="flex items-center gap-4">
                                    <img src="<?= htmlspecialchars($item['image_url'] ?? '') ?>" alt=""
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
                            <div class="flex justify-between text-gray-600"><span>Subtotal</span><span>$<?= number_format($order['subtotal'], 2) ?></span></div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span><?= $order['shipping_cost'] == 0 ? 'Free' : '$' . number_format($order['shipping_cost'], 2) ?></span>
                            </div>
                            <div class="flex justify-between text-gray-600"><span>Tax</span><span>$<?= number_format($order['tax'], 2) ?></span></div>
                            <div class="flex justify-between font-bold text-gray-900 pt-2 border-t border-gray-100">
                                <span>Total</span><span>$<?= number_format($order['total_amount'], 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-3">Payment</h2>
                        <?php if ($order['payment_status'] === 'paid'): ?>
                            <div class="flex items-center gap-3 border border-gray-200 rounded-xl p-4">
                                <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        <?= $order['payment_method'] === 'cod' ? 'Cash collected on delivery' : 'Paid via Paystack' ?>
                                    </p>
                                    <p class="text-xs text-gray-500">Payment complete. No action needed.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center gap-3 border border-gray-200 rounded-xl p-4">
                                <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="banknote" class="w-5 h-5 text-orange-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Cash on Delivery: unpaid</p>
                                    <p class="text-xs text-gray-500">Call the customer to confirm before shipping. Marking the order Delivered records the cash as collected.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Private notes -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-3">Internal Notes</h2>
                        <form method="POST">
                            <input type="hidden" name="action" value="save_note">
                            <textarea name="notes" rows="3" maxlength="2000"
                                placeholder="e.g. Called customer, confirmed delivery for Friday..."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black resize-none"><?= htmlspecialchars($order['admin_notes'] ?? '') ?></textarea>
                            <button type="submit" class="mt-3 bg-gray-900 text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-black">
                                Save Note
                            </button>
                        </form>
                    </div>
                </div>

                <!-- RIGHT: customer + address -->
                <div class="lg:col-span-1 space-y-6">

                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4"></i> Customer
                        </h2>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
                                <?= strtoupper(substr($order['customer_name'], 0, 1)) ?>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($order['customer_name']) ?></p>
                                <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($order['customer_email']) ?></p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <a href="tel:<?= htmlspecialchars($telNumber) ?>"
                               class="w-full bg-black text-white text-sm font-semibold py-3 rounded-lg hover:bg-gray-800 flex items-center justify-center gap-2">
                                <i data-lucide="phone-call" class="w-4 h-4"></i>
                                Call <?= htmlspecialchars($order['phone']) ?>
                            </a>
                            <a href="mailto:<?= htmlspecialchars($order['customer_email']) ?>"
                               class="w-full border border-gray-300 text-gray-700 text-sm font-semibold py-3 rounded-lg hover:bg-gray-50 flex items-center justify-center gap-2">
                                <i data-lucide="mail" class="w-4 h-4"></i> Email Customer
                            </a>
                        </div>
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
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    lucide.createIcons();

    function confirmStatus(form) {
        if (form.status.value === 'cancelled') {
            return confirm('Cancel this order? The stock will be returned to inventory.');
        }
        return true;
    }
</script>

</body>
</html>