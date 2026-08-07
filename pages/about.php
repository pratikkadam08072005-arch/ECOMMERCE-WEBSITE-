<?php
session_start();

// Database connection
require_once "../includes/db_connect.php";
checkSessionAndRedirect();

// Function to display alert messages
function displayAlert($type, $message) {
    return "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
}

// Handle success and error messages from rating submission
$alertMessage = '';
if (isset($_GET['success']) && $_GET['success'] === 'rating_submitted') {
    $alertMessage = displayAlert('success', 'Thank you for your feedback! Your rating has been submitted.');
} elseif (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_input':
            $alertMessage = displayAlert('danger', 'Please fill in all required fields correctly.');
            break;
        case 'db_error':
            $alertMessage = displayAlert('danger', 'There was an error submitting your rating. Please try again.');
            break;
        default:
            $alertMessage = displayAlert('danger', 'An unexpected error occurred.');
    }
}

// Fetch ratings from the database
$sql = "SELECT name, rating, comments, created_at FROM ratings ORDER BY created_at DESC";
$result = $conn->query($sql);

// Store ratings in an array
$ratings = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ratings[] = $row;
    }
}

// Calculate average rating
$avgSql = "SELECT AVG(rating) as average FROM ratings";
$avgResult = $conn->query($avgSql);
$avgRating = 0;
if ($avgResult->num_rows > 0) {
    $avgRow = $avgResult->fetch_assoc();
    $avgRating = round($avgRow['average'], 1);
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - StepStyle Footwear</title>
    <!-- Bootstrap CSS for alerts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/about.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
   <?php include'../includes/header.php'; ?>
    
    <!-- Alert Messages Container -->
    <div class="container mt-3">
        <?php echo $alertMessage; ?>
    </div>
    
    <section class="section-container" id="mission">
        <div class="container">
            <h2 class="section-title">About Us</h2>
            <div class="content-box">
                <p>At StepStyle Footwear, we're dedicated to providing high-quality, stylish, and comfortable shoes for every occasion. Our store aims to be your trusted destination for all your footwear needs.</p>
                <p>Our mission is to offer a diverse collection of fashionable, durable, and comfortable shoes that cater to all ages and lifestyles while ensuring excellent customer service, affordability, and expert guidance for our customers.</p>
            </div>
        </div>
    </section>

    <section class="section-container" id="features">
        <div class="container">
            <h2 class="section-title">Our Footwear Features</h2>
            <div class="features-grid">
                <div class="feature-box">
                    <h3>Premium Materials</h3>
                    <p>We carefully select footwear made from high-quality leather, sustainable fabrics, and innovative synthetic materials that combine comfort with durability.</p>
                </div>
                <div class="feature-box">
                    <h3>Ergonomic Design</h3>
                    <p>Our shoes feature anatomically correct designs with proper arch support, cushioned insoles, and balanced weight distribution to prevent foot fatigue and enhance comfort.</p>
                </div>
                <div class="feature-box">
                    <h3>Ethical Manufacturing</h3>
                    <p>We partner with brands committed to fair labor practices and environmentally responsible production methods.</p>
                </div>
                <div class="feature-box">
                    <h3>Extensive Size Range</h3>
                    <p>We offer an inclusive size range to ensure everyone finds their perfect fit, including hard-to-find sizes and widths.</p>
                </div>
                <div class="feature-box">
                    <h3>Style Versatility</h3>
                    <p>From casual everyday wear to formal occasions, our collection covers all your footwear needs with the latest trends and timeless classics.</p>
                </div>
                <div class="feature-box">
                    <h3>Seasonal Selections</h3>
                    <p>We continuously update our inventory with season-appropriate footwear that combines fashion with functionality for year-round comfort.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-container" id="journey">
        <div class="container">
            <h2 class="section-title">Our Store's Journey</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="year">2008</div>
                    <div class="content">
                        <h3>Our Beginning</h3>
                        <p>StepStyle Footwear was founded by Priya Sharma, a fashion enthusiast with a vision to bring quality, stylish footwear to our local community.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="year">2012</div>
                    <div class="content">
                        <h3>Store Expansion</h3>
                        <p>Expanded our retail space to showcase a wider variety of brands and styles, including a dedicated section for children's footwear.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="year">2016</div>
                    <div class="content">
                        <h3>Sustainable Collection</h3>
                        <p>Introduced our eco-friendly footwear collection featuring shoes made from recycled and sustainable materials.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="year">2020</div>
                    <div class="content">
                        <h3>Custom Fitting Service</h3>
                        <p>Launched our professional fitting service with trained staff to help customers find their perfect size and style.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="year">Today</div>
                    <div class="content">
                        <h3>Fashion Destination</h3>
                        <p>StepStyle has evolved into a premier footwear destination known for curating the best styles from around the world while maintaining personal service.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-container" id="values">
        <div class="container">
            <h2 class="section-title">Our Store Values</h2>
            <div class="values-grid">
                <div class="value-box">
                    <div class="value-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3>Quality Selection</h3>
                    <p>We carefully curate our collection to ensure every pair meets our high standards for craftsmanship, comfort, and style.</p>
                </div>
                <div class="value-box">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Customer Experience</h3>
                    <p>We're committed to providing knowledgeable, personalized service to help you find the perfect footwear for your needs.</p>
                </div>
                <div class="value-box">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Sustainability</h3>
                    <p>We actively seek out and promote footwear brands with sustainable practices and eco-friendly materials.</p>
                </div>
                <div class="value-box">
                    <div class="value-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3>Community Connection</h3>
                    <p>As a local business, we're dedicated to supporting our community through partnerships, events, and giving back initiatives.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-container" id="team">
        <div class="container">
            <h2 class="section-title">Our Store Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image">
                        <img src="../assets/images/anita_sharma.webp" alt="anita sharma">
                    </div>
                    <h3>anita sharma</h3>
                    <p class="position">Founder & Creative Director</p>
                    <p class="bio">With a background in fashion design, Priya brings her creative vision and industry knowledge to curate our unique collection.</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="../assets/images/vijay_kumar.jpeg" alt="vijay kumar">
                    </div>
                    <h3>vijay kumar</h3>
                    <p class="position">Store Manager</p>
                    <p class="bio">Rohit ensures our store operations run smoothly while maintaining our high standards of customer service.</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="../assets/images/rajesh_patel.jpg" alt="rajesh patel">
                    </div>
                    <h3>rajesh Patel</h3>
                    <p class="position">Footwear Specialist</p>
                    <p class="bio">With extensive knowledge of footwear construction and fitting, Neha helps customers find their perfect match in comfort and style.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-container" id="testimonials">
        <div class="container">
            <h2 class="section-title">What Our Customers Say</h2>
            
            <div class="average-rating-container">
                <div class="average-rating">
                    <?php echo $avgRating; ?>
                    <div class="stars">
                        <?php
                        $fullStars = floor($avgRating);
                        $halfStar = round($avgRating - $fullStars, 1) >= 0.5;
                        
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $fullStars) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ($i == $fullStars + 1 && $halfStar) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <?php if (count($ratings) > 0): ?>
            <div class="testimonials-container">
                <?php 
                $displayCount = min(count($ratings), 4);
                for ($i = 0; $i < $displayCount; $i++): 
                    $rating = $ratings[$i];
                ?>
                <div class="testimonial">
                    <div class="testimonial-content">
                        <div class="rating-stars">
                            <?php
                            for ($j = 1; $j <= 5; $j++) {
                                if ($j <= $rating['rating']) {
                                    echo '<i class="fas fa-star"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <p><?php echo !empty($rating['comments']) ? '"' . htmlspecialchars($rating['comments']) . '"' : '"Excellent selection and service!"'; ?></p>
                    </div>
                    <div class="testimonial-author">
                        <img src="../assets/images/customer-placeholder.jpg" alt="<?php echo htmlspecialchars($rating['name']); ?>">
                        <div class="author-info">
                            <h4><?php echo htmlspecialchars($rating['name']); ?></h4>
                            <p>Happy Customer</p>
                            <span class="rating-date"><?php echo date('M d, Y', strtotime($rating['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            
            <?php if (count($ratings) > 4): ?>
            <a href="all_reviews.php" class="show-more-btn">View All Reviews</a>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="no-ratings">
                <p>Be the first to share your experience with our footwear collection!</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section-container" id="cta">
        <div class="container">
            <h2>Find your perfect pair today!</h2>
            <p>Visit our store for personalized fitting and expert advice, or browse our online collection.</p>
            <a href="contact.php" class="cta-button">Get in Touch</a>
        </div>
    </section>

    <section class="section-container" id="rating-section">
        <div class="container">
            <h2 class="section-title">Rate Your Experience</h2>
            <form class="rating-form" action="process_rating.php" method="post">
                <?php 
                // Only include user_id input if user is logged in
                if(isset($_SESSION['user_id'])): 
                ?>
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Your Rating</label>
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5" title="5 stars">★</label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4" title="4 stars">★</label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3" title="3 stars">★</label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2" title="2 stars">★</label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1" title="1 star">★</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="comments">Your Comments</label>
                    <textarea id="comments" name="comments" class="form-control" placeholder="Tell us about your experience shopping with us..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="products">Products Purchased</label>
                    <input type="text" id="products" name="products" class="form-control" placeholder="Which footwear products did you purchase?">
                </div>
                
                <button type="submit" class="submit-btn">Submit Rating</button>
            </form>
        </div>
    </section>

    <?php include'../includes/footer.php'; ?>

    <!-- Bootstrap JS and Popper.js for alerts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <script>
document.querySelector('.rating-form').addEventListener('submit', function(e) {
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const rating = document.querySelector('input[name="rating"]:checked');
    
    if (name.value.trim().length === 0 || name.value.length > 100) {
        e.preventDefault();
        alert('Please enter a valid name (1-100 characters)');
        return;
    }
    
    if (!validateEmail(email.value)) {
        e.preventDefault();
        alert('Please enter a valid email address');
        return;
    }
    
    if (!rating) {
        e.preventDefault();
        alert('Please select a rating');
        return;
    }
});

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}
</script>
</body>
</html>