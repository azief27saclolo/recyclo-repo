<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is an approved seller
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Verify seller status
$user_id = $_SESSION['user_id'];
$seller_check = $conn->prepare("SELECT * FROM sellers WHERE user_id = ? AND status = 'approved'");
$seller_check->bind_param("i", $user_id);
$seller_check->execute();
$seller = $seller_check->get_result()->fetch_assoc();

if (!$seller) {
    header('Location: ../register_seller.php');
    exit();
}

// Fetch categories for dropdown
$categories = $conn->query("SELECT * FROM categories ORDER BY category_name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create uploads directory if it doesn't exist
    $upload_dir = "../uploads/products/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Get form data
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $product_image = "";

    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $file_ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($file_ext, $allowed)) {
            $file_name = time() . '_' . $_FILES['product_image']['name'];
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $file_path)) {
                $product_image = 'uploads/products/' . $file_name;
            }
        }
    }

    // Insert product
    $stmt = $conn->prepare("INSERT INTO products (seller_id, category_id, product_name, description, price, quantity, product_image, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("iissdis", $seller['id'], $category_id, $product_name, $description, $price, $quantity, $product_image);
    
    if ($stmt->execute()) {
        $success_message = "Product added successfully!";
        // Redirect to products list
        header("Location: manage_products.php");
        exit();
    } else {
        $error_message = "Error adding product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Seller Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .product-form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .image-preview {
            max-width: 200px;
            margin-top: 10px;
            display: none;
        }

        .submit-btn {
            background: var(--hoockers-green);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: var(--hoockers-green_80);
            transform: translateY(-2px);
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            color: var(--hoockers-green);
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="seller-dashboard">
        <!-- Include your seller sidebar here -->
        
        <div class="main-content">
            <a href="manage_products.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Products
            </a>

            <div class="product-form-container">
                <h1>Add New Product</h1>

                <form method="POST" enctype="multipart/form-data" id="productForm">
                    <div class="form-group">
                        <label for="product_name">Product Name *</label>
                        <input type="text" id="product_name" name="product_name" required>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php while($category = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $category['category_id']; ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Price (₱) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity *</label>
                        <input type="number" id="quantity" name="quantity" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="product_image">Product Image *</label>
                        <input type="file" id="product_image" name="product_image" accept="image/*" required>
                        <img id="imagePreview" class="image-preview">
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="bi bi-plus-circle"></i> Add Product
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image preview
        document.getElementById('product_image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        });

        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const price = document.getElementById('price').value;
            const quantity = document.getElementById('quantity').value;

            if (price <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Price',
                    text: 'Price must be greater than 0'
                });
                return;
            }

            if (quantity < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Quantity',
                    text: 'Quantity must be at least 1'
                });
                return;
            }

            this.submit();
        });
    </script>
</body>
</html>
