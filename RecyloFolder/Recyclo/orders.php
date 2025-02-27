<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Recyclo</title>
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
                    <a href="login.html" class="header-action-btn" aria-label="user">
                        <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                    </a>
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
                        <li><a href="index.html" class="navbar-link has-after">Home</a></li>
                        <li><a href="#collection" class="navbar-link has-after">About Us</a></li>
                        <li><a href="#shop" class="navbar-link">Categories</a></li>
                        <li><a href="#collection" class="navbar-link has-after">Goals</a></li>
                        <li><a href="shops.html" class="navbar-link has-after">Shops</a></li>
                        <li><a href="orders.html" class="navbar-link has-after">Order</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <article>
            <!-- Add intro banner -->
            <div class="order-banner">
                <div class="container">
                    <h1>My Orders</h1>
                    <p>Track and manage your recyclable material orders</p>
                </div>
            </div>

            <section class="section orders" aria-label="orders" data-section>
                <div class="container">
                    <!-- Order Stats -->
                    <div class="order-stats">
                        <div class="stat-card">
                            <ion-icon name="bag-handle-outline"></ion-icon>
                            <div class="stat-info">
                                <h3>3</h3>
                                <p>Active Orders</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <ion-icon name="time-outline"></ion-icon>
                            <div class="stat-info">
                                <h3>1</h3>
                                <p>In Transit</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                            <div class="stat-info">
                                <h3>2</h3>
                                <p>Completed</p>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Tab Design -->
                    <div class="order-tabs">
                        <button class="tab-btn active" data-tab="new">
                            <ion-icon name="file-tray-outline"></ion-icon>
                            New Orders
                            <span class="badge">3</span>
                        </button>
                        <button class="tab-btn" data-tab="to-ship">
                            <ion-icon name="airplane-outline"></ion-icon>
                            To Ship
                            <span class="badge">1</span>
                        </button>
                        <button class="tab-btn" data-tab="to-receive">
                            <ion-icon name="cube-outline"></ion-icon>
                            To Receive
                            <span class="badge">1</span>
                        </button>
                        <button class="tab-btn" data-tab="completed">
                            <ion-icon name="checkmark-done-outline"></ion-icon>
                            Completed Orders
                            <span class="badge">2</span>
                        </button>
                        <button class="tab-btn" data-tab="refund">
                            <ion-icon name="refresh-outline"></ion-icon>
                            Refunding
                            <span class="badge">1</span>
                        </button>
                        <button class="tab-btn" data-tab="cancelled">
                            <ion-icon name="close-circle-outline"></ion-icon>
                            Cancelled
                            <span class="badge">1</span>
                        </button>
                    </div>

                    <!-- New Orders Tab Content -->
                    <div class="order-cards" id="new-orders">
                        <div class="order-card">
                            <div class="order-header">
                                <img src="./assets/images/wood.jpg" alt="Wood Scraps" class="order-img">
                                <div class="order-details">
                                    <h3>Wood Scraps Furniture</h3>
                                    <p>Seller: Ronald Organic Shop</p>
                                    <p>Order Date: Jan 15, 2024</p>
                                    <p>Quantity: 3kg</p>
                                    <p>Total: ₱95.00</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge pending">Pending</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <button class="btn btn-primary" style="border-radius: 10px;">Track Order</button>
                                <button class="btn btn-secondary" style="border-radius: 10px;">Cancel Order</button>
                            </div>
                        </div>

                        <!-- New Order 2 -->
                        <div class="order-card">
                            <div class="order-header">
                                <img src="./assets/images/mets.jpg" alt="Metal Scraps" class="order-img">
                                <div class="order-details">
                                    <h3>Metal Scraps Collection</h3>
                                    <p>Seller: John's Shop</p>
                                    <p>Order Date: Jan 18, 2024</p>
                                    <p>Quantity: 5kg</p>
                                    <p>Total: ₱100.00</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge pending">Pending</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <button class="btn btn-primary" style="border-radius: 10px;">Track Order</button>
                                <button class="btn btn-secondary" style="border-radius: 10px;">Cancel Order</button>
                            </div>
                        </div>

                        <!-- New Order 3 -->
                        <div class="order-card">
                            <div class="order-header">
                                <img src="./assets/images/bottle.jpg" alt="Glass Bottles" class="order-img">
                                <div class="order-details">
                                    <h3>Glass Bottle Set</h3>
                                    <p>Seller: Teppey's Shop</p>
                                    <p>Order Date: Jan 19, 2024</p>
                                    <p>Quantity: 2kg</p>
                                    <p>Total: ₱40.00</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge pending">Pending</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <button class="btn btn-primary" style="border-radius: 10px;">Track Order</button>
                                <button class="btn btn-secondary" style="border-radius: 10px;">Cancel Order</button>
                            </div>
                        </div>
                    </div>

                    <!-- To Ship Orders Tab Content -->
                    <div class="order-cards" id="to-ship-orders" style="display: none;">
                        <div class="order-card">
                            <div class="order-header">
                                <img src="./assets/images/plastic.jpg" alt="Plastic Materials" class="order-img">
                                <div class="order-details">
                                    <h3>Plastic Containers</h3>
                                    <p>Seller: Fred's Shop</p>
                                    <p>Order Date: Jan 10, 2024</p>
                                    <p>Quantity: 4kg</p>
                                    <p>Total: ₱80.00</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge in-transit">In Transit</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <button class="btn btn-primary" style="border-radius: 10px;">Track Order</button>
                            </div>
                        </div>
                    </div>

                    <!-- To Receive Orders Tab Content -->
                    <div class="order-cards" id="to-receive-orders" style="display: none;">
                        <div class="order-card">
                            <div class="order-header">
                                <img src="./assets/images/glass.jpg" alt="Glass Bottles" class="order-img">
                                <div class="order-details">
                                    <h3>Glass Bottle Set</h3>
                                    <p>Seller: Teppey's Shop</p>
                                    <p>Order Date: Jan 19, 2024</p>
                                    <p>Quantity: 2kg</p>
                                    <p>Total: ₱40.00</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge to-deliver">To Deliver</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <button class="btn btn-primary" style="border-radius: 10px;">Track Order</button>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Orders Tab Content -->
                    <div class="order-cards" id="completed-orders" style="display: none;">
                        <div class="order-card">
                            <div class="order-header">
                                <img src="./assets/images/wood.jpg" alt="Wood Materials" class="order-img">
                                <div class="order-details">
                                    <h3>Wood Scraps Collection</h3>
                                    <p>Seller: Ragnar's Shop</p>
                                    <p>Order Date: Jan 5, 2024</p>
                                    <p>Delivery Date: Jan 8, 2024</p>
                                    <p>Quantity: 6kg</p>
                                    <p>Total: ₱120.00</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge completed">Completed</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <button class="btn btn-primary" style="border-radius: 10px;">Buy Again</button>
                                <button class="btn btn-secondary" style="border-radius: 10px;">Review</button>
                            </div>
                        </div>

                        <div class="order-card">
                            <div class="order-header">
                                <img src="./assets/images/br.jpg" alt="Plastic Materials" class="order-img">
                                <div class="order-details">
                                    <h3>Mixed Plastic Items</h3>
                                    <p>Seller: Ronald Organic Shop</p>
                                    <p>Order Date: Jan 2, 2024</p>
                                    <p>Delivery Date: Jan 4, 2024</p>
                                    <p>Quantity: 3kg</p>
                                    <p>Total: ₱60.00</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge completed">Completed</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <button class="btn btn-primary" style="border-radius: 10px;">Buy Again</button>
                                <button class="btn btn-secondary" style="border-radius: 10px;">Review</button>
                            </div>
                        </div>
                    </div>

                    <!-- Refunding Orders Tab Content -->
                    <div class="order-cards" id="refund-orders" style="display: none;">
                        <div class="order-card">
                            <div class="order-header">
                                <img src="./assets/images/metal.jpg" alt="Metal Scraps" class="order-img">
                                <div class="order-details">
                                    <h3>Metal Scraps Collection</h3>
                                    <p>Seller: John's Shop</p>
                                    <p>Order Date: Jan 18, 2024</p>
                                    <p>Quantity: 5kg</p>
                                    <p>Total: ₱100.00</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge processing">Processing</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <button class="btn btn-primary" style="border-radius: 10px;">Track Order</button>
                            </div>
                        </div>
                    </div>

                    <!-- Cancelled Orders Tab Content -->
                    <div class="order-cards" id="cancelled-orders" style="display: none;">
                        <div class="order-card">
                            <div class="order-header">
                                <img src="./assets/images/wood.jpg" alt="Wood Scraps" class="order-img">
                                <div class="order-details">
                                    <h3>Wood Scraps Furniture</h3>
                                    <p>Seller: Ronald Organic Shop</p>
                                    <p>Order Date: Jan 15, 2024</p>
                                    <p>Quantity: 3kg</p>
                                    <p>Total: ₱95.00</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge cancelled">Cancelled</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <button class="btn btn-primary" style="border-radius: 10px;">Track Order</button>
                            </div>
                        </div>
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
                            <i class="bi bi-facebook"></i> <a href="#" class="link">Recyclo</a>
                        </p>
                    </li>
                    <br>
                    <li>
                        <p class="footer-list-text">
                            <i class="bi bi-instagram"></i> <a href="#" class="link">@RecycloEst2024</a>
                        </p>
                    </li>
                    <br>
                    <li>
                        <p class="footer-list-text">
                            <i class="bi bi-twitter"></i> <a href="#" class="link">RecycloEst2024</a>
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
                        <p class="footer-list-title"><i class="bi bi-info-circle"></i> Information</p>
                    </li>
                    <li>
                        <a href="#" class="footer-link">About Us</a>
                    </li>
                    <li>
                        <a href="#" class="footer-link">Contact Us</a>
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
                        <input type="email" name="email_address" placeholder="Enter your email address" required class="email-field">
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="wrapper">
                    <p class="copyright">&copy; 2024 Recyclo</p>
                    <ul class="social-list">
                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-facebook"></ion-icon>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-twitter"></ion-icon>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-instagram"></ion-icon>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
        <ion-icon name="arrow-up" aria-hidden="true"></ion-icon>
    </a>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');
                
                // Hide all order cards
                document.querySelectorAll('.order-cards').forEach(cards => cards.style.display = 'none');
                // Show selected tab's cards
                document.getElementById(`${button.dataset.tab}-orders`).style.display = 'grid';
            });
        });
    </script>

    <script src="./assets/js/script.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
