<?php
session_start();
require_once '../config/database.php';

// Check admin login
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit();
}

// Get filter parameter
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Prepare base query
$base_query = "SELECT s.*, u.username, u.email as user_email, 
               CONCAT('../', s.valid_id_url) as valid_id_path,
               CONCAT('../', s.business_permit_url) as business_permit_path,
               s.shop_address, s.application_date, s.status
               FROM sellers s 
               JOIN users u ON s.user_id = u.id";

// Apply filters
switch($status_filter) {
    case 'pending':
        $base_query .= " WHERE s.status = 'pending'";
        break;
    case 'approved':
        $base_query .= " WHERE s.status = 'approved'";
        break;
    case 'rejected':
        $base_query .= " WHERE s.status = 'rejected'";
        break;
}

// Add ordering
$base_query .= " ORDER BY s.application_date DESC";

// Execute query
$result = $conn->query($base_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Management - Recyclo Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Base styles */
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

        /* Sidebar styles - matching dashboard.php */
        .sidebar {
            width: 250px;
            background: var(--hoockers-green);
            padding: 20px;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
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

        /* Main content styles - matching users.php */
        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: 250px;
        }

        .dashboard-header {
            margin-bottom: 30px;
        }

        .welcome-text {
            font-size: 1.8rem;
            color: var(--hoockers-green);
            margin-bottom: 20px;
        }

        .status-tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .status-tab {
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            background: white;
            border: 2px solid var(--hoockers-green);
            color: var(--hoockers-green);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .status-tab.active {
            background: var(--hoockers-green);
            color: white;
        }

        .seller-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .seller-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .seller-info h3 {
            margin: 0;
            color: var(--hoockers-green);
            font-size: 1.2rem;
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .seller-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-group {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 500;
            color: #333;
        }

        .document-preview {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }

        .document-preview {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .document-box {
            display: inline-block;
            margin-right: 20px;
        }

        .image-container {
            position: relative;
            margin-top: 10px;
            cursor: pointer;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .image-container img {
            max-width: 200px;
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-overlay i {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .image-container:hover .image-overlay {
            opacity: 1;
        }

        .image-container:hover img {
            transform: scale(1.05);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-approve {
            background: var(--hoockers-green);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-reject {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-approve:hover, .btn-reject:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .document-preview {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }

        .document-box {
            display: inline-block;
        }

        .document-title {
            color: #517A5B;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .image-container {
            position: relative;
            display: inline-block;
            margin: 10px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .image-container img {
            max-width: 300px;
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            color: white;
        }

        .image-overlay i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .image-container:hover .image-overlay {
            opacity: 1;
        }

        .image-container:hover img {
            transform: scale(1.05);
        }

        .upload-date {
            font-size: 0.9rem;
            color: #666;
            margin-top: 5px;
        }

        .no-document {
            color: #dc3545;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Updated Sidebar matching dashboard.php -->
        <div class="sidebar">
            <div class="logo-section">
                <img src="../assets/images/mainlogo.png" alt="Recyclo Logo">
                <h2>Recyclo Admin</h2>
            </div>
            <nav>
                <a href="dashboard.php" class="nav-link">
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
                <a href="seller.php" class="nav-link active">
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

        <!-- Main Content -->
        <div class="main-content">
            <div class="dashboard-header">
                <h1 class="welcome-text">Seller Applications Management</h1>
            </div>

            <div class="status-tabs">
                <button class="status-tab <?php echo $status_filter == 'all' ? 'active' : ''; ?>" 
                        onclick="window.location.href='?status=all'">
                    All Applications
                </button>
                <button class="status-tab <?php echo $status_filter == 'pending' ? 'active' : ''; ?>"
                        onclick="window.location.href='?status=pending'">
                    Pending
                </button>
                <button class="status-tab <?php echo $status_filter == 'approved' ? 'active' : ''; ?>"
                        onclick="window.location.href='?status=approved'">
                    Approved
                </button>
                <button class="status-tab <?php echo $status_filter == 'rejected' ? 'active' : ''; ?>"
                        onclick="window.location.href='?status=rejected'">
                    Rejected
                </button>
            </div>

            <?php if($result->num_rows > 0): ?>
                <?php while($seller = $result->fetch_assoc()): ?>
                    <div class="seller-card">
                        <div class="seller-header">
                            <div class="seller-info">
                                <h3><?php echo htmlspecialchars($seller['shop_name']); ?></h3>
                                <small>Application Date: <?php echo date('F j, Y g:i A', strtotime($seller['application_date'])); ?></small>
                            </div>
                            <span class="status-badge status-<?php echo $seller['status']; ?>">
                                <?php echo ucfirst($seller['status']); ?>
                            </span>
                        </div>

                        <div class="seller-details">
                            <div class="detail-group">
                                <span class="detail-label">Applicant Name</span>
                                <span class="detail-value"><?php echo htmlspecialchars($seller['username']); ?></span>
                            </div>
                            <div class="detail-group">
                                <span class="detail-label">Email</span>
                                <span class="detail-value"><?php echo htmlspecialchars($seller['user_email']); ?></span>
                            </div>
                            <div class="detail-group">
                                <span class="detail-label">Shop Address</span>
                                <span class="detail-value"><?php echo htmlspecialchars($seller['shop_address']); ?></span>
                            </div>
                        </div>

                        <div class="document-preview">
                            <?php if ($seller['business_permit_path']): ?>
                                <div>
                                    <span class="detail-label">Business Permit</span>
                                    <img src="<?php echo htmlspecialchars($seller['business_permit_path']); ?>" 
                                         alt="Business Permit"
                                         onclick="viewDocument(this.src)">
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($seller['valid_id_path']) && file_exists($seller['valid_id_path'])): ?>
                                <div class="document-box">
                                    <h4 class="document-title">Valid ID</h4>
                                    <div class="image-container">
                                        <img src="<?php echo htmlspecialchars($seller['valid_id_path']); ?>" 
                                             alt="Valid ID"
                                             onclick="viewDocument(this.src, 'Valid ID Document')">
                                        <div class="image-overlay">
                                            <i class="bi bi-zoom-in"></i>
                                            <span>Click to view</span>
                                        </div>
                                    </div>
                                    <p class="upload-date">Uploaded: <?php echo date('M j, Y', strtotime($seller['application_date'])); ?></p>
                                </div>
                            <?php else: ?>
                                <div class="document-box">
                                    <h4 class="document-title">Valid ID</h4>
                                    <p class="no-document">Image not found or not uploaded properly</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if($seller['status'] == 'pending'): ?>
                            <div class="action-buttons">
                                <button class="btn-approve" onclick="updateStatus(<?php echo $seller['id']; ?>, 'approved')">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>
                                <button class="btn-reject" onclick="updateStatus(<?php echo $seller['id']; ?>, 'rejected')">
                                    <i class="bi bi-x-circle"></i> Reject
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="seller-card">
                    <p style="text-align: center;">No seller applications found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function viewDocument(src, title) {
            Swal.fire({
                title: title,
                imageUrl: src,
                imageWidth: 800,
                imageHeight: 600,
                imageAlt: title,
                showCloseButton: true,
                showConfirmButton: false,
                width: 850,
                background: '#fff',
                customClass: {
                    image: 'swal-image'
                }
            });
        }

        // Add this CSS for the SweetAlert modal
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                .swal-image {
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }
                .swal2-popup {
                    padding: 20px;
                    border-radius: 15px;
                }
                .swal-container {
                    padding: 20px;
                }
            </style>
        `);

        function updateStatus(sellerId, status) {
            // Show confirmation dialog
            Swal.fire({
                title: 'Confirm Action',
                text: `Are you sure you want to ${status} this seller application?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#517A5B',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false
                    });

                    // Send AJAX request
                    fetch('update_seller_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            seller_id: sellerId,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: `Seller application has been ${status}`,
                                confirmButtonColor: '#517A5B'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Failed to update status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'Something went wrong',
                            confirmButtonColor: '#517A5B'
                        });
                    });
                }
            });
        }
    </script>
</body>
</html>
