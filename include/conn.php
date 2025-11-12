<?php
date_default_timezone_set('Asia/Calcutta'); 
$host =  mysql-catalogo.alwaysdata.net;
$username = catalogo_querend;
$password = querendon13102025;
$db = catalogo_querend;
error_reporting(1);
$conn = mysqli_connect($host, $username, $password, $db) or die("Failed To Connect Database! 02H");


$conn1 = new mysqli($host, $username, $password, $db);













// ====================== Secure Fetch Configuration ====================

// Initialize config array
$config = [];

// Prepare the query safely
$stmt = $conn->prepare("SELECT config_key, config_value FROM config");

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch as associative array
    while ($row = $result->fetch_assoc()) {
        $config[$row['config_key']] = $row['config_value'];
    }

    $stmt->close();
} else {
    // Log error or handle it gracefully
    die("Error in preparing statement: " . $conn->error);
}

// Assign config values safely
$website_name       = $config['site_name'] ?? '';
$support_email      = $config['support_email'] ?? '';
$supportPhoneNumber = $config['support_mobile'] ?? '';
$color_background   = $config['color_background'] ?? '#ffffff';
$color_text         = $config['color_text'] ?? '#000000';
$main_logo          = $config['main_logo'] ?? 'default-logo.png';
$favicon_logo       = $config['favicon_logo'] ?? 'favicon.ico';
$extension_file     = $config['extension_file_name'] ?? '';
$external_link      = $config['external_link'] ?? '#';
$trial_days         = $config['trial_days'] ?? 1;

// Style assignment with escaping
$style = "style='background:" . htmlspecialchars($color_background) . ";color:" . htmlspecialchars($color_text) . ";border:none;'";



//Session auto expire if your not active on portal
session_start();
$timeout_duration = 180;//seconds

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Session timeout ho chuka hai
    session_unset();     // Clear all session variables
    session_destroy();   // Destroy the session
    header("Location: login.php?timeout=true"); // Redirect to login
    exit();
}

$_SESSION['last_activity'] = time(); // Reset last activity time






?>


