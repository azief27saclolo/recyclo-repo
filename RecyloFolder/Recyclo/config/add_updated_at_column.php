<?php
require_once 'database.php';

$sql = "ALTER TABLE sellers 
        ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'";

if ($conn->query($sql) === TRUE) {
    echo "Table updated successfully";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>
