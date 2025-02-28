<?php
require_once 'database.php';

// Add updated_at column to sellers table if it doesn't exist
$check_column = "SHOW COLUMNS FROM sellers LIKE 'updated_at'";
$result = $conn->query($check_column);

if ($result->num_rows == 0) {
    $sql = "ALTER TABLE sellers 
            ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";

    if ($conn->query($sql) === TRUE) {
        echo "Column 'updated_at' added successfully";
    } else {
        echo "Error adding column: " . $conn->error;
    }
} else {
    echo "Column 'updated_at' already exists";
}

$conn->close();
?>
