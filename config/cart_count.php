<?php
// ============================================
// config/cart_count.php
// Sets $cartCount for the cart badge in the header.
// Include it AFTER config/db.php on any page that shows the badge.
// ============================================

// Start the session only if it isn't already running
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0; // logged-out visitors, or an empty cart, see no badge

if (isset($_SESSION['user_id'])) {
    // Total units in this user's cart (COALESCE turns NULL into 0)
    $countStmt = $pdo->prepare("SELECT COALESCE(SUM(quantity), 0) FROM cart_items WHERE user_id = ?");
    $countStmt->execute([(int)$_SESSION['user_id']]);
    $cartCount = (int)$countStmt->fetchColumn();
}
?>