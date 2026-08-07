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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    
    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Check if username already exists
        $check_username = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
        $check_username->bind_param("s", $username);
        $check_username->execute();
        $username_result = $check_username->get_result();
        
        // Check if email already exists
        $check_email = $conn->prepare("SELECT id FROM admin_users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $email_result = $check_email->get_result();
        
        if ($username_result->num_rows > 0) {
            $error_message = "Username already exists. Please choose a different username.";
        } elseif ($email_result->num_rows > 0) {
            $error_message = "Email already exists. Please use a different email address.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Validate role
            $valid_roles = ['super_admin', 'product_manager', 'order_manager', 'customer_support'];
            if (!in_array($role, $valid_roles)) {
                $error_message = "Invalid role selected.";
            } else {
                // Insert new admin user
                $insert_admin = $conn->prepare("INSERT INTO admin_users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $insert_admin->bind_param("ssss", $username, $email, $hashed_password, $role);
                
                if ($insert_admin->execute()) {
                    $new_admin_id = $conn->insert_id;
                    
                    // Log activity
                    logAdminActivity($_SESSION['admin_id'], 'create', "Added new admin user: $username (ID: $new_admin_id) with role: $role");
                    
                    $success_message = "Admin user added successfully.";
                    
                    // Clear form data
                    $username = '';
                    $email = '';
                    $role = '';
                } else {
                    $error_message = "Error adding admin user: " . $conn->error;
                }
            }
        }
    }
}

// Get existing admin users for the table
$admin_users_query = "SELECT id, username, email, role, last_login, created_at FROM admin_users ORDER BY created_at DESC";
$admin_users_result = $conn->query($admin_users_query);
$admin_users = [];

if ($admin_users_result) {
    while ($row = $admin_users_result->fetch_assoc()) {
        $admin_users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admin Users - Bharat FootWare</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Manage Admin Users</h1>
                <p>Add and manage administrator accounts</p>
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
                    <h2>Add New Admin User</h2>
                </div>
                <div class="card-content">
                    <form method="POST" action="" class="admin-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="username">Username <span class="required">*</span></label>
                                <input type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Password <span class="required">*</span></label>
                                <input type="password" id="password" name="password" required>
                                <small>Must be at least 8 characters long</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="role">Role <span class="required">*</span></label>
                                <select id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="super_admin" <?php echo (isset($role) && $role === 'super_admin') ? 'selected' : ''; ?>>Super Admin</option>
                                    <option value="product_manager" <?php echo (isset($role) && $role === 'product_manager') ? 'selected' : ''; ?>>Product Manager</option>
                                    <option value="order_manager" <?php echo (isset($role) && $role === 'order_manager') ? 'selected' : ''; ?>>Order Manager</option>
                                    <option value="customer_support" <?php echo (isset($role) && $role === 'customer_support') ? 'selected' : ''; ?>>Customer Support</option>
                                </select>
                                <small>
                                    <strong>Super Admin:</strong> Full access to all features<br>
                                    <strong>Product Manager:</strong> Manage products and inventory<br>
                                    <strong>Order Manager:</strong> Process orders and shipments<br>
                                    <strong>Customer Support:</strong> Handle customer inquiries and ratings
                                </small>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <span class="material-icons">person_add</span> Add Admin User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="admin-card mt-30">
                <div class="card-header">
                    <h2>Existing Admin Users</h2>
                </div>
                <div class="card-content">
                    <?php if (empty($admin_users)): ?>
                        <p class="no-data">No admin users found</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Last Login</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($admin_users as $admin): ?>
                                        <tr>
                                            <td>#<?php echo $admin['id']; ?></td>
                                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                            <td>
                                                <span class="role-badge <?php echo $admin['role']; ?>">
                                                    <?php 
                                                        $role_display = str_replace('_', ' ', $admin['role']);
                                                        echo ucwords($role_display);
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo $admin['last_login'] ? date('M d, Y H:i', strtotime($admin['last_login'])) : 'Never'; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($admin['created_at'])); ?></td>
                                            <td class="actions">
                                                <a href="ad_editadmin.php?id=<?php echo $admin['id']; ?>" class="action-btn edit" title="Edit">
                                                    <span class="material-icons">edit</span>
                                                </a>
                                                <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                                    <a href="ad_deleteadmin.php?id=<?php echo $admin['id']; ?>" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure you want to delete this admin user?');">
                                                        <span class="material-icons">delete</span>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
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