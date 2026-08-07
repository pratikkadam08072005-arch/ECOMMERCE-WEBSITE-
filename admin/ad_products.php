<?php
require_once 'ad_config.php';
requireLogin();

// Handle product deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $product_id = $_GET['delete'];
    
    // First check if product exists
    $check = $conn->prepare("SELECT id, image1, image2 FROM products WHERE id = ?");
    $check->bind_param("i", $product_id);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Delete the product
        $delete = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete->bind_param("i", $product_id);
        
        if ($delete->execute()) {
            // Remove image files if they exist
            if (!empty($product['image1']) && file_exists('../' . $product['image1'])) {
                unlink('../' . $product['image1']);
            }
            if (!empty($product['image2']) && file_exists('../' . $product['image2'])) {
                unlink('../' . $product['image2']);
            }
            
            // Log the activity
            logAdminActivity($_SESSION['admin_id'], 'delete', "Deleted product ID: $product_id");
            
            // Redirect to prevent resubmission
            header("Location: ad_products.php?success=Product deleted successfully");
            exit;
        } else {
            $error = "Failed to delete product: " . $conn->error;
        }
    } else {
        $error = "Product not found";
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
    $search_condition = "WHERE product_name LIKE '%$search%' OR description LIKE '%$search%' OR category LIKE '%$search%'";
}

// Get total number of products for pagination
$count_query = "SELECT COUNT(*) as total FROM products $search_condition";
$count_result = $conn->query($count_query);
$total_products = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_products / $limit);

// Get products with pagination
$query = "SELECT * FROM products $search_condition ORDER BY id DESC LIMIT $offset, $limit";
$result = $conn->query($query);

// Log activity
logAdminActivity($_SESSION['admin_id'], 'view', 'Viewed products list');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Bharat FootWare Admin</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .product-image {
            width: 80px;
            height: 80px;
            overflow: hidden;
            border-radius: 5px;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-error {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f8f8;
            color: #888;
            font-size: 12px;
            text-align: center;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Manage Products</h1>
                <a href="ad_add_product.php" class="btn primary-btn">
                    <span class="material-icons">add</span> Add New Product
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
                        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="products-container">
                <?php if ($result->num_rows == 0): ?>
                    <div class="no-results">
                        <?php echo empty($search) ? 'No products found' : 'No products match your search'; ?>
                    </div>
                <?php else: ?>
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>For</th>
                                <th>Price (₹)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($product = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $product['id']; ?></td>
                                    <td>
                                        <?php if (!empty($product['image1'])): ?>
                                            <div class="product-image">
                                                <?php 
                                                // Make sure to add '../' prefix since admin is likely in a subdirectory
                                                $image_path = '../' . $product['image1'];
                                                if (file_exists($image_path)): 
                                                ?>
                                                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                                <?php else: ?>
                                                    <div class="image-error">Image not found</div>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="image-error">No image</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                                    <td><?php echo htmlspecialchars($product['for_who']); ?></td>
                                    <td><?php echo number_format($product['price'], 2); ?></td>
                                    <td class="actions">
                                        <a href="ad_edit_product.php?id=<?php echo $product['id']; ?>" class="btn edit-btn" title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $product['id']; ?>)" class="btn delete-btn" title="Delete">
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
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                window.location.href = 'ad_products.php?delete=' + productId;
            }
        }
        
        // Script to handle image loading errors
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.product-image img');
            images.forEach(img => {
                img.onerror = function() {
                    const parentDiv = this.parentElement;
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'image-error';
                    errorDiv.textContent = 'Image not found';
                    parentDiv.replaceChild(errorDiv, this);
                };
            });
        });
    </script>
</body>
</html>