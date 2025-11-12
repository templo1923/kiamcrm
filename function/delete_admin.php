<?php
include("../include/conn.php");
include("../include/function.php");
header('Content-Type: application/json');

// Validate & sanitize
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['status' => false, 'message' => 'Invalid or missing reseller ID.']);
    exit;
}

$id = intval($_POST['id']);

// Prepared statement to update reseller
$reseller_stmt = $conn->prepare("UPDATE `admin` SET `deleted` = 'yes' WHERE `id` = ?");
$reseller_stmt->bind_param("i", $id);

if ($reseller_stmt->execute()) {
    // Prepared statement to update associated users
    $users_stmt = $conn->prepare("UPDATE `users` SET `status` = 'false' WHERE `user_id` = ?");
    $users_stmt->bind_param("i", $id);

    if ($users_stmt->execute()) {
        echo json_encode(['status' => true, 'message' => 'Admin marked as deleted and associated resellers/users deactivated.']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Admin marked as deleted, but user status update failed.']);
    }

    $users_stmt->close();
} else {
    echo json_encode(['status' => false, 'message' => 'Failed to mark admin as deleted.']);
}

$reseller_stmt->close();
?>
