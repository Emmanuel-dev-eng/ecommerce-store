<?php
// ============================================
// config/db.example.php
// TEMPLATE ONLY. Copy this file to config/db.php and
// fill in your real database details. db.php is ignored by
// Git, so your real credentials never reach GitHub.
// ============================================

$host = "localhost";
$dbname = "ecommerce_store";
$username = "YOUR_DB_USERNAME";
$password = "YOUR_DB_PASSWORD";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>