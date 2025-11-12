<?php
include_once("conn.php");
session_start();

// 🛡️ Sanitize and fetch GET parameter
function get($param)
{
    $d = isset($_GET[$param]) ? $_GET[$param] : null;
    $d = filter_var($d, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    return $d;
}

// 🛡️ Sanitize and fetch POST parameter
function post($param)
{
    $d = isset($_POST[$param]) ? $_POST[$param] : null;
    $d = filter_var($d, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    return $d;
}

// ✅ Check session login status
function cekSession()
{
    return isset($_SESSION['login']) && $_SESSION['login'] ? 1 : 0;
}

// 🔁 Redirect user safely
function redirect($target)
{
    header("Location: $target");
    exit;
}

// 🔐 Generate license key in XXXX-XXXX-XXXX-XXXX format
function generate_license($length = 16)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
        if ($i % 4 == 3 && $i < $length - 1) {
            $randomString .= '-';
        }
    }
    return $randomString;
}

// ✅ Check if WhatsApp number exists using prepared statements
function check_number($number)
{
    global $conn;

    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE whatsapp_number = ?");
    $stmt->bind_param("s", $number);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return ($count > 0);
}
?>
