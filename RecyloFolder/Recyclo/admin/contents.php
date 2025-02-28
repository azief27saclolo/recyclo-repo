<?php
session_start();
require_once '../config/database.php';

// Check admin login
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit();
}

// Function to safely fetch content
function fetchContent($conn, $sql) {
    $result = $conn->query($sql);
    if ($result) {
        return $result;
    } else {
        echo "Error fetching content: " . $conn->error . "<br>";
        return false;
    }
}

// Fetch content from database
$hero_content = fetchContent($conn, "SELECT * FROM hero_content ORDER BY slide_order");
$collection_content = fetchContent($conn, "SELECT * FROM collection_content ORDER BY card_order");
$features_content = fetchContent($conn, "SELECT * FROM features_content ORDER BY feature_order");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Content - Recyclo Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

        /* Sidebar styles */
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

        /* Main content styles */
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

        /* Content section styles */
        .content-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .content-tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .tab-button {
            padding: 10px 20px;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            font-size: 1rem;
            color: #666;
        }

        .tab-button.active {
            color: var(--hoockers-green);
            border-bottom-color: var(--hoockers-green);
        }

        .content-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .content-box img {
            max-width: 100px;
            border-radius: 5px;
        }

        .content-box .content-info {
            flex: 1;
            margin-left: 20px;
        }

        .content-box .content-info h3 {
            margin: 0;
            font-size: 1.4rem;
            color: var(--hoockers-green);
        }

        .content-box .content-info p {
            margin: 5px 0;
            color: #666;
            font-size: 1.1rem;
        }

        .content-box .content-actions {
            display: flex;
            gap: 10px;
        }

        .content-box .content-actions button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .content-box .content-actions .edit-btn {
            background: var(--hoockers-green);
            color: white;
        }

        .content-box .content-actions .delete-btn {
            background: #dc3545;
            color: white;
        }

        .content-box .content-actions .edit-btn:hover {
            background: var(--hoockers-green_80);
        }

        .content-box .content-actions .delete-btn:hover {
            background: #c82333;
        }

        .edit-form {
            display: none;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }

        .edit-form .form-group {
            margin-bottom: 15px;
        }

        .edit-form .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .edit-form .form-group input[type="text"],
        .edit-form .form-group input[type="number"],
        .edit-form .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .edit-form .form-group input[type="file"] {
            padding: 10px;
        }

        .edit-form .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .edit-form .action-buttons .update-btn {
            background: var(--hoockers-green);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .edit-form .action-buttons .update-btn:hover {
            background: var(--hoockers-green_80);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
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
                <a href="contents.php" class="nav-link active">
                    <i class="bi bi-file-earmark-text"></i> Contents
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
                <h1 class="welcome-text">Content Management</h1>
            </div>

            <div class="content-tabs">
                <button class="tab-button" data-tab="hero-section">Hero Section</button>
                <button class="tab-button" data-tab="collection-section">Collection Cards</button>
                <button class="tab-button active" data-tab="features-section">Features Section</button>
            </div>

            <!-- Hero Slider Section -->
            <div class="content-section" id="hero-section" style="display: none;">
                <h2 class="section-title">Hero Slides</h2>
                <?php if ($hero_content): ?>
                    <?php while($slide = $hero_content->fetch_assoc()): ?>
                        <div class="content-box">
                            <img src="../<?php echo $slide['image_url']; ?>" alt="Hero Image">
                            <div class="content-info">
                                <h3><?php echo htmlspecialchars($slide['title']); ?></h3>
                                <p><?php echo htmlspecialchars($slide['description']); ?></p>
                                <p>Starting at ₱<?php echo htmlspecialchars(number_format($slide['price'], 2)); ?></p>
                            </div>
                            <div class="content-actions">
                                <button class="edit-btn" onclick="toggleEditForm(<?php echo $slide['id']; ?>)">Edit</button>
                                <button class="delete-btn" onclick="deleteSlide(<?php echo $slide['id']; ?>)">Delete</button>
                            </div>
                        </div>
                        <div class="edit-form" id="edit-form-<?php echo $slide['id']; ?>">
                            <form action="update_content.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="content_type" value="hero">
                                <input type="hidden" name="id" value="<?php echo $slide['id']; ?>">

                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" value="<?php echo htmlspecialchars($slide['title']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" rows="3" required><?php echo htmlspecialchars($slide['description']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($slide['price']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" name="image_url" accept="image/*">
                                    <p>Current Image: <?php echo htmlspecialchars(basename($slide['image_url'])); ?></p>
                                </div>

                                <div class="action-buttons">
                                    <button type="submit" class="update-btn">Update</button>
                                    <button type="button" class="btn btn-secondary" onclick="toggleEditForm(<?php echo $slide['id']; ?>)">Cancel</button>
                                </div>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No hero content found.</p>
                <?php endif; ?>
            </div>

            <!-- Collection Cards Section -->
            <div class="content-section" id="collection-section" style="display: none;">
                <h2 class="section-title">Collection Cards</h2>
                <!-- Add collection card content management here -->
            </div>

            <!-- Features Section -->
            <div class="content-section" id="features-section">
                <h2 class="section-title">Featured Content</h2>
                <!-- Form to add new featured content -->
                <div class="content-editor">
                    <form action="update_content.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="content_type" value="features">
                        
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image_url" accept="image/*" required>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="update-btn">Add</button>
                        </div>
                    </form>
                </div>

                <?php if ($features_content): ?>
                    <?php while($feature = $features_content->fetch_assoc()): ?>
                        <div class="content-box">
                            <img src="../<?php echo $feature['image_url']; ?>" alt="Feature Image">
                            <div class="content-info">
                                <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                                <p><?php echo htmlspecialchars($feature['description']); ?></p>
                                <p>Starting at ₱<?php echo htmlspecialchars(number_format($feature['price'], 2)); ?></p>
                            </div>
                            <div class="content-actions">
                                <button class="edit-btn" onclick="toggleEditForm(<?php echo $feature['id']; ?>)">Edit</button>
                                <button class="delete-btn" onclick="deleteFeature(<?php echo $feature['id']; ?>)">Delete</button>
                            </div>
                        </div>
                        <div class="edit-form" id="edit-form-<?php echo $feature['id']; ?>">
                            <form action="update_content.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="content_type" value="features">
                                <input type="hidden" name="id" value="<?php echo $feature['id']; ?>">

                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" value="<?php echo htmlspecialchars($feature['title']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" rows="3" required><?php echo htmlspecialchars($feature['description']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($feature['price']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" name="image_url" accept="image/*">
                                    <p>Current Image: <?php echo htmlspecialchars(basename($feature['image_url'])); ?></p>
                                </div>

                                <div class="action-buttons">
                                    <button type="submit" class="update-btn">Update</button>
                                    <button type="button" class="btn btn-secondary" onclick="toggleEditForm(<?php echo $feature['id']; ?>)">Cancel</button>
                                </div>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No featured content found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and hide all sections
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.content-section').forEach(section => section.style.display = 'none');
                
                // Add active class to clicked button and show corresponding section
                button.classList.add('active');
                document.getElementById(button.dataset.tab).style.display = 'block';
            });
        });

        // Toggle edit form visibility
        function toggleEditForm(id) {
            const form = document.getElementById(`edit-form-${id}`);
            form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
        }

        // Function to delete slide (implement as needed)
        function deleteSlide(id) {
            if (confirm('Are you sure you want to delete this slide?')) {
                // Implement delete functionality
            }
        }

        // Function to delete feature (implement as needed)
        function deleteFeature(id) {
            if (confirm('Are you sure you want to delete this feature?')) {
                // Implement delete functionality
            }
        }
    </script>
</body>
</html>
