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

// Handle delete action
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $delete_stmt = $conn->prepare("UPDATE products SET status = 'deleted' WHERE product_id = ? AND seller_id = ?");
    $delete_stmt->bind_param("ii", $product_id, $seller['id']);
    $delete_stmt->execute();
}

// Fetch seller's products
$products_query = $conn->prepare("
    SELECT p.*, c.category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.category_id 
    WHERE p.seller_id = ? AND p.status != 'deleted'
    ORDER BY p.created_at DESC
");
$products_query->bind_param("i", $seller['id']);
$products_query->execute();
$products = $products_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Seller Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--hoockers-green);
        }

        .product-category {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .product-price {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .product-stock {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-edit, .btn-delete {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: var(--hoockers-green);
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .add-product-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--hoockers-green);
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .add-product-btn:hover {
            background: var(--hoockers-green_80);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="seller-dashboard">
        <!-- Include your seller sidebar here -->
        
        <div class="main-content">
            <h1>Manage Products</h1>
            
            <a href="add_product.php" class="add-product-btn">
                <i class="bi bi-plus-circle"></i> Add New Product
            </a>

            <div class="products-grid">
                <?php while($product = $products->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                             class="product-image">
                        
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                            <p class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></p>
                            <p class="product-price">₱<?php echo number_format($product['price'], 2); ?></p>
                            <p class="product-stock">Stock: <?php echo $product['quantity']; ?></p>
                            
                            <div class="action-buttons">
                                <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" 
                                   class="btn-edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button onclick="deleteProduct(<?php echo $product['product_id']; ?>)" 
                                        class="btn-delete">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script>
        function deleteProduct(productId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#517A5B',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="delete_product" value="1">
                        <input type="hidden" name="product_id" value="${productId}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
