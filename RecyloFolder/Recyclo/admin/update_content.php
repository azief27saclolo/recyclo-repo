<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['admin_logged_in'])) {
    $content_type = $_POST['content_type'];
    
    switch($content_type) {
        case 'hero':
            // Handle hero slide update or add
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $price = floatval($_POST['price']);
            
            // Handle image upload if provided
            if (isset($_FILES['image_url']) && $_FILES['image_url']['size'] > 0) {
                $target_dir = "../assets/images/";
                $file_extension = pathinfo($_FILES["image_url"]["name"], PATHINFO_EXTENSION);
                $new_filename = "hero_" . ($id ? $id : time()) . "_" . time() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file)) {
                    $image_url = "assets/images/" . $new_filename;
                    if ($id) {
                        $sql = "UPDATE hero_content SET title = ?, description = ?, price = ?, image_url = ? WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssdsi", $title, $description, $price, $image_url, $id);
                    } else {
                        $max_slide_order_sql = "SELECT IFNULL(MAX(slide_order), 0) + 1 FROM hero_content";
                        $max_slide_order_result = $conn->query($max_slide_order_sql);
                        $max_slide_order = $max_slide_order_result->fetch_row()[0];
                        
                        $sql = "INSERT INTO hero_content (title, description, price, image_url, slide_order) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssdsi", $title, $description, $price, $image_url, $max_slide_order);
                    }
                }
            } else {
                if ($id) {
                    $sql = "UPDATE hero_content SET title = ?, description = ?, price = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssdi", $title, $description, $price, $id);
                } else {
                    $max_slide_order_sql = "SELECT IFNULL(MAX(slide_order), 0) + 1 FROM hero_content";
                    $max_slide_order_result = $conn->query($max_slide_order_sql);
                    $max_slide_order = $max_slide_order_result->fetch_row()[0];
                    
                    $sql = "INSERT INTO hero_content (title, description, price, slide_order) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssdi", $title, $description, $price, $max_slide_order);
                }
            }
            
            if ($stmt->execute()) {
                header('Location: contents.php?success=1');
            } else {
                header('Location: contents.php?error=1');
            }
            break;

        case 'collection':
            // Handle collection card update
            $id = $_POST['id'];
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);

            // Handle image upload if provided
            if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                $target_dir = "../assets/images/";
                $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $new_filename = "collection_" . $id . "_" . time() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_url = "assets/images/" . $new_filename;
                    $sql = "UPDATE collection_content SET title = ?, description = ?, image_url = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssi", $title, $description, $image_url, $id);
                }
            } else {
                $sql = "UPDATE collection_content SET title = ?, description = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $title, $description, $id);
            }

            if ($stmt->execute()) {
                header('Location: contents.php?success=1');
            } else {
                header('Location: contents.php?error=1');
            }
            break;

        case 'features':
            // Handle features section update
            $id = $_POST['id'];
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $price = floatval($_POST['price']);
            $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

            $sql = "UPDATE features_content SET title = ?, description = ?, price = ?, image_url = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsi", $title, $description, $price, $image_url, $id);

            if ($stmt->execute()) {
                header('Location: contents.php?success=1');
            } else {
                header('Location: contents.php?error=1');
            }
            break;
    }
}
?>
