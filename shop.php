<?php
// ============================================
// shop.php — top section
// Fetches all active products with their primary image
// ============================================

require 'config/db.php';

// This query joins three tables together:
// - products: the core product info
// - product_images: to get each product's main photo
// - categories: to show the category name
//
// LEFT JOIN means: "include the product even if it has no matching
// image row" (so a product never disappears just because it's missing
// a photo — it would just show blank instead).
//
// The "AND product_images.is_primary = 1" ensures we only grab
// the ONE image marked as primary per product, not duplicates.

$sql = "
    SELECT 
        products.id,
        products.name,
        products.slug,
        products.base_price,
        categories.name AS category_name,
        product_images.image_url
    FROM products
    LEFT JOIN product_images 
        ON products.id = product_images.product_id 
        AND product_images.is_primary = 1
    LEFT JOIN categories 
        ON products.category_id = categories.id
    WHERE products.is_active = 1
    ORDER BY products.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
// fetchAll() grabs EVERY matching row, as an array of associative arrays.
// So $products[0]['name'], $products[1]['name'], etc.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | ShopName</title>
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
                <a href="shop.php" class="text-sm font-medium text-gray-900">Shop</a>
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
            <a href="index.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="home" class="w-5 h-5"></i><span class="text-sm font-medium">Home</span>
            </a>
            <a href="shop.php" class="flex items-center gap-3 py-3 px-2 rounded-lg bg-gray-100 text-gray-900">
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

    <!-- ===================== PAGE HEADING ===================== -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Shop All</h1>
            <p class="text-sm text-gray-500 mt-1"><?= count($products) ?> products</p>
        </div>

        <!-- Filter button (mobile only) -->
        <button id="filterToggle" class="sm:hidden flex items-center gap-2 border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium">
            <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
            Filters
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-8">

        <!-- ===================== FILTER SIDEBAR (desktop) ===================== -->
        <aside class="hidden sm:block sm:col-span-1">
            <div class="space-y-8">

                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Category</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" class="rounded border-gray-300"> Men
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" class="rounded border-gray-300"> Women
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" class="rounded border-gray-300"> Shoes
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" class="rounded border-gray-300"> Accessories
                        </label>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Price Range</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="radio" name="price" class="border-gray-300"> Under $50
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="radio" name="price" class="border-gray-300"> $50 - $100
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="radio" name="price" class="border-gray-300"> $100 - $200
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="radio" name="price" class="border-gray-300"> Over $200
                        </label>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Size</h3>
                    <div class="flex flex-wrap gap-2">
                        <button class="w-9 h-9 border border-gray-300 rounded-lg text-xs font-medium hover:border-black">S</button>
                        <button class="w-9 h-9 border border-gray-300 rounded-lg text-xs font-medium hover:border-black">M</button>
                        <button class="w-9 h-9 border border-gray-300 rounded-lg text-xs font-medium hover:border-black">L</button>
                        <button class="w-9 h-9 border border-gray-300 rounded-lg text-xs font-medium hover:border-black">XL</button>
                    </div>
                </div>

                <button class="w-full bg-black text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-gray-800">
                    Apply Filters
                </button>
            </div>
        </aside>

        <!-- ===================== PRODUCT GRID ===================== -->
        <div class="sm:col-span-3">

            <!-- Sort bar -->
            <div class="flex items-center justify-end mb-4">
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                    <option>Sort by: Newest</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Most Popular</option>
                </select>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

               <?php foreach ($products as $product): ?>
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

            <!-- Pagination -->
            <div class="flex items-center justify-center gap-2 mt-10">
                <button class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-100">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-black text-white text-sm font-medium">1</button>
                <button class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 text-sm font-medium hover:bg-gray-100">2</button>
                <button class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 text-sm font-medium hover:bg-gray-100">3</button>
                <button class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-100">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>

        </div>
    </div>
</main>

<!-- ===================== MOBILE FILTER DRAWER ===================== -->
<div id="filterDrawer" class="hidden fixed inset-0 z-50">
    <div id="filterOverlay" class="absolute inset-0 bg-black/50"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-2xl max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 sticky top-0 bg-white">
            <h2 class="text-lg font-bold">Filters</h2>
            <button id="filterClose" class="text-gray-500 hover:text-black">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="px-5 py-5 space-y-8">
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-3">Category</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 text-sm text-gray-600">
                        <input type="checkbox" class="rounded border-gray-300 w-4 h-4"> Men
                    </label>
                    <label class="flex items-center gap-3 text-sm text-gray-600">
                        <input type="checkbox" class="rounded border-gray-300 w-4 h-4"> Women
                    </label>
                    <label class="flex items-center gap-3 text-sm text-gray-600">
                        <input type="checkbox" class="rounded border-gray-300 w-4 h-4"> Shoes
                    </label>
                    <label class="flex items-center gap-3 text-sm text-gray-600">
                        <input type="checkbox" class="rounded border-gray-300 w-4 h-4"> Accessories
                    </label>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-3">Price Range</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 text-sm text-gray-600">
                        <input type="radio" name="mPrice" class="border-gray-300 w-4 h-4"> Under $50
                    </label>
                    <label class="flex items-center gap-3 text-sm text-gray-600">
                        <input type="radio" name="mPrice" class="border-gray-300 w-4 h-4"> $50 - $100
                    </label>
                    <label class="flex items-center gap-3 text-sm text-gray-600">
                        <input type="radio" name="mPrice" class="border-gray-300 w-4 h-4"> $100 - $200
                    </label>
                    <label class="flex items-center gap-3 text-sm text-gray-600">
                        <input type="radio" name="mPrice" class="border-gray-300 w-4 h-4"> Over $200
                    </label>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-3">Size</h3>
                <div class="flex flex-wrap gap-2">
                    <button class="w-11 h-11 border border-gray-300 rounded-lg text-sm font-medium hover:border-black">S</button>
                    <button class="w-11 h-11 border border-gray-300 rounded-lg text-sm font-medium hover:border-black">M</button>
                    <button class="w-11 h-11 border border-gray-300 rounded-lg text-sm font-medium hover:border-black">L</button>
                    <button class="w-11 h-11 border border-gray-300 rounded-lg text-sm font-medium hover:border-black">XL</button>
                </div>
            </div>
        </div>

        <div class="px-5 py-4 border-t border-gray-100 sticky bottom-0 bg-white">
            <button id="filterApply" class="w-full bg-black text-white text-sm font-semibold py-3 rounded-lg hover:bg-gray-800">
                Apply Filters
            </button>
        </div>
    </div>
</div>

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

    // Mobile filter drawer
    const filterDrawer = document.getElementById('filterDrawer');
    document.getElementById('filterToggle').addEventListener('click', () => {
        filterDrawer.classList.remove('hidden');
    });
    document.getElementById('filterClose').addEventListener('click', () => {
        filterDrawer.classList.add('hidden');
    });
    document.getElementById('filterOverlay').addEventListener('click', () => {
        filterDrawer.classList.add('hidden');
    });
    document.getElementById('filterApply').addEventListener('click', () => {
        filterDrawer.classList.add('hidden');
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