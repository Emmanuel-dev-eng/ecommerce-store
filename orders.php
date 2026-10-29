<?php
// ============================================
// orders.php
// Shows the logged-in customer's REAL orders.
// ============================================

require 'config/auth_check.php';
require 'config/db.php';
require 'config/cart_count.php';

$userId = (int)$_SESSION['user_id'];

// ---- Status filter (whitelist) ----
$allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
$filter = $_GET['status'] ?? 'all';

// If the value isn't 'all' or one of our known statuses, ignore it.
if ($filter !== 'all' && !in_array($filter, $allowedStatuses, true)) {
    $filter = 'all';
}

// ---- Fetch this user's orders ----
// The two inner SELECTs (subqueries) calculate, per order, the number
// of items and the first product's picture.
$sql = "
    SELECT
        o.id, o.order_number, o.status, o.total_amount, o.created_at,
        (SELECT COALESCE(SUM(oi.quantity), 0)
           FROM order_items oi
          WHERE oi.order_id = o.id) AS item_count,
        (SELECT pi.image_url
           FROM order_items oi
           JOIN product_variants pv ON oi.variant_id = pv.id
           JOIN product_images pi ON pi.product_id = pv.product_id AND pi.is_primary = 1
          WHERE oi.order_id = o.id
          ORDER BY oi.id
          LIMIT 1) AS image_url
    FROM orders o
    WHERE o.user_id = ?
";
$params = [$userId];

if ($filter !== 'all') {
    $sql .= " AND o.status = ?";
    $params[] = $filter;
}

$sql .= " ORDER BY o.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Badge colors per status
$statusStyles = [
    'pending'    => 'bg-gray-100 text-gray-600',
    'processing' => 'bg-yellow-50 text-yellow-700',
    'shipped'    => 'bg-blue-50 text-blue-600',
    'delivered'  => 'bg-green-50 text-green-600',
    'cancelled'  => 'bg-red-50 text-red-600',
];

// Tabs: URL value => label
$tabs = [
    'all'        => 'All Orders',
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
    <title>My Orders | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER ===================== -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight">ShopName</a>

            <nav class="hidden md:flex items-center gap-8">
                <a href="index.php" class="text-sm font-medium text-gray-700 hover:text-black">Home</a>
                <a href="shop.php" class="text-sm font-medium text-gray-700 hover:text-black">Shop</a>
                <a href="about.php" class="text-sm font-medium text-gray-700 hover:text-black">About</a>
                <a href="contact.php" class="text-sm font-medium text-gray-700 hover:text-black">Contact</a>
            </nav>

            <div class="flex items-center gap-4">
                <button id="searchToggle" class="text-gray-700 hover:text-black">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
                <a href="cart.php" class="relative text-gray-700 hover:text-black">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-black text-white text-[10px] font-bold rounded-full min-w-4 h-4 px-1 flex items-center justify-center"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="account.php" class="hidden md:block text-gray-900">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </a>
                <button id="menuToggle" class="md:hidden text-gray-700 hover:text-black">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <div id="searchBar" class="hidden pb-4">
            <div class="relative">
                <input type="text" placeholder="Search products..."
                    class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100">
        <nav class="flex flex-col px-4 py-4 gap-1">
            <a href="index.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="home" class="w-5 h-5"></i><span class="text-sm font-medium">Home</span>
            </a>
            <a href="shop.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i><span class="text-sm font-medium">Shop</span>
            </a>
            <a href="account.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="user" class="w-5 h-5"></i><span class="text-sm font-medium">My Account</span>
            </a>
            <a href="orders.php" class="flex items-center gap-3 py-3 px-2 rounded-lg bg-gray-100 text-gray-900">
                <i data-lucide="package" class="w-5 h-5"></i><span class="text-sm font-medium">My Orders</span>
            </a>
            <a href="about.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="info" class="w-5 h-5"></i><span class="text-sm font-medium">About</span>
            </a>
            <a href="contact.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="mail" class="w-5 h-5"></i><span class="text-sm font-medium">Contact</span>
            </a>
        </nav>
    </div>
</header>

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <h1 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h1>

    <!-- ===================== STATUS TABS (real links) ===================== -->
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        <?php foreach ($tabs as $value => $label): ?>
            <a href="orders.php<?= $value === 'all' ? '' : '?status=' . $value ?>"
               class="whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold <?= $filter === $value
                    ? 'bg-black text-white'
                    : 'bg-white border border-gray-300 text-gray-600 hover:border-black' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- ===================== ORDERS LIST ===================== -->
    <?php if (empty($orders)): ?>
        <div class="text-center py-16">
            <i data-lucide="package-x" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
            <p class="text-gray-500 mb-4">
                <?= $filter === 'all' ? "You haven't placed any orders yet." : 'No orders in this category.' ?>
            </p>
            <?php if ($filter === 'all'): ?>
                <a href="shop.php" class="inline-block bg-black text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-gray-800">
                    Start Shopping
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($orders as $o): ?>
                <a href="order-detail.php?order=<?= urlencode($o['order_number']) ?>"
                   class="block bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 hover:border-gray-300 transition">
                    <div class="flex items-center gap-4">
                        <img src="<?= htmlspecialchars($o['image_url'] ?? '') ?>" alt=""
                             class="w-16 h-16 rounded-xl object-cover flex-shrink-0 bg-gray-100">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <p class="text-sm font-semibold text-gray-900">#<?= htmlspecialchars($o['order_number']) ?></p>
                                <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full flex-shrink-0 <?= $statusStyles[$o['status']] ?? '' ?>">
                                    <?= ucfirst($o['status']) ?>
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">
                                <?= date('M j, Y', strtotime($o['created_at'])) ?> ·
                                <?= (int)$o['item_count'] ?> item<?= $o['item_count'] == 1 ? '' : 's' ?>
                            </p>
                            <p class="text-sm font-bold text-gray-900 mt-1">$<?= number_format($o['total_amount'], 2) ?></p>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<!-- ===================== FOOTER ===================== -->
<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-8">
            <div>
                <h3 class="text-white font-bold text-lg mb-3">ShopName</h3>
                <p class="text-sm text-gray-400">Quality products, delivered to your door.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Shop</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="shop.php" class="hover:text-white">All Products</a></li>
                    <li><a href="shop.php?category=new" class="hover:text-white">New Arrivals</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="contact.php" class="hover:text-white">Contact Us</a></li>
                    <li><a href="orders.php" class="hover:text-white">Track Order</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Legal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="terms.php" class="hover:text-white">Terms of Service</a></li>
                    <li><a href="privacy.php" class="hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Follow Us</h4>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-white"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                    <a href="#" class="hover:text-white"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                    <a href="#" class="hover:text-white"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-xs text-gray-500">
            &copy; 2026 ShopName. All rights reserved.
        </div>
    </div>
</footer>

<script>
    lucide.createIcons();

    document.getElementById('menuToggle').addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
    document.getElementById('searchToggle').addEventListener('click', () => {
        document.getElementById('searchBar').classList.toggle('hidden');
    });

    // ---- Connect header search to the real search page ----
    const searchInputField = document.querySelector('#searchBar input');
    searchInputField.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const term = searchInputField.value.trim();
            if (term) {
                window.location.href = `search.php?q=${encodeURIComponent(term)}`;
            }
        }
    });
</script>

</body>
</html>