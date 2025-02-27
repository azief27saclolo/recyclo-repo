<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Special handling for default admin account
        if ($email === 'admin@recyclo.com' && $password === 'admin12345') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_name'] = 'Admin';
            header('Location: admin/dashboard.php');
            exit();
        }
        
        // If not default admin, check admins table
        $admin_sql = "SELECT * FROM admins WHERE email = ?";
        $stmt = $conn->prepare($admin_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_name'] = $admin['name'];
                header('Location: admin/dashboard.php');
                exit();
            } else {
                $error = "Invalid password for admin account";
            }
        } else {
            // If not admin, check regular users
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
                    $_SESSION['user_type'] = $user['user_type']; // Add this line
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

    if (isset($_POST['signup'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $birthday = $_POST['birthday'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Set default user type as buyer
        $user_type = 'buyer';
        $account_status = 'active';
    
        $stmt = $conn->prepare("INSERT INTO users (username, email, birthday, password, user_type, account_status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $email, $birthday, $password, $user_type, $account_status);
        
        if ($stmt->execute()) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_type'] = $user_type;
            header('Location: index.php');
            exit();
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-body">
    <div class="login-container">
        <div class="forms-container">
            <div class="signin-signup">
                <!-- Login Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="sign-in-form">
                    <img src="./assets/images/mainlogo.png" alt="Recyclo Logo" class="login-logo">
                    <h2 class="title">Sign in to Recyclo</h2>
                    <?php if ($error): ?>
                        <script>
                            Swal.fire({
                                title: 'Error!',
                                text: '<?php echo $error; ?>',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        </script>
                    <?php endif; ?>
                    <div class="input-field">
                        <i class="bi bi-person-fill"></i>
                        <input type="email" name="email" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <input type="submit" name="login" value="Login" class="btn solid" />
                    
                    <p class="social-text">Or Sign in with</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-twitter"></i>
                        </a>
                    </div>
                </form>

                <!-- Sign Up Form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="sign-up-form" id="signupForm">
                    <img src="./assets/images/mainlogo.png" alt="Recyclo Logo" class="login-logo">
                    <h2 class="title">Join Recyclo</h2>
                    <div class="input-field">
                        <i class="bi bi-person-fill"></i>
                        <input type="text" name="username" placeholder="Username" required />
                    </div>
                    <div class="input-field">
                        <i class="bi bi-envelope-fill"></i>
                        <input type="email" name="email" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="bi bi-calendar-fill"></i>
                        <input type="date" name="birthday" placeholder="Birthday" required />
                    </div>
                    <div class="input-field">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" name="password" placeholder="Password" id="password" required />
                    </div>
                    <div class="input-field">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm-password" required />
                    </div>
                    <span class="password-error"></span>
                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I agree to the Terms & Conditions</label>
                    </div>
                    <input type="submit" name="signup" class="btn" value="Sign up" />
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>New to Recyclo?</h3>
                    <p>Join our community of eco-conscious buyers and sellers. Turn waste into opportunity!</p>
                    <button class="btn transparent" id="sign-up-btn">Sign up</button>
                </div>
                <img src="./assets/images/reduce.svg" class="image" alt="" />
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>Already a member?</h3>
                    <p>Sign in to continue your journey in making the world a better place, one recycled item at a time.</p>
                    <button class="btn transparent" id="sign-in-btn">Sign in</button>
                </div>
                <img src="./assets/images/recycle.svg" class="image" alt="" />
            </div>
        </div>
    </div>
    <script src="./assets/js/login.js"></script>
    <script>
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('signup_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Login'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.php';
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    </script>
</body>
</html>
