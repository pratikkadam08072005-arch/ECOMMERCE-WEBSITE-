<?php
// Include configuration file
require_once 'ad_config.php';

// Check if user is logged in
if (isset($_SESSION['admin_id'])) {
    // Log the logout activity
    logAdminActivity($_SESSION['admin_id'], 'auth', 'Logged out');
    
    // Unset all session variables
    $_SESSION = array();
    
    // If a session cookie is used, destroy it
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
}

// Set success message in a temporary session
session_start();
$_SESSION['success_message'] = "You have been successfully logged out.";

// Redirect to login page
header("Location: ad_login.php");
exit();
?>