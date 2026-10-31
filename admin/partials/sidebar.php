<?php
// ============================================
// admin/partials/sidebar.php
// Shared admin sidebar (desktop) and slide-in drawer (mobile).
// The page that includes this sets $active first, e.g. $active = 'orders';
// ============================================

$adminNav = [
    'dashboard'  => ['Dashboard',  'layout-dashboard', 'dashboard.php'],
    'products'   => ['Products',   'package',          'products.php'],
    'categories' => ['Categories', 'tag',              'categories.php'],
    'orders'     => ['Orders',     'shopping-cart',    'orders.php'],
    'customers'  => ['Customers',  'users',            'customers.php'],
    'settings'   => ['Settings',   'settings',         'settings.php'],
];

$active = $active ?? ''; // if the page didn't set it, highlight nothing
?>
<!-- ===================== SIDEBAR (desktop) ===================== -->
<aside class="hidden lg:flex lg:flex-col w-64 bg-gray-900 text-gray-300 flex-shrink-0">
    <div class="h-16 flex items-center px-6 border-b border-gray-800">
        <span class="text-white font-bold text-lg">ShopName <span class="text-gray-500 font-normal text-sm">Admin</span></span>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-1">
        <?php foreach ($adminNav as $key => [$label, $icon, $href]): ?>
            <a href="<?= $href ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium <?= $key === $active ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' ?>">
                <i data-lucide="<?= $icon ?>" class="w-4 h-4"></i> <?= $label ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="p-3 border-t border-gray-800">
        <a href="../logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium text-red-400">
            <i data-lucide="log-out" class="w-4 h-4"></i> Log Out
        </a>
    </div>
</aside>

<!-- ===================== MOBILE DRAWER ===================== -->
<div id="adminMobileDrawer" class="hidden fixed inset-0 z-50 lg:hidden">
    <div id="adminDrawerOverlay" class="absolute inset-0 bg-black/50"></div>
    <div class="absolute left-0 top-0 bottom-0 w-64 bg-gray-900 text-gray-300 flex flex-col">
        <div class="h-16 flex items-center justify-between px-4 border-b border-gray-800">
            <span class="text-white font-bold">ShopName Admin</span>
            <button id="adminDrawerClose" class="text-gray-400">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
            <?php foreach ($adminNav as $key => [$label, $icon, $href]): ?>
                <a href="<?= $href ?>"
                   class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium <?= $key === $active ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' ?>">
                    <i data-lucide="<?= $icon ?>" class="w-4 h-4"></i> <?= $label ?>
                </a>
            <?php endforeach; ?>
            <a href="../logout.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium text-red-400">
                <i data-lucide="log-out" class="w-4 h-4"></i> Log Out
            </a>
        </nav>
    </div>
</div>

<script>
    // Wait until the whole page exists, because the hamburger button
    // (adminMenuToggle) lives in the page header, after this file.
    document.addEventListener('DOMContentLoaded', () => {
        const drawer = document.getElementById('adminMobileDrawer');
        document.getElementById('adminMenuToggle')?.addEventListener('click', () => drawer.classList.remove('hidden'));
        document.getElementById('adminDrawerClose')?.addEventListener('click', () => drawer.classList.add('hidden'));
        document.getElementById('adminDrawerOverlay')?.addEventListener('click', () => drawer.classList.add('hidden'));
    });
</script>