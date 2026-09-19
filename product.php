<?php
// ============================================
// product.php — top section
// ============================================

require 'config/db.php';

// Get the product ID from the URL. (int) forces it to be a whole number,
// which protects against someone typing something weird into the URL.
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If no valid ID was given, redirect to the shop page instead of
// showing a broken page.
if ($productId <= 0) {
    header("Location: shop.php");
    exit;
}

// Fetch the main product info + category name
$stmt = $pdo->prepare("
    SELECT products.*, categories.name AS category_name
    FROM products
    LEFT JOIN categories ON products.category_id = categories.id
    WHERE products.id = ? AND products.is_active = 1
");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If no product was found with that ID, don't show a broken page —
// send them somewhere sensible instead.
if (!$product) {
    header("Location: 404.php");
    exit;
}

// Fetch all images for this product, ordered so the primary shows first
$imagesStmt = $pdo->prepare("
    SELECT image_url, is_primary 
    FROM product_images 
    WHERE product_id = ? 
    ORDER BY is_primary DESC, sort_order ASC
");
$imagesStmt->execute([$productId]);
$images = $imagesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all variants for this product (size/color combos, price, stock)
$variantsStmt = $pdo->prepare("
    SELECT * FROM product_variants WHERE product_id = ?
");
$variantsStmt->execute([$productId]);
$variants = $variantsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($product['name']) ?> | ShopName</title>    <script src="https://cdn.tailwindcss.com"></script>
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

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-gray-500 mb-6">
        <a href="index.php" class="hover:text-black">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="shop.php" class="hover:text-black">Shop</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-gray-900">Classic White Sneakers</span>
    </div>

    <div class="grid md:grid-cols-2 gap-8 lg:gap-12">

        <!-- ===================== IMAGE GALLERY ===================== -->
        <div>
            <div class="rounded-2xl overflow-hidden bg-gray-100 aspect-square mb-4">
                <img id="mainImage" src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80"
                     alt="Classic White Sneakers" class="w-full h-full object-cover">
            </div>
            <div class="grid grid-cols-4 gap-3">
                <button class="thumb rounded-lg overflow-hidden aspect-square border-2 border-black"
                        data-img="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&q=80" class="w-full h-full object-cover">
                </button>
                <button class="thumb rounded-lg overflow-hidden aspect-square border-2 border-transparent hover:border-gray-300"
                        data-img="https://images.unsplash.com/photo-1584735175315-9d5df23860e6?w=800&q=80">
                    <img src="https://images.unsplash.com/photo-1584735175315-9d5df23860e6?w=200&q=80" class="w-full h-full object-cover">
                </button>
                <button class="thumb rounded-lg overflow-hidden aspect-square border-2 border-transparent hover:border-gray-300"
                        data-img="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&q=80">
                    <img src="https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=200&q=80" class="w-full h-full object-cover">
                </button>
                <button class="thumb rounded-lg overflow-hidden aspect-square border-2 border-transparent hover:border-gray-300"
                        data-img="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=800&q=80">
                    <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=200&q=80" class="w-full h-full object-cover">
                </button>
            </div>
        </div>

        <!-- ===================== PRODUCT INFO ===================== -->
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></p>
<h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3"><?= htmlspecialchars($product['name']) ?></h1>

            <div class="flex items-center gap-2 mb-4">
                <div class="flex text-yellow-400">
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-current text-gray-200"></i>
                </div>
                <span class="text-xs text-gray-500">4.2 (86 reviews)</span>
            </div>

<p id="currentPrice" class="text-2xl font-bold text-gray-900 mb-5">$<?= number_format($product['base_price'], 2) ?></p>
        <p class="text-sm text-gray-600 leading-relaxed mb-6">
    <?= nl2br(htmlspecialchars($product['description'])) ?>
</p>

            <!-- Color picker -->
            <div class="mb-6">
                <p class="text-sm font-semibold text-gray-900 mb-3">
                    Color: <span id="selectedColor" class="font-normal text-gray-500">White</span>
                </p>
                <div class="flex gap-3">
                    <button class="color-swatch w-9 h-9 rounded-full bg-white border-2 border-black ring-2 ring-offset-2 ring-black"
                            data-color="White"></button>
                    <button class="color-swatch w-9 h-9 rounded-full bg-gray-900 border-2 border-transparent hover:border-gray-300"
                            data-color="Black"></button>
                    <button class="color-swatch w-9 h-9 rounded-full bg-red-600 border-2 border-transparent hover:border-gray-300"
                            data-color="Red"></button>
                </div>
            </div>

            <!-- Size picker -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-900">
                        Size: <span id="selectedSize" class="font-normal text-gray-500">Select a size</span>
                    </p>
                    <button class="text-xs text-gray-500 underline hover:text-black">Size Guide</button>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button class="size-btn w-12 h-12 border border-gray-300 rounded-lg text-sm font-medium hover:border-black" data-size="S">S</button>
                    <button class="size-btn w-12 h-12 border border-gray-300 rounded-lg text-sm font-medium hover:border-black" data-size="M">M</button>
                    <button class="size-btn w-12 h-12 border border-gray-300 rounded-lg text-sm font-medium hover:border-black" data-size="L">L</button>
                    <button class="size-btn w-12 h-12 border border-gray-200 rounded-lg text-sm font-medium text-gray-300 cursor-not-allowed" disabled data-size="XL">XL</button>
                </div>
                <p id="sizeWarning" class="hidden text-xs text-red-500 mt-2">Please select a size before adding to cart.</p>
            </div>

            <!-- Quantity -->
            <div class="mb-6">
                <p class="text-sm font-semibold text-gray-900 mb-3">Quantity</p>
                <div class="flex items-center border border-gray-300 rounded-lg w-fit">
                    <button id="qtyMinus" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg">
                        <i data-lucide="minus" class="w-4 h-4"></i>
                    </button>
                    <span id="qtyValue" class="w-12 text-center text-sm font-semibold">1</span>
                    <button id="qtyPlus" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                </div>
                <?php $firstVariantStock = $variants[0]['stock_quantity'] ?? 0; ?>
<p id="stockNote" class="text-xs mt-2 flex items-center gap-1 <?= $firstVariantStock > 0 ? 'text-green-600' : 'text-red-500' ?>">
    <i data-lucide="<?= $firstVariantStock > 0 ? 'check-circle' : 'x-circle' ?>" class="w-3 h-3"></i>
    <?= $firstVariantStock > 0 ? "In stock — {$firstVariantStock} available" : 'Out of stock' ?>
</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 mb-6">
                <button id="addToCartBtn" class="flex-1 bg-black text-white font-semibold py-3.5 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    Add to Cart
                </button>
                <button id="wishlistBtn" class="w-14 border border-gray-300 rounded-lg flex items-center justify-center hover:border-black">
                    <i data-lucide="heart" class="w-5 h-5 text-gray-700"></i>
                </button>
            </div>

            <!-- Toast confirmation -->
            <div id="cartToast" class="hidden items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3 mb-6">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span id="cartToastText">Added to cart!</span>
            </div>

            <!-- Trust badges -->
            <div class="grid grid-cols-3 gap-3 border-t border-gray-100 pt-6">
                <div class="flex flex-col items-center text-center gap-1">
                    <i data-lucide="truck" class="w-5 h-5 text-gray-600"></i>
                    <span class="text-[11px] text-gray-500">Free shipping over $75</span>
                </div>
                <div class="flex flex-col items-center text-center gap-1">
                    <i data-lucide="rotate-ccw" class="w-5 h-5 text-gray-600"></i>
                    <span class="text-[11px] text-gray-500">30-day returns</span>
                </div>
                <div class="flex flex-col items-center text-center gap-1">
                    <i data-lucide="shield-check" class="w-5 h-5 text-gray-600"></i>
                    <span class="text-[11px] text-gray-500">Secure checkout</span>
                </div>
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

    // ---- Image gallery: click thumbnail to swap main image ----
    const mainImage = document.getElementById('mainImage');
    document.querySelectorAll('.thumb').forEach(btn => {
        btn.addEventListener('click', () => {
            mainImage.src = btn.dataset.img;
            document.querySelectorAll('.thumb').forEach(t => t.classList.remove('border-black'));
            document.querySelectorAll('.thumb').forEach(t => t.classList.add('border-transparent'));
            btn.classList.remove('border-transparent');
            btn.classList.add('border-black');
        });
    });

    // ---- Color picker ----
    const selectedColorLabel = document.getElementById('selectedColor');
    document.querySelectorAll('.color-swatch').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('ring-2', 'ring-offset-2', 'ring-black'));
            btn.classList.add('ring-2', 'ring-offset-2', 'ring-black');
            selectedColorLabel.textContent = btn.dataset.color;
        });
    });

    // ---- Size picker ----
    const selectedSizeLabel = document.getElementById('selectedSize');
    const sizeWarning = document.getElementById('sizeWarning');
    let chosenSize = null;
    document.querySelectorAll('.size-btn:not([disabled])').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.size-btn').forEach(s => {
                s.classList.remove('bg-black', 'text-white', 'border-black');
            });
            btn.classList.add('bg-black', 'text-white', 'border-black');
            chosenSize = btn.dataset.size;
            selectedSizeLabel.textContent = chosenSize;
            sizeWarning.classList.add('hidden');
        });
    });

    // ---- Quantity stepper ----
    const qtyValue = document.getElementById('qtyValue');
    let qty = 1;
    const maxStock = 14;
    document.getElementById('qtyMinus').addEventListener('click', () => {
        if (qty > 1) { qty--; qtyValue.textContent = qty; }
    });
    document.getElementById('qtyPlus').addEventListener('click', () => {
        if (qty < maxStock) { qty++; qtyValue.textContent = qty; }
    });

    // ---- Add to cart (client-side validation + confirmation) ----
    const cartToast = document.getElementById('cartToast');
    const cartToastText = document.getElementById('cartToastText');
    document.getElementById('addToCartBtn').addEventListener('click', () => {
        if (!chosenSize) {
            sizeWarning.classList.remove('hidden');
            return;
        }
        cartToastText.textContent = `Added ${qty} × Classic White Sneakers (${selectedColorLabel.textContent}, ${chosenSize}) to cart!`;
        cartToast.classList.remove('hidden');
        cartToast.classList.add('flex');
        // NOTE: this confirms the interaction visually. Actually saving
        // the item and persisting it across pages needs PHP sessions +
        // the database — that's wired up in the backend phase.
        setTimeout(() => cartToast.classList.add('hidden'), 4000);
    });

    // ---- Wishlist toggle ----
    const wishlistBtn = document.getElementById('wishlistBtn');
    let wishlisted = false;
    wishlistBtn.addEventListener('click', () => {
        wishlisted = !wishlisted;
        const icon = wishlistBtn.querySelector('i');
        if (wishlisted) {
            wishlistBtn.classList.add('border-black');
            icon.setAttribute('class', 'w-5 h-5 text-red-500 fill-current');
        } else {
            wishlistBtn.classList.remove('border-black');
            icon.setAttribute('class', 'w-5 h-5 text-gray-700');
        }
        lucide.createIcons();
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