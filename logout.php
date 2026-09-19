<?php
// ============================================
// logout.php
// Destroys the session completely, then redirects.
// ============================================

session_start(); // must start the session before we can destroy it

$_SESSION = []; // empty out all session data first

session_destroy(); // tells PHP to fully delete this session on the server

header("Location: login.php");
exit;
?>