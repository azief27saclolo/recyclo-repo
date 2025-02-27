<?php
require_once 'database.php';

// Update users table
$sql_users = "ALTER TABLE users 
              MODIFY COLUMN user_type ENUM('buyer', 'seller') DEFAULT 'buyer',
              ADD COLUMN IF NOT EXISTS account_status ENUM('active', 'inactive') DEFAULT 'active'";

// Update sellers table
$sql_sellers = "ALTER TABLE sellers 
                MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
                ON UPDATE CURRENT_TIMESTAMP";

try {
    if ($conn->query($sql_users)) {
        echo "Users table updated successfully<br>";
    }
    if ($conn->query($sql_sellers)) {
        echo "Sellers table updated successfully<br>";
    }
    
    // Add trigger for seller approval
    $trigger_sql = "CREATE TRIGGER IF NOT EXISTS after_seller_approval
                   AFTER UPDATE ON sellers
                   FOR EACH ROW
                   BEGIN
                       IF NEW.status = 'approved' THEN
                           UPDATE users SET user_type = 'seller'
                           WHERE id = NEW.user_id;
                       END IF;
                   END;";
                   
    if ($conn->query($trigger_sql)) {
        echo "Trigger created successfully";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Print current tables structure
echo "<h3>Current Database Structure:</h3>";
$tables = ['users', 'sellers'];
foreach ($tables as $table) {
    $result = $conn->query("DESCRIBE $table");
    echo "<h4>$table table:</h4>";
    while ($row = $result->fetch_assoc()) {
        print_r($row);
        echo "<br>";
    }
}
?>
