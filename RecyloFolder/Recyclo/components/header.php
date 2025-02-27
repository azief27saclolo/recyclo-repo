<header class="header">
    // ...existing header code...

    <div class="header-actions">
        <?php if (isset($_SESSION['user_logged_in']) || isset($_SESSION['admin_logged_in'])): ?>
            <div class="profile-dropdown">
                <button type="button" class="profile-btn" id="profileDropdownBtn">
                    <ion-icon name="person-circle-outline"></ion-icon>
                    <span><?php echo isset($_SESSION['admin_logged_in']) ? 'Admin' : 'Profile'; ?></span>
                    <ion-icon name="chevron-down-outline"></ion-icon>
                </button>
                <div class="dropdown-content" id="profileDropdown">
                    <?php if (isset($_SESSION['admin_logged_in'])): ?>
                        <a href="/RecyloFolder/Recyclo/admin/dashboard.php">
                            <ion-icon name="speedometer-outline"></ion-icon>
                            <span>Dashboard</span>
                        </a>
                    <?php else: ?>
                        <a href="/RecyloFolder/Recyclo/profile.php">
                            <ion-icon name="person-outline"></ion-icon>
                            <span>My Profile</span>
                        </a>
                        <a href="/RecyloFolder/Recyclo/orders.php">
                            <ion-icon name="cart-outline"></ion-icon>
                            <span>My Orders</span>
                        </a>
                    <?php endif; ?>
                    
                    <a href="/RecyloFolder/Recyclo/settings.php">
                        <ion-icon name="settings-outline"></ion-icon>
                        <span>Settings</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/RecyloFolder/Recyclo/logout.php" class="logout-btn">
                        <ion-icon name="log-out-outline"></ion-icon>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="auth-buttons">
                <a href="/RecyloFolder/Recyclo/login.php" class="login-btn">
                    <ion-icon name="person-outline"></ion-icon>
                    <span>Login</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</header>

<style>
.header-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

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
    border: 1px solid var(--hoockers-green);
    border-radius: 20px;
    cursor: pointer;
    color: var(--hoockers-green);
    transition: all 0.3s ease;
}

.profile-btn:hover {
    background: var(--hoockers-green);
    color: white;
}

.dropdown-content {
    position: absolute;
    top: 120%;
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
    transition: all 0.2s ease;
}

.dropdown-content a:hover {
    background: #f5f5f5;
    color: var(--hoockers-green);
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
    background: #fff5f5 !important;
}

.auth-buttons {
    display: flex;
    gap: 10px;
}

.login-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--hoockers-green);
    color: white;
    text-decoration: none;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.login-btn:hover {
    background: var(--hoockers-green_80);
}
</style>

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
