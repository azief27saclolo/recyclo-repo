<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in and is an approved seller
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verify seller status
$user_id = $_SESSION['user_id'];
$seller_check = $conn->prepare("SELECT s.*, u.email FROM sellers s 
                               JOIN users u ON s.user_id = u.id 
                               WHERE s.user_id = ? AND s.status = 'approved'");
$seller_check->bind_param("i", $user_id);
$seller_check->execute();
$seller = $seller_check->get_result()->fetch_assoc();

if (!$seller) {
    header('Location: register_seller.php');
    exit();
}

// Get seller statistics
$stats = array(
    'total_products' => 0,
    'total_orders' => 0,
    'total_earnings' => 0,
    'pending_orders' => 0
);

// Get total products
$products_query = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE seller_id = ? AND status != 'deleted'");
$products_query->bind_param("i", $seller['id']);
$products_query->execute();
$stats['total_products'] = $products_query->get_result()->fetch_assoc()['total'];

// Get recent orders with error handling
try {
    $recent_orders = $conn->prepare("
        SELECT 
            o.order_id,
            o.customer_name,
            o.quantity,
            o.total_price,
            o.status,
            o.order_date,
            p.product_name,
            p.price
        FROM orders o 
        JOIN products p ON o.product_id = p.product_id 
        WHERE p.seller_id = ? 
        AND o.status != 'cancelled'
        ORDER BY o.order_date DESC 
        LIMIT 5
    ");

    if (!$recent_orders) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $recent_orders->bind_param("i", $seller['id']);
    $recent_orders->execute();
    $orders = $recent_orders->get_result();

} catch (Exception $e) {
    // Log error and show empty orders
    error_log("Error fetching orders: " . $e->getMessage());
    $orders = new class {
        public $num_rows = 0;
        public function fetch_assoc() { return null; }
    };
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - <?php echo htmlspecialchars($seller['shop_name']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .seller-dashboard {
            display: flex;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .dashboard-sidebar {
            width: 250px;
            background: var(--hoockers-green);
            padding: 20px;
            color: white;
            position: fixed;
            height: 100vh;
        }

        .dashboard-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .action-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .action-card:hover {
            transform: translateY(-5px);
        }

        .recent-orders {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-table th, .order-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
        }

        .nav-link i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="seller-dashboard">
        <!-- Sidebar -->
        <div class="dashboard-sidebar">
            <div class="shop-info" style="margin-bottom: 30px;">
                <h2><?php echo htmlspecialchars($seller['shop_name']); ?></h2>
                <p>Seller Dashboard</p>
            </div>

            <nav>
                <a href="seller_dashboard.php" class="nav-link active">
                    <i class="bi bi-grid"></i> Dashboard
                </a>
                <a href="seller/products.php" class="nav-link">
                    <i class="bi bi-box"></i> My Products
                </a>
                <a href="seller/orders.php" class="nav-link">
                    <i class="bi bi-cart"></i> Orders
                </a>
                <a href="seller/earnings.php" class="nav-link">
                    <i class="bi bi-wallet2"></i> Earnings
                </a>
                <a href="seller/settings.php" class="nav-link">
                    <i class="bi bi-gear"></i> Settings
                </a>
                <a href="logout.php" class="nav-link">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content">
            <h1>Welcome, <?php echo htmlspecialchars($seller['shop_name']); ?>!</h1>
            
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><i class="bi bi-box"></i> Total Products</h3>
                    <p class="stat-number"><?php echo $stats['total_products']; ?></p>
                </div>
                <div class="stat-card">
                    <h3><i class="bi bi-cart"></i> Total Orders</h3>
                    <p class="stat-number"><?php echo $stats['total_orders']; ?></p>
                </div>
                <div class="stat-card">
                    <h3><i class="bi bi-currency-dollar"></i> Total Earnings</h3>
                    <p class="stat-number">₱<?php echo number_format($stats['total_earnings'], 2); ?></p>
                </div>
            </div>

            <!-- Quick Actions -->
            <h2>Quick Actions</h2>
            <div class="quick-actions">
                <a href="seller/add_product.php" class="action-card">
                    <i class="bi bi-plus-circle"></i>
                    <h3>Add New Product</h3>
                </a>
                <a href="seller/orders.php?status=pending" class="action-card">
                    <i class="bi bi-clock"></i>
                    <h3>View Pending Orders</h3>
                    <p><?php echo $stats['pending_orders']; ?> pending</p>
                </a>
            </div>

            <!-- Recent Orders -->
            <div class="recent-orders">
                <h2>Recent Orders</h2>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($orders->num_rows > 0): ?>
                            <?php while($order = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $order['order_id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                    <td>₱<?php echo number_format($order['price'], 2); ?></td>
                                    <td><?php echo ucfirst($order['status']); ?></td>
                                    <td>
                                        <a href="seller/order_details.php?id=<?php echo $order['order_id']; ?>" 
                                           class="btn btn-primary">View</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No recent orders</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
