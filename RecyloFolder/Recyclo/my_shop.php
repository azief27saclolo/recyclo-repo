<?php
session_start();
require_once 'config/database.php';
require_once 'includes/seller_check.php';

if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit();
}

$seller = isApprovedSeller($conn, $_SESSION['user_id']);
if (!$seller) {
    header('Location: index.php');
    exit();
}

// Fetch shop statistics
$shop_id = $seller['id'];
$stats = [
    'total_products' => 0,
    'total_orders' => 0,
    'total_earnings' => 0
];

// Get total products
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE seller_id = ? AND status != 'deleted'");
$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result = $stmt->get_result();
$stats['total_products'] = $result->fetch_assoc()['total'];

// Fetch recent products
$products_query = $conn->prepare("
    SELECT p.*, c.category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.category_id 
    WHERE p.seller_id = ? AND p.status != 'deleted'
    ORDER BY p.created_at DESC LIMIT 5
");
$products_query->bind_param("i", $shop_id);
$products_query->execute();
$recent_products = $products_query->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop - <?php echo htmlspecialchars($seller['shop_name']); ?></title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .shop-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .shop-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .shop-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: var(--hoockers-green);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .action-btn {
            background: var(--hoockers-green);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: var(--hoockers-green_80);
            transform: translateY(-2px);
        }

        .recent-products {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-top: 30px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }

        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>

    <div class="shop-container">
        <div class="shop-header">
            <h1><?php echo htmlspecialchars($seller['shop_name']); ?></h1>
            <p><?php echo htmlspecialchars($seller['shop_address']); ?></p>
            
            <div class="shop-stats">
                <div class="stat-card">
                    <i class="bi bi-box-seam"></i>
                    <div class="stat-number"><?php echo $stats['total_products']; ?></div>
                    <div>Products</div>
                </div>
                <div class="stat-card">
                    <i class="bi bi-cart-check"></i>
                    <div class="stat-number"><?php echo $stats['total_orders']; ?></div>
                    <div>Orders</div>
                </div>
                <div class="stat-card">
                    <i class="bi bi-currency-dollar"></i>
                    <div class="stat-number">₱<?php echo number_format($stats['total_earnings'], 2); ?></div>
                    <div>Earnings</div>
                </div>
            </div>
        </div>

        <div class="quick-actions">
            <a href="sellers/products.php" class="action-btn">
                <i class="bi bi-plus-circle"></i> Add Product
            </a>
            <a href="sellers/orders.php" class="action-btn">
                <i class="bi bi-list-check"></i> Manage Orders
            </a>
            <a href="sellers/settings.php" class="action-btn">
                <i class="bi bi-gear"></i> Shop Settings
            </a>
        </div>

        <div class="recent-products">
            <h2>Recent Products</h2>
            <div class="product-grid">
                <?php while($product = $recent_products->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['product_image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p>₱<?php echo number_format($product['price'], 2); ?></p>
                        <p>Stock: <?php echo $product['quantity']; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>
