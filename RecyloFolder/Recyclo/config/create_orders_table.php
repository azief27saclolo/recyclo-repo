<?php
require_once 'database.php';

// Create orders table
$create_orders = "CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    customer_id INT,
    customer_name VARCHAR(255),
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE SET NULL,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE SET NULL
)";

try {
    if ($conn->query($create_orders)) {
        echo "Orders table created successfully<br>";
    } else {
        throw new Exception("Error creating orders table: " . $conn->error);
    }

    // Create indexes for better performance
    $conn->query("CREATE INDEX idx_product ON orders(product_id)");
    $conn->query("CREATE INDEX idx_customer ON orders(customer_id)");
    $conn->query("CREATE INDEX idx_order_date ON orders(order_date)");

    // Verify table structure
    $result = $conn->query("DESCRIBE orders");
    echo "<h3>Orders Table Structure:</h3>";
    while ($row = $result->fetch_assoc()) {
        print_r($row);
        echo "<br>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
