<?php?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | ShopName</title>
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
                <a href="cart.php" class="relative text-gray-900">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span id="cartBadge" class="absolute -top-2 -right-2 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">2</span>
                </a>
                <a href="account.php" class="hidden md:block text-gray-700 hover:text-black">
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
            <a href="orders.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
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

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <h1 class="text-2xl font-bold text-gray-900 mb-6">Your Cart</h1>

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- ===================== CART ITEMS ===================== -->
        <div class="lg:col-span-2">
            <div id="cartItemsContainer" class="space-y-4">
                <!-- Cart items are injected here by JavaScript -->
            </div>

            <div id="emptyCartMsg" class="hidden text-center py-16">
                <i data-lucide="shopping-cart" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                <p class="text-gray-500 mb-4">Your cart is empty.</p>
                <a href="shop.php" class="inline-block bg-black text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-gray-800">
                    Continue Shopping
                </a>
            </div>
        </div>

        <!-- ===================== ORDER SUMMARY ===================== -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="subtotalValue">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span id="shippingValue">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Tax (est.)</span>
                        <span id="taxValue">$0.00</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 mt-4 pt-4 flex justify-between font-bold text-gray-900">
                    <span>Total</span>
                    <span id="totalValue">$0.00</span>
                </div>

                <!-- Promo code -->
                <div class="mt-5">
                    <label class="text-xs font-semibold text-gray-500 mb-2 block">Promo Code</label>
                    <div class="flex gap-2">
                        <input type="text" placeholder="Enter code" id="promoInput"
                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <button id="promoApply" class="bg-gray-100 text-gray-700 text-sm font-medium px-4 rounded-lg hover:bg-gray-200">
                            Apply
                        </button>
                    </div>
                    <!-- NOTE: promo codes are validated visually here. Checking
                         a real code against the database happens in the PHP phase. -->
                    <p id="promoMsg" class="hidden text-xs mt-2"></p>
                </div>

                <a href="checkout.php" id="checkoutBtn" class="mt-6 w-full bg-black text-white font-semibold py-3.5 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2">
                    Proceed to Checkout
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>

                <p class="text-[11px] text-gray-400 text-center mt-3 flex items-center justify-center gap-1">
                    <i data-lucide="lock" class="w-3 h-3"></i> Secure checkout
                </p>
            </div>
        </div>
    </div>
</main>

<!-- ===================== FOOTER ===================== -->
<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-8">            <div>
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

    // ---- Sample cart data ----
    // NOTE: this array simulates what will later come from the database
    // via PHP sessions (cart_items table). Everything below (quantity,
    // totals, removal) is fully working client-side logic.
    let cart = [
        {
            id: 1,
            name: 'Classic White Sneakers',
            variant: 'White, Size M',
            price: 59.99,
            qty: 1,
            image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&q=80'
        },
        {
            id: 2,
            name: 'Oversized Denim Jacket',
            variant: 'Blue, Size L',
            price: 89.99,
            qty: 1,
            image: 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=200&q=80'
        }
    ];

    const container = document.getElementById('cartItemsContainer');
    const emptyMsg = document.getElementById('emptyCartMsg');
    const cartBadge = document.getElementById('cartBadge');
    const checkoutBtn = document.getElementById('checkoutBtn');

    function renderCart() {
        container.innerHTML = '';

        if (cart.length === 0) {
            emptyMsg.classList.remove('hidden');
            checkoutBtn.classList.add('pointer-events-none', 'opacity-40');
        } else {
            emptyMsg.classList.add('hidden');
            checkoutBtn.classList.remove('pointer-events-none', 'opacity-40');
        }

        cart.forEach(item => {
            const row = document.createElement('div');
            row.className = 'bg-white rounded-2xl border border-gray-200 p-4 flex gap-4 items-center';
            row.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold text-gray-900 truncate">${item.name}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">${item.variant}</p>
                    <p class="text-sm font-bold text-gray-900 mt-2">$${item.price.toFixed(2)}</p>
                </div>
                <div class="flex flex-col items-end gap-3">
                    <button class="remove-btn text-gray-400 hover:text-red-500" data-id="${item.id}">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <button class="qty-minus w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg" data-id="${item.id}">
                            <i data-lucide="minus" class="w-3 h-3"></i>
                        </button>
                        <span class="w-8 text-center text-xs font-semibold">${item.qty}</span>
                        <button class="qty-plus w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg" data-id="${item.id}">
                            <i data-lucide="plus" class="w-3 h-3"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(row);
        });

        lucide.createIcons();
        attachRowEvents();
        updateTotals();
    }

    function attachRowEvents() {
        document.querySelectorAll('.qty-plus').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = cart.find(i => i.id == btn.dataset.id);
                if (item.qty < 10) item.qty++;
                renderCart();
            });
        });
        document.querySelectorAll('.qty-minus').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = cart.find(i => i.id == btn.dataset.id);
                if (item.qty > 1) item.qty--;
                renderCart();
            });
        });
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                cart = cart.filter(i => i.id != btn.dataset.id);
                renderCart();
            });
        });
    }

    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
        const shipping = subtotal === 0 ? 0 : (subtotal >= 75 ? 0 : 6.99);
        const tax = subtotal * 0.075;
        const total = subtotal + shipping + tax;
        const itemCount = cart.reduce((sum, item) => sum + item.qty, 0);

        document.getElementById('subtotalValue').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('shippingValue').textContent = shipping === 0 ? 'Free' : `$${shipping.toFixed(2)}`;
        document.getElementById('taxValue').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('totalValue').textContent = `$${total.toFixed(2)}`;
        cartBadge.textContent = itemCount;
    }

    // ---- Promo code (visual validation demo) ----
    document.getElementById('promoApply').addEventListener('click', () => {
        const code = document.getElementById('promoInput').value.trim().toUpperCase();
        const msg = document.getElementById('promoMsg');
        msg.classList.remove('hidden');
        if (code === 'SAVE10') {
            msg.className = 'text-xs mt-2 text-green-600';
            msg.textContent = 'Promo code applied! 10% off (demo only).';
        } else if (code === '') {
            msg.className = 'text-xs mt-2 text-gray-400';
            msg.textContent = 'Enter a code first.';
        } else {
            msg.className = 'text-xs mt-2 text-red-500';
            msg.textContent = 'Invalid or expired code.';
        }
    });

    renderCart();

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