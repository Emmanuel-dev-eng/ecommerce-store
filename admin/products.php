<?php
// ============================================
// admin/products.php
// Real product list: search, filter, hide/show, delete.
// ============================================

require '../config/admin_auth_check.php'; // starts the session, admins only
require '../config/db.php';

// ---- Read the filter and search from the URL ----
$filter = $_GET['status'] ?? 'all';
if (!in_array($filter, ['all', 'active', 'hidden'], true)) {
    $filter = 'all'; // only known values are accepted
}
$q = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';

// Where to return after an action, keeping the current filter and search
$backParams = [];
if ($filter !== 'all') { $backParams['status'] = $filter; }
if ($q !== '')         { $backParams['q'] = $q; }
$backUrl = 'products.php' . ($backParams ? '?' . http_build_query($backParams) : '');

// ---- Handle actions (hide/show, delete) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action    = is_string($_POST['action'] ?? null) ? $_POST['action'] : '';
    $productId = (int)($_POST['product_id'] ?? 0);

    if ($productId > 0 && $action === 'toggle') {

        // Flip 1 to 0 or 0 to 1 in a single query
        $stmt = $pdo->prepare("UPDATE products SET is_active = 1 - is_active WHERE id = ?");
        $stmt->execute([$productId]);
        $_SESSION['flash'] = ['type' => 'success', 'text' => 'Product visibility updated.'];

    } elseif ($productId > 0 && $action === 'delete') {

        // Has this product ever been ordered?
        $usedStmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM order_items oi
            JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE pv.product_id = ?
        ");
        $usedStmt->execute([$productId]);

        if ((int)$usedStmt->fetchColumn() > 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'text' => "This product appears in past orders, so it can't be deleted. Hide it instead; it will disappear from the shop but keep your order history intact."
            ];
        } else {
            try {
                // The database removes this product's variants, images and cart entries too
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$productId]);
                $_SESSION['flash'] = ['type' => 'success', 'text' => 'Product deleted.'];
            } catch (PDOException $e) {
                error_log($e->getMessage());
                $_SESSION['flash'] = ['type' => 'error', 'text' => "Couldn't delete this product. Try hiding it instead."];
            }
        }
    }

    header("Location: " . $backUrl);
    exit;
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']); // so it only shows once

// ---- Counts for the tabs ----
// is_active is 1 or 0, so this returns something like [1 => 5, 0 => 1]
$counts = $pdo->query("SELECT is_active, COUNT(*) FROM products GROUP BY is_active")->fetchAll(PDO::FETCH_KEY_PAIR);
$activeCount = (int)($counts[1] ?? 0);
$hiddenCount = (int)($counts[0] ?? 0);
$allCount    = $activeCount + $hiddenCount;

// ---- Fetch the products ----
$sql = "
    SELECT
        p.id, p.name, p.base_price, p.is_active,
        c.name AS category_name,
        (SELECT pi.image_url
           FROM product_images pi
          WHERE pi.product_id = p.id
          ORDER BY pi.is_primary DESC, pi.sort_order ASC
          LIMIT 1) AS image_url,
        (SELECT COALESCE(SUM(pv.stock_quantity), 0)
           FROM product_variants pv
          WHERE pv.product_id = p.id) AS total_stock,
        (SELECT COUNT(*)
           FROM product_variants pv
          WHERE pv.product_id = p.id) AS variant_count
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE 1=1
";
$params = [];

if ($filter === 'active') {
    $sql .= " AND p.is_active = 1";
} elseif ($filter === 'hidden') {
    $sql .= " AND p.is_active = 0";
}
if ($q !== '') {
    $sql .= " AND p.name LIKE ?";
    $params[] = '%' . $q . '%';
}
$sql .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tabs = [
    'all'    => ['All',    $allCount],
    'active' => ['Active', $activeCount],
    'hidden' => ['Hidden', $hiddenCount],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | ShopName Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<div class="flex min-h-screen">

    <?php $active = 'products'; require __DIR__ . '/partials/sidebar.php'; ?>

    <!-- ===================== MAIN AREA ===================== -->
    <div class="flex-1 flex flex-col min-w-0">

        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <button id="adminMenuToggle" class="lg:hidden text-gray-700">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="text-lg font-bold text-gray-900">Products</h1>
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

            <!-- Toolbar: search + add -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <form method="GET" class="flex gap-2 w-full sm:max-w-md">
                    <?php if ($filter !== 'all'): ?>
                        <input type="hidden" name="status" value="<?= htmlspecialchars($filter) ?>">
                    <?php endif; ?>
                    <div class="relative flex-1">
                        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search products..."
                            class="w-full border border-gray-300 rounded-lg py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button type="submit" class="bg-black text-white text-sm font-semibold px-4 rounded-lg hover:bg-gray-800">Search</button>
                    <?php if ($q !== ''): ?>
                        <a href="<?= $filter !== 'all' ? 'products.php?status=' . urlencode($filter) : 'products.php' ?>"
                           class="flex items-center px-2 text-sm text-gray-500 hover:text-black">Clear</a>
                    <?php endif; ?>
                </form>

                <a href="product-edit.php" class="inline-flex items-center justify-center gap-2 bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-gray-800">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Product
                </a>
            </div>

            <!-- Status tabs -->
            <div class="flex items-center gap-2 mb-5 overflow-x-auto pb-1">
                <?php foreach ($tabs as $key => [$label, $count]): ?>
                    <?php
                    $tabParams = [];
                    if ($key !== 'all') { $tabParams['status'] = $key; }
                    if ($q !== '')      { $tabParams['q'] = $q; }
                    ?>
                    <a href="products.php<?= $tabParams ? '?' . http_build_query($tabParams) : '' ?>"
                       class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-semibold <?= $filter === $key
                            ? 'bg-black text-white'
                            : 'bg-white border border-gray-300 text-gray-600 hover:border-black' ?>">
                        <?= $label ?> (<?= $count ?>)
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Products table -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-400 text-xs border-b border-gray-100 bg-gray-50">
                                <th class="py-3 px-4 font-medium">Product</th>
                                <th class="py-3 px-4 font-medium">Category</th>
                                <th class="py-3 px-4 font-medium">Price</th>
                                <th class="py-3 px-4 font-medium">Stock</th>
                                <th class="py-3 px-4 font-medium">Status</th>
                                <th class="py-3 px-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach ($products as $p): ?>
                                <?php
                                $stock    = (int)$p['total_stock'];
                                $variants = (int)$p['variant_count'];
                                $isActive = (int)$p['is_active'] === 1;
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3 min-w-[200px]">
                                            <img src="<?= htmlspecialchars($p['image_url'] ?? '') ?>" alt=""
                                                 class="w-10 h-10 rounded-lg object-cover flex-shrink-0 bg-gray-100">
                                            <span class="font-medium text-gray-900"><?= htmlspecialchars($p['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 whitespace-nowrap"><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
                                    <td class="py-3 px-4 font-medium text-gray-900 whitespace-nowrap">$<?= number_format($p['base_price'], 2) ?></td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <?php if ($variants === 0): ?>
                                            <span class="text-xs text-orange-500">No variants</span>
                                            <p class="text-[11px] text-gray-400">Can't be bought yet</p>
                                        <?php else: ?>
                                            <span class="<?= $stock === 0 ? 'text-red-500' : ($stock <= 5 ? 'text-orange-500' : 'text-gray-600') ?>">
                                                <?= $stock === 0 ? 'Out of stock' : $stock . ' in stock' ?>
                                            </span>
                                            <p class="text-[11px] text-gray-400"><?= $variants ?> variant<?= $variants === 1 ? '' : 's' ?></p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-[11px] font-semibold px-2 py-1 rounded-full <?= $isActive ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' ?>">
                                            <?= $isActive ? 'Active' : 'Hidden' ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-end gap-1">

                                            <a href="product-edit.php?id=<?= (int)$p['id'] ?>" title="Edit"
                                               class="p-2 text-gray-500 hover:text-black hover:bg-gray-100 rounded-lg">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </a>

                                            <form method="POST">
                                                <input type="hidden" name="action" value="toggle">
                                                <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                                                <button type="submit" title="<?= $isActive ? 'Hide from shop' : 'Show in shop' ?>"
                                                    class="p-2 text-gray-500 hover:text-black hover:bg-gray-100 rounded-lg">
                                                    <i data-lucide="<?= $isActive ? 'eye' : 'eye-off' ?>" class="w-4 h-4"></i>
                                                </button>
                                            </form>

                                            <form method="POST" data-name="<?= htmlspecialchars($p['name']) ?>" onsubmit="return confirmDelete(this);">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                                                <button type="submit" title="Delete"
                                                    class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (empty($products)): ?>
                    <p class="text-center text-sm text-gray-400 py-10">
                        <?= ($q !== '' || $filter !== 'all') ? 'No products match your filters.' : 'No products yet. Click "Add Product" to create your first one.' ?>
                    </p>
                <?php endif; ?>
            </div>

        </main>
    </div>
</div>

<script>
    lucide.createIcons();

    // Returning false stops the form from submitting.
    // The product name comes from the form's data-name attribute.
    function confirmDelete(form) {
        return confirm('Delete "' + form.dataset.name + '"? This cannot be undone.');
    }
</script>

</body>
</html>