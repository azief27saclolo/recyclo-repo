<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug output
echo "<!-- Session contents: ";
print_r($_SESSION);
echo " -->";

// Check if user is logged in as admin
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit();
}

require_once '../config/database.php';

// Fetch admin details
$admin_email = $_SESSION['admin_email'];
$sql = "SELECT * FROM admins WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();

// Get stats (example queries - modify according to your actual tables)
$orders_query = "SELECT COUNT(*) as total FROM orders";
$users_query = "SELECT COUNT(*) as total FROM users";
$shops_query = "SELECT COUNT(*) as total FROM shops";

// Get stats with error handling
$orders_count = 0;
$users_count = 0;
$shops_count = 0;

// Check if tables exist before querying
$table_check = $conn->query("SHOW TABLES LIKE 'orders'");
if($table_check->num_rows > 0) {
    $result = $conn->query($orders_query);
    if($result) {
        $orders_count = $result->fetch_assoc()['total'];
    }
}

$table_check = $conn->query("SHOW TABLES LIKE 'users'");
if($table_check->num_rows > 0) {
    $result = $conn->query($users_query);
    if($result) {
        $users_count = $result->fetch_assoc()['total'];
    }
}

$table_check = $conn->query("SHOW TABLES LIKE 'shops'");
if($table_check->num_rows > 0) {
    $result = $conn->query($shops_query);
    if($result) {
        $shops_count = $result->fetch_assoc()['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recyclo Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --hoockers-green: #517A5B;
            --hoockers-green_80: #517A5Bcc;
        }
        
        body {
            font-family: 'Urbanist', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: var(--hoockers-green);
            padding: 20px;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            margin: 0;
            color: var(--hoockers-green);
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .recent-orders {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }

        .logo-section {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo-section img {
            width: 45px;
            height: 45px;
            margin-right: 15px;
        }

        .logo-section h2 {
            font-size: 1.5rem;
            margin: 0;
            font-weight: 600;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table th {
            background-color: #f8f9fa;
            color: var(--hoockers-green);
            font-weight: 600;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge.completed {
            background-color: #d4edda;
            color: #155724;
        }

        .btn-primary {
            background-color: var(--hoockers-green);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--hoockers-green_80);
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .welcome-text {
            font-size: 1.8rem;
            color: var(--hoockers-green);
            margin: 0;
        }

        .date-text {
            color: #666;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="logo-section">
                <img src="../assets/images/mainlogo.png" alt="Recyclo Logo">
                <h2>Recyclo Admin</h2>
            </div>
            <nav>
                <a href="dashboard.php" class="nav-link active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="orders.php" class="nav-link">
                    <i class="bi bi-cart"></i> Orders
                </a>
                <a href="products.php" class="nav-link">
                    <i class="bi bi-box"></i> Products
                </a>
                <a href="users.php" class="nav-link">
                    <i class="bi bi-people"></i> Users
                </a>
                <a href="shops.php" class="nav-link">
                    <i class="bi bi-shop"></i> Shops
                </a>
                <a href="contents.php" class="nav-link">
                    <i class="bi bi-file-earmark-text"></i> Contents
                </a>
                <a href="seller.php" class="nav-link">
                    <i class="bi bi-graph-up"></i> Sellers
                </a>
                <a href="settings.php" class="nav-link">
                    <i class="bi bi-gear"></i> Settings
                </a>
                <a href="logout.php" class="nav-link" onclick="return confirm('Are you sure you want to logout?')">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </nav>
        </div>

        <div class="main-content">
            <div class="dashboard-header">
                <div>
                    <h1 class="welcome-text">Welcome back, <?php echo htmlspecialchars($admin['name']); ?>!</h1>
                    <p class="date-text"><?php echo date('l, F j, Y'); ?></p>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><i class="bi bi-cart-check"></i> Total Orders</h3>
                    <p class="stat-number"><?php echo $orders_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3><i class="bi bi-people"></i> Active Users</h3>
                    <p class="stat-number"><?php echo $users_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3><i class="bi bi-shop"></i> Total Shops</h3>
                    <p class="stat-number"><?php echo $shops_count; ?></p>
                </div>
            </div>

            <div class="recent-orders">
                <h2><i class="bi bi-clock-history"></i> Recent Orders</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#12345</td>
                            <td>Ronnald</td>
                            <td>Wood Scraps</td>
                            <td>₱450.00</td>
                            <td><span class="badge pending">Pending</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm">View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#12344</td>
                            <td>Panganiban Baki</td>
                            <td>Metal Cans</td>
                            <td>₱280.00</td>
                            <td><span class="badge completed">Completed</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm">View</button>
                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];
        
        // Check if the user exists in the users table
        $user_sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($user_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                header('Location: index.php');
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "Account not found";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup - Recyclo</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="login-body">
    <div class="login-container">
        <form action="login.php" method="post" class="login-form">
            <h2 class="title">Login</h2>
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="input-field">
                <i class="bi bi-envelope-fill"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-field">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <input type="submit" name="login" class="btn" value="Login">
        </form>
        <form action="login.php" method="post" class="sign-up-form">
            <h2 class="title">Create Account</h2>
            <div class="input-field">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-field">
                <i class="bi bi-envelope-fill"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-field">
                <i class="bi bi-calendar-fill"></i>
                <input type="date" name="birthday" placeholder="Birthday" required>
            </div>
            <div class="input-field">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-field">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="terms-checkbox">
                <input type="checkbox" id="terms" required>
                <label for="terms">I agree to the <a href="#" style="color: var(--hoockers-green);">Terms and Conditions</a></label>
            </div>
            <input type="submit" name="signup" class="btn" value="Sign Up">
        </form>
    </div>
</body>
</html>
