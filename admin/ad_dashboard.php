<?php
require_once 'ad_config.php';
requireLogin();

// Get quick stats for dashboard
$stats = [
    'total_products' => 0,
    'total_orders' => 0,
    'total_users' => 0,
    'total_revenue' => 0,
    'pending_orders' => 0,
    'recent_orders' => [],
    'recent_users' => [],
    'low_stock_products' => []
];

// Total products
$result = $conn->query("SELECT COUNT(*) as count FROM products");
if ($result && $row = $result->fetch_assoc()) {
    $stats['total_products'] = $row['count'];
}

// Total orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders");
if ($result && $row = $result->fetch_assoc()) {
    $stats['total_orders'] = $row['count'];
}

// Total users
$result = $conn->query("SELECT COUNT(*) as count FROM users");
if ($result && $row = $result->fetch_assoc()) {
    $stats['total_users'] = $row['count'];
}

// Total revenue
$result = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'completed'");
if ($result && $row = $result->fetch_assoc()) {
    $stats['total_revenue'] = $row['total'] ? $row['total'] : 0;
}

// Pending orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status = 'processing'");
if ($result && $row = $result->fetch_assoc()) {
    $stats['pending_orders'] = $row['count'];
}

// Recent orders
$result = $conn->query("
    SELECT o.id, o.created_at, o.total_amount, o.order_status, u.username 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
    LIMIT 5
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $stats['recent_orders'][] = $row;
    }
}

// Recent users
$result = $conn->query("
    SELECT id, username, email, created_at 
    FROM users 
    ORDER BY created_at DESC
    LIMIT 5
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $stats['recent_users'][] = $row;
    }
}

// Log activity
logAdminActivity($_SESSION['admin_id'], 'view', 'Viewed dashboard');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bharat FootWare</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo $_SESSION['admin_username']; ?>!</p>
            </div>
            
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="material-icons">inventory</span>
                    </div>
                    <div class="stat-details">
                        <h3>Total Products</h3>
                        <p class="stat-number"><?php echo $stats['total_products']; ?></p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="material-icons">shopping_cart</span>
                    </div>
                    <div class="stat-details">
                        <h3>Total Orders</h3>
                        <p class="stat-number"><?php echo $stats['total_orders']; ?></p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="material-icons">people</span>
                    </div>
                    <div class="stat-details">
                        <h3>Total Users</h3>
                        <p class="stat-number"><?php echo $stats['total_users']; ?></p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="material-icons">payments</span>
                    </div>
                    <div class="stat-details">
                        <h3>Total Revenue</h3>
                        <p class="stat-number">₹<?php echo number_format($stats['total_revenue'], 2); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-grid">
                <div class="dashboard-card recent-orders">
                    <div class="card-header">
                        <h2>Recent Orders</h2>
                        <a href="ad_orders.php" class="view-all">View All</a>
                    </div>
                    <div class="card-content">
                        <?php if (empty($stats['recent_orders'])): ?>
                            <p class="no-data">No orders found</p>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['recent_orders'] as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                                            <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td><span class="status <?php echo $order['order_status']; ?>"><?php echo ucfirst($order['order_status']); ?></span></td>
                                            <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="dashboard-card recent-users">
                    <div class="card-header">
                        <h2>Recent Users</h2>
                        <a href="ad_users.php" class="view-all">View All</a>
                    </div>
                    <div class="card-content">
                        <?php if (empty($stats['recent_users'])): ?>
                            <p class="no-data">No users found</p>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['recent_users'] as $user): ?>
                                        <tr>
                                            <td>#<?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>