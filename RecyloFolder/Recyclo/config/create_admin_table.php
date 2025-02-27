<?php
require_once 'database.php';

// Drop existing admins table
$conn->query("DROP TABLE IF EXISTS admins");

// Create admins table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    // Create default admin account
    $default_email = "admin@recyclo.com";
    $default_password = "admin12345";
    $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
    $default_name = "Admin";
    
    $sql = "INSERT INTO admins (email, password, name) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $default_email, $hashed_password, $default_name);
    
    if ($stmt->execute()) {
        echo "Admin table created and default admin account created successfully.<br>";
        echo "Email: admin@recyclo.com<br>";
        echo "Password: admin12345";
    } else {
        echo "Error creating default admin: " . $conn->error;
    }
} else {
    echo "Error creating admin table: " . $conn->error;
}

$conn->close();
?>

