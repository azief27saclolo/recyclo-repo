<?php
require_once 'database.php';

// Create categories table
$create_categories = "CREATE TABLE IF NOT EXISTS categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

// Create products table with foreign key relationships
$create_products = "CREATE TABLE IF NOT EXISTS products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    seller_id INT,
    product_name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    product_image VARCHAR(255),
    status ENUM('active', 'inactive', 'deleted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    FOREIGN KEY (seller_id) REFERENCES sellers(id) ON DELETE CASCADE
)";

// Insert default categories
$insert_categories = "INSERT INTO categories (category_name) VALUES 
    ('Plastic'),
    ('Metal'),
    ('Paper'),
    ('Glass'),
    ('Wood')";

try {
    // Create tables
    if ($conn->query($create_categories)) {
        echo "Categories table created successfully<br>";
        
        // Check if categories are already inserted
        $check_categories = $conn->query("SELECT * FROM categories");
        if ($check_categories->num_rows == 0) {
            // Insert default categories only if table is empty
            if ($conn->query($insert_categories)) {
                echo "Default categories inserted successfully<br>";
            }
        }
    }

    if ($conn->query($create_products)) {
        echo "Products table created successfully<br>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
