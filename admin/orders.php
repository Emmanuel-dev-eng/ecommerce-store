<?php require '../config/admin_auth_check.php'; ?>
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
            <a href="orders.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-gray-800 text-white text-sm font-medium">
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
                <h1 class="text-lg font-bold text-gray-900">Orders</h1>
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
                    <a href="orders.php" class="flex items-center gap-3 px-3 py-3 rounded-lg bg-gray-800 text-white text-sm font-medium">
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

            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div class="relative w-full sm:max-w-xs">
                    <input type="text" id="orderSearch" placeholder="Search order # or customer..."
                        class="w-full border border-gray-300 rounded-lg py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
                <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
                    <button class="status-filter whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-semibold bg-black text-white" data-status="all">All</button>
                    <button class="status-filter whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-gray-300 text-gray-600 hover:border-black" data-status="pending">Pending</button>
                    <button class="status-filter whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-gray-300 text-gray-600 hover:border-black" data-status="processing">Processing</button>
                    <button class="status-filter whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-gray-300 text-gray-600 hover:border-black" data-status="shipped">Shipped</button>
                    <button class="status-filter whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-gray-300 text-gray-600 hover:border-black" data-status="delivered">Delivered</button>
                </div>
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
                                <th class="py-3 px-4 font-medium">Status</th>
                                <th class="py-3 px-4 font-medium text-right">Update</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody" class="divide-y divide-gray-50">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>
                <p id="noOrdersMsg" class="hidden text-center text-sm text-gray-400 py-10">No orders match your filters.</p>
            </div>

        </main>
    </div>
</div>

<!-- ===================== TOAST ===================== -->
<div id="toast" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-sm px-4 py-3 rounded-lg shadow-lg z-50 items-center gap-2">
    <i data-lucide="check-circle" class="w-4 h-4 text-green-400"></i>
    <span id="toastText">Status updated.</span>
</div>

<script>
    lucide.createIcons();

    const drawer = document.getElementById('adminMobileDrawer');
    document.getElementById('adminMenuToggle').addEventListener('click', () => drawer.classList.remove('hidden'));
    document.getElementById('adminDrawerClose').addEventListener('click', () => drawer.classList.add('hidden'));
    document.getElementById('adminDrawerOverlay').addEventListener('click', () => drawer.classList.add('hidden'));

    // ---- Sample order data ----
    // NOTE: this becomes `SELECT * FROM orders JOIN users ON orders.user_id
    // = users.id ORDER BY created_at DESC` once PHP + MySQL are connected.
    let orders = [
        { id: 'ORD-482913', customer: 'Jane Doe', date: 'Aug 20, 2026', total: 149.98, status: 'shipped' },
        { id: 'ORD-482910', customer: 'Michael Obi', date: 'Aug 19, 2026', total: 89.99, status: 'processing' },
        { id: 'ORD-482905', customer: 'Sarah Lee', date: 'Aug 18, 2026', total: 59.99, status: 'pending' },
        { id: 'ORD-482899', customer: 'Chinedu Okafor', date: 'Aug 15, 2026', total: 229.97, status: 'delivered' },
        { id: 'ORD-482890', customer: 'Amaka Eze', date: 'Aug 12, 2026', total: 74.99, status: 'delivered' },
        { id: 'ORD-482875', customer: 'Tunde Bakare', date: 'Aug 10, 2026', total: 129.99, status: 'pending' }
    ];

    const statusStyles = {
        pending: 'bg-gray-100 text-gray-600', processing: 'bg-yellow-50 text-yellow-700',
        shipped: 'bg-blue-50 text-blue-600', delivered: 'bg-green-50 text-green-600'
    };

    const tbody = document.getElementById('ordersTableBody');
    const noOrdersMsg = document.getElementById('noOrdersMsg');
    let currentFilter = 'all';
    let currentSearch = '';

    function renderOrders() {
        tbody.innerHTML = '';
        let filtered = orders;

        if (currentFilter !== 'all') {
            filtered = filtered.filter(o => o.status === currentFilter);
        }
        if (currentSearch) {
            const term = currentSearch.toLowerCase();
            filtered = filtered.filter(o => o.id.toLowerCase().includes(term) || o.customer.toLowerCase().includes(term));
        }

        if (filtered.length === 0) {
            noOrdersMsg.classList.remove('hidden');
        } else {
            noOrdersMsg.classList.add('hidden');
        }

        filtered.forEach(o => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.innerHTML = `
<td class="py-3 px-4 font-medium text-gray-900"><a href="order-detail.php?order=${o.id}" class="hover:underline">#${o.id}</a></td>                <td class="py-3 px-4 text-gray-600">${o.customer}</td>
                <td class="py-3 px-4 text-gray-500">${o.date}</td>
                <td class="py-3 px-4 font-semibold text-gray-900">$${o.total.toFixed(2)}</td>
                <td class="py-3 px-4">
                    <span class="text-[11px] font-semibold px-2 py-1 rounded-full ${statusStyles[o.status]}">
                        ${o.status.charAt(0).toUpperCase() + o.status.slice(1)}
                    </span>
                </td>
                <td class="py-3 px-4 text-right">
                    <select class="status-select border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-black" data-id="${o.id}">
                        <option value="pending" ${o.status==='pending'?'selected':''}>Pending</option>
                        <option value="processing" ${o.status==='processing'?'selected':''}>Processing</option>
                        <option value="shipped" ${o.status==='shipped'?'selected':''}>Shipped</option>
                        <option value="delivered" ${o.status==='delivered'?'selected':''}>Delivered</option>
                        <option value="cancelled" ${o.status==='cancelled'?'selected':''}>Cancelled</option>
                    </select>
                </td>
            `;
            tbody.appendChild(row);
        });

        // ---- Status change handler ----
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', () => {
                // NOTE: real update runs `UPDATE orders SET status = ?
                // WHERE id = ?` via PHP in the backend phase — likely also
                // triggering an email notification to the customer.
                const order = orders.find(o => o.id === select.dataset.id);
                order.status = select.value;
                renderOrders();
                showToast(`Order #${order.id} marked as ${order.status}.`);
            });
        });
    }

    // ---- Filter tabs ----
    document.querySelectorAll('.status-filter').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.status-filter').forEach(b => {
                b.classList.remove('bg-black', 'text-white');
                b.classList.add('bg-white', 'border', 'border-gray-300', 'text-gray-600');
            });
            btn.classList.add('bg-black', 'text-white');
            btn.classList.remove('bg-white', 'border', 'border-gray-300', 'text-gray-600');
            currentFilter = btn.dataset.status;
            renderOrders();
        });
    });

    // ---- Search ----
    document.getElementById('orderSearch').addEventListener('input', (e) => {
        currentSearch = e.target.value.trim();
        renderOrders();
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

    renderOrders();
</script>

</body>
</html>