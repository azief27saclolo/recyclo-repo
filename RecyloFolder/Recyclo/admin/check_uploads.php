<?php
require_once '../config/database.php';

// Query to get all seller documents
$query = "SELECT id, valid_id_url, business_permit_url FROM sellers";
$result = $conn->query($query);

echo "<h2>Checking File Paths:</h2>";
while($row = $result->fetch_assoc()) {
    echo "<hr>";
    echo "Seller ID: " . $row['id'] . "<br>";
    echo "Valid ID Path: " . $row['valid_id_url'] . "<br>";
    echo "File exists: " . (file_exists('../' . $row['valid_id_url']) ? 'Yes' : 'No') . "<br>";
    if(!empty($row['valid_id_url'])) {
        echo "<img src='../{$row['valid_id_url']}' style='max-width: 200px;'><br>";
    }
}
?>
