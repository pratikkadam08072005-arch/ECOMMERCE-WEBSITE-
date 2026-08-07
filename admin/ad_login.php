<?php
require_once 'ad_config.php';

// Check if already logged in
if (isLoggedIn()) {
    header("Location: ad_dashboard.php");
    exit();
}

$login_error = "";

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST["username"]);
    $password = $_POST["password"];
    
    // Validate credentials
    $stmt = $conn->prepare("SELECT id, username, password, role FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row["password"])) {
            // Set session variables
            $_SESSION["admin_id"] = $row["id"];
            $_SESSION["admin_username"] = $row["username"];
            $_SESSION["admin_role"] = $row["role"];
            
            // Update last login timestamp
            $updateStmt = $conn->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $updateStmt->bind_param("i", $row["id"]);
            $updateStmt->execute();
            
            // Log the activity
            logAdminActivity($row["id"], "login", "Admin logged in");
            
            // Redirect to dashboard
            header("Location: ad_dashboard.php");
            exit();
        } else {
            $login_error = "Invalid password";
        }
    } else {
        $login_error = "User not found";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bharat Footwear</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <h1>Bharat Footware Admin</h1>
            <p>Login to access the admin panel</p>
        </div>
        
        <form class="login-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php if (!empty($login_error)): ?>
                <div class="error-message"><?php echo $login_error; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>