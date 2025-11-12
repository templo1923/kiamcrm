<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

include("../include/conn.php");
include("../include/function.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => false, 'message' => 'Username or password cannot be empty.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND status = 'true'");
    if (!$stmt) {
        echo json_encode(['status' => false, 'message' => 'Database query error.']);
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['last_activity'] = time();

            echo json_encode(['status' => true, 'message' => 'Login successful.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid username or password.']);
        }
    } else {
        echo json_encode(['status' => false, 'message' => 'Invalid username or password.']);
    }

    $stmt->close();
}
?>
