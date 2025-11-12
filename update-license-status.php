<?php
include("include/conn.php");
header('Content-Type: application/json');

if (isset($_POST['id']) && isset($_POST['status'])) {
    // Sanitize inputs
    $id = intval($_POST['id']);
    $status = ($_POST['status']) ? 1 : 0;

    // Use prepared statements for security
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>
