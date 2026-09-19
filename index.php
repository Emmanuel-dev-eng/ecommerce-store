<?php
// ============================================
// index.php — top section
// Fetches a few featured products for the homepage
// ============================================

require 'config/db.php';

$sql = "
    SELECT 
        products.id,
        products.name,
        products.base_price,
        product_images.image_url
    FROM products
    LEFT JOIN product_images 
        ON products.id = product_images.product_id 
        AND product_images.is_primary = 1
    WHERE products.is_active = 1
    ORDER BY products.created_at DESC
    LIMIT 4
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | ShopName</title>
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
                <a href="index.php" class="text-sm font-medium text-gray-900">Home</a>
                <a href="shop.php" class="text-sm font-medium text-gray-700 hover:text-black">Shop</a>
                <a href="about.php" class="text-sm font-medium text-gray-700 hover:text-black">About</a>
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
            <div class="relative">
                <input type="text" placeholder="Search products..."
                    class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100">
        <nav class="flex flex-col px-4 py-4 gap-1">
            <a href="index.php" class="flex items-center gap-3 py-3 px-2 rounded-lg bg-gray-100 text-gray-900">
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

<main>

    <!-- ===================== HERO SECTION ===================== -->
    <section class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 grid md:grid-cols-2 gap-8 items-center">
            <div>
                <p class="text-sm uppercase tracking-widest text-gray-400 mb-3">New Season Collection</p>
                <h1 class="text-3xl sm:text-5xl font-bold leading-tight mb-4">
                    Style That Speaks<br>Before You Do
                </h1>
                <p class="text-gray-300 mb-6 max-w-md">
                    Discover pieces made to last — quality fabrics, honest prices, delivered to your door.
                </p>
                <a href="shop.php" class="inline-flex items-center gap-2 bg-white text-gray-900 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition">
                    Shop Now
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            <div class="hidden md:block">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&q=80"
                     alt="Featured fashion collection"
                     class="rounded-2xl w-full h-80 object-cover">
            </div>
        </div>
    </section>

    <!-- ===================== CATEGORY STRIP ===================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-xl font-bold mb-6">Shop by Category</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

            <a href="shop.php?category=men" class="relative rounded-xl overflow-hidden group h-32 sm:h-40">
                <img src="https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=400&q=80" alt="Men's clothing"
                     class="w-full h-full object-cover group-hover:scale-105 transition">
                <div class="absolute inset-0 bg-black/40 flex items-end p-3">
                    <span class="text-white font-semibold text-sm">Men</span>
                </div>
            </a>

            <a href="shop.php?category=women" class="relative rounded-xl overflow-hidden group h-32 sm:h-40">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=400&q=80" alt="Women's clothing"
                     class="w-full h-full object-cover group-hover:scale-105 transition">
                <div class="absolute inset-0 bg-black/40 flex items-end p-3">
                    <span class="text-white font-semibold text-sm">Women</span>
                </div>
            </a>

            <a href="shop.php?category=shoes" class="relative rounded-xl overflow-hidden group h-32 sm:h-40">
                <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&q=80" alt="Shoes"
                     class="w-full h-full object-cover group-hover:scale-105 transition">
                <div class="absolute inset-0 bg-black/40 flex items-end p-3">
                    <span class="text-white font-semibold text-sm">Shoes</span>
                </div>
            </a>

            <a href="shop.php?category=accessories" class="relative rounded-xl overflow-hidden group h-32 sm:h-40">
                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80" alt="Accessories"
                     class="w-full h-full object-cover group-hover:scale-105 transition">
                <div class="absolute inset-0 bg-black/40 flex items-end p-3">
                    <span class="text-white font-semibold text-sm">Accessories</span>
                </div>
            </a>

        </div>
    </section>

    <!-- ===================== FEATURED PRODUCTS GRID ===================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 pb-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Featured Products</h2>
            <a href="shop.php" class="text-sm font-medium text-gray-600 hover:text-black flex items-center gap-1">
                View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">

          

<?php foreach ($featuredProducts as $product): ?>
    <a href="product.php?id=<?= $product['id'] ?>" class="group">
        <div class="relative rounded-xl overflow-hidden bg-gray-100 aspect-square mb-3">
            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                 class="w-full h-full object-cover group-hover:scale-105 transition">
            <button class="absolute top-2 right-2 bg-white/90 rounded-full p-2 hover:bg-white">
                <i data-lucide="heart" class="w-4 h-4 text-gray-700"></i>
            </button>
        </div>
        <h3 class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($product['name']) ?></h3>
        <p class="text-sm font-bold text-gray-900 mt-1">$<?= number_format($product['base_price'], 2) ?></p>
    </a>
<?php endforeach; ?>



        </div>
    </section>

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