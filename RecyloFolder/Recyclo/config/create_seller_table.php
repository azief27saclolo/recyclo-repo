<?php
require_once 'database.php';

// Create sellers table
$sql = "CREATE TABLE IF NOT EXISTS sellers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    shop_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    shop_address TEXT NOT NULL,
    business_permit_url VARCHAR(255),
    valid_id_url VARCHAR(255) NOT NULL,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    remarks TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY unique_shop_name (shop_name),
    UNIQUE KEY unique_user_id (user_id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Sellers table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
