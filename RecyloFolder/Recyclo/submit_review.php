<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $seller_id = (int)$_POST['seller_id'];
    $rating = (int)$_POST['rating'];
    $review = trim($_POST['review']);
    $customer_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO shop_reviews (seller_id, customer_id, rating, review) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $seller_id, $customer_id, $rating, $review);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit review']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
