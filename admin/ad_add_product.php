<?php
require_once 'ad_config.php';
requireLogin();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $for_who = $_POST['for_who'];
    
    // Handle multiple sizes and colors
    $size = isset($_POST['size']) ? implode(', ', $_POST['size']) : '';
    $color = isset($_POST['color']) ? implode(', ', $_POST['color']) : '';
    
    $brand = $_POST['brand'];
    $material = $_POST['material'];
    $stock = $_POST['stock'];
    $added_by = $_SESSION['admin_id']; // Get logged-in admin ID

    // Image upload handling with absolute path
    $target_dir = __DIR__ . "/../assets/uploads/"; // Ensure correct path
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            $error = "Failed to create directory: " . $target_dir . ". Check permissions!";
        }
    }

    $image1 = NULL;
    $image2 = NULL;
    $error = NULL;

    // Handle first image upload (required)
    if (!empty($_FILES["image1"]["name"])) {
        $image1_name = time() . "_1_" . basename($_FILES["image1"]["name"]);
        $image1_path = $target_dir . $image1_name;
        
        // Check file type
        $imageFileType = strtolower(pathinfo($image1_path, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($imageFileType, $allowedTypes)) {
            $error = "Only JPG, JPEG, PNG, GIF, and WEBP files are allowed for images";
        } elseif ($_FILES["image1"]["size"] > 5000000) { // 5MB limit
            $error = "Image 1 is too large (max 5MB)";
        } elseif (move_uploaded_file($_FILES["image1"]["tmp_name"], $image1_path)) {
            $image1 = "assets/uploads/" . $image1_name;
        } else {
            $error = "Error uploading Image 1!";
        }
    } else {
        $error = "Primary image is required";
    }

    // Handle second image upload (optional)
    if (empty($error) && !empty($_FILES["image2"]["name"])) {
        $image2_name = time() . "_2_" . basename($_FILES["image2"]["name"]);
        $image2_path = $target_dir . $image2_name;
        
        // Check file type
        $imageFileType = strtolower(pathinfo($image2_path, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($imageFileType, $allowedTypes)) {
            $error = "Only JPG, JPEG, PNG, GIF, and WEBP files are allowed for images";
        } elseif ($_FILES["image2"]["size"] > 5000000) { // 5MB limit
            $error = "Image 2 is too large (max 5MB)";
        } elseif (move_uploaded_file($_FILES["image2"]["tmp_name"], $image2_path)) {
            $image2 = "assets/uploads/" . $image2_name;
        } else {
            $error = "Error uploading Image 2!";
        }
    }

    // Insert product into database if no errors
    if (empty($error)) {
        // Updated SQL to include created_at field
        $sql = "INSERT INTO products (product_name, description, price, image1, image2, category, for_who, size, color, brand, material, stock, added_by, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssssssssii", $product_name, $description, $price, $image1, $image2, $category, $for_who, $size, $color, $brand, $material, $stock, $added_by);

        if ($stmt->execute()) {
            $product_id = $stmt->insert_id;
            
            // Log the activity
            logAdminActivity($_SESSION['admin_id'], 'create', "Added new footwear product: $product_name (ID: $product_id)");
            
            // Redirect to products page with success message
            header("Location: ad_products.php?success=Product added successfully");
            exit;
        } else {
            $error = "Database error: " . $conn->error;
            
            // If insertion fails, delete uploaded images
            if ($image1 && file_exists($target_dir . basename($image1))) {
                unlink($target_dir . basename($image1));
            }
            if ($image2 && file_exists($target_dir . basename($image2))) {
                unlink($target_dir . basename($image2));
            }
        }
    }
}

// Log activity
logAdminActivity($_SESSION['admin_id'], 'view', 'Viewed add footwear product page');

// Categories and target users for dropdown
$categories = ['Womens', 'Kids', 'Formal', 'Sandals', 'Boots','Running','sports'];
$target_users = ['Men', 'Women', 'Kids', 'Unisex'];
$size_ranges = ['5', '6', '7', '8', '9', '10', '11', '12']; // Updated for individual sizes
$colors = ['Black', 'Brown', 'White', 'Blue', 'Red', 'Grey', 'Multicolor', 'Green', 'Yellow', 'Orange', 'Purple', 'Pink'];
$materials = ['Leather', 'Canvas', 'Synthetic', 'Rubber', 'Fabric', 'Suede'];
$brands = ['Nike', 'Adidas', 'Reebok', 'Puma', 'Bata', 'Woodland', 'Crocs', 'Other'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Footwear Product - Admin</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Add Select2 CSS for better multi-select dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Additional styles for multi-select dropdowns */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            min-height: 40px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            margin: 2px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 5px;
            color: #999;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Add New Footwear Product</h1>
                <a href="ad_products.php" class="btn secondary-btn">
                    <span class="material-icons">arrow_back</span> Back to Products
                </a>
            </div>
            
            <?php if (isset($error)): ?>
            <div class="alert error">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <div class="product-form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div>
                            <div class="form-group">
                                <label for="product_name" class="required-field">Product Name</label>
                                <input type="text" id="product_name" name="product_name" required 
                                    value="<?php echo isset($_POST['product_name']) ? htmlspecialchars($_POST['product_name']) : ''; ?>">
                                <small>Enter the complete product name</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="price" class="required-field">Price (₹)</label>
                                <input type="number" id="price" name="price" step="0.01" min="0" required
                                    value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                                <small>Enter the price in rupees</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="category" class="required-field">Category</label>
                                <select id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat; ?>" <?php echo (isset($_POST['category']) && $_POST['category'] == $cat) ? 'selected' : ''; ?>>
                                        <?php echo $cat; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Choose the footwear category</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="for_who" class="required-field">Target Users</label>
                                <select id="for_who" name="for_who" required>
                                    <option value="">Select target users</option>
                                    <?php foreach ($target_users as $user): ?>
                                    <option value="<?php echo $user; ?>" <?php echo (isset($_POST['for_who']) && $_POST['for_who'] == $user) ? 'selected' : ''; ?>>
                                        <?php echo $user; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Select who this footwear is designed for</small>
                            </div>

                            <div class="form-group">
                                <label for="brand" class="required-field">Brand</label>
                                <select id="brand" name="brand" required>
                                    <option value="">Select a brand</option>
                                    <?php foreach ($brands as $b): ?>
                                    <option value="<?php echo $b; ?>" <?php echo (isset($_POST['brand']) && $_POST['brand'] == $b) ? 'selected' : ''; ?>>
                                        <?php echo $b; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Select the footwear brand</small>
                            </div>
                        </div>
                        
                        <div>
                            <div class="form-group">
                                <label for="size" class="required-field">Available Sizes</label>
                                <select id="size" name="size[]" class="multi-select" multiple="multiple" required>
                                    <?php foreach ($size_ranges as $sr): ?>
                                    <option value="<?php echo $sr; ?>" <?php echo (isset($_POST['size']) && in_array($sr, $_POST['size'])) ? 'selected' : ''; ?>>
                                        <?php echo $sr; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Select all available sizes (multiple selection possible)</small>
                            </div>

                            <div class="form-group">
                                <label for="color" class="required-field">Available Colors</label>
                                <select id="color" name="color[]" class="multi-select" multiple="multiple" required>
                                    <?php foreach ($colors as $c): ?>
                                    <option value="<?php echo $c; ?>" <?php echo (isset($_POST['color']) && in_array($c, $_POST['color'])) ? 'selected' : ''; ?>>
                                        <?php echo $c; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Select all available colors (multiple selection possible)</small>
                            </div>

                            <div class="form-group">
                                <label for="material" class="required-field">Material</label>
                                <select id="material" name="material" required>
                                    <option value="">Select a material</option>
                                    <?php foreach ($materials as $m): ?>
                                    <option value="<?php echo $m; ?>" <?php echo (isset($_POST['material']) && $_POST['material'] == $m) ? 'selected' : ''; ?>>
                                        <?php echo $m; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Select the primary material of the footwear</small>
                            </div>

                            <div class="form-group">
                                <label for="stock" class="required-field">Stock Quantity</label>
                                <input type="number" id="stock" name="stock" min="0" required
                                    value="<?php echo isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : ''; ?>">
                                <small>Enter the available quantity in stock</small>
                            </div>

                            <div class="form-group">
                                <label for="description" class="required-field">Product Description</label>
                                <textarea id="description" name="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                                <small>Provide detailed information about the product including features, comfort, durability, etc.</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-image-section">
                        <div class="form-group">
                            <label for="image1" class="required-field">Primary Image</label>
                            <div class="file-input-container">
                                <input type="file" id="image1" name="image1" accept="image/*" required onchange="previewImage(this, 'preview1')">
                                <label for="image1" class="file-input-label">
                                    <span class="material-icons">file_upload</span> Choose Primary Image
                                </label>
                            </div>
                            <small>Required. Max size: 5MB. Allowed types: JPG, JPEG, PNG, GIF, WEBP</small>
                            <div class="image-preview-container">
                                <div class="image-preview" id="preview1">
                                    <span class="material-icons">image</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image2">Secondary Image (Optional)</label>
                            <div class="file-input-container">
                                <input type="file" id="image2" name="image2" accept="image/*" onchange="previewImage(this, 'preview2')">
                                <label for="image2" class="file-input-label">
                                    <span class="material-icons">file_upload</span> Choose Secondary Image
                                </label>
                            </div>
                            <small>Optional. Max size: 5MB. Allowed types: JPG, JPEG, PNG, GIF, WEBP</small>
                            <div class="image-preview-container">
                                <div class="image-preview" id="preview2">
                                    <span class="material-icons">image</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="btn-container">
                        <a href="ad_products.php" class="btn secondary-btn">Cancel</a>
                        <button type="submit" class="btn primary-btn">
                            <span class="material-icons">add_circle_outline</span> Add Footwear Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Add Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Initialize Select2 for multi-select dropdowns
        $(document).ready(function() {
            $('.multi-select').select2({
                placeholder: "Select options",
                allowClear: true,
                width: '100%'
            });
        });
        
        // Function to preview selected images
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '<span class="material-icons">image</span>';
            }
        }
        
        // Update file input labels with selected file name
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const label = this.nextElementSibling;
                    
                    if (this.files.length > 0) {
                        const fileName = this.files[0].name;
                        if (fileName.length > 25) {
                            const shortName = fileName.substring(0, 22) + '...';
                            label.innerHTML = `<span class="material-icons">check_circle</span> ${shortName}`;
                        } else {
                            label.innerHTML = `<span class="material-icons">check_circle</span> ${fileName}`;
                        }
                    } else {
                        label.innerHTML = `<span class="material-icons">file_upload</span> Choose ${this.id === 'image1' ? 'Primary' : 'Secondary'} Image`;
                    }
                });
            });
        });
    </script>
</body>
</html>