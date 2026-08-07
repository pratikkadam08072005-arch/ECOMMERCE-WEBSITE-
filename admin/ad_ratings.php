<?php
require_once 'ad_config.php';
requireLogin();

// Pagination setup
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 10;
$offset = ($current_page - 1) * $items_per_page;

// Get total ratings count for pagination
$count_result = $conn->query("SELECT COUNT(*) as total FROM ratings");
$total_ratings = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_ratings / $items_per_page);

// Sorting options
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Validate sorting parameters to prevent SQL injection
$valid_sort_fields = ['created_at', 'rating', 'name', 'email'];
$valid_sort_orders = ['ASC', 'DESC'];

if (!in_array($sort_by, $valid_sort_fields)) {
    $sort_by = 'created_at';
}

if (!in_array($sort_order, $valid_sort_orders)) {
    $sort_order = 'DESC';
}

// Filter by rating
$rating_filter = '';
if (isset($_GET['rating']) && $_GET['rating'] !== '') {
    $rating = (int)$_GET['rating'];
    if ($rating >= 1 && $rating <= 5) {
        $rating_filter = "WHERE rating = $rating";
    }
}

// Get ratings with pagination and sorting
$sql = "SELECT r.*, u.username 
        FROM ratings r 
        LEFT JOIN users u ON r.user_id = u.id
        $rating_filter
        ORDER BY $sort_by $sort_order
        LIMIT $offset, $items_per_page";

$result = $conn->query($sql);
$ratings = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $ratings[] = $row;
    }
}

// Get average rating
$avg_sql = "SELECT AVG(rating) as avg_rating FROM ratings";
$avg_result = $conn->query($avg_sql);
$avg_rating = $avg_result->fetch_assoc()['avg_rating'];

// Get rating distribution
$dist_sql = "SELECT rating, COUNT(*) as count FROM ratings GROUP BY rating ORDER BY rating";
$dist_result = $conn->query($dist_sql);
$rating_distribution = [];

if ($dist_result) {
    while ($row = $dist_result->fetch_assoc()) {
        $rating_distribution[$row['rating']] = $row['count'];
    }
}

// Initialize counts for all ratings (1-5)
for ($i = 1; $i <= 5; $i++) {
    if (!isset($rating_distribution[$i])) {
        $rating_distribution[$i] = 0;
    }
}
ksort($rating_distribution);

// Log activity
logAdminActivity($_SESSION['admin_id'], 'view', 'Viewed ratings page');

function getRatingClass($rating) {
    if ($rating >= 4) return 'excellent';
    if ($rating >= 3) return 'good';
    if ($rating >= 2) return 'average';
    return 'poor';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Ratings - Bharat FootWare Admin</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Customer Ratings</h1>
                <p>Manage and analyze customer feedback</p>
            </div>
            
            <div class="ratings-overview">
                <div class="rating-summary">
                    <div class="average-rating">
                        <h2>Average Rating</h2>
                        <div class="rating-score">
                            <span class="rating-number"><?php echo number_format($avg_rating, 1); ?></span>
                            <div class="rating-stars">
                                <?php 
                                $filled_stars = floor($avg_rating);
                                $half_star = $avg_rating - $filled_stars >= 0.5;
                                
                                for($i = 1; $i <= 5; $i++) {
                                    if ($i <= $filled_stars) {
                                        echo '<span class="material-icons">star</span>';
                                    } elseif ($i == $filled_stars + 1 && $half_star) {
                                        echo '<span class="material-icons">star_half</span>';
                                    } else {
                                        echo '<span class="material-icons">star_border</span>';
                                    }
                                }
                                ?>
                            </div>
                            <p>Based on <?php echo $total_ratings; ?> reviews</p>
                        </div>
                    </div>
                    
                    <div class="rating-distribution">
                        <h2>Rating Distribution</h2>
                        <?php foreach($rating_distribution as $rating => $count): 
                            $percentage = $total_ratings > 0 ? ($count / $total_ratings) * 100 : 0;
                        ?>
                        <div class="rating-bar">
                            <div class="rating-label"><?php echo $rating; ?> Star</div>
                            <div class="rating-progress">
                                <div class="rating-progress-bar" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                            <div class="rating-count"><?php echo $count; ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="ratings-filters">
                <div class="filter-container">
                    <form action="" method="get" class="filter-form">
                        <div class="form-group">
                            <label for="rating-filter">Filter by Rating:</label>
                            <select name="rating" id="rating-filter">
                                <option value="">All Ratings</option>
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($_GET['rating']) && $_GET['rating'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sort-by">Sort by:</label>
                            <select name="sort" id="sort-by">
                                <option value="created_at" <?php echo $sort_by == 'created_at' ? 'selected' : ''; ?>>Date</option>
                                <option value="rating" <?php echo $sort_by == 'rating' ? 'selected' : ''; ?>>Rating</option>
                                <option value="name" <?php echo $sort_by == 'name' ? 'selected' : ''; ?>>Name</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sort-order">Order:</label>
                            <select name="order" id="sort-order">
                                <option value="DESC" <?php echo $sort_order == 'DESC' ? 'selected' : ''; ?>>Descending</option>
                                <option value="ASC" <?php echo $sort_order == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="filter-button">Apply Filters</button>
                    </form>
                </div>
            </div>
            
            <div class="ratings-list">
                <?php if (empty($ratings)): ?>
                    <div class="no-data">No ratings found</div>
                <?php else: ?>
                    <?php foreach($ratings as $rating): ?>
                        <div class="rating-card">
                            <div class="rating-header">
                                <div class="rating-user">
                                    <span class="material-icons">account_circle</span>
                                    <div class="user-info">
                                        <h3><?php echo htmlspecialchars($rating['name']); ?></h3>
                                        <p><?php echo htmlspecialchars($rating['email']); ?></p>
                                        <?php if($rating['username']): ?>
                                            <p class="registered-user"><span class="material-icons">verified</span> Registered User: <?php echo htmlspecialchars($rating['username']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="rating-date">
                                    <?php echo date('F d, Y', strtotime($rating['created_at'])); ?>
                                </div>
                            </div>
                            
                            <div class="rating-content">
                                <div class="rating-stars-display">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= $rating['rating']): ?>
                                            <span class="material-icons">star</span>
                                        <?php else: ?>
                                            <span class="material-icons">star_border</span>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="rating-label <?php echo getRatingClass($rating['rating']); ?>"><?php echo $rating['rating']; ?>.0</span>
                                </div>
                                
                                <?php if($rating['products_purchased']): ?>
                                    <div class="purchased-products">
                                        <h4>Products Purchased:</h4>
                                        <p><?php echo htmlspecialchars($rating['products_purchased']); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($rating['comments']): ?>
                                    <div class="rating-comments">
                                        <h4>Customer Comments:</h4>
                                        <p><?php echo nl2br(htmlspecialchars($rating['comments'])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Pagination -->
                    <?php if($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if($current_page > 1): ?>
                                <a href="?page=<?php echo ($current_page - 1); ?>&sort=<?php echo $sort_by; ?>&order=<?php echo $sort_order; ?><?php echo isset($_GET['rating']) ? '&rating='.$_GET['rating'] : ''; ?>" class="page-link">&laquo; Previous</a>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if($i == $current_page): ?>
                                    <span class="page-link active"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>&sort=<?php echo $sort_by; ?>&order=<?php echo $sort_order; ?><?php echo isset($_GET['rating']) ? '&rating='.$_GET['rating'] : ''; ?>" class="page-link"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if($current_page < $total_pages): ?>
                                <a href="?page=<?php echo ($current_page + 1); ?>&sort=<?php echo $sort_by; ?>&order=<?php echo $sort_order; ?><?php echo isset($_GET['rating']) ? '&rating='.$_GET['rating'] : ''; ?>" class="page-link">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>