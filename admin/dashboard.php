<?php
// ============================================
// admin/dashboard.php
// Real numbers from the database: revenue, orders, customers,
// low stock, and the most recent orders.
// ============================================

require '../config/admin_auth_check.php'; // starts the session, admins only
require '../config/db.php';
require '../config/helpers.php';

// A variant counts as "low stock" at or below this many units
const LOW_STOCK_THRESHOLD = 5;

// ---- 1) Order statistics: ONE query returns every number we need ----
// No user input is involved, so query() is safe here.
$orderStats = $pdo->query("
    SELECT
        COUNT(*) AS orders_total,
        COALESCE(SUM(DATE(created_at) = CURDATE()), 0) AS orders_today,
        COALESCE(SUM(status = 'pending'), 0) AS pending_count,
        COALESCE(SUM(CASE WHEN payment_status = 'paid' AND status <> 'cancelled'
                          THEN total_amount END), 0) AS revenue_total,
        COALESCE(SUM(CASE WHEN payment_status = 'paid' AND status <> 'cancelled'
                           AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
                          THEN total_amount END), 0) AS revenue_month,
        COALESCE(SUM(CASE WHEN payment_status = 'unpaid' AND status <> 'cancelled'
                          THEN total_amount END), 0) AS awaiting_payment
    FROM orders
")->fetch(PDO::FETCH_ASSOC);

$ordersTotal     = (int)$orderStats['orders_total'];
$ordersToday     = (int)$orderStats['orders_today'];
$pendingCount    = (int)$orderStats['pending_count'];
$revenueTotal    = (float)$orderStats['revenue_total'];
$revenueMonth    = (float)$orderStats['revenue_month'];
$awaitingPayment = (float)$orderStats['awaiting_payment'];

// ---- 2) Customers ----
$customerStats = $pdo->query("
    SELECT
        COUNT(*) AS customers_total,
        COALESCE(SUM(created_at >= NOW() - INTERVAL 7 DAY), 0) AS new_this_week
    FROM users
    WHERE role = 'customer'
")->fetch(PDO::FETCH_ASSOC);

$customersTotal = (int)$customerStats['customers_total'];
$newThisWeek    = (int)$customerStats['new_this_week'];

// ---- 3) Low stock ----
$lowCountStmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM product_variants pv
    JOIN products p ON pv.product_id = p.id
    WHERE p.is_active = 1 AND pv.stock_quantity <= ?
");
$lowCountStmt->execute([LOW_STOCK_THRESHOLD]);
$lowStockCount = (int)$lowCountStmt->fetchColumn();

// The 6 lowest-stocked variants, emptiest first
$lowStmt = $pdo->prepare("
    SELECT pv.id AS variant_id, pv.sku, pv.stock_quantity, p.id AS product_id, p.name
    FROM product_variants pv
    JOIN products p ON pv.product_id = p.id
    WHERE p.is_active = 1 AND pv.stock_quantity <= ?
    ORDER BY pv.stock_quantity ASC, p.name ASC
    LIMIT 6
");
$lowStmt->execute([LOW_STOCK_THRESHOLD]);
$lowStock = $lowStmt->fetchAll(PDO::FETCH_ASSOC);

$lowLabels = variantLabels($pdo, array_column($lowStock, 'variant_id'));

// ---- 4) Five most recent orders ----
$recentOrders = $pdo->query("
    SELECT order_number, customer_name, status, total_amount, created_at
    FROM orders
    ORDER BY created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$statusStyles = [
    'pending'    => 'bg-gray-100 text-gray-600',
    'processing' => 'bg-yellow-50 text-yellow-700',
    'shipped'    => 'bg-blue-50 text-blue-600',
    'delivered'  => 'bg-green-50 text-green-600',
    'cancelled'  => 'bg-red-50 text-red-600',
];

$firstName = explode(' ', trim($_SESSION['user_name'] ?? 'Admin'))[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<div class="flex min-h-screen">

    <?php $active = 'dashboard'; require __DIR__ . '/partials/sidebar.php'; ?>

    <!-- ===================== MAIN AREA ===================== -->
    <div class="flex-1 flex flex-col min-w-0">

        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <button id="adminMenuToggle" class="lg:hidden text-gray-700">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="text-lg font-bold text-gray-900">Dashboard</h1>
            </div>
            <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">
                <?= strtoupper(substr($firstName, 0, 1)) ?>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6">

            <p class="text-sm text-gray-500 mb-5">Welcome back, <span class="font-medium text-gray-900"><?= htmlspecialchars($firstName) ?></span>.</p>

            <?php if ($pendingCount > 0): ?>
                <a href="orders.php?status=pending"
                   class="flex items-center justify-between bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl px-4 py-3 mb-6 text-sm hover:bg-yellow-100">
                    <span class="flex items-center gap-2">
                        <i data-lucide="phone-call" class="w-4 h-4"></i>
                        <span><strong><?= $pendingCount ?></strong> order<?= $pendingCount == 1 ? '' : 's' ?> waiting to be confirmed</span>
                    </span>
                    <span class="font-semibold">Review &rarr;</span>
                </a>
            <?php endif; ?>

            <!-- ===================== STAT CARDS ===================== -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

                <a href="orders.php" class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-gray-300 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">Revenue (collected)</span>
                        <i data-lucide="dollar-sign" class="w-4 h-4 text-green-500"></i>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">$<?= number_format($revenueTotal, 2) ?></p>
                    <p class="text-xs text-gray-500 mt-1">$<?= number_format($revenueMonth, 2) ?> this month</p>
                    <p class="text-[11px] text-orange-500 mt-0.5">$<?= number_format($awaitingPayment, 2) ?> awaiting payment</p>
                </a>

                <a href="orders.php" class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-gray-300 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">Total Orders</span>
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-blue-500"></i>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900"><?= $ordersTotal ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?= $ordersToday ?> today</p>
                </a>

                <a href="customers.php" class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-gray-300 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">Customers</span>
                        <i data-lucide="users" class="w-4 h-4 text-purple-500"></i>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900"><?= $customersTotal ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?= $newThisWeek ?> new this week</p>
                </a>

                <a href="products.php" class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-gray-300 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">Low Stock</span>
                        <i data-lucide="alert-triangle" class="w-4 h-4 <?= $lowStockCount > 0 ? 'text-red-500' : 'text-green-500' ?>"></i>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900"><?= $lowStockCount ?></p>
                    <p class="text-xs mt-1 <?= $lowStockCount > 0 ? 'text-red-500' : 'text-green-600' ?>">
                        <?= $lowStockCount > 0 ? 'Needs attention' : 'All stocked up' ?>
                    </p>
                </a>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">

                <!-- ===================== RECENT ORDERS ===================== -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-900">Recent Orders</h2>
                        <a href="orders.php" class="text-xs font-medium text-gray-500 hover:text-black">View all</a>
                    </div>

                    <?php if (empty($recentOrders)): ?>
                        <p class="text-sm text-gray-400 text-center py-8">No orders yet. They'll appear here as customers check out.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto -mx-2">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                                        <th class="pb-3 px-2 font-medium">Order</th>
                                        <th class="pb-3 px-2 font-medium">Customer</th>
                                        <th class="pb-3 px-2 font-medium">Status</th>
                                        <th class="pb-3 px-2 font-medium text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php foreach ($recentOrders as $o): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-2 font-medium text-gray-900 whitespace-nowrap">
                                                <a href="order-detail.php?order=<?= urlencode($o['order_number']) ?>" class="hover:underline">
                                                    #<?= htmlspecialchars($o['order_number']) ?>
                                                </a>
                                                <p class="text-[11px] font-normal text-gray-400"><?= date('M j, g:i A', strtotime($o['created_at'])) ?></p>
                                            </td>
                                            <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($o['customer_name']) ?></td>
                                            <td class="py-3 px-2">
                                                <span class="text-[11px] font-semibold px-2 py-1 rounded-full <?= $statusStyles[$o['status']] ?? '' ?>">
                                                    <?= ucfirst($o['status']) ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-2 text-right font-semibold text-gray-900">$<?= number_format($o['total_amount'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ===================== LOW STOCK ALERTS ===================== -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6">
                    <h2 class="text-sm font-bold text-gray-900 mb-1">Low Stock Alerts</h2>
                    <p class="text-xs text-gray-400 mb-4"><?= LOW_STOCK_THRESHOLD ?> units or fewer</p>

                    <?php if (empty($lowStock)): ?>
                        <p class="text-sm text-gray-400 text-center py-6">Nothing is running low.</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($lowStock as $item): ?>
                                <?php
                                $label = isset($lowLabels[$item['variant_id']])
                                    ? implode(', ', $lowLabels[$item['variant_id']])
                                    : $item['sku'];
                                $stock = (int)$item['stock_quantity'];
                                ?>
                                <a href="product-edit.php?id=<?= (int)$item['product_id'] ?>" class="flex items-center justify-between text-sm hover:bg-gray-50 rounded-lg -mx-2 px-2 py-1">
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 truncate"><?= htmlspecialchars($item['name']) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($label) ?></p>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full flex-shrink-0 ml-2 <?= $stock === 0 ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-600' ?>">
                                        <?= $stock === 0 ? 'Out of stock' : $stock . ' left' ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($lowStockCount > count($lowStock)): ?>
                            <p class="text-xs text-gray-400 mt-4">
                                + <?= $lowStockCount - count($lowStock) ?> more not shown
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>