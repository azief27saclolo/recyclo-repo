<?php
require_once 'database.php';

// Show table structure
$sql = "DESCRIBE users";
$result = $conn->query($sql);

if ($result) {
    echo "<h3>Users Table Structure:</h3>";
    while($row = $result->fetch_assoc()) {
        echo "Column: " . $row['Field'] . " | Type: " . $row['Type'] . "<br>";
    }
} else {
    echo "Error showing table structure: " . $conn->error;
}

$conn->close();
?>
