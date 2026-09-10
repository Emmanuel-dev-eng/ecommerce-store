<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER ===================== -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight">ShopName</a>
            <a href="account.php" class="text-gray-700 hover:text-black">
                <i data-lucide="user" class="w-5 h-5"></i>
            </a>
        </div>
    </div>
</header>

<main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <!-- ===================== SUCCESS BANNER ===================== -->
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <i data-lucide="check" class="w-8 h-8 text-green-600"></i>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
        <p class="text-sm text-gray-500">
            Thanks for shopping with us — a confirmation email is on its way.
        </p>
    </div>

    <!-- ===================== STEP PROGRESS ===================== -->
    <div class="flex items-center justify-center gap-2 mb-10 text-xs font-medium">
        <div class="flex items-center gap-2 text-gray-900">
            <span class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-[11px]">
                <i data-lucide="check" class="w-3 h-3"></i>
            </span>
            Cart
        </div>
        <div class="w-8 h-px bg-gray-300"></div>
        <div class="flex items-center gap-2 text-gray-900">
            <span class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-[11px]">
                <i data-lucide="check" class="w-3 h-3"></i>
            </span>
            Checkout
        </div>
        <div class="w-8 h-px bg-gray-300"></div>
        <div class="flex items-center gap-2 text-gray-900">
            <span class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-[11px]">
                <i data-lucide="check" class="w-3 h-3"></i>
            </span>
            Confirmation
        </div>
    </div>

    <!-- ===================== ORDER DETAILS CARD ===================== -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-5 pb-5 border-b border-gray-100">
            <div>
                <p class="text-xs text-gray-500">Order Number</p>
                <p id="orderNumber" class="text-sm font-bold text-gray-900">#—</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Order Date</p>
                <p id="orderDate" class="text-sm font-bold text-gray-900">—</p>
            </div>
        </div>

        <div id="orderItemsList" class="space-y-4 mb-5 pb-5 border-b border-gray-100">
            <!-- Items injected by JS -->
        </div>

        <div class="space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span id="confSubtotal">$0.00</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span id="confShipping">$0.00</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Tax</span>
                <span id="confTax">$0.00</span>
            </div>
            <div class="flex justify-between font-bold text-gray-900 text-base pt-2 border-t border-gray-100">
                <span>Total Paid</span>
                <span id="confTotal">$0.00</span>
            </div>
        </div>
    </div>

    <!-- ===================== SHIPPING INFO ===================== -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
            <i data-lucide="truck" class="w-4 h-4"></i> Shipping To
        </h2>
        <p class="text-sm text-gray-600">
            Standard delivery — estimated arrival in <span class="font-medium text-gray-900">5-7 business days</span>.
        </p>
    </div>

    <!-- ===================== ACTIONS ===================== -->
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="orders.php" class="flex-1 text-center border border-gray-300 text-gray-900 font-semibold py-3 rounded-lg hover:bg-gray-50">
            View My Orders
        </a>
        <a href="shop.php" class="flex-1 text-center bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800">
            Continue Shopping
        </a>
    </div>

</main>

<script>
    lucide.createIcons();

    // ---- Sample order data ----
    // NOTE: in the PHP phase, this entire block gets replaced by data
    // pulled from the real `orders` and `order_items` tables using the
    // order ID just created at checkout.
    const order = {
        number: 'ORD-' + Math.floor(100000 + Math.random() * 900000),
        date: new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }),
        items: [
            { name: 'Classic White Sneakers', variant: 'White, Size M', price: 59.99, qty: 1,
              image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&q=80' },
            { name: 'Oversized Denim Jacket', variant: 'Blue, Size L', price: 89.99, qty: 1,
              image: 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=200&q=80' }
        ],
        shipping: 0
    };

    document.getElementById('orderNumber').textContent = '#' + order.number;
    document.getElementById('orderDate').textContent = order.date;

    const itemsList = document.getElementById('orderItemsList');
    order.items.forEach(item => {
        const row = document.createElement('div');
        row.className = 'flex items-center gap-4';
        row.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${item.name}</p>
                <p class="text-xs text-gray-500">${item.variant} · Qty ${item.qty}</p>
            </div>
            <span class="text-sm font-semibold text-gray-900 flex-shrink-0">$${(item.price * item.qty).toFixed(2)}</span>
        `;
        itemsList.appendChild(row);
    });

    const subtotal = order.items.reduce((s, i) => s + i.price * i.qty, 0);
    const tax = subtotal * 0.075;
    const total = subtotal + order.shipping + tax;

    document.getElementById('confSubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('confShipping').textContent = order.shipping === 0 ? 'Free' : `$${order.shipping.toFixed(2)}`;
    document.getElementById('confTax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('confTotal').textContent = `$${total.toFixed(2)}`;


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