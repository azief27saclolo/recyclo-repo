<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'cart.php?id=' . $product_id;
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $action = $_POST['action'];
    
    // Add to cart
    if ($action === 'cart') {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE quantity = quantity + ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $_SESSION['user_id'], $product_id, $quantity, $quantity);
        
        if ($stmt->execute()) {
            header('Location: cart_view.php?success=1');
        } else {
            header('Location: cart.php?id=' . $product_id . '&error=1');
        }
    }
    
    // Direct checkout
    else if ($action === 'checkout') {
        $_SESSION['checkout_items'] = [
            ['product_id' => $product_id, 'quantity' => $quantity]
        ];
        header('Location: checkout.php');
    }
    
    exit();
}
