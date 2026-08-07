<?php
// Get current page for highlighting active menu
$current_page = basename($_SERVER['PHP_SELF']);

// Define menu items with access control
$menu_items = [
    [
        'title' => 'Dashboard',
        'url' => 'ad_dashboard.php',
        'icon' => 'dashboard',
        'roles' => ['super_admin', 'product_manager', 'order_manager', 'customer_support']
    ],
    [
        'title' => 'Products',
        'url' => 'ad_products.php',
        'icon' => 'inventory',
        'roles' => ['super_admin', 'product_manager']
    ],
    [
        'title' => 'Orders',
        'url' => 'ad_orders.php',
        'icon' => 'shopping_cart',
        'roles' => ['super_admin', 'order_manager']
    ],
    [
        'title' => 'Users',
        'url' => 'ad_users.php',
        'icon' => 'people',
        'roles' => ['super_admin', 'customer_support']
    ],
    [
        'title' => 'Ratings',
        'url' => 'ad_ratings.php',
        'icon' => 'star',
        'roles' => ['super_admin', 'customer_support']
    ],
    
    [
        'title' => 'Add Admin',
        'url' => 'ad_addadmin.php',
        'icon' => 'person_add',
        'roles' => ['super_admin']
    ]
];

// Get current admin role
$current_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : '';
?>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Bharat Footwear</h2>
        <p>Admin Panel</p>
    </div>
    
    <div class="admin-profile">
        <div class="admin-avatar">
            <span class="avatar-text"><?php echo isset($_SESSION['admin_username']) ? substr($_SESSION['admin_username'], 0, 1) : 'A'; ?></span>
        </div>
        <div class="admin-info">
            <p class="admin-name"><?php echo isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin'; ?></p>
            <p class="admin-role"><?php echo isset($_SESSION['admin_role']) ? ucfirst(str_replace('_', ' ', $_SESSION['admin_role'])) : ''; ?></p>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <?php foreach ($menu_items as $item): ?>
                <?php if (in_array($current_role, $item['roles'])): ?>
                    <li class="<?php echo $current_page === $item['url'] ? 'active' : ''; ?>">
                        <a href="<?php echo $item['url']; ?>">
                            <span class="material-icons"><?php echo $item['icon']; ?></span>
                            <span class="menu-title"><?php echo $item['title']; ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <li class="logout">
                <a href="ad_logout.php">
                    <span class="material-icons">logout</span>
                    <span class="menu-title">Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</div>