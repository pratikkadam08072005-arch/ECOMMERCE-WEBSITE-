<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "foot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize session for user tracking if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in and redirect if not
function checkSessionAndRedirect() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header("Location: ../login.php");
        exit();
    }
}
?>