<header class="header">
    <div class="header-top">
        <div class="container">
            <!-- ...existing header code... -->
            
            <div class="header-actions">
                <?php if (isset($_SESSION['user_logged_in'])): ?>
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileDropdownBtn">
                            <i class="bi bi-person-circle"></i>
                            <span class="profile-name">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </span>
                        </button>
                        <div class="dropdown-content" id="profileDropdown">
                            <a href="profile.php">My Profile</a>
                            <a href="orders.php">My Orders</a>
                            <a href="wishlist.php">Wishlist</a>
                            <div class="dropdown-divider"></div>
                            <a href="logout.php" class="logout-btn">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
