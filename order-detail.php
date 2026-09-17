<?php require 'config/auth_check.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER ===================== -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight">ShopName</a>
            <div class="flex items-center gap-4">
                <a href="cart.php" class="relative text-gray-700 hover:text-black">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span class="absolute -top-2 -right-2 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">2</span>
                </a>
                <a href="account.php" class="text-gray-700 hover:text-black">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-6">
        <a href="orders.php" class="hover:text-black flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Orders
        </a>
    </div>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 id="orderTitle" class="text-xl font-bold text-gray-900">Order #—</h1>
            <p id="orderMeta" class="text-sm text-gray-500 mt-1">—</p>
        </div>
        <span id="statusBadge" class="text-xs font-semibold px-3 py-1.5 rounded-full flex-shrink-0"></span>
    </div>

    <!-- ===================== TRACKING TIMELINE ===================== -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="text-sm font-bold text-gray-900 mb-6">Order Tracking</h2>
        <div id="trackingTimeline" class="relative">
            <!-- Injected by JS -->
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        <!-- ===================== ITEMS ===================== -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-sm font-bold text-gray-900 mb-4">Items</h2>
                <div id="orderItemsList" class="space-y-4">
                    <!-- Injected by JS -->
                </div>
            </div>
        </div>

        <!-- ===================== SUMMARY + ADDRESS ===================== -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-sm font-bold text-gray-900 mb-4">Summary</h2>
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
                <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> Shipping Address
                </h2>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Jane Doe<br>
                    12 Palm Avenue, Lekki<br>
                    Lagos, Nigeria<br>
                    +234 800 000 0000
                </p>
            </div>

            <button id="cancelOrderBtn" class="w-full border border-red-200 text-red-600 text-sm font-semibold py-3 rounded-lg hover:bg-red-50">
                Cancel Order
            </button>
        </div>
    </div>

</main>

<script>
    lucide.createIcons();

    // ---- Sample order lookup ----
    // NOTE: in the PHP phase, the ?order= value in the URL is used in a
    // real SQL query (WHERE order_number = ...) to pull this from the
    // `orders` and `order_items` tables. Here we simulate it with a
    // small lookup table so the page is fully interactive right now.
    const sampleOrders = {
        'ORD-482913': {
            date: 'Aug 20, 2026', status: 'shipped',
            items: [
                { name: 'Classic White Sneakers', variant: 'White, Size M', price: 59.99, qty: 1, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&q=80' },
                { name: 'Wool Blend Overcoat', variant: 'Grey, Size L', price: 89.99, qty: 1, image: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=200&q=80' }
            ],
            shipping: 0
        },
        'ORD-471820': {
            date: 'Aug 10, 2026', status: 'delivered',
            items: [{ name: 'Leather Crossbody Bag', variant: 'Tan', price: 74.99, qty: 1, image: 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=200&q=80' }],
            shipping: 0
        },
        'ORD-459102': {
            date: 'Jul 15, 2026', status: 'processing',
            items: [{ name: 'Classic White Sneakers', variant: 'White, Size L', price: 59.99, qty: 1, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&q=80' }],
            shipping: 0
        },
        'ORD-450221': {
            date: 'Jul 2, 2026', status: 'pending',
            items: [{ name: 'Minimalist Wrist Watch', variant: 'Silver', price: 129.99, qty: 1, image: 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=200&q=80' }],
            shipping: 0
        }
    };

    // Get order number from URL, e.g. order-detail.php?order=ORD-482913
    const params = new URLSearchParams(window.location.search);
    const orderNum = params.get('order') || 'ORD-482913'; // fallback for direct preview
    const order = sampleOrders[orderNum] || sampleOrders['ORD-482913'];

    const statusStyles = {
        pending: 'bg-gray-100 text-gray-600', processing: 'bg-yellow-50 text-yellow-700',
        shipped: 'bg-blue-50 text-blue-600', delivered: 'bg-green-50 text-green-600'
    };
    const statusLabels = { pending: 'Pending', processing: 'Processing', shipped: 'Shipped', delivered: 'Delivered' };
    const statusOrder = ['pending', 'processing', 'shipped', 'delivered'];

    document.getElementById('orderTitle').textContent = `Order #${orderNum}`;
    document.getElementById('orderMeta').textContent = `Placed on ${order.date} · ${order.items.length} item${order.items.length > 1 ? 's' : ''}`;
    const badge = document.getElementById('statusBadge');
    badge.textContent = statusLabels[order.status];
    badge.className = `text-xs font-semibold px-3 py-1.5 rounded-full flex-shrink-0 ${statusStyles[order.status]}`;

    // ---- Tracking timeline (dynamically built based on current status) ----
    const steps = [
        { key: 'pending', label: 'Order Placed', icon: 'circle-check' },
        { key: 'processing', label: 'Processing', icon: 'package' },
        { key: 'shipped', label: 'Shipped', icon: 'truck' },
        { key: 'delivered', label: 'Delivered', icon: 'home' }
    ];
    const currentIndex = statusOrder.indexOf(order.status);
    const timeline = document.getElementById('trackingTimeline');

    steps.forEach((step, i) => {
        const isDone = i <= currentIndex;
        const row = document.createElement('div');
        row.className = 'flex items-start gap-4 pb-6 last:pb-0 relative';
        row.innerHTML = `
            ${i < steps.length - 1 ? `<span class="absolute left-4 top-8 w-px h-full ${isDone ? 'bg-black' : 'bg-gray-200'}"></span>` : ''}
            <span class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 z-10 ${isDone ? 'bg-black text-white' : 'bg-gray-100 text-gray-400'}">
                <i data-lucide="${step.icon}" class="w-4 h-4"></i>
            </span>
            <div>
                <p class="text-sm font-semibold ${isDone ? 'text-gray-900' : 'text-gray-400'}">${step.label}</p>
                ${i === currentIndex ? '<p class="text-xs text-gray-500 mt-0.5">Current status</p>' : ''}
            </div>
        `;
        timeline.appendChild(row);
    });

    // ---- Items ----
    const itemsList = document.getElementById('orderItemsList');
    order.items.forEach(item => {
        const row = document.createElement('div');
        row.className = 'flex items-center gap-4';
        row.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${item.name}</p>
                <p class="text-xs text-gray-500">${item.variant} · Qty ${item.qty}</p>
            </div>
            <span class="text-sm font-semibold text-gray-900 flex-shrink-0">$${(item.price * item.qty).toFixed(2)}</span>
        `;
        itemsList.appendChild(row);
    });

    // ---- Totals ----
    const subtotal = order.items.reduce((s, i) => s + i.price * i.qty, 0);
    const tax = subtotal * 0.075;
    const total = subtotal + order.shipping + tax;
    document.getElementById('detSubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('detShipping').textContent = order.shipping === 0 ? 'Free' : `$${order.shipping.toFixed(2)}`;
    document.getElementById('detTax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('detTotal').textContent = `$${total.toFixed(2)}`;

    lucide.createIcons();

    // ---- Cancel order button ----
    const cancelBtn = document.getElementById('cancelOrderBtn');
    if (order.status === 'shipped' || order.status === 'delivered') {
        cancelBtn.disabled = true;
        cancelBtn.classList.add('opacity-40', 'cursor-not-allowed');
        cancelBtn.title = 'This order can no longer be cancelled.';
    }
    cancelBtn.addEventListener('click', () => {
        if (cancelBtn.disabled) return;
        if (confirm('Are you sure you want to cancel this order?')) {
            // NOTE: real cancellation updates the `orders.status` row via
            // PHP in the backend phase. This confirms the interaction works.
            badge.textContent = 'Cancelled';
            badge.className = 'text-xs font-semibold px-3 py-1.5 rounded-full flex-shrink-0 bg-red-50 text-red-600';
            cancelBtn.disabled = true;
            cancelBtn.textContent = 'Order Cancelled';
            cancelBtn.classList.add('opacity-40', 'cursor-not-allowed');
        }
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