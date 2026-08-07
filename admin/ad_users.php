<?php
require_once 'ad_config.php';
requireLogin();

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = $_GET['delete'];
    
    // Check if user exists
    $check = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        // Delete the user
        $delete = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete->bind_param("i", $user_id);
        
        if ($delete->execute()) {
            // Log the activity
            logAdminActivity($_SESSION['admin_id'], 'delete', "Deleted user ID: $user_id");
            
            // Redirect to prevent resubmission
            header("Location: ad_users.php?success=User deleted successfully");
            exit;
        } else {
            $error = "Failed to delete user: " . $conn->error;
        }
    } else {
        $error = "User not found";
    }
}

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Items per page
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_condition = '';
if (!empty($search)) {
    $search_condition = "WHERE username LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR location LIKE '%$search%'";
}

// Get total number of users for pagination
$count_query = "SELECT COUNT(*) as total FROM users $search_condition";
$count_result = $conn->query($count_query);
$total_users = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_users / $limit);

// Get users with pagination
$query = "SELECT * FROM users $search_condition ORDER BY id DESC LIMIT $offset, $limit";
$result = $conn->query($query);

// Log activity
logAdminActivity($_SESSION['admin_id'], 'view', 'Viewed users list');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Bharat FootWare Admin</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Manage Users</h1>
                <a href="ad_add_user.php" class="btn primary-btn">
                    <span class="material-icons">person_add</span> Add New User
                </a>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert success">
                    <?php echo htmlspecialchars($_GET['success']); ?>
                </div>
            <?php endif; ?>
            
            <div class="filter-section">
                <form method="GET" action="" class="search-form">
                    <div class="form-group">
                        <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="users-container">
                <?php if ($result->num_rows == 0): ?>
                    <div class="no-results">
                        <?php echo empty($search) ? 'No users found' : 'No users match your search'; ?>
                    </div>
                <?php else: ?>
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($user['location'] ?? 'Not specified'); ?></td>
                                    <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                    <td class="actions">
                                        <a href="ad_view_user_orders.php?user_id=<?php echo $user['id']; ?>" class="btn view-btn" title="View Orders">
                                            <span class="material-icons">receipt</span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $user['id']; ?>)" class="btn delete-btn" title="Delete">
                                            <span class="material-icons">delete</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="btn page-link">
                                    <span class="material-icons">chevron_left</span> Previous
                                </a>
                            <?php endif; ?>
                            
                            <div class="page-numbers">
                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                
                                if ($start_page > 1) {
                                    echo '<a href="?page=1' . (!empty($search) ? '&search=' . urlencode($search) : '') . '" class="page-number">1</a>';
                                    if ($start_page > 2) {
                                        echo '<span class="page-ellipsis">...</span>';
                                    }
                                }
                                
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    $active_class = $i == $page ? 'active' : '';
                                    echo '<a href="?page=' . $i . (!empty($search) ? '&search=' . urlencode($search) : '') . '" class="page-number ' . $active_class . '">' . $i . '</a>';
                                }
                                
                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<span class="page-ellipsis">...</span>';
                                    }
                                    echo '<a href="?page=' . $total_pages . (!empty($search) ? '&search=' . urlencode($search) : '') . '" class="page-number">' . $total_pages . '</a>';
                                }
                                ?>
                            </div>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="btn page-link">
                                    Next <span class="material-icons">chevron_right</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user? This action will remove all their data and cannot be undone.')) {
                window.location.href = 'ad_users.php?delete=' + userId;
            }
        }
    </script>
</body>
</html>