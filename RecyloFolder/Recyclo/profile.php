<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Recyclo</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .profile-container {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        .sidebar {
            width: 250px;
            background: var(--hoockers-green);
            padding: 20px;
            color: white;
            position: fixed;
            height: 100vh;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 40px;
        }

        .profile-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .profile-header h1 {
            margin: 0;
            color: var(--hoockers-green);
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .profile-info {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .info-group {
            margin-bottom: 25px;
        }

        .info-group label {
            display: block;
            color: #666;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .info-group .value {
            color: #333;
            font-size: 1.1rem;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .edit-btn {
            background: var(--hoockers-green);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-btn:hover {
            background: var(--hoockers-green_80);
        }

        .password-group .value {
            letter-spacing: 2px;
        }

        .password-field {
            position: relative;
        }

        .password-dots {
            letter-spacing: 4px;
            font-weight: bold;
        }

        .change-password-btn {
            background: none;
            border: none;
            color: var(--hoockers-green);
            text-decoration: underline;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 5px 0;
            margin-top: 5px;
            display: block;
        }

        .change-password-btn:hover {
            color: var(--hoockers-green_80);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="profile-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="./assets/images/mainlogo.png" alt="Recyclo Logo">
                <h2>Recyclo</h2>
            </div>
            <nav>
                <a href="index.php" class="menu-item">
                    <i class="bi bi-house-door"></i>
                    <span>Home</span>
                </a>
                <a href="profile.php" class="menu-item active">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
                <a href="privacy_settings.php" class="menu-item">
                    <i class="bi bi-shield-lock"></i>
                    <span>Privacy Settings</span>
                </a>
                <a href="register_seller.php" class="menu-item">
                <i class="bi bi-person-check-fill"></i>
                    <span>Become A Seller</span>
                </a>
                <a href="#" class="menu-item" style="color: #dc3545;" onclick="confirmLogout(event)">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="profile-header">
                <h1>My Profile</h1>
                <p>Manage your personal information and account settings</p>
            </div>

            <div class="profile-info">
                <div class="info-group">
                    <label>Username</label>
                    <div class="value"><?php echo htmlspecialchars($user['username']); ?></div>
                </div>

                <div class="info-group">
                    <label>Email Address</label>
                    <div class="value"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>

                <div class="info-group">
                    <label>Birthday</label>
                    <div class="value"><?php echo date('F j, Y', strtotime($user['birthday'])); ?></div>
                </div>

                <div class="info-group">
                    <label>Password</label>
                    <div class="value password-field">
                        <span class="password-dots">••••••••</span>
                        <button class="change-password-btn" onclick="window.location.href='change_password.php'">
                            Change Password
                        </button>
                    </div>
                </div>

                <button onclick="window.location.href='edit_profile.php'" class="edit-btn">
                    <i class="bi bi-pencil"></i> Edit Profile
                </button>
            </div>
        </div>
    </div>
    <script>
    function confirmLogout(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Logout Confirmation',
            text: "Do you really want to logout?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#517A5B',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'No, stay'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php';
            }
        });
    }
    </script>
</body>
</html>