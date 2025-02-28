<?php
session_start();
require_once 'config/database.php';

// Fetch approved sellers with their ratings and product counts
$sellers_query = "SELECT s.*, u.username as owner_name, 
                 COUNT(DISTINCT p.product_id) as product_count,
                 AVG(sr.rating) as avg_rating,
                 COUNT(DISTINCT sr.id) as review_count
                 FROM sellers s
                 JOIN users u ON s.user_id = u.id
                 LEFT JOIN products p ON s.id = p.seller_id
                 LEFT JOIN shop_reviews sr ON s.id = sr.seller_id
                 WHERE s.status = 'approved'
                 GROUP BY s.id
                 ORDER BY avg_rating DESC";

$sellers = $conn->query($sellers_query)->fetch_all(MYSQLI_ASSOC);

// Fetch latest products for each seller
$seller_products = [];
foreach ($sellers as $seller) {
    $products_query = "SELECT p.*, c.category_name,
                      COUNT(r.id) as review_count,
                      AVG(COALESCE(r.rating, 0)) as avg_rating
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.category_id
                      LEFT JOIN reviews r ON p.product_id = r.product_id
                      WHERE p.seller_id = ? AND p.status = 'active'
                      GROUP BY p.product_id
                      ORDER BY p.created_at DESC
                      LIMIT 6";
    
    $stmt = $conn->prepare($products_query);
    $stmt->bind_param("i", $seller['id']);
    $stmt->execute();
    $seller_products[$seller['id']] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recyclo - Main-Page</title>
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="preload" as="image" href="./assets/images/logo.png">
  <link rel="preload" as="image" href="./assets/images/sss.jpg">
  <link rel="preload" as="image" href="./assets/images/mm.jpg">
  <link rel="preload" as="image" href="./assets/images/bboo.jpg">
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
              <a href="shops.php" class="navbar-link has-after">Shops</a>
            </li>
            <li>
                <a href="orders.php" class="navbar-link has-after">Orders</a>
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
      <section class="section collection" id="collection" aria-label="collection" data-section>
        <div class="container">
          <ul class="collection-list">
            <li>
              <div class="collection-card has-before hover:shine">
                <h2 class="h2 card-title">Recyclable Materials</h2>
                <p class="card-text">All sorts of solid waste awaits!</p>
                <a href="#" class="btn-link">
                  <span class="span">Shop Now</span>
                  <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                </a>
                <div class="has-bg-image" style="background-image: url('./assets/images/bos.jpg')"></div>
              </div>
            </li>
            <li>
              <div class="collection-card has-before hover:shine">
                <h2 class="h2 card-title">Thrash?</h2>
                <p class="card-text">No. They are treasures!</p>
                <a href="#" class="btn-link">
                  <span class="span">Shop Now</span>
                  <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                </a>
                <div class="has-bg-image" style="background-image: url('./assets/images/plastic.jpg')"></div>
              </div>
            </li>
            <li>
              <div class="collection-card has-before hover:shine">

                <h2 class="h2 card-title">Shop in Recyclo</h2>

                <p class="card-text">Budget-friendly & Economic Growth</p>

                <a href="#" class="btn-link">
                  <span class="span">Shop Now</span>

                  <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                </a>

                <div class="has-bg-image" style="background-image: url('./assets/images/glass.jpg')"></div>

              </div>
            </li>

          </ul>

        </div>
      </section>

      <!-- Add this section before the sellers section -->
<section class="section shop" id="featured" aria-label="featured" data-section>
    <div class="container">
        <div class="title-wrapper">
            <h2 class="h2 section-title">Featured Products</h2>
            <a href="#" class="btn-link">
                <span class="span">View More Products</span>
                <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
            </a>
        </div>

        <ul class="has-scrollbar">
            <li class="scrollbar-item">
                <div class="shop-card">
                    <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                        <img src="./assets/images/cups.jpg" width="540" height="720" loading="lazy" alt="Plastic Cups" class="img-cover">
                        <span class="badge">Plastic</span>
                        <div class="card-actions">
                            <button class="action-btn" aria-label="add to cart">
                                <ion-icon name="bag-handle-outline" aria-hidden="true"></ion-icon>
                            </button>
                            <button class="action-btn" aria-label="add to wishlist">
                                <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                            </button>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="price">
                            <span class="span">₱20.00</span>
                        </div>
                        <h3>
                            <a href="#" class="card-title">Recyclable Plastic Cups</a>
                        </h3>
                        <div class="card-rating">
                            <div class="rating-wrapper" aria-label="5 star rating">
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                                <ion-icon name="star" aria-hidden="true"></ion-icon>
                            </div>
                            <p class="rating-text">5170 reviews</p>
                        </div>
                    </div>
                </div>
            </li>
            <!-- Add 5 more similar items with different images and categories -->
        </ul>
    </div>
</section>

<!-- Update the sellers section styles -->
<style>
    /* Improved card styling */
    .shop-section {
        padding: 40px 0;
    }

    .seller-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .shop-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .shop-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
    }

    .card-banner {
        position: relative;
        height: 280px;
        overflow: hidden;
    }

    .img-cover {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .shop-card:hover .img-cover {
        transform: scale(1.05);
    }

    .card-content {
        padding: 20px;
    }

    .price {
        color: var(--hoockers-green);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .card-title {
        font-size: 1.1rem;
        color: var(--eerie-black);
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8em;
    }

    .card-rating {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rating-wrapper {
        color: #ffd700;
        font-size: 1rem;
    }

    .rating-text {
        color: var(--sonic-silver);
        font-size: 0.9rem;
    }

    .badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--hoockers-green);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .card-actions {
        position: absolute;
        top: 15px;
        right: 15px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.3s ease;
    }

    .shop-card:hover .card-actions {
        opacity: 1;
        transform: translateX(0);
    }

    .action-btn {
        background: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: var(--hoockers-green);
        color: white;
    }

    /* Seller header styling */
    .seller-header {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .seller-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .seller-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .seller-details h3 {
        font-size: 1.2rem;
        color: var(--eerie-black);
        margin-bottom: 5px;
    }

    .seller-stats {
        display: flex;
        gap: 15px;
        color: var(--sonic-silver);
        font-size: 0.9rem;
    }
</style>

<!-- Update the sellers section HTML structure -->
<section class="section shop-section" id="sellers" aria-label="sellers" data-section>
    <div class="container">
        <?php foreach ($sellers as $seller): ?>
        <div class="seller-header">
            <div class="seller-info">
                <img src="<?php echo htmlspecialchars($seller['shop_image'] ?? './assets/images/default-shop.jpg'); ?>" 
                     alt="<?php echo htmlspecialchars($seller['shop_name']); ?>" 
                     class="seller-avatar">
                <div class="seller-details">
                    <h3><?php echo htmlspecialchars($seller['shop_name']); ?></h3>
                    <div class="seller-stats">
                        <span><i class="bi bi-star-fill"></i> <?php echo number_format($seller['avg_rating'], 1); ?></span>
                        <span><i class="bi bi-box"></i> <?php echo $seller['product_count']; ?> products</span>
                        <span><i class="bi bi-chat-left-text"></i> <?php echo $seller['review_count']; ?> reviews</span>
                    </div>
                </div>
            </div>
            <a href="view_shop.php?id=<?php echo $seller['id']; ?>" class="btn btn-primary">View Shop</a>
        </div>

        <div class="seller-grid">
            <?php foreach ($seller_products[$seller['id']] as $product): ?>
            <!-- Product card structure remains the same but will use the new styles -->
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

      <section class="section banner" aria-label="banner" data-section>
        <div class="container">

          <ul class="banner-list">

            <li>
              <div class="banner-card banner-card-1 has-before hover:shine">

                <p class="card-subtitle" style="color: whitesmoke;">Make an Order in Recyclo</p>

                <h2 class="h2 card-title"style="color: whitesmoke;">Budget-Friendly Prices!</h2>

                <a href="#" class="btn btn-secondary" >Order Now</a>

                <div class="has-bg-image" style="background-image: url('./assets/images/rec.jpg')"></div>

              </div>
            </li>

            <li>
              <div class="banner-card banner-card-2 has-before hover:shine">

                <h2 class="h2 card-title" style="color: green;">Recyclo</h2>

                <p class="card-text">
                  In Recyclo, we practice proper and innovative ways to use recyclable materials.
                </p>

                <a href="#" class="btn btn-secondary">Shop Sale</a>

                <div class="has-bg-image" style="background-image: url('./assets/images/bag.jpg')"></div>

              </div>
            </li>

          </ul>

        </div>
      </section>

      <section class="section feature" aria-label="feature" data-section>
        <div class="container">

          <h2 class="h2-large section-title">Why Use Recyclo?</h2>

          <ul class="flex-list">

            <li class="flex-item">
              <div class="feature-card">

                <img src="./assets/images/c1.png" width="204" height="236" loading="lazy" alt="Guaranteed PURE"
                  class="card-icon">

                <h3 class="h3 card-title">100% Recyclable Materials</h3>

                <p class="card-text">
                  Throwaway materials can be much more and can be use in many different ways.
                </p>

              </div>
            </li>

            <li class="flex-item">
              <div class="feature-card">

                <img src="./assets/images/c2.png" width="204" height="236" loading="lazy"
                  alt="Completely Cruelty-Free" class="card-icon">

                <h3 class="h3 card-title">Pollution-Free</h3>

                <p class="card-text">
                  We care not only for our users but for the entire world. Hence, we support a greener planet!
                </p>

              </div>
            </li>

            <li class="flex-item">
              <div class="feature-card">

                <img src="./assets/images/c3.png" width="204" height="236" loading="lazy"
                  alt="Ingredient Sourcing" class="card-icon">

                <h3 class="h3 card-title">Innovation</h3>

                <p class="card-text">
                  An innovation lies hidden among these scraps.
                </p>

              </div>
            </li>

          </ul>

        </div>
      </section>
      <section class="section offer" id="offer" aria-label="offer" data-section>
        <div class="container">

          <figure class="offer-banner">
            <img src="./assets/images/r2.jpg" width="305" height="408" loading="lazy" alt="offer products"
              class="w-100">

            <img src="./assets/images/r1.jpg" width="450" height="625" loading="lazy" alt="offer products"
              class="w-100">
          </figure>

          <div class="offer-content">

            <p class="offer-subtitle">
              <span class="span">Budget-Friendly Prices</span>

              <span class="badge" aria-label="20% off">Plastic</span>
              <span class="badge" aria-label="20% off">Wood</span>
              <span class="badge" aria-label="20% off">Metals</span>
              <span class="badge" aria-label="20% off">More!</span>
            </p>

            <h2 class="h2-large section-title">Products That Are Made Out Of Solid Waste</h2>

            <p class="section-text" style="color: black;">
             Here are some examples of products that are recycled up using recyclable materials.
            </p>

            <div class="countdown">
              <span class="time" aria-label="days">Reduce</span>
              <span class="time" aria-label="hours">Reuse</span>
              <span class="time" aria-label="minutes">Recycle</span>
            </div>
            <a href="#" class="btn btn-primary">Shop Now In Recyclo</a>
          </div>
        </div>
      </section>
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