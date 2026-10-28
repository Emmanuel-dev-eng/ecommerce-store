<?php
// ============================================
// cart-add.php
// Receives "Add to Cart" from product.php and saves it to the
// cart_items table (the database), then redirects.
// ============================================

require 'config/auth_check.php'; // starts the session AND blocks logged-out visitors
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: shop.php");
    exit;
}

$userId    = (int)$_SESSION['user_id'];
$variantId = (int)($_POST['variant_id'] ?? 0);
$quantity  = (int)($_POST['quantity'] ?? 0);

if ($variantId <= 0 || $quantity <= 0) {
    header("Location: shop.php");
    exit;
}

// Make sure this variant really exists AND belongs to an active product.
$stmt = $pdo->prepare("
    SELECT product_variants.id, product_variants.stock_quantity
    FROM product_variants
    JOIN products ON product_variants.product_id = products.id
    WHERE product_variants.id = ? AND products.is_active = 1
");
$stmt->execute([$variantId]);
$variant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$variant) {
    header("Location: shop.php");
    exit;
}

// How many of this variant is THIS user already holding in their cart?
$cartStmt = $pdo->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND variant_id = ?");
$cartStmt->execute([$userId, $variantId]);
$currentQty = (int)$cartStmt->fetchColumn();

// New total, but never more than we have in stock.
$newQty = min($currentQty + $quantity, (int)$variant['stock_quantity']);

if ($newQty <= 0) {
    header("Location: shop.php");
    exit;
}

// Insert a new cart row, or update the existing one if this user
// already has this variant (thanks to the UNIQUE KEY in our schema).
$saveStmt = $pdo->prepare("
    INSERT INTO cart_items (user_id, variant_id, quantity)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE quantity = ?
");
$saveStmt->execute([$userId, $variantId, $newQty, $newQty]);

header("Location: cart.php");
exit;
?>