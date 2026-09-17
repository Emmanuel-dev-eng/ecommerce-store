<?php require '../config/admin_auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details | ShopName Admin</title>
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
                <a href="orders.php" class="text-gray-500 hover:text-black flex items-center gap-1 text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                </a>
                <h1 id="pageTitle" class="text-lg font-bold text-gray-900">Order #—</h1>
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

            <div class="flex items-center justify-between mb-6">
                <div>
                    <p id="orderMeta" class="text-sm text-gray-500">—</p>
                </div>
                <div class="flex items-center gap-2">
                    <span id="statusBadge" class="text-xs font-semibold px-3 py-1.5 rounded-full"></span>
                    <select id="statusUpdateSelect" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-black">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">

                <!-- LEFT: items + payment -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-4">Items</h2>
                        <div id="orderItemsList" class="space-y-4 mb-4 pb-4 border-b border-gray-100">
                            <!-- Injected by JS -->
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span><span id="detSubtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span><span id="detShipping">$0.00</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax</span><span id="detTax">$0.00</span>
                            </div>
                            <div class="flex justify-between font-bold text-gray-900 pt-2 border-t border-gray-100">
                                <span>Total</span><span id="detTotal">$0.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-3">Payment</h2>
                        <div id="paymentInfoBox" class="flex items-center gap-3 border border-gray-200 rounded-xl p-4">
                            <!-- Injected by JS -->
                        </div>
                    </div>

                    <!-- Internal admin notes -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-3">Internal Notes</h2>
                        <textarea id="adminNotes" rows="3" placeholder="e.g. Called customer, confirmed delivery time for Friday..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black resize-none"></textarea>
                        <button id="saveNoteBtn" class="mt-3 bg-gray-900 text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-gray-800">
                            Save Note
                        </button>
                        <span id="noteSavedMsg" class="hidden text-xs text-green-600 ml-3">✓ Saved</span>
                    </div>
                </div>

                <!-- RIGHT: customer + shipping -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4"></i> Customer
                        </h2>
                        <div class="flex items-center gap-3 mb-4">
                            <div id="customerAvatar" class="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center text-sm font-bold flex-shrink-0"></div>
                            <div>
                                <p id="customerName" class="text-sm font-semibold text-gray-900"></p>
                                <p id="customerEmail" class="text-xs text-gray-500"></p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <a id="callBtn" href="#" class="w-full bg-black text-white text-sm font-semibold py-3 rounded-lg hover:bg-gray-800 flex items-center justify-center gap-2">
                                <i data-lucide="phone-call" class="w-4 h-4"></i>
                                <span id="callBtnText">Call Customer</span>
                            </a>
                            <a id="emailBtn" href="#" class="w-full border border-gray-300 text-gray-700 text-sm font-semibold py-3 rounded-lg hover:bg-gray-50 flex items-center justify-center gap-2">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                                Email Customer
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4"></i> Shipping Address
                        </h2>
                        <p id="shippingAddress" class="text-sm text-gray-600 leading-relaxed"></p>
                    </div>
                </div>
            </div>
        </main>
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

    // ---- Sample order lookup ----
    // NOTE: real data comes from a query joining `orders`, `order_items`,
    // and `users` on the order ID passed via ?order=... once PHP + MySQL
    // are connected.
    const sampleOrders = {
        'ORD-482913': {
            date: 'Aug 20, 2026', status: 'shipped', paymentMethod: 'paystack', paymentStatus: 'paid',
            customer: { name: 'Jane Doe', email: 'jane.doe@example.com', phone: '+2348001234567' },
            address: '12 Palm Avenue, Lekki, Lagos, Nigeria',
            items: [
                { name: 'Classic White Sneakers', variant: 'White, Size M', price: 59.99, qty: 1 },
                { name: 'Wool Blend Overcoat', variant: 'Grey, Size L', price: 89.99, qty: 1 }
            ],
            shipping: 0
        },
        'ORD-482905': {
            date: 'Aug 18, 2026', status: 'pending', paymentMethod: 'cod', paymentStatus: 'unpaid',
            customer: { name: 'Sarah Lee', email: 'sarah.lee@example.com', phone: '+2348029876543' },
            address: '5 Adeola Street, Ikeja, Lagos, Nigeria',
            items: [
                { name: 'Classic White Sneakers', variant: 'White, Size L', price: 59.99, qty: 1 }
            ],
            shipping: 0
        }
    };

    const params = new URLSearchParams(window.location.search);
    const orderNum = params.get('order') || 'ORD-482905'; // fallback for direct preview
    const order = sampleOrders[orderNum] || sampleOrders['ORD-482905'];

    const statusStyles = {
        pending: 'bg-gray-100 text-gray-600', processing: 'bg-yellow-50 text-yellow-700',
        shipped: 'bg-blue-50 text-blue-600', delivered: 'bg-green-50 text-green-600',
        cancelled: 'bg-red-50 text-red-600'
    };

    document.getElementById('pageTitle').textContent = `Order #${orderNum}`;
    document.getElementById('orderMeta').textContent = `Placed on ${order.date} · ${order.items.length} item${order.items.length > 1 ? 's' : ''}`;

    const badge = document.getElementById('statusBadge');
    badge.textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);
    badge.className = `text-xs font-semibold px-3 py-1.5 rounded-full ${statusStyles[order.status]}`;
    document.getElementById('statusUpdateSelect').value = order.status;

    // ---- Items + totals ----
    const itemsList = document.getElementById('orderItemsList');
    order.items.forEach(item => {
        const row = document.createElement('div');
        row.className = 'flex items-center justify-between text-sm';
        row.innerHTML = `
            <div>
                <p class="font-medium text-gray-900">${item.name}</p>
                <p class="text-xs text-gray-500">${item.variant} · Qty ${item.qty}</p>
            </div>
            <span class="font-semibold text-gray-900">$${(item.price * item.qty).toFixed(2)}</span>
        `;
        itemsList.appendChild(row);
    });

    const subtotal = order.items.reduce((s, i) => s + i.price * i.qty, 0);
    const tax = subtotal * 0.075;
    const total = subtotal + order.shipping + tax;
    document.getElementById('detSubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('detShipping').textContent = order.shipping === 0 ? 'Free' : `$${order.shipping.toFixed(2)}`;
    document.getElementById('detTax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('detTotal').textContent = `$${total.toFixed(2)}`;

    // ---- Payment info box ----
    const paymentBox = document.getElementById('paymentInfoBox');
    if (order.paymentMethod === 'paystack') {
        paymentBox.innerHTML = `
            <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center flex-shrink-0">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Paid via Paystack</p>
                <p class="text-xs text-gray-500">Payment confirmed automatically — no action needed.</p>
            </div>
        `;
    } else {
        paymentBox.innerHTML = `
            <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center flex-shrink-0">
                <i data-lucide="banknote" class="w-5 h-5 text-orange-500"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Cash on Delivery — Unpaid</p>
                <p class="text-xs text-gray-500">Call the customer to confirm before shipping.</p>
            </div>
        `;
    }
    lucide.createIcons();

    // ---- Customer info ----
    function getInitials(name) {
        return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
    }
    document.getElementById('customerAvatar').textContent = getInitials(order.customer.name);
    document.getElementById('customerName').textContent = order.customer.name;
    document.getElementById('customerEmail').textContent = order.customer.email;
    document.getElementById('shippingAddress').innerHTML =
        `${order.customer.name}<br>${order.address}<br>${order.customer.phone}`;

    // ---- Tap-to-call and email links ----
    const callBtn = document.getElementById('callBtn');
    callBtn.href = `tel:${order.customer.phone}`;
    document.getElementById('callBtnText').textContent = `Call ${order.customer.name.split(' ')[0]}`;
    document.getElementById('emailBtn').href = `mailto:${order.customer.email}`;

    // ---- Status update ----
    document.getElementById('statusUpdateSelect').addEventListener('change', (e) => {
        // NOTE: real update runs `UPDATE orders SET status = ? WHERE
        // order_number = ?` via PHP in the backend phase.
        const newStatus = e.target.value;
        badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
        badge.className = `text-xs font-semibold px-3 py-1.5 rounded-full ${statusStyles[newStatus]}`;
        showToast(`Order marked as ${newStatus}.`);
    });

    // ---- Save internal note ----
    document.getElementById('saveNoteBtn').addEventListener('click', () => {
        // NOTE: real save writes to an `order_notes` table (or a notes
        // column on `orders`) via PHP in the backend phase.
        const msg = document.getElementById('noteSavedMsg');
        msg.classList.remove('hidden');
        setTimeout(() => msg.classList.add('hidden'), 2500);
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
</script>

</body>
</html>