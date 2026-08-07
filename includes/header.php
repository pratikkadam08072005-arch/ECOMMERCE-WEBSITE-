<?php
// Start session at the beginning of the file
//session_start();

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Get the current directory depth to create proper relative paths
$current_path = $_SERVER['PHP_SELF'];
$root_path = '';

// If we're in a subdirectory, adjust the path accordingly
if (strpos($current_path, '/pages/') !== false) {
    $root_path = '../';
} else if (strpos($current_path, '/admin/') !== false) {
    $root_path = '../';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bharat Footwear</title>
    <link rel="stylesheet" href="<?php echo $root_path; ?>assets/css/head.css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       
    </style>
</head>
<body>
<nav class="navbar">
    <div class="logo-container">
        <div class="logo-icon">B</div>
        <div class="logo-text">Bharat <span>Footwear</span></div>
    </div>
    <div class="nav-icons">
        <div class="nav-item" onclick="window.location.href='<?php echo $root_path; ?>index.php'">
            <i class="fas fa-home"></i>
            <span>HOME</span>
        </div>
        <div class="nav-item" id="profileIcon">
            <i class="fas fa-user"></i>
            <span>Account</span>
        </div>
        <div class="nav-item cart-badge" onclick="window.location.href='<?php echo $root_path; ?>pages/cart.php'">
            <i class="fas fa-shopping-cart"></i>
            <span>Cart</span>
            <?php if ($is_logged_in): ?>
                <div class="cart-count">0</div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Overlay for sidebar -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <!-- Close button -->
    <div class="close-btn" id="closeBtn">
        <i class="fas fa-times"></i>
    </div>
    
    <?php if ($is_logged_in): ?>
        <!-- User information at the top of sidebar -->
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
            <div class="user-email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
        </div>
        
        <!-- Navigation tabs for logged-in users with icons -->
        <div class="tab <?php echo (strpos($current_path, 'profile.php') !== false) ? 'active' : ''; ?>" data-href="<?php echo $root_path; ?>pages/profile.php">
            <i class="fas fa-id-card"></i> Account
        </div>
        <div class="tab <?php echo ($current_path == '/index.php') ? 'active' : ''; ?>" data-href="<?php echo $root_path; ?>index.php">
            <i class="fas fa-home"></i> Home
        </div>
       
        <div class="tab <?php echo (strpos($current_path, 'cart.php') !== false) ? 'active' : ''; ?>" data-href="<?php echo $root_path; ?>pages/cart.php">
            <i class="fas fa-shopping-cart"></i> Cart
        </div>
        <div class="tab <?php echo (strpos($current_path, 'contact.php') !== false) ? 'active' : ''; ?>" data-href="<?php echo $root_path; ?>pages/contact.php">
            <i class="fas fa-envelope"></i> Contact Us
        </div>
        <div class="tab <?php echo (strpos($current_path, 'about.php') !== false) ? 'active' : ''; ?>" data-href="<?php echo $root_path; ?>pages/about.php">
            <i class="fas fa-info-circle"></i> About Us
        </div>
        
        <div class="action-btn logout-btn" id="logoutBtn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </div>
    <?php else: ?>
        <!-- Options for non-logged in users -->
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-name">Welcome, Guest</div>
        </div>
        
        <div class="tab <?php echo ($current_path == '/index.php') ? 'active' : ''; ?>" data-href="<?php echo $root_path; ?>index.php">
            <i class="fas fa-home"></i> Home
        </div>
        <div class="tab <?php echo (strpos($current_path, 'contact.php') !== false) ? 'active' : ''; ?>" data-href="<?php echo $root_path; ?>pages/contact.php">
            <i class="fas fa-envelope"></i> Contact Us
        </div>
        <div class="tab <?php echo (strpos($current_path, 'about.php') !== false) ? 'active' : ''; ?>" data-href="<?php echo $root_path; ?>pages/about.php">
            <i class="fas fa-info-circle"></i> About Us
        </div>
        
        <div class="action-btn" onclick="window.location.href='<?php echo $root_path; ?>login.php'">
            <i class="fas fa-sign-in-alt"></i> Login
        </div>
        <div class="action-btn" onclick="window.location.href='<?php echo $root_path; ?>register.php'">
            <i class="fas fa-user-plus"></i> Register
        </div>
    <?php endif; ?>
</div>

<script>
    const profileIcon = document.getElementById('profileIcon');
    const sidebar = document.getElementById('sidebar');
    const closeBtn = document.getElementById('closeBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Function to open sidebar
    function openSidebar() {
        sidebar.classList.add('active');
        sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Function to close sidebar
    function closeSidebar() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Add click event for profile icon
    profileIcon.addEventListener('click', openSidebar);
    
    // Add click event for close button
    closeBtn.addEventListener('click', closeSidebar);
    
    // Close sidebar when clicking on overlay
    sidebarOverlay.addEventListener('click', closeSidebar);
    
    <?php if ($is_logged_in): ?>
    // Add logout functionality
    const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn.addEventListener('click', () => {
        window.location.href = '<?php echo $root_path; ?>logout.php';
    });
    <?php endif; ?>
    
    // Add event listeners to all tabs for navigation
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const url = this.getAttribute('data-href');
            if (url) {
                window.location.href = url;
            }
        });
    });
    
    // Close sidebar when pressing ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    });
    
    // Highlight current page in sidebar
    document.addEventListener('DOMContentLoaded', () => {
        const currentPath = window.location.pathname;
        document.querySelectorAll('.tab').forEach(tab => {
            const href = tab.getAttribute('data-href');
            if (href && currentPath.includes(href)) {
                tab.classList.add('active');
            }
        });
    });
</script>
</body>
</html>