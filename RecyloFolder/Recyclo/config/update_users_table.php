<?php
require_once 'database.php';

// Add user_type column to users table
$alter_users = "ALTER TABLE users 
                ADD COLUMN user_type ENUM('buyer', 'seller') DEFAULT 'buyer' AFTER email,
                ADD COLUMN account_status ENUM('active', 'inactive', 'suspended') DEFAULT 'active'";

try {
    if ($conn->query($alter_users)) {
        echo "Successfully added user_type and account_status columns to users table<br>";
    } else {
        throw new Exception("Error adding columns: " . $conn->error);
    }

    // Update existing users to have 'buyer' type
    $update_existing = "UPDATE users SET user_type = 'buyer' WHERE user_type IS NULL";
    if ($conn->query($update_existing)) {
        echo "Successfully updated existing users to buyer type<br>";
    }

    // Update users who are sellers
    $update_sellers = "UPDATE users u 
                      JOIN sellers s ON u.id = s.user_id 
                      SET u.user_type = 'seller' 
                      WHERE s.status = 'approved'";
    if ($conn->query($update_sellers)) {
        echo "Successfully updated seller users<br>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
