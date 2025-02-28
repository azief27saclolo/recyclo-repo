<?php
session_start();
require_once 'config/database.php';
require_once 'includes/seller_check.php';

echo "<h2>Seller Status Debug</h2>";

if (isset($_SESSION['user_id'])) {
    echo "User ID: " . $_SESSION['user_id'] . "<br>";
    
    $seller = isApprovedSeller($conn, $_SESSION['user_id']);
    echo "Seller Check Result: <pre>";
    var_dump($seller);
    echo "</pre>";
    
    // Check sellers table
    $stmt = $conn->prepare("SELECT * FROM sellers WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "Sellers Table Data: <pre>";
    var_dump($result->fetch_assoc());
    echo "</pre>";
} else {
    echo "No user logged in";
}
?>
