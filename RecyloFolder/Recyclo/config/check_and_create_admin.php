<?php
require_once 'database.php';

// Check if admins table exists
$table_check = $conn->query("SHOW TABLES LIKE 'admins'");
if ($table_check->num_rows == 0) {
    // Create admins table
    $sql = "CREATE TABLE IF NOT EXISTS admins (
        id INT PRIMARY KEY AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql)) {
        // Insert default admin
        $default_email = "admin@recyclo.com";
        $default_password = "admin12345";
        $default_name = "Admin";
        
        $sql = "INSERT INTO admins (email, password, name) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $default_email, $default_password, $default_name);
        $stmt->execute();
        
        echo "Admin table and default account created successfully.";
    }
}

// Verify admin account exists
$sql = "SELECT * FROM admins WHERE email = 'admin@recyclo.com'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    // Insert default admin if not exists
    $default_email = "admin@recyclo.com";
    $default_password = "admin12345";
    $default_name = "Admin";
    
    $sql = "INSERT INTO admins (email, password, name) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $default_email, $default_password, $default_name);
    $stmt->execute();
    
    echo "Default admin account created.";
}

$conn->close();
?>
