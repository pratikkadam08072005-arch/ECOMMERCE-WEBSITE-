<?php
require_once 'ad_config.php';
requireLogin();

// Check if the current user is a super_admin
$admin_id = $_SESSION['admin_id'];
$check_admin = $conn->prepare("SELECT role FROM admin_users WHERE id = ?");
$check_admin->bind_param("i", $admin_id);
$check_admin->execute();
$admin_result = $check_admin->get_result();
$admin_data = $admin_result->fetch_assoc();

// If not a super_admin, redirect to dashboard
if ($admin_data['role'] !== 'super_admin') {
    $_SESSION['error_message'] = "You do not have permission to access this page.";
    header("Location: ad_dashboard.php");
    exit();
}

$error_message = '';
$success_message = '';

// Check if ID parameter exists
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid admin user ID.";
    header("Location: ad_addadmin.php");
    exit();
}

$edit_id = (int)$_GET['id'];

// Get admin user details
$get_admin = $conn->prepare("SELECT id, username, email, role FROM admin_users WHERE id = ?");
$get_admin->bind_param("i", $edit_id);
$get_admin->execute();
$admin_result = $get_admin->get_result();

if ($admin_result->num_rows === 0) {
    $_SESSION['error_message'] = "Admin user not found.";
    header("Location: ad_addadmin.php");
    exit();
}

$admin_data = $admin_result->fetch_assoc();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Optional - might be empty if not changing
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    
    // Validate inputs
    if (empty($username) || empty($email) || empty($role)) {
        $error_message = "Username, email, and role are required.";
    } elseif (!empty($password) && $password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } elseif (!empty($password) && strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Check if username already exists (excluding current user)
        $check_username = $conn->prepare("SELECT id FROM admin_users WHERE username = ? AND id != ?");
        $check_username->bind_param("si", $username, $edit_id);
        $check_username->execute();
        $username_result = $check_username->get_result();
        
        // Check if email already exists (excluding current user)
        $check_email = $conn->prepare("SELECT id FROM admin_users WHERE email = ? AND id != ?");
        $check_email->bind_param("si", $email, $edit_id);
        $check_email->execute();
        $email_result = $check_email->get_result();
        
        if ($username_result->num_rows > 0) {
            $error_message = "Username already exists. Please choose a different username.";
        } elseif ($email_result->num_rows > 0) {
            $error_message = "Email already exists. Please use a different email address.";
        } else {
            // Validate role
            $valid_roles = ['super_admin', 'product_manager', 'order_manager', 'customer_support'];
            if (!in_array($role, $valid_roles)) {
                $error_message = "Invalid role selected.";
            } else {
                // Update admin user
                if (!empty($password)) {
                    // Update with new password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $update_admin = $conn->prepare("UPDATE admin_users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?");
                    $update_admin->bind_param("ssssi", $username, $email, $hashed_password, $role, $edit_id);
                } else {
                    // Update without changing password
                    $update_admin = $conn->prepare("UPDATE admin_users SET username = ?, email = ?, role = ? WHERE id = ?");
                    $update_admin->bind_param("sssi", $username, $email, $role, $edit_id);
                }
                
                if ($update_admin->execute()) {
                    // Log activity
                    logAdminActivity($_SESSION['admin_id'], 'update', "Updated admin user: $username (ID: $edit_id) with role: $role");
                    
                    $success_message = "Admin user updated successfully.";
                    
                    // Refresh data
                    $get_admin->execute();
                    $admin_result = $get_admin->get_result();
                    $admin_data = $admin_result->fetch_assoc();
                } else {
                    $error_message = "Error updating admin user: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin User - Bharat FootWare</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Edit Admin User</h1>
                <p>Modify administrator account details</p>
            </div>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error">
                    <span class="material-icons">error</span>
                    <span><?php echo $error_message; ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <span class="material-icons">check_circle</span>
                    <span><?php echo $success_message; ?></span>
                </div>
            <?php endif; ?>
            
            <div class="admin-card">
                <div class="card-header">
                    <h2>Edit Admin User #<?php echo $admin_data['id']; ?></h2>
                    <a href="ad_addadmin.php" class="btn btn-secondary btn-sm">
                        <span class="material-icons">arrow_back</span> Back to Admin Users
                    </a>
                </div>
                <div class="card-content">
                    <form method="POST" action="" class="admin-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="username">Username <span class="required">*</span></label>
                                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin_data['username']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_data['email']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" id="password" name="password">
                                <small>Leave blank to keep current password. Must be at least 8 characters long if changed.</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password">
                            </div>
                            
                            <div class="form-group">
                                <label for="role">Role <span class="required">*</span></label>
                                <select id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="super_admin" <?php echo ($admin_data['role'] === 'super_admin') ? 'selected' : ''; ?>>Super Admin</option>
                                    <option value="product_manager" <?php echo ($admin_data['role'] === 'product_manager') ? 'selected' : ''; ?>>Product Manager</option>
                                    <option value="order_manager" <?php echo ($admin_data['role'] === 'order_manager') ? 'selected' : ''; ?>>Order Manager</option>
                                    <option value="customer_support" <?php echo ($admin_data['role'] === 'customer_support') ? 'selected' : ''; ?>>Customer Support</option>
                                </select>
                                <small>
                                    <strong>Super Admin:</strong> Full access to all features<br>
                                    <strong>Product Manager:</strong> Manage products and inventory<br>
                                    <strong>Order Manager:</strong> Process orders and shipments<br>
                                    <strong>Customer Support:</strong> Handle customer inquiries and ratings
                                </small>
                            </div>
                        </div>
                        
                        <?php if ($edit_id == $_SESSION['admin_id']): ?>
                        <div class="alert alert-warning mt-20">
                            <span class="material-icons">warning</span>
                            <span>You are editing your own account. Changing your role might affect your access permissions.</span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <span class="material-icons">save</span> Update Admin User
                            </button>
                            <a href="ad_addadmin.php" class="btn btn-secondary">
                                <span class="material-icons">cancel</span> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('keyup', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password === confirmPassword) {
                this.setCustomValidity('');
            } else {
                this.setCustomValidity('Passwords do not match');
            }
        });
    </script>
    
    <script src="script.js"></script>
</body>
</html>