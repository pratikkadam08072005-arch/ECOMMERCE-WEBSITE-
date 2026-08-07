<?php
require_once 'ad_config.php';
requireLogin();

// Check if user_id is provided
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header("Location: ad_users.php?error=Invalid user ID");
    exit;
}

$user_id = $_GET['user_id'];

// Get user details
$user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();

if ($user_result->num_rows === 0) {
    header("Location: ad_users.php?error=User not found");
    exit;
}

$user = $user_result->fetch_assoc();

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Items per page
$offset = ($page - 1) * $limit;

// Filter by order status
$status_filter = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
$status_condition = '';
if (!empty($status_filter) && $status_filter !== 'all') {
    $status_condition = "AND order_status = '$status_filter'";
}

// Get total number of orders for pagination
$count_query = "SELECT COUNT(*) as total FROM orders WHERE user_id = ? $status_condition";
$count_stmt = $conn->prepare($count_query);
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_orders = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_orders / $limit);

// Get orders with pagination
$query = "SELECT * FROM orders WHERE user_id = ? $status_condition ORDER BY created_at DESC LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $user_id, $offset, $limit);
$stmt->execute();
$orders_result = $stmt->get_result();

// Log activity
logAdminActivity($_SESSION['admin_id'], 'view', "Viewed orders for user ID: $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Orders - Bharat FootWare Admin</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Orders for <?php echo htmlspecialchars($user['username']); ?></h1>
                <a href="ad_users.php" class="btn secondary-btn">
                    <span class="material-icons">arrow_back</span> Back to Users
                </a>
            </div>
            
            <div class="user-info-card">
                <div class="user-details">
                    <h2>User Information</h2>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="label">Username:</span>
                            <span class="value"><?php echo htmlspecialchars($user['username']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Email:</span>
                            <span class="value"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Phone:</span>
                            <span class="value"><?php echo htmlspecialchars($user['phone']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Location:</span>
                            <span class="value"><?php echo htmlspecialchars($user['location'] ?? 'Not specified'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Registered:</span>
                            <span class="value"><?php echo date('d M Y', strtotime($user['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="filter-section">
                <form method="GET" action="" class="filter-form">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <div class="form-group">
                        <label for="status">Filter by Status:</label>
                        <select name="status" id="status" onchange="this.form.submit()">
                            <option value="all" <?php echo ($status_filter === '' || $status_filter === 'all') ? 'selected' : ''; ?>>All Orders</option>
                            <option value="processing" <?php echo ($status_filter === 'processing') ? 'selected' : ''; ?>>Processing</option>
                            <option value="shipped" <?php echo ($status_filter === 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                            <option value="delivered" <?php echo ($status_filter === 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo ($status_filter === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </form>
            </div>
            
            <div class="orders-container">
                <?php if ($orders_result->num_rows === 0): ?>
                    <div class="no-results">
                        No orders found for this user<?php echo !empty($status_filter) && $status_filter !== 'all' ? ' with the selected status' : ''; ?>.
                    </div>
                <?php else: ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $orders_result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                    <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="status-badge payment-<?php echo strtolower($order['payment_status']); ?>">
                                            <?php echo ucfirst($order['payment_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge order-<?php echo strtolower($order['order_status']); ?>">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <a href="ad_view_order.php?id=<?php echo $order['id']; ?>" class="btn view-btn" title="View Order Details">
                                            <span class="material-icons">visibility</span>
                                        </a>
                                        <a href="ad_edit_order.php?id=<?php echo $order['id']; ?>" class="btn edit-btn" title="Edit Order">
                                            <span class="material-icons">edit</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <!-- Order Summary -->
                    <div class="order-summary">
                        <?php
                        // Get order statistics
                        $stats_query = "SELECT 
                            COUNT(*) as total_orders,
                            SUM(total_amount) as total_spent,
                            COUNT(CASE WHEN order_status = 'delivered' THEN 1 END) as delivered_orders,
                            COUNT(CASE WHEN order_status = 'processing' THEN 1 END) as processing_orders,
                            COUNT(CASE WHEN order_status = 'shipped' THEN 1 END) as shipped_orders,
                            COUNT(CASE WHEN order_status = 'cancelled' THEN 1 END) as cancelled_orders
                        FROM orders WHERE user_id = ?";
                        $stats_stmt = $conn->prepare($stats_query);
                        $stats_stmt->bind_param("i", $user_id);
                        $stats_stmt->execute();
                        $stats_result = $stats_stmt->get_result();
                        $stats = $stats_result->fetch_assoc();
                        ?>
                        <h3>Order Statistics</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="label">Total Orders:</span>
                                <span class="value"><?php echo $stats['total_orders']; ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Total Spent:</span>
                                <span class="value">₹<?php echo number_format($stats['total_spent'], 2); ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Delivered:</span>
                                <span class="value"><?php echo $stats['delivered_orders']; ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Processing:</span>
                                <span class="value"><?php echo $stats['processing_orders']; ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Shipped:</span>
                                <span class="value"><?php echo $stats['shipped_orders']; ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Cancelled:</span>
                                <span class="value"><?php echo $stats['cancelled_orders']; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?user_id=<?php echo $user_id; ?>&page=<?php echo $page - 1; ?><?php echo !empty($status_filter) && $status_filter !== 'all' ? '&status=' . urlencode($status_filter) : ''; ?>" class="btn page-link">
                                    <span class="material-icons">chevron_left</span> Previous
                                </a>
                            <?php endif; ?>
                            
                            <div class="page-numbers">
                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                
                                if ($start_page > 1) {
                                    echo '<a href="?user_id=' . $user_id . '&page=1' . (!empty($status_filter) && $status_filter !== 'all' ? '&status=' . urlencode($status_filter) : '') . '" class="page-number">1</a>';
                                    if ($start_page > 2) {
                                        echo '<span class="page-ellipsis">...</span>';
                                    }
                                }
                                
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    $active_class = $i == $page ? 'active' : '';
                                    echo '<a href="?user_id=' . $user_id . '&page=' . $i . (!empty($status_filter) && $status_filter !== 'all' ? '&status=' . urlencode($status_filter) : '') . '" class="page-number ' . $active_class . '">' . $i . '</a>';
                                }
                                
                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<span class="page-ellipsis">...</span>';
                                    }
                                    echo '<a href="?user_id=' . $user_id . '&page=' . $total_pages . (!empty($status_filter) && $status_filter !== 'all' ? '&status=' . urlencode($status_filter) : '') . '" class="page-number">' . $total_pages . '</a>';
                                }
                                ?>
                            </div>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?user_id=<?php echo $user_id; ?>&page=<?php echo $page + 1; ?><?php echo !empty($status_filter) && $status_filter !== 'all' ? '&status=' . urlencode($status_filter) : ''; ?>" class="btn page-link">
                                    Next <span class="material-icons">chevron_right</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>