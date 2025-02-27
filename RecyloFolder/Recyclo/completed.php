
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Completed - Recyclo</title>
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
      <section class="section completed" id="completed" aria-label="completed" data-section>
        <div class="container">
          <h2 class="h2 section-title" style="margin-bottom: 20px;">Order Completed</h2>
          <div class="completed-details" style="text-align: center; padding: 20px; background-color: white; border-radius: var(--radius-3); box-shadow: var(--shadow-1);">
            <h3 style="color: green;"> <i class="bi bi-shield-fill-check" style="color: green;"></i> Your order has been placed successfully!</h3>
            <p style="color: black;">Thank you for your purchase. Your order is now being processed.</p>
            <a href="index.html" class="btn btn-primary" style="margin-top: 20px; border-radius: 5px;">Return to Home</a>
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