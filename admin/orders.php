<?php
// ============================================
// admin/orders.php
// Every customer order, with filtering, search and status updates.
// ============================================

require '../config/admin_auth_check.php'; // starts the session, admins only
require '../config/db.php';
require '../config/helpers.php';

$validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

// ---- Read the filter and search from the URL ----
$filter = $_GET['status'] ?? 'all';
if ($filter !== 'all' && !in_array($filter, $validStatuses, true)) {
    $filter = 'all'; // only known statuses are accepted
}
// is_string() guards against tricks like ?q[]=x
$q = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';

// Where to return after an action, keeping the current filter and search
$backParams = [];
if ($filter !== 'all') { $backParams['status'] = $filter; }
if ($q !== '')         { $backParams['q'] = $q; }
$backUrl = 'orders.php' . ($backParams ? '?' . http_build_query($backParams) : '');

// ---- Handle a status update ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_status') {
    $orderId   = (int)($_POST['order_id'] ?? 0);
    $newStatus = is_string($_POST['status'] ?? null) ? $_POST['status'] : '';

    $error = updateOrderStatus($pdo, $orderId, $newStatus);

    // A "flash" message: stored in the session, shown once after the redirect
    $_SESSION['flash'] = ($error === '')
        ? ['type' => 'success', 'text' => 'Order status updated.']
        : ['type' => 'error',   'text' => $error];

    header("Location: " . $backUrl);
    exit;
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']); // so it only shows once

// ---- Counts for the tabs, e.g. ['pending' => 3, 'shipped' => 1] ----
// FETCH_KEY_PAIR turns a two-column result into [first column => second column].
$counts = $pdo->query("SELECT status, COUNT(*) FROM orders GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
$totalCount = array_sum($counts);

// ---- Fetch the orders ----
// "WHERE 1=1" is always true; it lets every optional filter below start with AND.
$sql = "
    SELECT id, order_number, customer_name, phone, total_amount,
           status, payment_method, payment_status, created_at
    FROM orders
    WHERE 1=1
";
$params = [];

if ($filter !== 'all') {
    $sql .= " AND status = ?";
    $params[] = $filter;
}
if ($q !== '') {
    $sql .= " AND (order_number LIKE ? OR customer_name LIKE ? OR phone LIKE ?)";
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like);
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statusStyles = [
    'pending'    => 'bg-gray-100 text-gray-600',
    'processing' => 'bg-yellow-50 text-yellow-700',
    'shipped'    => 'bg-blue-50 text-blue-600',
    'delivered'  => 'bg-green-50 text-green-600',
    'cancelled'  => 'bg-red-50 text-red-600',
];

$tabs = [
    'all'        => 'All',
    'pending'    => 'Pending',
    'processing' => 'Processing',
    'shipped'    => 'Shipped',
    'delivered'  => 'Delivered',
    'cancelled'  => 'Cancelled',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders | ShopName Admin</title>
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
                <h1 class="text-lg font-bold text-gray-900">Orders</h1>
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

            <!-- Search -->
            <form method="GET" class="mb-4 flex gap-2 max-w-md">
                <?php if ($filter !== 'all'): ?>
                    <input type="hidden" name="status" value="<?= htmlspecialchars($filter) ?>">
                <?php endif; ?>
                <div class="relative flex-1">
                    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Order #, name or phone..."
                        class="w-full border border-gray-300 rounded-lg py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
                <button type="submit" class="bg-black text-white text-sm font-semibold px-4 rounded-lg hover:bg-gray-800">Search</button>
                <?php if ($q !== ''): ?>
                    <a href="<?= $filter !== 'all' ? 'orders.php?status=' . urlencode($filter) : 'orders.php' ?>"
                       class="flex items-center px-3 text-sm text-gray-500 hover:text-black">Clear</a>
                <?php endif; ?>
            </form>

            <!-- Status tabs -->
            <div class="flex items-center gap-2 mb-5 overflow-x-auto pb-1">
                <?php foreach ($tabs as $key => $label): ?>
                    <?php
                    $tabParams = [];
                    if ($key !== 'all') { $tabParams['status'] = $key; }
                    if ($q !== '')      { $tabParams['q'] = $q; }
                    $count = ($key === 'all') ? $totalCount : ($counts[$key] ?? 0);
                    ?>
                    <a href="orders.php<?= $tabParams ? '?' . http_build_query($tabParams) : '' ?>"
                       class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-semibold <?= $filter === $key
                            ? 'bg-black text-white'
                            : 'bg-white border border-gray-300 text-gray-600 hover:border-black' ?>">
                        <?= $label ?> (<?= $count ?>)
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Orders table -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-400 text-xs border-b border-gray-100 bg-gray-50">
                                <th class="py-3 px-4 font-medium">Order</th>
                                <th class="py-3 px-4 font-medium">Customer</th>
                                <th class="py-3 px-4 font-medium">Date</th>
                                <th class="py-3 px-4 font-medium">Total</th>
                                <th class="py-3 px-4 font-medium">Payment</th>
                                <th class="py-3 px-4 font-medium">Status</th>
                                <th class="py-3 px-4 font-medium text-right">Update</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach ($orders as $o): ?>
                                <?php $next = allowedNextStatuses($o['status']); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 font-medium text-gray-900 whitespace-nowrap">
                                        <a href="order-detail.php?order=<?= urlencode($o['order_number']) ?>" class="hover:underline">
                                            #<?= htmlspecialchars($o['order_number']) ?>
                                        </a>
                                    </td>
                                    <td class="py-3 px-4">
                                        <p class="text-gray-900"><?= htmlspecialchars($o['customer_name']) ?></p>
                                        <p class="text-xs text-gray-400"><?= htmlspecialchars($o['phone']) ?></p>
                                    </td>
                                    <td class="py-3 px-4 text-gray-500 whitespace-nowrap"><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                                    <td class="py-3 px-4 font-semibold text-gray-900">$<?= number_format($o['total_amount'], 2) ?></td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <p class="text-xs text-gray-600"><?= $o['payment_method'] === 'cod' ? 'Cash on Delivery' : 'Paystack' ?></p>
                                        <p class="text-[11px] <?= $o['payment_status'] === 'paid' ? 'text-green-600' : 'text-orange-500' ?>">
                                            <?= ucfirst($o['payment_status']) ?>
                                        </p>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-[11px] font-semibold px-2 py-1 rounded-full <?= $statusStyles[$o['status']] ?? '' ?>">
                                            <?= ucfirst($o['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php if (empty($next)): ?>
                                            <p class="text-xs text-gray-400 text-right">—</p>
                                        <?php else: ?>
                                            <form method="POST" class="flex items-center justify-end gap-2" onsubmit="return confirmStatus(this);">
                                                <input type="hidden" name="action" value="update_status">
                                                <input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>">
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
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (empty($orders)): ?>
                    <p class="text-center text-sm text-gray-400 py-10">No orders match your filters.</p>
                <?php endif; ?>
            </div>

        </main>
    </div>
</div>

<script>
    lucide.createIcons();

    // Ask before cancelling. Returning false stops the form from submitting.
    function confirmStatus(form) {
        if (form.status.value === 'cancelled') {
            return confirm('Cancel this order? The stock will be returned to inventory.');
        }
        return true;
    }
</script>

</body>
</html>