<?php
include("../include/conn.php");
include("../include/function.php");
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = ($_POST['status'] === 'true') ? 'true' : 'false';

    if ($id > 0) {
        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE `admin` SET `status` = ? WHERE `id` = ?");
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            $response['status'] = true;
            $response['message'] = 'Status updated successfully.';
        } else {
            $response['status'] = false;
            $response['message'] = 'Failed to update status.';
        }

        $stmt->close();
    } else {
        $response['status'] = false;
        $response['message'] = 'Invalid ID.';
    }
} else {
    $response['status'] = false;
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
