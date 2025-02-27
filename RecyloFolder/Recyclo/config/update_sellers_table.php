<?php
require_once 'database.php';

$sql = "ALTER TABLE sellers 
        ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        MODIFY application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP";

if ($conn->query($sql) === TRUE) {
    echo "Sellers table updated successfully";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>
