<?php
require_once 'database.php';

// Create hero_content table
$sql_hero = "CREATE TABLE IF NOT EXISTS hero_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    slide_order INT NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Create collection_content table
$sql_collection = "CREATE TABLE IF NOT EXISTS collection_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    card_order INT NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Create features_content table
$sql_features = "CREATE TABLE IF NOT EXISTS features_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    feature_order INT NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute table creation
if ($conn->query($sql_hero) === TRUE) {
    echo "hero_content table created successfully<br>";
} else {
    echo "Error creating hero_content table: " . $conn->error . "<br>";
}

if ($conn->query($sql_collection) === TRUE) {
    echo "collection_content table created successfully<br>";
} else {
    echo "Error creating collection_content table: " . $conn->error . "<br>";
}

if ($conn->query($sql_features) === TRUE) {
    echo "features_content table created successfully<br>";
} else {
    echo "Error creating features_content table: " . $conn->error . "<br>";
}

$conn->close();
?>
