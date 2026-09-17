<?php require '../config/admin_auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers | ShopName Admin</title>
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
            <a href="dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
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
            <a href="customers.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-gray-800 text-white text-sm font-medium">
                <i data-lucide="users" class="w-4 h-4"></i> Customers
            </a>
            <a href="settings.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="settings" class="w-4 h-4"></i> Settings
            </a>
        </nav>
        <div class="p-3 border-t border-gray-800">
            <a href="../login.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium text-red-400">
                <i data-lucide="log-out" class="w-4 h-4"></i> Log Out
            </a>
        </div>
    </aside>

    <!-- ===================== MAIN AREA ===================== -->
    <div class="flex-1 flex flex-col min-w-0">

        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <button id="adminMenuToggle" class="lg:hidden text-gray-700">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="text-lg font-bold text-gray-900">Customers</h1>
            </div>
            <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">A</div>
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
                    <a href="dashboard.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
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
                    <a href="customers.php" class="flex items-center gap-3 px-3 py-3 rounded-lg bg-gray-800 text-white text-sm font-medium">
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

            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div class="relative w-full sm:max-w-xs">
                    <input type="text" id="customerSearch" placeholder="Search name or email..."
                        class="w-full border border-gray-300 rounded-lg py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
                <p class="text-xs text-gray-500"><span id="customerCount">0</span> customers</p>
            </div>

            <!-- Customers table -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-400 text-xs border-b border-gray-100 bg-gray-50">
                                <th class="py-3 px-4 font-medium">Customer</th>
                                <th class="py-3 px-4 font-medium">Email</th>
                                <th class="py-3 px-4 font-medium">Orders</th>
                                <th class="py-3 px-4 font-medium">Joined</th>
                                <th class="py-3 px-4 font-medium">Status</th>
                                <th class="py-3 px-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customersTableBody" class="divide-y divide-gray-50">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>
                <p id="noCustomersMsg" class="hidden text-center text-sm text-gray-400 py-10">No customers match your search.</p>
            </div>

        </main>
    </div>
</div>

<!-- ===================== CUSTOMER DETAIL DRAWER ===================== -->
<div id="customerDrawer" class="hidden fixed inset-0 z-50">
    <div id="customerDrawerOverlay" class="absolute inset-0 bg-black/50"></div>
    <div class="absolute right-0 top-0 bottom-0 w-full max-w-sm bg-white flex flex-col">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Customer Details</h2>
            <button id="customerDrawerClose" class="text-gray-500 hover:text-black">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5">
            <div class="flex items-center gap-4 mb-6">
                <div id="customerAvatar" class="w-14 h-14 rounded-full bg-gray-900 text-white flex items-center justify-center text-lg font-bold flex-shrink-0"></div>
                <div>
                    <p id="customerDetailName" class="font-bold text-gray-900"></p>
                    <p id="customerDetailEmail" class="text-sm text-gray-500"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500">Total Orders</p>
                    <p id="customerDetailOrders" class="text-lg font-bold text-gray-900"></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500">Total Spent</p>
                    <p id="customerDetailSpent" class="text-lg font-bold text-gray-900"></p>
                </div>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 mb-2">Account Status</p>
                <label class="flex items-center justify-between border border-gray-200 rounded-xl p-3">
                    <span class="text-sm text-gray-700">Account Active</span>
                    <input type="checkbox" id="customerActiveToggle" class="w-4 h-4 rounded border-gray-300">
                </label>
            </div>
        </div>
    </div>
</div>

<!-- ===================== TOAST ===================== -->
<div id="toast" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-sm px-4 py-3 rounded-lg shadow-lg z-50 items-center gap-2">
    <i data-lucide="check-circle" class="w-4 h-4 text-green-400"></i>
    <span id="toastText">Updated.</span>
</div>

<script>
    lucide.createIcons();

    const drawer = document.getElementById('adminMobileDrawer');
    document.getElementById('adminMenuToggle').addEventListener('click', () => drawer.classList.remove('hidden'));
    document.getElementById('adminDrawerClose').addEventListener('click', () => drawer.classList.add('hidden'));
    document.getElementById('adminDrawerOverlay').addEventListener('click', () => drawer.classList.add('hidden'));

    // ---- Sample customer data ----
    // NOTE: this becomes `SELECT * FROM users WHERE role = 'customer'`
    // (with an order count subquery) once PHP + MySQL are connected.
    let customers = [
        { id: 1, name: 'Jane Doe', email: 'jane.doe@example.com', orders: 7, spent: 620.45, joined: 'Mar 12, 2026', active: true },
        { id: 2, name: 'Michael Obi', email: 'michael.obi@example.com', orders: 3, spent: 289.97, joined: 'Apr 2, 2026', active: true },
        { id: 3, name: 'Sarah Lee', email: 'sarah.lee@example.com', orders: 1, spent: 59.99, joined: 'Jun 18, 2026', active: true },
        { id: 4, name: 'Chinedu Okafor', email: 'chinedu.okafor@example.com', orders: 12, spent: 1420.80, joined: 'Jan 8, 2026', active: true },
        { id: 5, name: 'Amaka Eze', email: 'amaka.eze@example.com', orders: 2, spent: 149.98, joined: 'Jul 25, 2026', active: false },
        { id: 6, name: 'Tunde Bakare', email: 'tunde.bakare@example.com', orders: 4, spent: 380.20, joined: 'May 30, 2026', active: true }
    ];

    const tbody = document.getElementById('customersTableBody');
    const noCustomersMsg = document.getElementById('noCustomersMsg');
    const customerCount = document.getElementById('customerCount');

    function getInitials(name) {
        return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
    }

    function renderCustomers(term = '') {
        tbody.innerHTML = '';
        const filtered = customers.filter(c =>
            c.name.toLowerCase().includes(term.toLowerCase()) ||
            c.email.toLowerCase().includes(term.toLowerCase())
        );

        customerCount.textContent = filtered.length;

        if (filtered.length === 0) {
            noCustomersMsg.classList.remove('hidden');
        } else {
            noCustomersMsg.classList.add('hidden');
        }

        filtered.forEach(c => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 cursor-pointer';
            row.innerHTML = `
                <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                            ${getInitials(c.name)}
                        </div>
                        <span class="font-medium text-gray-900">${c.name}</span>
                    </div>
                </td>
                <td class="py-3 px-4 text-gray-500">${c.email}</td>
                <td class="py-3 px-4 text-gray-600">${c.orders}</td>
                <td class="py-3 px-4 text-gray-500">${c.joined}</td>
                <td class="py-3 px-4">
                    <span class="text-[11px] font-semibold px-2 py-1 rounded-full ${c.active ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'}">
                        ${c.active ? 'Active' : 'Suspended'}
                    </span>
                </td>
                <td class="py-3 px-4 text-right">
                    <button class="view-btn text-xs font-semibold text-gray-900 border border-gray-300 rounded-lg px-3 py-1.5 hover:bg-gray-50" data-id="${c.id}">
                        View
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });

        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => openCustomerDrawer(parseInt(btn.dataset.id)));
        });
    }

    // ---- Customer detail drawer ----
    const customerDrawer = document.getElementById('customerDrawer');
    let currentCustomerId = null;

    function openCustomerDrawer(id) {
        const c = customers.find(cu => cu.id === id);
        currentCustomerId = id;
        document.getElementById('customerAvatar').textContent = getInitials(c.name);
        document.getElementById('customerDetailName').textContent = c.name;
        document.getElementById('customerDetailEmail').textContent = c.email;
        document.getElementById('customerDetailOrders').textContent = c.orders;
        document.getElementById('customerDetailSpent').textContent = `$${c.spent.toFixed(2)}`;
        document.getElementById('customerActiveToggle').checked = c.active;

        customerDrawer.classList.remove('hidden');
    }

    document.getElementById('customerDrawerClose').addEventListener('click', () => {
        customerDrawer.classList.add('hidden');
    });
    document.getElementById('customerDrawerOverlay').addEventListener('click', () => {
        customerDrawer.classList.add('hidden');
    });

    // ---- Toggle active/suspended ----
    document.getElementById('customerActiveToggle').addEventListener('change', (e) => {
        // NOTE: real toggle runs `UPDATE users SET active = ? WHERE id = ?`
        // via PHP in the backend phase — e.g. to suspend a fraudulent account.
        const c = customers.find(cu => cu.id === currentCustomerId);
        c.active = e.target.checked;
        renderCustomers(document.getElementById('customerSearch').value);
        showToast(`${c.name} is now ${c.active ? 'active' : 'suspended'}.`);
    });

    // ---- Search ----
    document.getElementById('customerSearch').addEventListener('input', (e) => {
        renderCustomers(e.target.value);
    });

    // ---- Toast ----
    function showToast(text) {
        const toast = document.getElementById('toast');
        document.getElementById('toastText').textContent = text;
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
        }, 3000);
    }

    renderCustomers();
</script>

</body>
</html>