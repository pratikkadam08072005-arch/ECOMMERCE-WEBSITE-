<?php
require_once 'ad_config.php';
requireLogin();

// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ad_products.php?error=Invalid product ID");
    exit;
}

$product_id = $_GET['id'];

// Fetch product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ad_products.php?error=Product not found");
    exit;
}

$product = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $size = $_POST['size'];
    $color = $_POST['color'];
    $brand = $_POST['brand'];
    $material = $_POST['material'];
    $stock = $_POST['stock'];
    
    // Image upload handling with absolute path
    $target_dir = __DIR__ . "/../assets/uploads/";
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            $error = "Failed to create directory: " . $target_dir . ". Check permissions!";
        }
    }

    $image1 = $product['image1']; // Keep existing image by default
    $image2 = $product['image2']; // Keep existing image by default
    $error = NULL;

    // Handle first image upload (if new file is selected)
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
            // Delete old image if exists and different from new one
            if (!empty($product['image1']) && file_exists(__DIR__ . "/../" . $product['image1']) && $product['image1'] != "assets/uploads/" . $image1_name) {
                unlink(__DIR__ . "/../" . $product['image1']);
            }
            $image1 = "assets/uploads/" . $image1_name;
        } else {
            $error = "Error uploading Image 1!";
        }
    }

    // Handle second image upload (if new file is selected)
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
            // Delete old image if exists and different from new one
            if (!empty($product['image2']) && file_exists(__DIR__ . "/../" . $product['image2']) && $product['image2'] != "assets/uploads/" . $image2_name) {
                unlink(__DIR__ . "/../" . $product['image2']);
            }
            $image2 = "assets/uploads/" . $image2_name;
        } else {
            $error = "Error uploading Image 2!";
        }
    }

    // Handle image removal requests
    if (isset($_POST['remove_image2']) && $_POST['remove_image2'] == '1') {
        // Delete image2 if it exists
        if (!empty($product['image2']) && file_exists(__DIR__ . "/../" . $product['image2'])) {
            unlink(__DIR__ . "/../" . $product['image2']);
        }
        $image2 = NULL;
    }

    // Update product in database if no errors
    if (empty($error)) {
        $sql = "UPDATE products SET product_name = ?, description = ?, price = ?, 
                image1 = ?, image2 = ?, category = ?, size = ?, color = ?, 
                brand = ?, material = ?, stock = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssssssiii", $product_name, $description, $price, $image1, 
                          $image2, $category, $size, $color, $brand, $material, $stock, $product_id);

        if ($stmt->execute()) {
            // Log the activity
            logAdminActivity($_SESSION['admin_id'], 'update', "Updated footwear product: $product_name (ID: $product_id)");
            
            // Redirect to products page with success message
            header("Location: ad_products.php?success=Product updated successfully");
            exit;
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}

// Log activity
logAdminActivity($_SESSION['admin_id'], 'view', "Viewed edit footwear product page for product ID: $product_id");

// Categories for dropdown
$categories = ['Men\'s Footwear', 'Women\'s Footwear', 'Kids Footwear', 'Sports Shoes', 'Casual Shoes'];

// Size options
$sizes = ['5', '6', '7', '8', '9', '10', '11', '12'];

// Common footwear materials
$materials = ['Leather', 'Canvas', 'Synthetic', 'Rubber', 'Mesh', 'Suede', 'Cotton'];

// Common footwear brands
$brands = ['Nike', 'Adidas', 'Puma', 'Reebok', 'New Balance', 'Converse', 'Vans', 'Other'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Footwear Product - Admin</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'ad_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="page-header">
                <h1>Edit Footwear Product: <?php echo htmlspecialchars($product['product_name']); ?></h1>
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
                                    value="<?php echo htmlspecialchars($product['product_name']); ?>">
                                <small>Enter the complete product name</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="price" class="required-field">Price (₹)</label>
                                <input type="number" id="price" name="price" step="0.01" min="0" required
                                    value="<?php echo htmlspecialchars($product['price']); ?>">
                                <small>Enter the price in rupees</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="category" class="required-field">Category</label>
                                <select id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat; ?>" <?php echo ($product['category'] == $cat) ? 'selected' : ''; ?>>
                                        <?php echo $cat; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Choose the product category</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="brand" class="required-field">Brand</label>
                                <select id="brand" name="brand" required>
                                    <option value="">Select a brand</option>
                                    <?php foreach ($brands as $b): ?>
                                    <option value="<?php echo $b; ?>" <?php echo ($product['brand'] == $b) ? 'selected' : ''; ?>>
                                        <?php echo $b; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Select the footwear brand</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="size" class="required-field">Size</label>
                                <select id="size" name="size" required>
                                    <option value="">Select a size</option>
                                    <?php foreach ($sizes as $s): ?>
                                    <option value="<?php echo $s; ?>" <?php echo ($product['size'] == $s) ? 'selected' : ''; ?>>
                                        <?php echo $s; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Select the footwear size</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="color" class="required-field">Color</label>
                                <input type="text" id="color" name="color" required
                                    value="<?php echo htmlspecialchars($product['color'] ?? ''); ?>">
                                <small>Enter the color (e.g., Black, White, Red)</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="material" class="required-field">Material</label>
                                <select id="material" name="material" required>
                                    <option value="">Select a material</option>
                                    <?php foreach ($materials as $m): ?>
                                    <option value="<?php echo $m; ?>" <?php echo ($product['material'] == $m) ? 'selected' : ''; ?>>
                                        <?php echo $m; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small>Select the primary material</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="stock" class="required-field">Stock Quantity</label>
                                <input type="number" id="stock" name="stock" min="0" required
                                    value="<?php echo htmlspecialchars($product['stock'] ?? 0); ?>">
                                <small>Enter the available quantity in stock</small>
                            </div>
                        </div>
                        
                        <div>
                            <div class="form-group">
                                <label for="description" class="required-field">Product Description</label>
                                <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                                <small>Provide detailed information about the product including features, comfort, style, etc.</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="image1" class="required-field">Primary Image</label>
                                <?php if (!empty($product['image1'])): ?>
                                <div class="image-preview-container">
                                    <div class="image-preview" id="current_preview1">
                                        <img src="<?php echo htmlspecialchars($product['image1']); ?>" alt="Current primary image">
                                    </div>
                                </div>
                                <small>Current primary image</small>
                                <?php endif; ?>
                                
                                <div class="file-input-container">
                                    <input type="file" id="image1" name="image1" accept="image/*" onchange="previewImage(this, 'preview1')">
                                    <label for="image1" class="file-input-label">
                                        <span class="material-icons">file_upload</span> Change Primary Image
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
                                <?php if (!empty($product['image2'])): ?>
                                <div class="image-preview-container">
                                    <div class="image-preview" id="current_preview2">
                                        <img src="<?php echo htmlspecialchars($product['image2']); ?>" alt="Current secondary image">
                                    </div>
                                </div>
                                <div class="image-actions">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="remove_image2" name="remove_image2" value="1">
                                        <label for="remove_image2">Remove secondary image</label>
                                    </div>
                                </div>
                                <small>Current secondary image</small>
                                <?php endif; ?>
                                
                                <div class="file-input-container">
                                    <input type="file" id="image2" name="image2" accept="image/*" onchange="previewImage(this, 'preview2')">
                                    <label for="image2" class="file-input-label">
                                        <span class="material-icons">file_upload</span> <?php echo !empty($product['image2']) ? 'Change' : 'Add'; ?> Secondary Image
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
                    </div>
                    
                    <div class="btn-container">
                        <a href="ad_products.php" class="btn secondary-btn">Cancel</a>
                        <button type="submit" class="btn primary-btn">
                            <span class="material-icons">save</span> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
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
                        label.innerHTML = `<span class="material-icons">file_upload</span> ${this.id === 'image1' ? 'Change Primary' : 'Change Secondary'} Image`;
                    }
                });
            });
            
            // Handle image2 removal checkbox
            const removeImage2Checkbox = document.getElementById('remove_image2');
            const image2Input = document.getElementById('image2');
            
            if (removeImage2Checkbox) {
                removeImage2Checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        // Disable the file input if user wants to remove the image
                        image2Input.disabled = true;
                        image2Input.nextElementSibling.classList.add('disabled');
                    } else {
                        // Re-enable the file input
                        image2Input.disabled = false;
                        image2Input.nextElementSibling.classList.remove('disabled');
                    }
                });
            }
        });
    </script>
</body>
</html>