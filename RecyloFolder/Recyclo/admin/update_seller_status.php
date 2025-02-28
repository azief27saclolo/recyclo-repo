<?php
// Turn off error reporting and output buffering
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

session_start();
require_once '../config/database.php';

// Clear any previous output
ob_clean();

// Set JSON header
header('Content-Type: application/json');

try {
    // Check admin login
    if(!isset($_SESSION['admin_logged_in'])) {
        throw new Exception('Unauthorized access');
    }

    // Get POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data) {
        throw new Exception('Invalid JSON data');
    }

    $seller_id = isset($data['seller_id']) ? intval($data['seller_id']) : 0;
    $status = isset($data['status']) ? $data['status'] : '';

    if (!$seller_id || !in_array($status, ['approved', 'rejected'])) {
        throw new Exception('Invalid parameters');
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update seller status
        $stmt = $conn->prepare("UPDATE sellers SET status = ? WHERE id = ?");
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param("si", $status, $seller_id);
        
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }

        // If status is approved, update user type
        if ($status === 'approved') {
            // Get user_id from sellers table
            $user_query = $conn->prepare("SELECT user_id FROM sellers WHERE id = ?");
            $user_query->bind_param("i", $seller_id);
            $user_query->execute();
            $user_result = $user_query->get_result();
            $user_data = $user_result->fetch_assoc();

            if ($user_data) {
                // Update user type to seller
                $update_user = $conn->prepare("UPDATE users SET user_type = 'seller' WHERE id = ?");
                $update_user->bind_param("i", $user_data['user_id']);
                if (!$update_user->execute()) {
                    throw new Exception('Failed to update user type');
                }
            }
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        throw $e;
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close database connections
if (isset($stmt)) $stmt->close();
if (isset($user_query)) $user_query->close();
if (isset($update_user)) $update_user->close();
if (isset($conn)) $conn->close();

// End output buffer and exit
ob_end_flush();
exit;
?>
