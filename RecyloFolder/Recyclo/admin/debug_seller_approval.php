<?php
require_once '../config/database.php';

echo "<h2>Seller Approval Debug</h2>";

// Check sellers table structure
$sellers_check = $conn->query("DESCRIBE sellers");
echo "<h3>Sellers Table Structure:</h3>";
while($row = $sellers_check->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}

// Check users table structure
$users_check = $conn->query("DESCRIBE users");
echo "<h3>Users Table Structure:</h3>";
while($row = $users_check->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}

// Check pending applications
$pending_check = $conn->query("SELECT s.*, u.email, u.user_type 
                              FROM sellers s 
                              JOIN users u ON s.user_id = u.id 
                              WHERE s.status = 'pending'");
echo "<h3>Pending Applications:</h3>";
while($row = $pending_check->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}

// Check approved sellers
$approved_check = $conn->query("SELECT s.*, u.email, u.user_type 
                               FROM sellers s 
                               JOIN users u ON s.user_id = u.id 
                               WHERE s.status = 'approved'");
echo "<h3>Approved Sellers:</h3>";
while($row = $approved_check->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}
?>
