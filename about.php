<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | ShopName</title>
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
                <a href="about.php" class="text-sm font-medium text-gray-900">About</a>
                <a href="contact.php" class="text-sm font-medium text-gray-700 hover:text-black">Contact</a>
            </nav>

            <div class="flex items-center gap-4">
                <button id="searchToggle" class="text-gray-700 hover:text-black">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
                <a href="cart.php" class="relative text-gray-700 hover:text-black">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span class="absolute -top-2 -right-2 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">2</span>
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
            <div class="relative flex gap-2">
                <div class="relative flex-1">
                    <input type="text" id="headerSearchInput" placeholder="Search products..."
                        class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
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
            <a href="about.php" class="flex items-center gap-3 py-3 px-2 rounded-lg bg-gray-100 text-gray-900">
                <i data-lucide="info" class="w-5 h-5"></i><span class="text-sm font-medium">About</span>
            </a>
            <a href="contact.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="mail" class="w-5 h-5"></i><span class="text-sm font-medium">Contact</span>
            </a>
        </nav>
    </div>
</header>

<main>

    <!-- ===================== HERO ===================== -->
    <section class="bg-gray-900 text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 text-center">
            <p class="text-sm uppercase tracking-widest text-gray-400 mb-3">Our Story</p>
            <h1 class="text-3xl sm:text-4xl font-bold mb-4">Built on Quality, Driven by People</h1>
            <p class="text-gray-300 max-w-2xl mx-auto">
                We started ShopName with one goal: make quality fashion accessible without compromising on craft.
            </p>
        </div>
    </section>

    <!-- ===================== STORY SECTION ===================== -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid md:grid-cols-2 gap-10 items-center">
        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=700&q=80"
             alt="Our workshop" class="rounded-2xl w-full h-72 object-cover">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Who We Are</h2>
            <p class="text-sm text-gray-600 leading-relaxed mb-4">
                ShopName began as a small idea between friends who believed everyday fashion could be both
                well-made and honestly priced. What started as a handful of pieces has grown into a full
                collection, but our approach hasn't changed.
            </p>
            <p class="text-sm text-gray-600 leading-relaxed">
                Every product goes through careful sourcing, quality checks, and a team that genuinely
                cares about what ends up in your hands.
            </p>
        </div>
    </section>

    <!-- ===================== VALUES ===================== -->
    <section class="bg-white border-y border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-10">What We Stand For</h2>
            <div class="grid sm:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="gem" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Quality First</h3>
                    <p class="text-sm text-gray-500">Every piece is checked for durability and craftsmanship before it reaches you.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="heart-handshake" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Honest Pricing</h3>
                    <p class="text-sm text-gray-500">No inflated markups — fair prices for genuinely well-made products.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="leaf" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Responsible Sourcing</h3>
                    <p class="text-sm text-gray-500">We work with suppliers who share our standards for ethical production.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== STATS STRIP ===================== -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
            <div>
                <p class="text-3xl font-bold text-gray-900">1,200+</p>
                <p class="text-xs text-gray-500 mt-1">Happy Customers</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">340+</p>
                <p class="text-xs text-gray-500 mt-1">Products</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">4.7★</p>
                <p class="text-xs text-gray-500 mt-1">Average Rating</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">3</p>
                <p class="text-xs text-gray-500 mt-1">Years Running</p>
            </div>
        </div>
    </section>

    <!-- ===================== CTA ===================== -->
    <section class="bg-gray-900 text-white text-center py-16">
        <h2 class="text-2xl font-bold mb-3">Ready to shop the collection?</h2>
        <p class="text-gray-300 mb-6 text-sm">Discover pieces made to last, at prices that make sense.</p>
        <a href="shop.php" class="inline-flex items-center gap-2 bg-white text-gray-900 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition">
            Browse Products <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </section>

</main>

<!-- ===================== FOOTER ===================== -->
<footer class="bg-gray-900 text-gray-300">
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
    document.getElementById('headerSearchInput').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const term = e.target.value.trim();
            if (term) window.location.href = `search.php?q=${encodeURIComponent(term)}`;
        }
    });
</script>

</body>
</html>