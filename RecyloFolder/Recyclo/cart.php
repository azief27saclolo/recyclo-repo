<?php
session_start();
require_once 'config/database.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Comprehensive query to get all product and seller details
$query = "SELECT p.*, s.*, c.category_name, u.username as seller_name,
          COUNT(r.id) as review_count,
          AVG(COALESCE(r.rating, 0)) as avg_rating,
          (
              SELECT GROUP_CONCAT(pi.image_url)
              FROM product_images pi
              WHERE pi.product_id = p.product_id
          ) as additional_images
          FROM products p
          JOIN sellers s ON p.seller_id = s.id
          LEFT JOIN categories c ON p.category_id = c.category_id
          LEFT JOIN reviews r ON p.product_id = r.product_id
          JOIN users u ON s.user_id = u.id
          WHERE p.product_id = ? AND p.status = 'active'
          GROUP BY p.product_id";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header('Location: shops.php');
    exit();
}

// Get similar products from same seller
$similar_query = "SELECT p.*, c.category_name 
                 FROM products p
                 LEFT JOIN categories c ON p.category_id = c.category_id
                 WHERE p.seller_id = ? AND p.product_id != ?
                 ORDER BY RAND() LIMIT 6";

$stmt = $conn->prepare($similar_query);
$stmt->bind_param("ii", $product['seller_id'], $product_id);
$stmt->execute();
$similar_products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Split additional images into array
$additional_images = $product['additional_images'] ? explode(',', $product['additional_images']) : [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['product_name']); ?> - Recyclo</title>
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
              <a href="index.html" class="navbar-link has-after">Home</a>
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
    
              <div class="title-wrapper" style="display: flex; align-items: start; gap: 30px;">
                <!-- Main Product Image -->
                <div class="product-images" style="flex: 0 0 400px;">
                    <img src="<?php echo htmlspecialchars($product['product_image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                         style="width: 400px; height: 450px; border-radius: 10px; object-fit: contain; background: #f8f8f8;">
                    
                    <!-- Thumbnail Images -->
                    <div class="thumbnails" style="display: flex; gap: 10px; margin-top: 15px;">
                        <?php foreach ($additional_images as $image): ?>
                        <img src="<?php echo htmlspecialchars($image); ?>" 
                             style="width: 80px; height: 80px; border-radius: 8px; cursor: pointer; object-fit: cover;"
                             onclick="updateMainImage(this.src)"
                             alt="Product thumbnail">
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="product-details" style="flex: 1;">
                    <h2 class="h2 section-title">
                        <?php echo htmlspecialchars($product['product_name']); ?>
                    </h2>
                    
                    <div class="seller-info" style="margin: 15px 0;">
                        <h3 style="font-weight: 500; margin-bottom: 10px;">
                            <i class="bi bi-shop"></i> <?php echo htmlspecialchars($product['shop_name']); ?>
                        </h3>
                        <p>
                            <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($product['shop_address']); ?><br>
                            <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($product['contact_number']); ?><br>
                            <i class="bi bi-person-check"></i> <?php echo htmlspecialchars($product['seller_name']); ?>
                        </p>
                    </div>

                    <div class="product-description">
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>

                    <div class="price-section" style="margin: 20px 0;">
                        <h3>Price per kg:</h3>
                        <h2 style="color: var(--hoockers-green);">
                            ₱<?php echo number_format($product['price'], 2); ?>
                        </h2>
                    </div>

                    <form action="process_order.php" method="POST" class="order-form">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <label>
                                <span style="display: block; margin-bottom: 5px;">Quantity (kg):</span>
                                <div class="quantity-controls">
                                    <button type="button" onclick="decreaseQuantity()" class="qty-btn">-</button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" 
                                           max="<?php echo $product['quantity']; ?>" 
                                           class="quantity-input">
                                    <button type="button" onclick="increaseQuantity()" class="qty-btn">+</button>
                                </div>
                            </label>
                            
                            <div class="action-buttons">
                                <button type="submit" name="action" value="cart" class="btn btn-primary">
                                    Add to Cart
                                </button>
                                <button type="submit" name="action" value="checkout" class="btn btn-secondary">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

              <ul class="has-scrollbar">
                <li class="scrollbar-item">
                  <div class="shop-card">
                    <div class="card-banner img-holder" style="--width: 150; --height: 100; border-radius: 15px;">
                      <img src="./assets/images/woods1.jpg" width="100" height="100" loading="lazy" alt="Plastic Cups" class="img-cover">
                    </div>
                  </div>    
                </li>
                <li class="scrollbar-item">
                  <div class="shop-card">
                    <div class="card-banner img-holder" style="--width: 150; --height: 100; border-radius: 15px;">
                      <img src="./assets/images/woods2.jpg" width="100" height="100" loading="lazy" alt="Metal Cans" class="img-cover">
                    </div>
                  </div>
                </li>
                <li class="scrollbar-item">
                  <div class="shop-card">
                    <div class="card-banner img-holder" style="--width: 150; --height: 100; border-radius: 15px;">
                      <img src="./assets/images/wood.jpg" width="100" height="100" loading="lazy" alt="Wood Scraps" class="img-cover">
                    </div>
                  </div>
                </li>
                <li class="scrollbar-item">
                  <div class="shop-card">
                    <div class="card-banner img-holder" style="--width: 150; --height: 100; border-radius: 15px;">
                      <img src="./assets/images/woods3.jpg" width="100" height="100" loading="lazy" alt="Glass Bottles" class="img-cover">
                    </div>
                  </div>
                </li>
                
                <li class="scrollbar-item">
                  <div class="shop-card">
                    <div class="card-banner img-holder" style="--width: 150; --height: 100; border-radius: 15px;">
                      <img src="./assets/images/woods4.jpg" width="100" height="100" loading="lazy" alt="Plastic Bottles" class="img-cover">
                    </div>
                  </div>
                </li>
                <li class="scrollbar-item">
                    <div class="shop-card">
                      <div class="card-banner img-holder" style="--width: 150; --height: 100; border-radius: 15px;">
                        <img src="./assets/images/woods3.jpg" width="100" height="100" loading="lazy" alt="Glass Bottles" class="img-cover">
                      </div>
                    </div>
                  </li>
                  <li class="scrollbar-item">
                    <div class="shop-card">
                      <div class="card-banner img-holder" style="--width: 150; --height: 100; border-radius: 15px;">
                        <img src="./assets/images/woods3.jpg" width="100" height="100" loading="lazy" alt="Glass Bottles" class="img-cover">
                      </div>
                    </div>
                  </li>
              </ul>

              <section style="border: 2px solid var(--hoockers-green); padding: 20px; margin-top: 20px;">
                <div style="font-size: 1.5rem;">
                  <h1>Product Details</h1>
                  <p>⦁ These Wood Scraps are from old furnitures where it was made up out of high quality woods</p>
                  <br>
                  <h1>You can use these recyclable materials for</h1>
                  <p>⦁ Making new products</p>
                  <p>⦁ Reuse or refurnish these woods </p>
                </div>
              </section>
              <section class="section shop" id="shop" aria-label="shop" data-section>
                <div class="container">
        
                  <div class="title-wrapper">
                    <h2 class="h2 section-title">Similar Products For You</h2>
        
                    <a href="#" class="btn-link">
                      <span class="span">View More Products</span>
        
                      <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                    </a>
                  </div>
        
                  <ul class="has-scrollbar">
                    <?php foreach ($similar_products as $similar): ?>
                    <li class="scrollbar-item">
                      <div class="shop-card">
        
                        <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                          <img src="<?php echo htmlspecialchars($similar['product_image']); ?>" width="540" height="720" loading="lazy"
                            alt="Facial cleanser" class="img-cover">
        
                          <span class="badge" aria-label="20% off"><?php echo htmlspecialchars($similar['category_name']); ?></span>
                          <div class="card-actions">
        
                            <button class="action-btn" aria-label="add to cart">
                              <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
                            </button>
        
                            <button class="action-btn" aria-label="add to whishlist">
                              <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                            </button>
        
                            <button class="action-btn" aria-label="compare">
                              <ion-icon name="repeat-outline" aria-hidden="true"></ion-icon>
                            </button>
        
                          </div>
                        </div>
        
                        <div class="card-content">
        
                          <div class="price">
        
                            <span class="span">₱<?php echo number_format($similar['price'], 2); ?></span>
                          </div>
        
                          <h3>
                            <a href="view_shop.html" class="card-title"><?php echo htmlspecialchars($similar['shop_name']); ?></a>
                          </h3>
        
                          <div class="card-rating">
        
                            <div class="rating-wrapper" aria-label="5 start rating">
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                            </div>
        
                            <p class="rating-text"><?php echo htmlspecialchars($similar['review_count']); ?> reviews</p>
        
                          </div>
        
                        </div>
        
                      </div>
                    </li>
                    <?php endforeach; ?>
                  </ul>
        
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
                    <li class="feedback-item">
                      <div class="feedback-card" style="border: 1px solid var(--light-gray); padding: 15px; border-radius: var(--radius-3); display: flex; align-items: center;">
                        <img src="./assets/images/f2.jpg" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                        <div>
                          <h3 class="feedback-title">Saclolo</h3>
                          <p class="feedback-text">Great shop! The products are of high quality and the prices are very reasonable. Highly recommend!</p>
                          <div class="feedback-rating" style="color: yellow; display: flex; flex-direction: row;">
                            <ion-icon name="star" aria-hidden="true"></ion-icon>
                            <ion-icon name="star" aria-hidden="true"></ion-icon>
                            <ion-icon name="star" aria-hidden="true"></ion-icon>
                            <ion-icon name="star" aria-hidden="true"></ion-icon>
                            <ion-icon name="star" aria-hidden="true"></ion-icon>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li class="feedback-item">
                        <div class="feedback-card" style="border: 1px solid var(--light-gray); padding: 15px; border-radius: var(--radius-3); display: flex; align-items: center;">
                          <img src="./assets/images/f1.jpg" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                          <div>
                            <h3 class="feedback-title">Saclolo</h3>
                            <p class="feedback-text">Great shop! The products are of high quality and the prices are very reasonable. Highly recommend!</p>
                            <div class="feedback-rating" style="color: yellow; display: flex; flex-direction: row;">
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="feedback-item">
                        <div class="feedback-card" style="border: 1px solid var(--light-gray); padding: 15px; border-radius: var(--radius-3); display: flex; align-items: center;">
                          <img src="./assets/images/f4.jpg" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                          <div>
                            <h3 class="feedback-title">Saclolo</h3>
                            <p class="feedback-text">Great shop! The products are of high quality and the prices are very reasonable. Highly recommend!</p>
                            <div class="feedback-rating" style="color: yellow; display: flex; flex-direction: row;">
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="feedback-item">
                        <div class="feedback-card" style="border: 1px solid var(--light-gray); padding: 15px; border-radius: var(--radius-3); display: flex; align-items: center;">
                          <img src="./assets/images/f3.jpg" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                          <div>
                            <h3 class="feedback-title">Saclolo</h3>
                            <p class="feedback-text">Great shop! The products are of high quality and the prices are very reasonable. Highly recommend!</p>
                            <div class="feedback-rating" style="color: yellow; display: flex; flex-direction: row;">
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="feedback-item">
                        <div class="feedback-card" style="border: 1px solid var(--light-gray); padding: 15px; border-radius: var(--radius-3); display: flex; align-items: center;">
                          <img src="./assets/images/f3.jpg" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                          <div>
                            <h3 class="feedback-title">Saclolo</h3>
                            <p class="feedback-text">Great shop! The products are of high quality and the prices are very reasonable. Highly recommend!</p>
                            <div class="feedback-rating" style="color: yellow; display: flex; flex-direction: row;">
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="feedback-item">
                        <div class="feedback-card" style="border: 1px solid var(--light-gray); padding: 15px; border-radius: var(--radius-3); display: flex; align-items: center;">
                          <img src="./assets/images/f3.jpg" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                          <div>
                            <h3 class="feedback-title">Saclolo</h3>
                            <p class="feedback-text">Great shop! The products are of high quality and the prices are very reasonable. Highly recommend!</p>
                            <div class="feedback-rating" style="color: yellow; display: flex; flex-direction: row;">
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                              <ion-icon name="star" aria-hidden="true"></ion-icon>
                            </div>
                          </div>
                        </div>
                      </li>
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
  <script>
    function increaseQuantity() {
      var quantity = document.getElementById('quantity');
      quantity.value = parseInt(quantity.value) + 1;
    }

    function decreaseQuantity() {
      var quantity = document.getElementById('quantity');
      if (quantity.value > 1) {
        quantity.value = parseInt(quantity.value) - 1;
      }
    }

    function updateMainImage(src) {
        document.querySelector('.product-images > img').src = src;
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.getAttribute('max'));
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }
  </script>
  <style>
    button:hover {
      background-color: var(--hoockers-green);
      color: var(--white);
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quantity-input {
        width: 60px;
        text-align: center;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .qty-btn {
        padding: 5px 12px;
        border: none;
        background: var(--hoockers-green);
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
    }

    .product-details-section {
        margin-top: 40px;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 10px;
    }

    .thumbnails img:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
  </style>
</body>
</html>