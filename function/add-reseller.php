<?php
include("../include/conn.php");
include("../include/function.php");
header('Content-Type: application/json');

// Session check
session_start();
if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized request.']);
    exit;
}

$admin_id = $_SESSION['id'];

// Securely fetch and sanitize POST data
function clean_input($param) {
    return isset($_POST[$param]) ? trim(htmlspecialchars($_POST[$param])) : '';
}

$cname        = clean_input('cname');
$wnumber      = clean_input('wnumber');
$username     = clean_input('username');
$password_raw = clean_input('password');
$user_type    = clean_input('user_type');
$status       = clean_input('status');
$start_date   = clean_input('start_date');
$expired_date = clean_input('expired_date');

// Validation
if (!$cname || !$wnumber || !$username || !$password_raw || !$user_type || !$status || !$start_date || !$expired_date) {
    echo json_encode(['status' => false, 'message' => 'All fields are required.']);
    exit;
}

// Use strong password hashing (bcrypt)
$password_hashed = password_hash($password_raw, PASSWORD_BCRYPT);

// Check if username already exists
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

// Insert reseller using prepared statement
$stmt = $conn->prepare("INSERT INTO admin (`username`, `name`, `contact_number`, `password`, `user_type`, `status`, `start_date`, `expired_date`, `admin_id`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssi", $username, $cname, $wnumber, $password_hashed, $user_type, $status, $start_date, $expired_date, $admin_id);

if ($stmt->execute()) {
    echo json_encode(['status' => true, 'message' => 'Reseller added successfully.']);
} else {
    echo json_encode(['status' => false, 'message' => 'Database error while adding reseller.']);
}

$stmt->close();
$conn->close();
?>
