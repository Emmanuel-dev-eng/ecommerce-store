<?php
// ============================================
// cart.php
// The cart lives in the DATABASE (cart_items table), so it
// survives logout, login, and closing the browser.
// ============================================

require 'config/auth_check.php'; // starts the session + blocks logged-out visitors
require 'config/db.php';

$userId = (int)$_SESSION['user_id'];

// ---- Handle form submissions (update quantity / remove) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action    = $_POST['action'] ?? '';
    $variantId = (int)($_POST['variant_id'] ?? 0);

    if ($variantId > 0) {

        if ($action === 'remove') {
            // "user_id = ?" means a user can only delete from THEIR OWN cart
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND variant_id = ?");
            $stmt->execute([$userId, $variantId]);

        } elseif ($action === 'update') {
            $quantity = (int)($_POST['quantity'] ?? 0);

            // Real stock for this variant, from the database
            $stockStmt = $pdo->prepare("SELECT stock_quantity FROM product_variants WHERE id = ?");
            $stockStmt->execute([$variantId]);
            $stock = (int)$stockStmt->fetchColumn();

            // min() picks the smaller number, so we never exceed stock
            $newQty = min($quantity, $stock);

            if ($newQty > 0) {
                $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND variant_id = ?");
                $stmt->execute([$newQty, $userId, $variantId]);
            } else {
                $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND variant_id = ?");
                $stmt->execute([$userId, $variantId]);
            }
        }
    }

    // Post/Redirect/Get, so refreshing doesn't resubmit the form
    header("Location: cart.php");
    exit;
}

// ---- Build the list of cart items from the database ----
$cartItems = [];
$subtotal = 0;
$itemCount = 0;

// One query: this user's cart rows, joined with product info + image
$stmt = $pdo->prepare("
    SELECT
        ci.variant_id,
        ci.quantity,
        pv.sku,
        pv.price,
        pv.stock_quantity,
        p.name,
        pi.image_url
    FROM cart_items ci
    JOIN product_variants pv ON ci.variant_id = pv.id
    JOIN products p ON pv.product_id = p.id
    LEFT JOIN product_images pi
        ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE ci.user_id = ? AND p.is_active = 1
    ORDER BY ci.added_at DESC
");
$stmt->execute([$userId]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch size/color labels (e.g. "M, White") for these variants
$labels = [];
if (!empty($rows)) {
    $ids = array_column($rows, 'variant_id'); // just the list of variant IDs
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $attrStmt = $pdo->prepare("
        SELECT vav.variant_id, av.value
        FROM variant_attribute_values vav
        JOIN attribute_values av ON vav.attribute_value_id = av.id
        WHERE vav.variant_id IN ($placeholders)
    ");
    $attrStmt->execute($ids);
    foreach ($attrStmt->fetchAll(PDO::FETCH_ASSOC) as $attr) {
        $labels[$attr['variant_id']][] = $attr['value'];
    }
}

// Prepare these once, then reuse them inside the loop
$fixStmt  = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND variant_id = ?");
$dropStmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND variant_id = ?");

foreach ($rows as $row) {
    $vid   = (int)$row['variant_id'];
    $stock = (int)$row['stock_quantity'];
    $qty   = min((int)$row['quantity'], $stock);

    if ($qty <= 0) {
        // Sold out since it was added, so remove it from the cart
        $dropStmt->execute([$userId, $vid]);
        continue;
    }

    if ($qty !== (int)$row['quantity']) {
        // Stock dropped below what's in the cart, so correct it in the database
        $fixStmt->execute([$qty, $userId, $vid]);
    }

    $lineTotal = $row['price'] * $qty;
    $subtotal += $lineTotal;
    $itemCount += $qty;

    $cartItems[] = [
        'variant_id' => $vid,
        'name'       => $row['name'],
        'label'      => isset($labels[$vid]) ? implode(', ', $labels[$vid]) : $row['sku'],
        'price'      => $row['price'],
        'qty'        => $qty,
        'stock'      => $stock,
        'image'      => $row['image_url'],
        'lineTotal'  => $lineTotal,
    ];
}

// ---- Totals (same rules as before; they'll move to Settings later) ----
$shipping = ($subtotal == 0) ? 0 : ($subtotal >= 75 ? 0 : 6.99);
$tax = $subtotal * 0.075;
$total = $subtotal + $shipping + $tax;
?>
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
                    <?php if ($itemCount > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-black text-white text-[10px] font-bold rounded-full min-w-4 h-4 px-1 flex items-center justify-center"><?= $itemCount ?></span>
                    <?php endif; ?>
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

            <?php if (empty($cartItems)): ?>
                <div class="text-center py-16">
                    <i data-lucide="shopping-cart" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                    <p class="text-gray-500 mb-4">Your cart is empty.</p>
                    <a href="shop.php" class="inline-block bg-black text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-gray-800">
                        Continue Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="bg-white rounded-2xl border border-gray-200 p-4 flex gap-4 items-center">
                            <img src="<?= htmlspecialchars($item['image'] ?? '') ?>" alt="<?= htmlspecialchars($item['name']) ?>"
                                 class="w-20 h-20 rounded-xl object-cover flex-shrink-0 bg-gray-100">

                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($item['label']) ?></p>
                                <p class="text-sm font-bold text-gray-900 mt-2">$<?= number_format($item['price'], 2) ?></p>
                                <?php if ($item['qty'] >= $item['stock']): ?>
                                    <p class="text-[11px] text-orange-500 mt-1">Maximum available</p>
                                <?php endif; ?>
                            </div>

                            <div class="flex flex-col items-end gap-3">
                                <!-- Remove -->
                                <form method="POST">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="variant_id" value="<?= $item['variant_id'] ?>">
                                    <button type="submit" class="text-gray-400 hover:text-red-500" aria-label="Remove item">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>

                                <!-- Quantity: each button sends a different quantity value -->
                                <form method="POST" class="flex items-center border border-gray-300 rounded-lg">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="variant_id" value="<?= $item['variant_id'] ?>">

                                    <button type="submit" name="quantity" value="<?= $item['qty'] - 1 ?>"
                                        <?= $item['qty'] <= 1 ? 'disabled' : '' ?>
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg disabled:opacity-30 disabled:cursor-not-allowed">
                                        <i data-lucide="minus" class="w-3 h-3"></i>
                                    </button>

                                    <span class="w-8 text-center text-xs font-semibold"><?= $item['qty'] ?></span>

                                    <button type="submit" name="quantity" value="<?= $item['qty'] + 1 ?>"
                                        <?= $item['qty'] >= $item['stock'] ? 'disabled' : '' ?>
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg disabled:opacity-30 disabled:cursor-not-allowed">
                                        <i data-lucide="plus" class="w-3 h-3"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===================== ORDER SUMMARY ===================== -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span><?= $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Tax (est.)</span>
                        <span>$<?= number_format($tax, 2) ?></span>
                    </div>
                </div>

                <div class="border-t border-gray-100 mt-4 pt-4 flex justify-between font-bold text-gray-900">
                    <span>Total</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>

                <a href="checkout.php"
                   class="mt-6 w-full bg-black text-white font-semibold py-3.5 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2 <?= empty($cartItems) ? 'pointer-events-none opacity-40' : '' ?>">
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
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-8">
            <div>
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