<?php
include("../include/conn.php");
include("../include/function.php");

session_start();
$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wnumber = trim($_POST['wnumber'] ?? '');
    $cname = trim($_POST['cname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $license_key_input = trim($_POST['license_key'] ?? '');
    $expiry_date_input = trim($_POST['expiry_date'] ?? '');
    $admin_id = $_SESSION['id'] ?? 0;
    $haspassword = sha1($license_key_input);

    // Validation
    if (empty($wnumber) || empty($expiry_date_input) || empty($cname)) {
        $response['status'] = false;
        $response['message'] = "Please fill in all required fields.";
        
    }else {
        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO `users` (`user_id`, `client_name`, `mobile_no`, `password`,`plan_expiry_date`,`email`,`status`) VALUES (?, ?, ?, ?, ?, ?,1)");
        $stmt->bind_param("isssss", $admin_id, $cname, $wnumber, $haspassword, $expiry_date_input, $email);

        if ($stmt->execute()) {
            $response['status'] = true;
            $response['message'] = "Password is : $license_key_input";
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to insert data: " . $stmt->error;
        }

        $stmt->close();
    }
} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method.";
}

// Response in JSON format
header('Content-Type: application/json');
echo json_encode($response);
?>
