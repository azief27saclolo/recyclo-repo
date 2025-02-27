<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug session info
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once $_SERVER['DOCUMENT_ROOT'] . '/RecyloFolder/Recyclo/config/database.php';

// Check if user is approved seller
$is_approved_seller = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT s.*, u.user_type 
                           FROM sellers s 
                           JOIN users u ON s.user_id = u.id 
                           WHERE s.user_id = ? AND s.status = 'approved'");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $is_approved_seller = $result->num_rows > 0;
}
?>

<nav class="navbar">
    <div class="nav-branding">
        <a href="/RecyloFolder/Recyclo/index.php">
            <img src="/RecyloFolder/Recyclo/assets/images/mainlogo.png" alt="Recyclo Logo">
        </a>
    </div>
    
    <div class="nav-links">
        <a href="/RecyloFolder/Recyclo/index.php">Home</a>
        <a href="/RecyloFolder/Recyclo/products.php">Products</a>
        <a href="/RecyloFolder/Recyclo/about.php">About</a>
        
        <?php if(isset($_SESSION['user_logged_in'])): ?>
            <div class="profile-dropdown">
                <button type="button" class="profile-btn" id="profileDropdownBtn">
                    <i class="bi bi-person-circle"></i>
                    <span>Profile</span>
                </button>
                <div class="dropdown-menu" id="profileDropdown">
                    <a href="/RecyloFolder/Recyclo/profile.php" class="dropdown-item">
                        <i class="bi bi-person"></i> My Profile
                    </a>
                    
                    <?php if($is_approved_seller): ?>
                        <a href="/RecyloFolder/Recyclo/my_shop.php" class="dropdown-item highlight">
                            <i class="bi bi-shop"></i> My Shop
                        </a>
                        <a href="/RecyloFolder/Recyclo/sellers/products.php" class="dropdown-item">
                            <i class="bi bi-box"></i> Manage Products
                        </a>
                    <?php else: ?>
                        <a href="/RecyloFolder/Recyclo/register_seller.php" class="dropdown-item">
                            <i class="bi bi-person-plus"></i> Become a Seller
                        </a>
                    <?php endif; ?>
                    
                    <div class="dropdown-divider"></div>
                    <a href="/RecyloFolder/Recyclo/logout.php" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        <?php else: ?>
            <a href="/RecyloFolder/Recyclo/login.php">Login</a>
        <?php endif; ?>
    </div>
</nav>

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
    color: #333;
}

.dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    width: 200px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 8px 0;
    z-index: 1000;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background: #f8f9fa;
}

.dropdown-item.highlight {
    color: var(--hoockers-green);
    font-weight: 500;
}

.dropdown-divider {
    height: 1px;
    background: #e9ecef;
    margin: 8px 0;
}

.text-danger {
    color: #dc3545 !important;
}

.text-danger:hover {
    background: #fff5f5;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownBtn = document.getElementById('profileDropdownBtn');
    const dropdown = document.getElementById('profileDropdown');

    if (dropdownBtn && dropdown) {
        dropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !dropdownBtn.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    }
});
</script>

<!-- Debug info (remove in production) -->
<?php if (isset($_SESSION['user_id'])): ?>
<div style="display: none;">
    <p>Debug Info:</p>
    <p>User ID: <?php echo $_SESSION['user_id']; ?></p>
    <p>Is Approved Seller: <?php echo $is_approved_seller ? 'Yes' : 'No'; ?></p>
    <?php
    $debug_query = "SELECT * FROM sellers WHERE user_id = " . $_SESSION['user_id'];
    $debug_result = $conn->query($debug_query);
    if ($debug_result) {
        $seller_data = $debug_result->fetch_assoc();
        echo "<p>Seller Status: " . ($seller_data ? $seller_data['status'] : 'No seller record') . "</p>";
    }
    ?>
</div>
<?php endif; ?>
