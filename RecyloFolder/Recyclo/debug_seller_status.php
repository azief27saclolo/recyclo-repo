<?php
session_start();
require_once 'config/database.php';

echo "<h2>Session Data:</h2>";
print_r($_SESSION);

if (isset($_SESSION['user_id'])) {
    echo "<h2>Seller Status Check:</h2>";
    
    $user_id = $_SESSION['user_id'];
    
    // Check user record
    $user_query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    echo "<h3>User Data:</h3>";
    print_r($user);
    
    // Check seller record
    $seller_query = "SELECT * FROM sellers WHERE user_id = ?";
    $stmt = $conn->prepare($seller_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $seller = $stmt->get_result()->fetch_assoc();
    
    echo "<h3>Seller Data:</h3>";
    print_r($seller);
}
?>
