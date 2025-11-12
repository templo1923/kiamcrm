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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];$passwordNew = $_POST['password'];
    if (!isset($_POST['id']) || empty(trim($_POST['password']))) {
        echo json_encode(['status' => false, 'message' => 'Password is required.']);
        exit;
    }

    // Secure password hashing
    $hashedPassword = password_hash($passwordNew, PASSWORD_BCRYPT);

    // Use prepared statement
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $id);

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
