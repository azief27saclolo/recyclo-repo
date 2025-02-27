<?php
require_once 'database.php';

// Create sellers table with foreign key to users
$create_sellers = "CREATE TABLE IF NOT EXISTS sellers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    shop_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    shop_address TEXT NOT NULL,
    business_permit_url VARCHAR(255),
    valid_id_url VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user (user_id)
)";

try {
    if ($conn->query($create_sellers)) {
        echo "Sellers table created successfully<br>";
    } else {
        throw new Exception("Error creating sellers table: " . $conn->error);
    }

    // Create trigger to update user_type when seller is approved
    $create_trigger = "CREATE TRIGGER after_seller_approved
                      AFTER UPDATE ON sellers
                      FOR EACH ROW
                      BEGIN
                          IF NEW.status = 'approved' THEN
                              UPDATE users 
                              SET user_type = 'seller'
                              WHERE id = NEW.user_id;
                          END IF;
                      END;";

    if ($conn->query($create_trigger)) {
        echo "Trigger created successfully<br>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
