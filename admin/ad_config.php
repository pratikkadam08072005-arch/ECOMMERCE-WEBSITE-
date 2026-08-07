<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "foot"; // You might want to rename this to match your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Function to redirect to login page if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ad_login.php");
        exit();
    }
}

// Function to log admin activity
function logAdminActivity($admin_id, $action_type, $action_details) {
    global $conn;
    
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $stmt = $conn->prepare("INSERT INTO admin_activity_log (admin_id, action_type, action_details, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $admin_id, $action_type, $action_details, $ip_address);
    
    return $stmt->execute();
}

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to get admin role
function getAdminRole() {
    if (isset($_SESSION['admin_role'])) {
        return $_SESSION['admin_role'];
    }
    return null;
}

// Function to check if admin has required role
function requireRole($requiredRoles = array('super_admin')) {
    $currentRole = getAdminRole();
    if (!$currentRole || !in_array($currentRole, $requiredRoles)) {
        header("Location: ad_dashboard.php?error=unauthorized");
        exit();
    }
}
?>