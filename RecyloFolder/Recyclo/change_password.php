<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get user's current password from database
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify current password
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password in database
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_password, $user_id);
            
            if ($update_stmt->execute()) {
                $success = "Password updated successfully!";
            } else {
                $error = "Error updating password.";
            }
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Recyclo</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .password-form {
            width: 100%;
            max-width: 800px;
            margin: 40px auto;
            padding: 50px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-header h2 {
            color: var(--hoockers-green);
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .form-header p {
            color: #666;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 30px;
        }

        .form-group label {
            display: block;
            margin-bottom: 12px;
            color: #333;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1.1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--hoockers-green);
            outline: none;
            box-shadow: 0 0 0 4px rgba(81, 122, 91, 0.1);
        }

        .submit-btn {
            background: var(--hoockers-green);
            color: white;
            border: none;
            padding: 18px 25px;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            font-size: 1.2rem;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background: var(--hoockers-green_80);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(81, 122, 91, 0.3);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--hoockers-green);
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 30px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            transform: translateX(-5px);
        }

        .password-requirements {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin: 30px 0;
        }

        .password-requirements h3 {
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            margin-bottom: 10px;
        }

        .password-requirements i {
            color: var(--hoockers-green);
        }
    </style>
</head>
<body>
    <div class="password-form">
        <div class="form-header">
            <h2>Change Password</h2>
            <p>Please enter your current password and choose a new secure password</p>
        </div>
        
        <form method="POST" action="" id="passwordForm">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" placeholder="Enter your current password" required>
            </div>
            
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" placeholder="Enter your new password" required>
            </div>
            
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm your new password" required>
            </div>

            <div class="password-requirements">
                <h3>Password Requirements:</h3>
                <ul>
                    <li><i class="bi bi-check-circle"></i> At least 8 characters long</li>
                    <li><i class="bi bi-check-circle"></i> Include at least one uppercase letter</li>
                    <li><i class="bi bi-check-circle"></i> Include at least one number</li>
                    <li><i class="bi bi-check-circle"></i> Include at least one special character</li>
                </ul>
            </div>
            
            <button type="submit" class="submit-btn">Update Password</button>
        </form>
        
        <a href="profile.php" class="back-link">
            <i class="bi bi-arrow-left"></i>
            Back to Profile
        </a>
    </div>

    <?php if ($error || $success): ?>
    <script>
        Swal.fire({
            title: '<?php echo $error ? 'Error!' : 'Success!'; ?>',
            text: '<?php echo $error ? $error : $success; ?>',
            icon: '<?php echo $error ? 'error' : 'success'; ?>',
            confirmButtonColor: '#517A5B'
        }).then((result) => {
            if (!<?php echo $error ? 'true' : 'false'; ?> && result.isConfirmed) {
                window.location.href = 'profile.php';
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
