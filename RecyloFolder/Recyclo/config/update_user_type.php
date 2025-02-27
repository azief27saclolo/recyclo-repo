<?php
require_once 'database.php';

try {
    // Add user_type column if it doesn't exist
    $check_column = "SHOW COLUMNS FROM users LIKE 'user_type'";
    $result = $conn->query($check_column);
    
    if ($result->num_rows == 0) {
        $alter_table = "ALTER TABLE users 
                       ADD COLUMN user_type ENUM('buyer', 'seller') DEFAULT 'buyer'";
        
        if ($conn->query($alter_table)) {
            echo "Added user_type column successfully<br>";
            
            // Update existing users to be buyers by default
            $update_users = "UPDATE users SET user_type = 'buyer' WHERE user_type IS NULL";
            if ($conn->query($update_users)) {
                echo "Updated existing users to buyer type<br>";
            }
            
            // Update users who are approved sellers
            $update_sellers = "UPDATE users u 
                             JOIN sellers s ON u.id = s.user_id 
                             SET u.user_type = 'seller' 
                             WHERE s.status = 'approved'";
            if ($conn->query($update_sellers)) {
                echo "Updated approved sellers<br>";
            }
        } else {
            throw new Exception("Error adding user_type column: " . $conn->error);
        }
    } else {
        echo "user_type column already exists<br>";
    }

    // Verify the table structure
    echo "<h3>Current Users Table Structure:</h3>";
    $verify = $conn->query("DESCRIBE users");
    while ($row = $verify->fetch_assoc()) {
        print_r($row);
        echo "<br>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
