<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER (simplified) ===================== -->
<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-center h-16">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight">ShopName</a>
        </div>
    </div>
</header>

<main class="max-w-lg mx-auto px-4 sm:px-6 py-20 text-center">

    <div class="w-20 h-20 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-6">
        <i data-lucide="compass" class="w-9 h-9 text-white"></i>
    </div>

    <p class="text-6xl font-bold text-gray-900 mb-2">404</p>
    <h1 class="text-xl font-bold text-gray-900 mb-2">Page Not Found</h1>
    <p class="text-sm text-gray-500 mb-8">
        The page you're looking for doesn't exist, may have been moved, or the link might be broken.
    </p>

    <!-- ===================== QUICK SEARCH ===================== -->
    <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-8 text-left">
        <label class="text-xs font-semibold text-gray-600 mb-2 block">Try searching for what you need</label>
        <div class="flex gap-2">
            <div class="relative flex-1">
                <input type="text" id="notFoundSearch" placeholder="Search products..."
                    class="w-full border border-gray-300 rounded-lg py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <button id="notFoundSearchBtn" class="bg-black text-white px-4 rounded-lg text-sm font-medium hover:bg-gray-800 flex-shrink-0">
                Search
            </button>
        </div>
    </div>

    <!-- ===================== QUICK LINKS ===================== -->
    <div class="flex flex-col sm:flex-row gap-3 justify-center mb-10">
        <a href="index.php" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-black text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-gray-800">
            <i data-lucide="home" class="w-4 h-4"></i> Back to Home
        </a>
        <a href="shop.php" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 border border-gray-300 text-gray-900 text-sm font-semibold px-6 py-3 rounded-lg hover:bg-gray-50">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i> Browse Shop
        </a>
    </div>

    <!-- ===================== HELPFUL LINKS ===================== -->
    <div class="text-left">
        <p class="text-xs font-semibold text-gray-500 mb-3">You might also be looking for:</p>
        <div class="grid grid-cols-2 gap-2">
            <a href="account.php" class="text-sm text-gray-600 hover:text-black flex items-center gap-2 p-2 rounded-lg hover:bg-white">
                <i data-lucide="user" class="w-4 h-4"></i> My Account
            </a>
            <a href="orders.php" class="text-sm text-gray-600 hover:text-black flex items-center gap-2 p-2 rounded-lg hover:bg-white">
                <i data-lucide="package" class="w-4 h-4"></i> My Orders
            </a>
            <a href="cart.php" class="text-sm text-gray-600 hover:text-black flex items-center gap-2 p-2 rounded-lg hover:bg-white">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i> My Cart
            </a>
            <a href="contact.php" class="text-sm text-gray-600 hover:text-black flex items-center gap-2 p-2 rounded-lg hover:bg-white">
                <i data-lucide="mail" class="w-4 h-4"></i> Contact Us
            </a>
        </div>
    </div>

</main>

<script>
    lucide.createIcons();

    // ---- Search redirect (button + Enter key) ----
    function runNotFoundSearch() {
        const term = document.getElementById('notFoundSearch').value.trim();
        if (term) {
            window.location.href = `search.php?q=${encodeURIComponent(term)}`;
        }
    }
    document.getElementById('notFoundSearchBtn').addEventListener('click', runNotFoundSearch);
    document.getElementById('notFoundSearch').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') runNotFoundSearch();
    });
</script>

</body>
</html>