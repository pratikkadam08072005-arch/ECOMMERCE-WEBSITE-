<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bharat Footwear</title>
    <style>
        .footer-section {
    background: linear-gradient(135deg, #1a1c20 0%, #2d3436 100%);
    color: #ffffff;
    padding: 60px 0 40px;
    position: relative;
    overflow: hidden;
}

/* Add a subtle animated background pattern */
.footer-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #00f2fe, #4facfe, #00f2fe);
    animation: rainbow 3s linear infinite;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
}

.footer-about, .footer-links, .footer-contact {
    padding: 20px;
    transition: transform 0.3s ease;
}

.footer-about:hover, .footer-links:hover, .footer-contact:hover {
    transform: translateY(-5px);
}

/* Section Headers */
.footer-section h3 {
    color: #4facfe;
    font-size: 1.5rem;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}

.footer-section h3::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, #00f2fe, #4facfe);
    transition: width 0.3s ease;
}

.footer-section div:hover h3::after {
    width: 75px;
}

/* About Section */
.footer-about p {
    line-height: 1.6;
    color: #b3b3b3;
    transition: color 0.3s ease;
}

.footer-about:hover p {
    color: #ffffff;
}

/* Links Section */
.footer-links ul {
    list-style: none;
    padding: 0;
}

.footer-links ul li {
    margin-bottom: 12px;
}

.footer-links ul li a {
    color: #b3b3b3;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    padding-left: 15px;
}

.footer-links ul li a::before {
    content: '→';
    position: absolute;
    left: 0;
    opacity: 0;
    transition: opacity 0.3s ease, transform 0.3s ease;
    transform: translateX(-10px);
}

.footer-links ul li a:hover {
    color: #4facfe;
    padding-left: 20px;
}

.footer-links ul li a:hover::before {
    opacity: 1;
    transform: translateX(0);
}

/* Contact Section */
.footer-contact p {
    color: #b3b3b3;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    transition: color 0.3s ease;
}

.footer-contact p:hover {
    color: #ffffff;
}

.footer-contact p::before {
    content: '•';
    color: #4facfe;
    margin-right: 10px;
    font-size: 1.2em;
}

/* Animations */
@keyframes rainbow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-container {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .footer-section h3::after {
        left: 50%;
        transform: translateX(-50%);
    }

    .footer-links ul li a::before {
        display: none;
    }

    .footer-links ul li a:hover {
        padding-left: 0;
    }

    .footer-contact p {
        justify-content: center;
    }
}

/* Add a dark mode animation */
@media (prefers-color-scheme: dark) {
    .footer-section {
        background: linear-gradient(135deg, #0f1114 0%, #1a1c20 100%);
    }
}

/* Improve accessibility */
.footer-section a:focus {
    outline: 2px solid #4facfe;
    outline-offset: 2px;
}

/* Add smooth scrolling for the entire page */
html {
    scroll-behavior: smooth;
}
    </style>
</head>
<body>
<footer class="footer-section">
    <div class="footer-container">
        <div class="footer-about">
            <h3>Bharat Footwear</h3>
            <p>Step into a world of comfort and style—because every journey begins with the perfect pair.</p>
        </div>
        <div class="footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><?php
                    // Define $current_page at the beginning
                    $current_page = basename($_SERVER['PHP_SELF']);
                    
                    if ($current_page === 'index.php') {
                        echo '<a href="#">home</a>';
                    } else {
                        echo '<a href="../index.php">home</a>';
                    }
                    ?></li>
                <li><a href="../index.php#products">Products</a></li>
                <li>
                    <?php
                    if ($current_page === 'contact.php') {
                        echo '<a href="#">Contact Us</a>';
                    } else {
                        echo '<a href="pages/contact.php">Contact Us</a>';
                    }
                    ?>
                </li>
                <li>
                    <?php
                    if ($current_page === 'about.php') {
                        echo '<a href="#">About Us</a>';
                    } else {
                        echo '<a href="pages/about.php">About Us</a>';
                    }
                    ?>
                </li>
            </ul>
        </div>
        <div class="footer-contact">
            <h3>Contact Info</h3>
            <p>Email: support@bharatfootwear.com</p>
            <p>Phone: +1 234 567 890</p>
            <p>Address: Abc town</p>
        </div>
    </div>
</footer>
</body>
</html>