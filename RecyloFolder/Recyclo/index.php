<?php
session_start(); 
require_once 'config/database.php';

$hero_sql = "SELECT * FROM hero_content ORDER BY slide_order";
$hero_result = $conn->query($hero_sql);
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
  <style>
    .profile-dropdown {
        position: relative;
        display: inline-block;
    }

    .profile-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: green;
        border: 2px solid #517A5B;
        border-radius: 20px;
        cursor: pointer;
        color: #517A5B; 
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .profile-btn:hover {
        border-color: #2c4432; 
        color:rgb(255, 255, 255); 
        background: #517A5B; 
    }

    .profile-btn i {
        font-size: 1.2rem;
        color: inherit; 
    }

    .profile-name {
        font-weight: 500;
        color: inherit; 
    }

    .dropdown-content {
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 220px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 8px 0;
        display: none;
        z-index: 1000;
    }

    .dropdown-content.show {
        display: block;
    }

    .dropdown-content a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .dropdown-content a:hover {
        background-color: #f5f5f5;
    }

    .dropdown-divider {
        height: 1px;
        background: #eee;
        margin: 8px 0;
    }

    .logout-btn {
        color: #dc3545 !important;
    }

    .logout-btn:hover {
        background-color: #fff5f5 !important;
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
    <?php if (isset($_SESSION['user_logged_in']) || isset($_SESSION['admin_logged_in'])): ?>
        <div class="profile-dropdown">
            <button type="button" class="profile-btn" id="profileDropdownBtn">
                <i class="bi bi-person-check-fill"></i>
                <span class="profile-name"><?php echo isset($_SESSION['admin_logged_in']) ? 'Admin' : 'Profile'; ?></span>
            </button>
            <div class="dropdown-content" id="profileDropdown">
                <?php if (isset($_SESSION['admin_logged_in'])): ?>
                    <a href="admin/dashboard.php">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                <?php else: ?>
                    <a href="profile.php">
                        <i class="bi bi-person"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="settings.php">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </a>
                    <a href="orders.php">
                        <i class="bi bi-cart"></i>
                        <span>My Orders</span>
                    </a>
                <?php endif; ?>
                
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    <?php else: ?>
        <a href="login.php" class="header-action-btn">
            <i class="bi bi-person"></i>
        </a>
    <?php endif; ?>
    
    <button class="header-action-btn" aria-label="heart item">
        <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
        <span class="btn-badge">0</span>
    </button>
    
    <button class="header-action-btn" aria-label="cart item">
        <ion-icon name="cart-outline" aria-hidden="true"></ion-icon>
        <span class="btn-badge">0</span>
    </button>
</div>

        <nav class="navbar">
          <ul class="navbar-list">
            <li>
              <a href="#home" class="navbar-link has-after">Home</a>
            </li>
            <li>
              <a href="#collection" class="navbar-link has-after">About Us</a>
            </li>
            <li>
              <a href="#shop" class="navbar-link" data-nav-link>Categories</a>
              
            </li>
            <li>
              <a href="#collection" class="navbar-link has-after">Goals</a>
            </li>
            <li>
              <a href="shops.php" class="navbar-link has-after">Shops</a>
            </li>
            <li>
              <a href="orders.php" class="navbar-link has-after">Order</a>
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
          <a href="#collection" class="navbar-link has-after">About Us</a>
        </li>
        <li>
          <a href="#shop" class="navbar-link" data-nav-link>Categories</a>
        </li>
        <li>
          <a href="#collection" class="navbar-link has-after">Goals</a>
        </li>
        <li>
          <a href="#offer" class="navbar-link" data-nav-link>Shops</a>
        </li>
        <li>
          <a href="shops.html" class="navbar-link has-after">Order</a>
        </li>
      </ul>
    </div>
    <div class="overlay" data-nav-toggler data-overlay></div>
  </div>
  <main>
    <article>
      <section class="section hero" id="home" aria-label="hero" data-section>
        <div class="container">
          <ul class="has-scrollbar">
            <?php while($slide = $hero_result->fetch_assoc()): ?>
            <li class="scrollbar-item">
              <div class="hero-card has-bg-image" style="background-image: url('<?php echo htmlspecialchars($slide['image_url']); ?>')">
                <div class="card-content">
                  <h1 class="h1 hero-title">
                    <?php echo htmlspecialchars($slide['title']); ?>
                  </h1>
                  <p class="hero-text" style="color: black">
                    <?php echo htmlspecialchars($slide['description']); ?>
                  </p>
                  <p class="price" style="color: black;">Starting at ₱ <?php echo htmlspecialchars(number_format($slide['price'], 2)); ?></p>
                  <a href="#" class="btn btn-primary">Shop Now</a>
                </div>
              </div>
            </li>
            <?php endwhile; ?>
          </ul>
        </div>
      </section>
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

      <section class="section shop" id="shop" aria-label="shop" data-section>
        <div class="container">

          <div class="title-wrapper">
            <h2 class="h2 section-title">Best Deals</h2>

            <a href="#" class="btn-link">
              <span class="span">View More Products</span>

              <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
            </a>
          </div>

          <ul class="has-scrollbar">

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/cups.jpg" width="540" height="720" loading="lazy"
                    alt="Facial cleanser" class="img-cover">

                  <span class="badge" aria-label="20% off">Plastic</span>
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

                    <span class="span">₱20.00</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">Ronald's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720; --border-radius: 20px;"                >
                  <img src="./assets/images/mets.jpg" border-radius="20px"width="540" height="720" loading="lazy"
                    alt="Bio-shroom Rejuvenating Serum" class="img-cover">
                    <span class="badge" aria-label="20% off">Metal</span>
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
                    <span class="span">₱20.00</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">John's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/wood.jpg" width="540" height="720" loading="lazy"
                    alt="Coffee Bean Caffeine Eye Cream" class="img-cover">
                    <span class="badge" aria-label="20% off">Wood</span>
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
                    <span class="span">₱20.00</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">Fred's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/bottle.jpg" width="540" height="720" loading="lazy"
                    alt="Facial cleanser" class="img-cover">
                    <span class="badge" aria-label="20% off">Glass</span>
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
                    <span class="span">₱20.00</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">Teppey's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/br.jpg" width="540" height="720" loading="lazy"
                    alt="Coffee Bean Caffeine Eye Cream" class="img-cover">

                  <span class="badge" aria-label="20% off">Plastic</span>

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
                    <span class="span">₱20.00</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">Ragnar's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/cups.jpg" width="540" height="720" loading="lazy"
                    alt="Facial cleanser" class="img-cover">
                    <span class="badge" aria-label="20% off">Plastic</span>
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
                    <span class="span">₱20.00</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">Ronald's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

          </ul>

        </div>
      </section>

      <section class="section shop" id="shop" aria-label="shop" data-section>
        <div class="container">

          <div class="title-wrapper">
            <h2 class="h2 section-title">Shops For You</h2>

            <a href="#" class="btn-link">
              <span class="span">View More Shops</span>

              <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
            </a>
          </div>

          <ul class="has-scrollbar">
            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/f1.jpg" width="540" height="720" loading="lazy"
                    alt="Facial cleanser" class="img-cover">
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
                    <span class="span">Ronald Organic</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">Ronald's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/f2.jpg" width="540" height="720" loading="lazy"
                    alt="Bio-shroom Rejuvenating Serum" class="img-cover">

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
                    <span class="span">John Organic</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">John's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/f3.jpg" width="540" height="720" loading="lazy"
                    alt="Coffee Bean Caffeine Eye Cream" class="img-cover">

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
                    <span class="span">Teppey Recycle</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">Teppey's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/f4.jpg" width="540" height="720" loading="lazy"
                    alt="Facial cleanser" class="img-cover">

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
                    <span class="span">Fred Economy</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">Fred's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/f5.jpg" width="540" height="720" loading="lazy"
                    alt="Coffee Bean Caffeine Eye Cream" class="img-cover">
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
                    <span class="span">Ragnar Junk Shop</span>
                  </div>

                  <h3>
                    <a href="#" class="card-title">Ragnar's Shop</a>
                  </h3>

                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

            <li class="scrollbar-item">
              <div class="shop-card">

                <div class="card-banner img-holder" style="--width: 540; --height: 720;">
                  <img src="./assets/images/f6.jpg" width="540" height="720" loading="lazy"
                    alt="Facial cleanser" class="img-cover">

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
                  <div class="card-rating">

                    <div class="rating-wrapper" aria-label="5 start rating">
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

          </ul>

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
      <section class="section blog" id="blog" aria-label="blog" data-section>
        <div class="container">
          <h2 class="h2-large section-title">More about <span><img src="./assets/images/mainlogo.png" alt="logo" style="width: 50px; height: 50px; margin-left: 600px;"></span></h2>
          <ul class="flex-list">
            <li class="flex-item">
              <div class="blog-card">
                <figure class="card-banner img-holder has-before hover:shine" style="--width: 700; --height: 450;">
                  <img src="./assets/images/m1.jpg" width="700" height="450" loading="lazy" alt="Find a Store"
                    class="img-cover">
                </figure>
                <h3 class="h3">
                  <a href="#" class="card-title">Our Mission</a>
                </h3>
                <a href="#" class="btn-link">
                  <span class="span">View</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>
            <li class="flex-item">
              <div class="blog-card">
                <figure class="card-banner img-holder has-before hover:shine" style="--width: 700; --height: 450;">
                  <img src="./assets/images/m2.jpg" width="700" height="450" loading="lazy" alt="From Our Blog"
                    class="img-cover">
                </figure>
                <h3 class="h3">
                  <a href="#" class="card-title">Our Goals</a>
                </h3>
                <a href="#" class="btn-link">
                  <span class="span">View</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>

              </div>
            </li>

            <li class="flex-item">
              <div class="blog-card">

                <figure class="card-banner img-holder has-before hover:shine" style="--width: 700; --height: 450;">
                  <img src="./assets/images/pik.jpg" width="700" height="450" loading="lazy" alt="Our Story"
                    class="img-cover">
                </figure>

                <h3 class="h3">
                  <a href="#" class="card-title">Our Vision</a>
                </h3>

                <a href="#" class="btn-link">
                  <span class="span">View</span>

                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>
          </ul>
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
  <script>
document.addEventListener('DOMContentLoaded', function() {
    const profileBtn = document.getElementById('profileDropdownBtn');
    const dropdownContent = document.getElementById('profileDropdown');

    if (profileBtn && dropdownContent) {
        // Toggle dropdown on button click
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownContent.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownContent.contains(e.target) && !profileBtn.contains(e.target)) {
                dropdownContent.classList.remove('show');
            }
        });
    }
});
</script>
<style>
.profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--white);
}

.profile-icon {
    font-size: 1.5rem;
}

.profile-name {
    font-size: 1rem;
}

.dropdown-content {
    position: absolute;
    top: 100%;
    right: 0;
    min-width: 220px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    padding: 8px 0;
    display: none;
    z-index: 1000;
}

.dropdown-content.show {
    display: block;
}

.dropdown-content a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    transition: background-color 0.3s;
}

.dropdown-content a:hover {
    background-color: #f5f5f5;
}

.dropdown-divider {
    height: 1px;
    background: #eee;
    margin: 8px 0;
}

.logout-btn {
    color: #dc3545 !important;
}

.logout-btn:hover {
    background-color: #fff5f5 !important;
}
</style>
</body>

</html>