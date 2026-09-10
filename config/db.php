




















<?php
// ============================================
// db_connect.php
// This file connects PHP to our MySQL database.
// Every page that needs data will "require" this file.
// ============================================

// Step 1: Store our database details in variables.
// Think of these as labeled boxes holding info PHP needs to log in to MySQL.
$host = "localhost";        // where the database lives — "localhost" means it's on the same computer
$dbname = "ecommerce_store"; // the exact database name we created in our SQL file
$username = "root";          // MySQL's default username (Laragon/XAMPP use "root" by default)
$password = "";               // MySQL's default password (usually blank/empty on local setups)

// Step 2: Try to connect. We wrap this in "try/catch" because
// connecting to a database can fail (wrong password, database not running, etc.)
// "try" means: attempt this code. "catch" means: if it fails, do this instead.
try {

    // PDO = PHP Data Objects — PHP's built-in tool for talking to databases safely.
    // We're creating a new PDO "object" (think of it as a connection handle)
    // and storing it in a variable called $pdo. Every other page will use $pdo
    // to run queries against the database.
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // This line tells PDO: "if something goes wrong with a query later,
    // throw a real error instead of failing silently." This makes bugs
    // much easier to find while we're building.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    // If the connection fails, this code runs instead.
    // We stop everything ("die") and show what went wrong.
    // NOTE: showing the raw error like this is fine while we're building
    // locally, but we'll hide this detail from real users later, since
    // exposing database error details publicly is a security risk.
    die("Database connection failed: " . $e->getMessage());

}

// If we reach this point with no errors, $pdo is now a working,
// ready-to-use connection that any page can use to query the database.
?>

















<?php
// ============================================
// db.php
// This file creates ONE connection to our MySQL
// database. Every other PHP page will "include"
// this file whenever it needs to talk to the database.
// ============================================

// ---- Step 1: Database credentials ----
// These are the details needed to log into MySQL itself.
// Think of this like a username/password to open the database.
$host = "localhost";        // the database lives on your own computer
$dbname = "ecommerce_store"; // the exact database name we created earlier
$username = "root";          // default MySQL username on most local setups (Laragon/XAMPP)
$password = "";               // default MySQL password on most local setups is empty

// ---- Step 2: Try to connect ----
// PHP has a built-in tool called PDO (PHP Data Objects) made
// specifically for talking to databases safely.
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // This next line tells PDO: "if something goes wrong with a query,
    // don't fail silently — throw a real error I can see and fix."
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // ---- Step 3: If the connection fails ----
    // This runs ONLY if something goes wrong above (wrong password,
    // MySQL not running, database name typo, etc.)
    die("Database connection failed: " . $e->getMessage());
}






?>