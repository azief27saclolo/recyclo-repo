<?php
require_once 'config/database.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $birthday = $_POST['birthday'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $response['status'] = 'error';
        $response['message'] = 'Passwords do not match';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, birthday, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $birthday, $hashed_password);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Account created successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error creating account: ' . $conn->error;
        }
    }
}

echo json_encode($response);
?>
