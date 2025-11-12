<?php
include("../include/conn.php");
include("../include/function.php"); // In case you have session check

session_start();

// Ensure response is JSON
header('Content-Type: application/json');

$response = [];

// Check session
if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$adminId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['new_password']) || empty(trim($_POST['new_password']))) {
        echo json_encode(['status' => false, 'message' => 'Password is required.']);
        exit;
    }

    // Secure password hashing
    $newPassword = trim($_POST['new_password']);
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Use prepared statement
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $adminId);

    if ($stmt->execute()) {
        $response['status'] = true;
        $response['message'] = "Password Updated Successfully.";
    } else {
        $response['status'] = false;
        $response['message'] = "Failed to update password.";
    }

    $stmt->close();
} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>
