<?php
function isApprovedSeller($conn, $user_id) {
    try {
        $stmt = $conn->prepare("SELECT * FROM sellers WHERE user_id = ? AND status = 'approved'");
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }
        
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }
        
        $result = $stmt->get_result();
        $seller = $result->fetch_assoc();
        
        $stmt->close();
        return $seller;
    } catch (Exception $e) {
        error_log("Error checking seller status: " . $e->getMessage());
        return false;
    }
}
?>
