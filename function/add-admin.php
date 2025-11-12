<?php
include("../include/conn.php");
include("../include/function.php");
header('Content-Type: application/json');

// Check if user is logged in
if (!cekSession()) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Validate & sanitize inputs
$cname       = trim(post('cname'));
$wnumber     = trim(post('wnumber'));
$username    = trim(post('username'));
$password = password_hash(trim(post('password')), PASSWORD_BCRYPT);
$user_type   = trim(post('user_type'));
$status      = trim(post('status'));
$start_date  = trim(post('start_date'));
$expired_date = trim(post('expired_date'));
$admin_id    = $_SESSION['id'] ?? null;

if (!$cname || !$wnumber || !$username || !$password || !$user_type || !$status || !$start_date || !$expired_date || !$admin_id) {
    echo json_encode(['status' => false, 'message' => 'All fields are required.']);
    exit;
}


// Check if username already exists using prepared statement
$stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => false, 'message' => 'Username already exists.']);
    $stmt->close();
    exit;
}
$stmt->close();

// Insert new admin using prepared statement
$stmt = $conn->prepare("INSERT INTO admin (`username`, `name`, `contact_number`, `password`, `user_type`, `status`, `start_date`, `expired_date`, `admin_id`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $username, $cname, $wnumber, $password, $user_type, $status, $start_date, $expired_date, $admin_id);

if ($stmt->execute()) {
    echo json_encode(['status' => true, 'message' => 'Admin added successfully.']);
} else {
    echo json_encode(['status' => false, 'message' => 'Database error.']);
}
$stmt->close();
?>
