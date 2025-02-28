<?php
require_once 'database.php';

$email = "admin@recyclo.com";
$sql = "SELECT * FROM admins WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "Admin found:<br>";
    echo "Email: " . $admin['email'] . "<br>";
    echo "Name: " . $admin['name'] . "<br>";
    
    // Test password verification
    $test_password = "admin12345";
    if (password_verify($test_password, $admin['password'])) {
        echo "Password verification successful";
    } else {
        echo "Password verification failed";
        echo "<br>Stored hash: " . $admin['password'];
    }
} else {
    echo "Admin account not found";
}

$conn->close();
?>
