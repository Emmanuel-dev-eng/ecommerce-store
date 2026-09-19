<?php require '../config/admin_auth_check.php'; ?>
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

    <!-- ===================== SIDEBAR (desktop) ===================== -->
    <aside class="hidden lg:flex lg:flex-col w-64 bg-gray-900 text-gray-300 flex-shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-gray-800">
            <span class="text-white font-bold text-lg">ShopName <span class="text-gray-500 font-normal text-sm">Admin</span></span>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-gray-800 text-white text-sm font-medium">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
            </a>
            <a href="products.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="package" class="w-4 h-4"></i> Products
            </a>
            <a href="categories.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="tag" class="w-4 h-4"></i> Categories
            </a>
            <a href="orders.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i> Orders
            </a>
            <a href="customers.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="users" class="w-4 h-4"></i> Customers
            </a>
            <a href="settings.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="settings" class="w-4 h-4"></i> Settings
            </a>
        </nav>
        <div class="p-3 border-t border-gray-800">
            <a href="../logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium text-red-400">
                <i data-lucide="log-out" class="w-4 h-4"></i> Log Out
            </a>
        </div>
    </aside>

    <!-- ===================== MAIN AREA ===================== -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Top bar (mobile has hamburger here) -->
        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <button id="adminMenuToggle" class="lg:hidden text-gray-700">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="text-lg font-bold text-gray-900">Dashboard</h1>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative text-gray-500 hover:text-gray-900">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">A</div>
            </div>
        </header>

        <!-- ===================== MOBILE SIDEBAR DRAWER ===================== -->
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
                    <a href="dashboard.php" class="flex items-center gap-3 px-3 py-3 rounded-lg bg-gray-800 text-white text-sm font-medium">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                    </a>
                    <a href="products.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="package" class="w-4 h-4"></i> Products
                    </a>
                    <a href="categories.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="tag" class="w-4 h-4"></i> Categories
                    </a>
                    <a href="orders.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i> Orders
                    </a>
                    <a href="customers.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="users" class="w-4 h-4"></i> Customers
                    </a>
                    <a href="settings.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="settings" class="w-4 h-4"></i> Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- ===================== PAGE CONTENT ===================== -->
        <main class="flex-1 p-4 sm:p-6">

            <!-- Stat cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">Total Revenue</span>
                        <i data-lucide="dollar-sign" class="w-4 h-4 text-green-500"></i>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">$18,420</p>
                    <p class="text-xs text-green-600 mt-1">↑ 12.4% this month</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">Total Orders</span>
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-blue-500"></i>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">342</p>
                    <p class="text-xs text-green-600 mt-1">↑ 8 today</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">Customers</span>
                        <i data-lucide="users" class="w-4 h-4 text-purple-500"></i>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">1,204</p>
                    <p class="text-xs text-gray-400 mt-1">32 new this week</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">Low Stock</span>
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500"></i>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">6</p>
                    <p class="text-xs text-red-500 mt-1">Needs attention</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">

                <!-- Recent Orders table -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-900">Recent Orders</h2>
                        <a href="orders.php" class="text-xs font-medium text-gray-500 hover:text-black">View all</a>
                    </div>
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
                            <tbody id="recentOrdersBody" class="divide-y divide-gray-50">
                                <!-- Injected by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Low Stock Alerts -->
                <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6">
                    <h2 class="text-sm font-bold text-gray-900 mb-4">Low Stock Alerts</h2>
                    <div id="lowStockList" class="space-y-3">
                        <!-- Injected by JS -->
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    lucide.createIcons();

    // ---- Mobile drawer ----
    const drawer = document.getElementById('adminMobileDrawer');
    document.getElementById('adminMenuToggle').addEventListener('click', () => drawer.classList.remove('hidden'));
    document.getElementById('adminDrawerClose').addEventListener('click', () => drawer.classList.add('hidden'));
    document.getElementById('adminDrawerOverlay').addEventListener('click', () => drawer.classList.add('hidden'));

    // ---- Sample recent orders ----
    // NOTE: real data comes from `SELECT * FROM orders ORDER BY created_at
    // DESC LIMIT 5` once PHP + MySQL are wired in.
    const recentOrders = [
        { id: 'ORD-482913', customer: 'Jane Doe', status: 'shipped', total: 149.98 },
        { id: 'ORD-482910', customer: 'Michael Obi', status: 'processing', total: 89.99 },
        { id: 'ORD-482905', customer: 'Sarah Lee', status: 'pending', total: 59.99 },
        { id: 'ORD-482899', customer: 'Chinedu Okafor', status: 'delivered', total: 229.97 },
        { id: 'ORD-482890', customer: 'Amaka Eze', status: 'delivered', total: 74.99 }
    ];
    const statusStyles = {
        pending: 'bg-gray-100 text-gray-600', processing: 'bg-yellow-50 text-yellow-700',
        shipped: 'bg-blue-50 text-blue-600', delivered: 'bg-green-50 text-green-600'
    };
    const tbody = document.getElementById('recentOrdersBody');
    recentOrders.forEach(o => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 cursor-pointer';
        row.innerHTML = `
            <td class="py-3 px-2 font-medium text-gray-900">#${o.id}</td>
            <td class="py-3 px-2 text-gray-600">${o.customer}</td>
            <td class="py-3 px-2">
                <span class="text-[11px] font-semibold px-2 py-1 rounded-full ${statusStyles[o.status]}">${o.status.charAt(0).toUpperCase() + o.status.slice(1)}</span>
            </td>
            <td class="py-3 px-2 text-right font-semibold text-gray-900">$${o.total.toFixed(2)}</td>
        `;
        tbody.appendChild(row);
    });

    // ---- Sample low stock products ----
    // NOTE: real data comes from `SELECT * FROM product_variants WHERE
    // stock_quantity < 5` once connected to MySQL.
    const lowStock = [
        { name: 'Classic White Sneakers', variant: 'Size L', stock: 2 },
        { name: 'Wool Blend Overcoat', variant: 'Size M', stock: 1 },
        { name: 'Running Sneakers', variant: 'Size S', stock: 3 },
        { name: 'Silk Scarf', variant: 'Red', stock: 0 }
    ];
    const lowStockList = document.getElementById('lowStockList');
    lowStock.forEach(item => {
        const row = document.createElement('div');
        row.className = 'flex items-center justify-between text-sm';
        row.innerHTML = `
            <div class="min-w-0">
                <p class="font-medium text-gray-900 truncate">${item.name}</p>
                <p class="text-xs text-gray-500">${item.variant}</p>
            </div>
            <span class="text-xs font-semibold px-2 py-1 rounded-full flex-shrink-0 ${item.stock === 0 ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-600'}">
                ${item.stock === 0 ? 'Out of stock' : item.stock + ' left'}
            </span>
        `;
        lowStockList.appendChild(row);
    });
</script>

</body>
</html>