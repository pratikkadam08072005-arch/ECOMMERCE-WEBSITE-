<?php
require_once 'ad_config.php';
requireLogin();

// Initialize variables
$orderStatus = isset($_GET['status']) ? sanitizeInput($_GET['status']) : '';
$searchTerm = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

// Handle order status updates
if (isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = sanitizeInput($_POST['order_status']);
    
    $stmt = $conn->prepare("UPDATE orders SET order_status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $orderId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'update', "Updated order #$orderId status to $newStatus");
        $successMessage = "Order #$orderId status updated successfully";
    } else {
        $errorMessage = "Error updating order status: " . $conn->error;
    }
    $stmt->close();
}

// Build query based on filters
$query = "SELECT o.*, u.username, u.email, u.phone 
          FROM orders o
          JOIN users u ON o.user_id = u.id";
$countQuery = "SELECT COUNT(*) as total FROM orders o JOIN users u ON o.user_id = u.id";

$whereConditions = [];
$whereParams = [];

if (!empty($orderStatus)) {
    $whereConditions[] = "o.order_status = ?";
    $whereParams[] = $orderStatus;
}

if (!empty($searchTerm)) {
    $whereConditions[] = "(o.id LIKE ? OR u.username LIKE ? OR u.email LIKE ? OR o.shipping_address LIKE ?)";
    $searchParam = "%$searchTerm%";
    $whereParams[] = $searchParam;
    $whereParams[] = $searchParam;
    $whereParams[] = $searchParam;
    $whereParams[] = $searchParam;
}

if (!empty($whereConditions)) {
    $whereClause = " WHERE " . implode(" AND ", $whereConditions);
    $query .= $whereClause;
    $countQuery .= $whereClause;
}

$query .= " ORDER BY o.created_at DESC LIMIT ?, ?";

// Get total records count
$totalOrders = 0;
if (!empty($whereParams)) {
    // Prepare count query with parameters
    $countStmt = $conn->prepare($countQuery);
    
    // Build the type string
    $types = str_repeat("s", count($whereParams));
    
    // Create references array for bind_param
    $countBindParams = array($types);
    foreach ($whereParams as $key => $value) {
        $countBindParams[] = &$whereParams[$key];
    }
    
    // Call bind_param with dynamic parameters
    call_user_func_array(array($countStmt, "bind_param"), $countBindParams);
    
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    if ($row = $countResult->fetch_assoc()) {
        $totalOrders = $row['total'];
    }
    $countStmt->close();
} else {
    $countResult = $conn->query($countQuery);
    if ($row = $countResult->fetch_assoc()) {
        $totalOrders = $row['total'];
    }
}

// Calculate total pages
$totalPages = ceil($totalOrders / $itemsPerPage);

// Execute main query
$orders = [];
$stmt = $conn->prepare($query);

if (!empty($whereParams)) {
    // Add pagination parameters
    $whereParams[] = $offset;
    $whereParams[] = $itemsPerPage;
    
    // Build the type string
    $types = str_repeat("s", count($whereParams) - 2) . "ii";
    
    // Create references array for bind_param
    $bindParams = array($types);
    foreach ($whereParams as $key => $value) {
        $bindParams[] = &$whereParams[$key];
    }
    
    // Call bind_param with dynamic parameters
    call_user_func_array(array($stmt, "bind_param"), $bindParams);
} else {
    $stmt->bind_param("ii", $offset, $itemsPerPage);
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

// Get order details if viewing a specific order
$orderDetails = null;
$orderItems = [];
if (isset($_GET['view_order'])) {
    $orderId = (int)$_GET['view_order'];
    
    // Get order details
    $stmt = $conn->prepare("
        SELECT o.*, u.username, u.email, u.phone
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ?
    ");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderDetails = $result->fetch_assoc();
    $stmt->close();
    
    // Get order items
    if ($orderDetails) {
        $stmt = $conn->prepare("
            SELECT oi.*, p.product_name, p.image1
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $orderItems[] = $row;
        }
        $stmt->close();
        
        logAdminActivity($_SESSION['admin_id'], 'view', "Viewed order #$orderId details");
    }
}

// Log activity for viewing orders page
logAdminActivity($_SESSION['admin_id'], 'view', 'Viewed orders page');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Bharat FootWare Admin</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .order-details {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .meta-item {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }
        .meta-item h4 {
            margin: 0 0 5px 0;
            color: #666;
            font-size: 14px;
        }
        .meta-item p {
            margin: 0;
            font-weight: 500;
        }
        .order-items {
            margin-top: 20px;
        }
        .item-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }
        .order-item {
            display: flex;
            align-items: center;
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 10px;
        }
        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 4px;
        }
        .item-details h4 {
            margin: 0 0 5px 0;
        }
        .item-details p {
            margin: 0;
            color: #666;
        }
        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }
        .processing { background-color: #fff8e1; color: #ff9800; }
        .shipped { background-color: #e3f2fd; color: #2196f3; }
        .delivered { background-color: #e8f5e9; color: #4caf50; }
        .cancelled { background-color: #feebee; color: #f44336; }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 4px;
            border-radius: 4px;
            background-color: #f5f5f5;
            color: #333;
            text-decoration: none;
        }
        .pagination a:hover {
            background-color: #e0e0e0;
        }
        .pagination .active {
            background-color: #4caf50;
            color: white;
        }
        .back-button {
            margin-bottom: 15px;
            display: inline-block;
        }
        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert.success {
            background-color: #e8f5e9;
            color: #4caf50;
            border-left: 4px solid #4caf50;
        }
        .alert.error {
            background-color: #feebee;
            color: #f44336;
            border-left: 4px solid #f44336;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1><?php echo isset($_GET['view_order']) ? "Order Details #" . $_GET['view_order'] : "Manage Orders"; ?></h1>
                <?php if (isset($_GET['view_order'])): ?>
                    <a href="ad_orders.php" class="back-button"><span class="material-icons">arrow_back</span> Back to Orders</a>
                <?php endif; ?>
            </div>
            
            <?php if (isset($successMessage)): ?>
                <div class="alert success">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errorMessage)): ?>
                <div class="alert error">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['view_order']) && $orderDetails): ?>
                <!-- Order Details View -->
                <div class="order-details">
                    <div class="order-header">
                        <h2>Order #<?php echo $orderDetails['id']; ?></h2>
                        <p>Placed on <?php echo date('d M Y, h:i A', strtotime($orderDetails['created_at'])); ?></p>
                    </div>
                    
                    <div class="order-meta">
                        <div class="meta-item">
                            <h4>Customer</h4>
                            <p><?php echo htmlspecialchars($orderDetails['username']); ?></p>
                            <p><?php echo htmlspecialchars($orderDetails['email']); ?></p>
                            <p><?php echo htmlspecialchars($orderDetails['phone']); ?></p>
                        </div>
                        
                        <div class="meta-item">
                            <h4>Shipping Address</h4>
                            <p><?php echo nl2br(htmlspecialchars($orderDetails['shipping_address'])); ?></p>
                        </div>
                        
                        <div class="meta-item">
                            <h4>Payment</h4>
                            <p>Method: <?php echo htmlspecialchars($orderDetails['payment_method']); ?></p>
                            <p>Status: <?php echo ucfirst($orderDetails['payment_status']); ?></p>
                        </div>
                        
                        <div class="meta-item">
                            <h4>Order Status</h4>
                            <p><span class="status <?php echo $orderDetails['order_status']; ?>"><?php echo ucfirst($orderDetails['order_status']); ?></span></p>
                            
                            <form method="post" style="margin-top: 10px;">
                                <input type="hidden" name="order_id" value="<?php echo $orderDetails['id']; ?>">
                                <select name="order_status" class="form-control">
                                    <option value="processing" <?php echo $orderDetails['order_status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $orderDetails['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $orderDetails['order_status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $orderDetails['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn">Update Status</button>
                            </form>
                        </div>
                        
                        <?php if (!empty($orderDetails['tracking_number'])): ?>
                        <div class="meta-item">
                            <h4>Tracking Number</h4>
                            <p><?php echo htmlspecialchars($orderDetails['tracking_number']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="order-summary">
                        <h3>Order Summary</h3>
                        <table>
                            <tr>
                                <td>Total Amount</td>
                                <td><strong>₹<?php echo number_format($orderDetails['total_amount'], 2); ?></strong></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="order-items">
                        <h3>Order Items</h3>
                        <div class="item-list">
                            <?php if (empty($orderItems)): ?>
                                <p>No items found for this order.</p>
                            <?php else: ?>
                                <?php foreach ($orderItems as $item): ?>
                                    <div class="order-item">
                                        <img src="<?php echo htmlspecialchars($item['image1']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                        <div class="item-details">
                                            <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                                            <p>Price: ₹<?php echo number_format($item['price_per_unit'], 2); ?></p>
                                            <p>Subtotal: ₹<?php echo number_format($item['subtotal'], 2); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($orderDetails['notes'])): ?>
                    <div class="order-notes">
                        <h3>Notes</h3>
                        <p><?php echo nl2br(htmlspecialchars($orderDetails['notes'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            
            <?php else: ?>
                <!-- Orders List View -->
                <div class="filters">
                    <div class="filter-group">
                        <form method="get" action="">
                            <div class="form-group">
                                <label for="status">Filter by Status</label>
                                <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Orders</option>
                                    <option value="processing" <?php echo $orderStatus === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $orderStatus === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $orderStatus === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $orderStatus === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    
                    <div class="filter-group">
                        <form method="get" action="">
                            <?php if (!empty($orderStatus)): ?>
                                <input type="hidden" name="status" value="<?php echo htmlspecialchars($orderStatus); ?>">
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="search">Search Orders</label>
                                <div class="search-container">
                                    <input type="text" name="search" id="search" class="form-control" placeholder="Order ID, Customer, Email..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                                    <button type="submit" class="btn">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Orders (<?php echo $totalOrders; ?>)</h2>
                    </div>
                    <div class="card-content">
                        <?php if (empty($orders)): ?>
                            <p class="no-data">No orders found</p>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($order['username']); ?><br>
                                                <small><?php echo htmlspecialchars($order['email']); ?></small>
                                            </td>
                                            <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td><?php echo ucfirst($order['payment_status']); ?></td>
                                            <td><span class="status <?php echo $order['order_status']; ?>"><?php echo ucfirst($order['order_status']); ?></span></td>
                                            <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                            <td>
                                                <a href="?view_order=<?php echo $order['id']; ?>" class="btn btn-sm">View Details</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                                <div class="pagination">
                                    <?php if ($page > 1): ?>
                                        <a href="?page=<?php echo $page - 1; ?><?php echo !empty($orderStatus) ? '&status=' . urlencode($orderStatus) : ''; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>">
                                            &laquo; Previous
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <?php if ($i == $page): ?>
                                            <span class="active"><?php echo $i; ?></span>
                                        <?php else: ?>
                                            <a href="?page=<?php echo $i; ?><?php echo !empty($orderStatus) ? '&status=' . urlencode($orderStatus) : ''; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $totalPages): ?>
                                        <a href="?page=<?php echo $page + 1; ?><?php echo !empty($orderStatus) ? '&status=' . urlencode($orderStatus) : ''; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>">
                                            Next &raquo;
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>