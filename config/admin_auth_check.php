<?php
// ============================================
// config/admin_auth_check.php
// Include this at the top of every page inside admin/
// ============================================

session_start();

// First check: are they logged in at all?
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    // NOTE the "../" here — admin pages live inside the admin/ folder,
    // so we need to go "up one level" to reach login.php in the root.
    exit;
}

// Second check: are they logged in AND an admin specifically?
// A regular customer could be logged in, but shouldn't reach admin pages.
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../account.php");
    // Send non-admins back to their own account page, not an error page —
    // better experience than showing a scary "access denied" screen.
    exit;
}
?>