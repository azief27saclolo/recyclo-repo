<?php
session_start();
require_once 'config/database.php';

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

// Check if user has already applied
$check_application = $conn->prepare("SELECT * FROM sellers WHERE user_id = ?");
$check_application->bind_param("i", $user_id);
$check_application->execute();
$application = $check_application->get_result()->fetch_assoc();

// Get application status
$application_status = $application ? $application['status'] : null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_name = $_POST['shop_name'];
    $shop_address = $_POST['shop_address'];
    $user_id = $_SESSION['user_id'];
    $email = $_SESSION['user_email'];
    
    // File upload handling
    $upload_dir = "uploads/sellers/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle business permit upload
    $business_permit_url = "";
    if (isset($_FILES['business_permit']) && $_FILES['business_permit']['error'] === 0) {
        $permit_name = "business_permit_" . time() . "_" . $_FILES['business_permit']['name'];
        $permit_path = $upload_dir . $permit_name;
        if (move_uploaded_file($_FILES['business_permit']['tmp_name'], $permit_path)) {
            $business_permit_url = $permit_path;
        }
    }

    // Handle valid ID upload
    $valid_id_url = "";
    if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] === 0) {
        $id_name = "valid_id_" . time() . "_" . $_FILES['valid_id']['name'];
        $id_path = $upload_dir . $id_name;
        if (move_uploaded_file($_FILES['valid_id']['tmp_name'], $id_path)) {
            $valid_id_url = $id_path;
        }
    }

    // Insert into sellers table
    $stmt = $conn->prepare("INSERT INTO sellers (user_id, shop_name, email, shop_address, business_permit_url, valid_id_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $shop_name, $email, $shop_address, $business_permit_url, $valid_id_url);
    
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Your seller application has been submitted successfully.',
                icon: 'success',
                confirmButtonColor: '#517A5B'
            }).then((result) => {
                window.location.href = 'profile.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'There was an error submitting your application.',
                icon: 'error',
                confirmButtonColor: '#517A5B'
            });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Seller - Recyclo</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Base styles matching profile.php */
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

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 40px;
        }

        /* Updated seller form styles to match profile.php */
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

        .requirements {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .requirements h3 {
            color: var(--hoockers-green);
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .requirements ul {
            list-style: none;
            padding: 0;
        }

        .requirements li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #333;
            font-size: 1.1rem;
        }

        .requirements li:before {
            content: "\F26B"; /* Bootstrap Icons check-circle */
            font-family: "Bootstrap Icons";
            margin-right: 10px;
            color: var(--hoockers-green);
        }

        .seller-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #666;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1.1rem;
        }

        .file-input-wrapper {
            margin-top: 10px;
        }

        .file-label {
            display: block;
            padding: 12px;
            background: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-label:hover {
            border-color: var(--hoockers-green);
            background: #f0f0f0;
        }

        .file-label.has-file {
            border-style: solid;
            border-color: var(--hoockers-green);
            color: var(--hoockers-green);
        }

        .submit-btn {
            background: var(--hoockers-green);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background: var(--hoockers-green_80);
        }

        .required {
            color: #dc3545;
            margin-left: 3px;
        }

        .input-hint {
            display: block;
            color: #666;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Matching sidebar styles from profile.php */
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
    </style>
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
                <a href="profile.php" class="menu-item">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
                <a href="privacy_settings.php" class="menu-item">
                    <i class="bi bi-shield-lock"></i>
                    <span>Privacy Settings</span>
                </a>
                <a href="register_seller.php" class="menu-item active">
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
            <?php if (!$application): ?>
                <!-- Show regular application form for new applicants -->
                <div class="profile-header">
                    <h1>Become a Seller</h1>
                    <p>Start your journey as a Recyclo seller and contribute to a sustainable future</p>
                </div>

                <div class="requirements">
                    <h3>Requirements</h3>
                    <ul>
                        <li>Valid Government-issued ID</li>
                        <li>Business Permit (if applicable)</li>
                        <li>Physical store/warehouse address</li>
                        <li>Active email address</li>
                        <li>Mobile number for verification</li>
                    </ul>
                </div>

                <div class="seller-form">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Shop Name</label>
                            <input type="text" name="shop_name" required placeholder="Enter your shop name">
                        </div>

                        <div class="form-group">
                            <label>Shop Address</label>
                            <textarea name="shop_address" rows="3" required placeholder="Enter your complete shop address"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Valid ID <span class="required">*</span></label>
                            <div class="file-input-wrapper">
                                <label class="file-label">
                                    <i class="bi bi-upload"></i> Upload Valid ID
                                    <input type="file" name="valid_id" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" required>
                                </label>
                            </div>
                            <small class="input-hint">Upload a valid government-issued ID (Max: 5MB)</small>
                        </div>

                        <div class="form-group">
                            <label>Business Permit <span class="required">*</span></label>
                            <div class="file-input-wrapper">
                                <label class="file-label">
                                    <i class="bi bi-upload"></i> Upload Business Permit
                                    <input type="file" name="business_permit" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" required>
                                </label>
                            </div>
                            <small class="input-hint">Upload your business permit (Max: 5MB)</small>
                        </div>

                        <button type="submit" class="submit-btn">Submit Application</button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Show application status -->
                <div class="application-status-container">
                    <?php if ($application_status == 'pending'): ?>
                        <div class="status-box pending">
                            <i class="bi bi-hourglass-split status-icon"></i>
                            <h2>Application Under Review</h2>
                            <p>Your seller application is currently being reviewed by our team.</p>
                            <div class="status-details">
                                <p>Application Date: <?php echo date('F j, Y - g:i A', strtotime($application['application_date'])); ?></p>
                                <?php if (isset($application['updated_at']) && $application['updated_at']): ?>
                                    <p>Status Update: <?php echo date('F j, Y - g:i A', strtotime($application['updated_at'])); ?></p>
                                <?php endif; ?>
                                <p>Status: <span class="status-badge <?php echo $application_status; ?>"><?php echo ucfirst($application_status); ?></span></p>
                            </div>
                            <div class="info-message">
                                <i class="bi bi-info-circle"></i>
                                <p>Please wait while we verify your information. This process typically takes 1-2 business days.</p>
                            </div>
                        </div>
                    <?php elseif ($application_status == 'approved'): ?>
                        <div class="status-box approved">
                            <i class="bi bi-check-circle-fill status-icon"></i>
                            <h2>Congratulations!</h2>
                            <p>Your application has been approved. You are now a verified Recyclo seller.</p>
                            <div class="status-details">
                                <p>Application Date: <?php echo date('F j, Y - g:i A', strtotime($application['application_date'])); ?></p>
                                <?php if (isset($application['updated_at']) && $application['updated_at']): ?>
                                    <p>Status Update: <?php echo date('F j, Y - g:i A', strtotime($application['updated_at'])); ?></p>
                                <?php endif; ?>
                                <p>Status: <span class="status-badge approved">Approved</span></p>
                            </div>
                            <a href="seller_dashboard.php" class="btn-primary">
                                <i class="bi bi-shop"></i> Go to Seller Dashboard
                            </a>
                        </div>
                    <?php elseif ($application_status == 'rejected'): ?>
                        <div class="status-box rejected">
                            <i class="bi bi-x-circle-fill status-icon"></i>
                            <h2>Application Not Approved</h2>
                            <p>Unfortunately, your seller application was not approved at this time.</p>
                            <div class="status-details">
                                <p>Application Date: <?php echo date('F j, Y - g:i A', strtotime($application['application_date'])); ?></p>
                                <?php if (isset($application['updated_at']) && $application['updated_at']): ?>
                                    <p>Status Update: <?php echo date('F j, Y - g:i A', strtotime($application['updated_at'])); ?></p>
                                <?php endif; ?>
                                <p>Status: <span class="status-badge rejected">Rejected</span></p>
                            </div>
                            <div class="info-message">
                                <i class="bi bi-info-circle"></i>
                                <p>You can submit a new application after 30 days with updated information.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <style>
                    .application-status-container {
                        max-width: 800px;
                        margin: 40px auto;
                    }

                    .status-box {
                        background: white;
                        padding: 40px;
                        border-radius: 15px;
                        text-align: center;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }

                    .status-box.pending {
                        border-top: 5px solid #ffc107;
                    }

                    .status-box.approved {
                        border-top: 5px solid #28a745;
                    }

                    .status-box.rejected {
                        border-top: 5px solid #dc3545;
                    }

                    .status-icon {
                        font-size: 4rem;
                        margin-bottom: 20px;
                    }

                    .pending .status-icon {
                        color: #ffc107;
                    }

                    .approved .status-icon {
                        color: #28a745;
                    }

                    .rejected .status-icon {
                        color: #dc3545;
                    }

                    .status-details {
                        margin: 25px 0;
                        padding: 15px;
                        background: #f8f9fa;
                        border-radius: 10px;
                    }

                    .status-badge {
                        padding: 5px 15px;
                        border-radius: 20px;
                        font-weight: 500;
                    }

                    .status-badge.pending {
                        background: #fff3cd;
                        color: #856404;
                    }

                    .status-badge.approved {
                        background: #d4edda;
                        color: #155724;
                    }

                    .status-badge.rejected {
                        background: #f8d7da;
                        color: #721c24;
                    }

                    .info-message {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding: 15px;
                        background: #e9ecef;
                        border-radius: 10px;
                        margin-top: 20px;
                    }

                    .info-message i {
                        color: #517A5B;
                        font-size: 1.2rem;
                    }

                    .btn-primary {
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        background: #517A5B;
                        color: white;
                        padding: 12px 25px;
                        border-radius: 8px;
                        text-decoration: none;
                        margin-top: 20px;
                        transition: all 0.3s ease;
                    }

                    .btn-primary:hover {
                        background: #3c5c44;
                        transform: translateY(-2px);
                    }
                </style>
            <?php endif; ?>
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

    // Show filename when file is selected
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            if (e.target.files[0]) {
                let fileName = e.target.files[0].name;
                let fileSize = e.target.files[0].size / (1024 * 1024); // Convert to MB
                let label = e.target.parentElement;
                
                if (fileSize > 5) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'File size must be less than 5MB',
                        icon: 'error',
                        confirmButtonColor: '#517A5B'
                    });
                    e.target.value = ''; // Clear the input
                    return;
                }

                label.innerHTML = `<i class="bi bi-file-earmark-check"></i> ${fileName}`;
                label.classList.add('has-file');
            }
        });
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        let validId = document.querySelector('input[name="valid_id"]');
        let businessPermit = document.querySelector('input[name="business_permit"]');

        if (!validId.files[0] || !businessPermit.files[0]) {
            e.preventDefault();
            Swal.fire({
                title: 'Error!',
                text: 'Both Valid ID and Business Permit are required',
                icon: 'error',
                confirmButtonColor: '#517A5B'
            });
        }
    });
    </script>
</body>
</html>
