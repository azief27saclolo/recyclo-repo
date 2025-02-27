<?php
session_start();
require_once 'config/database.php';

// Get seller ID from URL
$seller_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch seller information
$seller_query = "SELECT s.*, u.username as owner_name, COUNT(p.product_id) as total_products,
                 AVG(r.rating) as avg_rating, COUNT(DISTINCT r.id) as review_count
                 FROM sellers s
                 LEFT JOIN users u ON s.user_id = u.id
                 LEFT JOIN products p ON s.id = p.seller_id
                 LEFT JOIN shop_reviews r ON s.id = r.seller_id
                 WHERE s.id = ? AND s.status = 'approved'
                 GROUP BY s.id";

$stmt = $conn->prepare($seller_query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$shop = $stmt->get_result()->fetch_assoc();

if (!$shop) {
    header('Location: shops.php');
    exit();
}

// Fetch seller's products
$products_query = "SELECT p.*, c.category_name,
                  COUNT(pr.id) as review_count,
                  AVG(COALESCE(pr.rating, 0)) as avg_rating
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN reviews pr ON p.product_id = pr.product_id
                  WHERE p.seller_id = ? AND p.status = 'active'
                  GROUP BY p.product_id
                  ORDER BY p.created_at DESC";

$stmt = $conn->prepare($products_query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch shop reviews
$reviews_query = "SELECT r.*, u.username, u.id as user_id
                 FROM shop_reviews r
                 JOIN users u ON r.customer_id = u.id
                 WHERE r.seller_id = ?
                 ORDER BY r.created_at DESC
                 LIMIT 10";

$stmt = $conn->prepare($reviews_query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($shop['shop_name']); ?> - Recyclo</title>
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    .shop-card {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        width: 250px; /* Reduced from 300px */
        margin: 12px;
    }

    .shop-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    .card-banner {
        position: relative;
        height: 280px; /* Adjusted height */
        background: #f8f8f8;
    }

    .img-cover {
        object-fit: contain; /* Changed from cover to contain */
        padding: 10px;
        background: white;
        transition: transform 0.3s ease;
    }

    .shop-card:hover .img-cover {
        transform: scale(1.05);
    }

    .card-content {
        padding: 15px; /* Reduced padding */
    }

    .price {
        font-size: 1.1rem; /* Slightly smaller font */
        margin-bottom: 8px;
    }

    .card-title {
        font-size: 1rem;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .card-rating {
        font-size: 0.9rem;
    }

    /* New Arrivals specific styling */
    .new-arrivals .shop-card {
        width: 180px; /* Smaller cards for new arrivals */
        margin: 8px;
    }

    .new-arrivals .card-banner {
        height: 180px; /* Square aspect ratio for new arrivals */
        border-radius: 8px;
    }

    .new-arrivals .img-cover {
        border-radius: 8px;
    }

    /* Improved scrollbar styling */
    .has-scrollbar {
        gap: 12px;
        padding: 12px 5px;
        scroll-padding: 12px;
    }

    .scrollbar-item {
        min-width: 250px; /* Match card width */
    }

    .new-arrivals .scrollbar-item {
        min-width: 180px; /* Match new arrivals card width */
    }

    /* Badge styling */
    .badge {
        position: absolute;
        top: 8px;
        left: 8px;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        background: var(--hoockers-green);
        color: white;
    }

    /* Card actions styling */
    .card-actions {
        position: absolute;
        top: 8px;
        right: 8px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: white;
        font-size: 1.1rem;
        display: grid;
        place-items: center;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: var(--hoockers-green);
        color: white;
    }

    /* Add to existing styles */
    .shop-header {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .shop-info {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 30px;
        align-items: start;
    }

    .shop-image {
        width: 100%;
        height: 300px;
        border-radius: 10px;
        object-fit: cover;
    }

    .shop-details {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .shop-name {
        font-size: 2.2rem;
        color: var(--eerie-black);
        margin-bottom: 10px;
    }

    .shop-stats {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--sonic-silver);
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 15px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--sonic-silver);
    }

    .contact-item i {
        color: var(--hoockers-green);
        font-size: 1.2rem;
    }

    .shop-description {
        line-height: 1.6;
        color: var(--sonic-silver);
        margin-top: 15px;
    }
  </style>
</head>

<body id="top">
  <header class="header">
    <div class="header-top" data-header>
      <div class="container">
        <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
          <span class="line line-1"></span>
          <span class="line line-2"></span>
          <span class="line line-3"></span>
        </button>
        <div class="input-wrapper">
          <input type="search" name="search" placeholder="Search product" class="search-field">
          <button class="search-submit" aria-label="search">
            <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
          </button>
        </div>
        <div style="display: flex; align-items: center;">
          <img src="./assets/images/mainlogo.png" alt="Logo" style="width: 50px; height: 50px; flex-shrink: 0; margin-right: 10px;">
          <div style="flex-grow: 1; text-align: center;">
              <h1 style="color: green; margin: 0;">Recyclo</h1>
          </div>
      </div>
      
        <div class="header-actions">
          <button class="header-action-btn" aria-label="user">
            <ion-icon name="person-outline" aria-hidden="true" aria-hidden="true"></ion-icon>
          </button>
          <button class="header-action-btn" aria-label="heart item">
            <ion-icon name="heart-outline" aria-hidden="true" aria-hidden="true"></ion-icon>
            <span class="btn-badge">0</span>
          </button>
          <button class="header-action-btn" aria-label="cart item">
            <ion-icon name="cart-outline" aria-hidden="true" aria-hidden="true"></ion-icon>
            <span class="btn-badge">0</span>
          </button>
        </div>
        <nav class="navbar">
          <ul class="navbar-list">
            <li>
              <a href="index.php" class="navbar-link has-after">Home</a>
            </li>
            <li>
              <a href="#collection" class="navbar-link has-after">About Us</a>
            </li>
            <li>
              <a href="#collection" class="navbar-link has-after">Categories</a>
            </li>
            <li>
              <a href="#collection" class="navbar-link has-after">Goals</a>
            </li>
            <li>
              <a href="shops.html" class="navbar-link has-after">Shops</a>
            </li>
            <li>
                <a href="shops.html" class="navbar-link has-after">Orders</a>
              </li>
          </ul>
        </nav>

      </div>
    </div>
  </header>
  <div class="sidebar">
    <div class="mobile-navbar" data-navbar>

      <div class="wrapper">
        <h1>Recyclo</h1>
        <button class="nav-close-btn" aria-label="close menu" data-nav-toggler>
          <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
        </button>
      </div>
      <ul class="navbar-list">
        <li>
          <a href="#home" class="navbar-link" data-nav-link>Home</a>
        </li>
        <li>
          <a href="#shop" class="navbar-link" data-nav-link>Categories</a>
        </li>
        <li>
          <a href="#offer" class="navbar-link" data-nav-link>Shops</a>
        </li>
      </ul>
    </div>
    <div class="overlay" data-nav-toggler data-overlay></div>
  </div>
  <main>
    <article>
        <section class="section shop" id="shop" aria-label="shop" data-section>
            <div class="container">
    
              <div class="shop-header">
                <div class="shop-info">
                    <img src="<?php echo htmlspecialchars($shop['shop_image'] ?? './assets/images/default-shop.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($shop['shop_name']); ?>" 
                         class="shop-image">
                    
                    <div class="shop-details">
                        <h1 class="shop-name"><?php echo htmlspecialchars($shop['shop_name']); ?></h1>
                        
                        <div class="shop-stats">
                            <div class="stat-item">
                                <i class="bi bi-star-fill"></i>
                                <span><?php echo number_format($shop['avg_rating'], 1); ?> rating</span>
                            </div>
                            <div class="stat-item">
                                <i class="bi bi-chat-left-text"></i>
                                <span><?php echo $shop['review_count']; ?> reviews</span>
                            </div>
                            <div class="stat-item">
                                <i class="bi bi-box"></i>
                                <span><?php echo $shop['total_products']; ?> products</span>
                            </div>
                        </div>

                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="bi bi-geo-alt"></i>
                                <span><?php echo htmlspecialchars($shop['shop_address']); ?></span>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-telephone"></i>
                                <span><?php echo htmlspecialchars($shop['contact_number'] ?? 'Contact number not available'); ?></span>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-envelope"></i>
                                <span><?php echo htmlspecialchars($shop['email']); ?></span>
                            </div>
                        </div>

                        <p class="shop-description">
                            <?php echo htmlspecialchars($shop['description'] ?? 'No description available'); ?>
                        </p>
                    </div>
                </div>
            </div>
              <ul class="has-scrollbar">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <li class="scrollbar-item">
                <div class="shop-card">
                    <div class="card-banner img-holder">
                        <a href="cart.php?id=<?php echo $product['product_id']; ?>">
                            <img src="<?php echo htmlspecialchars($product['product_image'] ?? './assets/images/default-product.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                                 class="img-cover">
                        </a>

                        <span class="badge">
                            <?php echo htmlspecialchars($product['category_name']); ?>
                        </span>

                        <div class="card-actions">
                            <button class="action-btn" aria-label="add to cart">
                                <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
                            </button>

                            <button class="action-btn" aria-label="add to wishlist">
                                <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                            </button>

                            <button class="action-btn" aria-label="compare">
                                <ion-icon name="repeat-outline" aria-hidden="true"></ion-icon>
                            </button>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="price">
                            <span class="span">₱<?php echo number_format($product['price'], 2); ?> per kg</span>
                        </div>

                        <h3>
                            <a href="view_product.php?id=<?php echo $product['product_id']; ?>" 
                               class="card-title">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </a>
                        </h3>

                        <div class="card-rating">
                            <div class="rating-wrapper">
                                <?php
                                $rating = round($product['avg_rating']);
                                for ($i = 1; $i <= 5; $i++) {
                                    echo '<ion-icon name="' . ($i <= $rating ? 'star' : 'star-outline') . '" aria-hidden="true"></ion-icon>';
                                }
                                ?>
                            </div>
                            <p class="rating-text"><?php echo $product['review_count']; ?> reviews</p>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li class="no-products">
            <p>No products available from this seller yet.</p>
        </li>
    <?php endif; ?>
</ul>

              <section class="section new-arrivals">
                <div class="container">
                  <div class="title-wrapper">
                    <h2 class="h2 section-title">New Arrivals</h2>
                    <a href="#" class="btn-link">
                      <span class="span">View More</span>
                      <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                    </a>
                  </div>
                  <ul class="has-scrollbar">
                    <?php 
                    // Get latest 6 products
                    $latest_products = array_slice($products, 0, 6);
                    foreach ($latest_products as $product): 
                    ?>
                        <li class="scrollbar-item">
                            <div class="shop-card">
                                <div class="card-banner img-holder">
                                    <img src="<?php echo htmlspecialchars($product['product_image'] ?? './assets/images/default-product.jpg'); ?>" 
                                         alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                                         class="img-cover">
                                </div>
                                <div class="card-content">
                                    <h3 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                                    <p class="price">₱<?php echo number_format($product['price'], 2); ?></p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </section>
            </div>
          </section>

          <section class="section feedback" id="feedback" aria-label="feedback" data-section>
            <div class="container" style="border: 2px solid var(--hoockers-green); padding: 10px; border-radius: var(--radius-3); position: relative; height: 400px;">
              <div class="title-wrapper">
                <h2 class="h2 section-title">Feedback & Reviews</h2>
              </div>
          
              
          
              <!-- Feedback List -->
              <ul class="feedback-list" id="feedback-list" style="overflow-y: auto; max-height: 300px; margin-top: 10px; padding: 10px;">
                <!-- Feedback Items (4 shown at a time) -->
                <?php foreach ($reviews as $review): ?>
                                <li class="feedback-item">
                                    <div class="feedback-card">
                                        <div class="user-info">
                                            <img src="./assets/images/default-user.jpg" alt="User" class="user-avatar">
                                            <div class="user-details">
                                                <h4><?php echo htmlspecialchars($review['username']); ?></h4>
                                                <div class="rating-wrapper">
                                                    <?php
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        echo '<ion-icon name="' . ($i <= $review['rating'] ? 'star' : 'star-outline') . '"></ion-icon>';
                                                    }
                                                    ?>
                                                </div>
                                                <p class="review-date">
                                                    <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <p class="review-text">
                                            <?php echo htmlspecialchars($review['review']); ?>
                                        </p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                <!-- Add other feedback items here -->
              </ul>
    
            </div>
          </section>
          
          <script>
            const feedbackList = document.getElementById('feedback-list');
          
            function scrollUp() {
              feedbackList.scrollBy({ top: -100, behavior: 'smooth' });
            }
          
            function scrollDown() {
              feedbackList.scrollBy({ top: 100, behavior: 'smooth' });
            }
          </script>
          
    </article>
  </main>
  <footer class="footer" data-section>
    <div class="container">
      <div class="footer-top">
        <ul class="footer-list">
          <li>
            <p class="footer-list-title"><i class="bi bi-link-45deg"></i> Recyclo Links</p>
          </li>
          <li>
            <p class="footer-list-text">
              <i class="bi bi-facebook"></i>   <a href="#" class="link">Recyclo</a>
            </p>
          </li>
          <br>
          <li>
            <p class="footer-list-text">
              <i class="bi bi-instagram"></i>   <a href="#" class="link">@RecycloEst2024</a>
            </p>
          </li>
          <br>
          <li>
            <p class="footer-list-text">
              <i class="bi bi-twitter"></i>   <a href="#" class="link">RecycloEst2024</a>
            </p>
          </li>
        </ul>

        <ul class="footer-list">

          <li>
            <p class="footer-list-title">Shops</p>
          </li>

          <li>
            <a href="#" class="footer-link">New Products</a>
          </li>

          <li>
            <a href="#" class="footer-link">Best Sellers</a>
          </li>

        </ul>

        <ul class="footer-list">
          <li>
            <p class="footer-list-title"><i class="bi bi-info-circle"></i> Infomation</p>
          </li>
          <li>
            <a href="#" class="footer-link">About Us</a>
          </li>
          <li>
            <a href="#" class="footer-link">Start a Return</a>
          </li>

          <li>
            <a href="#" class="footer-link">Contact Us</a>
          </li>

          <li>
            <a href="#" class="footer-link">Shipping FAQ</a>
          </li>

          <li>
            <a href="#" class="footer-link">Terms & Conditions</a>
          </li>

          <li>
            <a href="#" class="footer-link">Privacy Policy</a>
          </li>

        </ul>

        <div class="footer-list">

          <p class="newsletter-title">Good emails.</p>

          <p class="newsletter-text">
            Enter your email below to be the first to know about new collections and product launches.
          </p>

          <form action="" class="newsletter-form">
            <input type="email" name="email_address" placeholder="Enter your email address" required
              class="email-field">

            <button type="submit" class="btn btn-primary">Subscribe</button>
          </form>

        </div>

      </div>

      <div class="footer-bottom">

        <div class="wrapper">
          <p class="copyright">
            &copy; 2024 Recyclo
          </p>

          <ul class="social-list">

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-twitter"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-facebook"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-instagram"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-youtube"></ion-icon>
              </a>
            </li>

          </ul>
        </div>

        <div style="display: flex; align-items: center;">
          <img src="./assets/images/mainlogo.png" alt="Logo" style="width: 50px; height: 50px; margin-right: 10px;">
          <h1 style="color: black; margin: 0;"></h1>
      </div>

        <img src="./assets/images/p.png" width="313" height="28" alt="available all payment method" class="w-100">

      </div>

    </div>
  </footer>


  <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
    <ion-icon name="arrow-up" aria-hidden="true"></ion-icon>
  </a>
  <script src="./assets/js/script.js" defer></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>