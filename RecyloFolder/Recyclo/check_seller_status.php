<?php
session_start();
require_once 'config/database.php';

echo "<h2>Database Tables Check:</h2>";

// Check users table
$users_check = $conn->query("DESCRIBE users");
echo "<h3>Users Table Structure:</h3>";
while($row = $users_check->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}

// Check sellers table
$sellers_check = $conn->query("DESCRIBE sellers");
echo "<h3>Sellers Table Structure:</h3>";
while($row = $sellers_check->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}

// Check current user's status
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    echo "<h3>Current User Status:</h3>";
    echo "User ID: " . $user_id . "<br>";
    
    $user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user = $user_query->get_result()->fetch_assoc();
    
    echo "<h4>User Data:</h4>";
    print_r($user);
    
    $seller_query = $conn->prepare("SELECT * FROM sellers WHERE user_id = ?");
    $seller_query->bind_param("i", $user_id);
    $seller_query->execute();
    $seller = $seller_query->get_result()->fetch_assoc();
    
    echo "<h4>Seller Data:</h4>";
    print_r($seller);
}
?>
