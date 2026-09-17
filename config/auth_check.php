<?php
// ============================================
// config/auth_check.php
// Include this at the top of ANY page that should
// only be visible to logged-in users.
// ============================================

session_start(); // must run before checking $_SESSION

// If there's no user_id in the session, they were never logged in
// (or their session expired). Send them to login and stop everything.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>