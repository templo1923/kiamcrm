<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header('Content-Type: application/json');

// Database connection
// Asegúrate de que este archivo carga la conexión $conn1 correctamente
include('../../include/conn.php'); 

// Get raw POST data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Validate input
if (!isset($data['email'], $data['senha'], $data['chromeStoreID'])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields",
        "msg_id"  => "missing_fields"
    ]);
    exit;
}

$email         = $data['email'];
$plain_password = $data['senha']; // Contraseña en texto plano para verificación segura
$chromeStoreID = $data['chromeStoreID'];

// Database check
if ($conn1->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed",
        "msg_id"  => "db_connection_error"
    ]);
    exit;
}

// Get user (usando sentencia preparada para prevenir inyección SQL)
$stmt = $conn1->prepare("SELECT id, password, plan_expiry_date, max_devices FROM users WHERE email = ? AND status= 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode([
        "success" => false,
        "message" => "User not found or inactive",
        "msg_id"  => "user_login_notFund"
    ]);
    exit;
}

$user = $result->fetch_assoc();

// INICIO DE LA CORRECCIÓN DE SEGURIDAD CRÍTICA
// ¡CRÍTICO! Usar password_verify() para verificar el hash Bcrypt almacenado
// Si el hash en la DB es SHA-1 (como estaba antes), esto fallará. 
// Es OBLIGATORIO migrar los hashes de la tabla `users` a Bcrypt.
if (!password_verify($plain_password, $user['password'])) {
    echo json_encode([
        "success" => false,
        "message" => "Incorrect password",
        "msg_id"  => "invalid_password"
    ]);
    exit;
}
// FIN DE LA CORRECCIÓN DE SEGURIDAD CRÍTICA

// Check expiry
$today = date('Y-m-d');
if ($user['plan_expiry_date'] < $today) {
    echo json_encode([
        "success" => false,
        "message" => "Please purchase a plan",
        "msg_id"  => "plan_expired"
    ]);
    exit;
}

// Device check (Lógica actual mantenida)
$deviceCheck = $conn1->prepare("SELECT id FROM user_devices WHERE user_id = ? AND chrome_store_id = ?");
$deviceCheck->bind_param("is", $user['id'], $chromeStoreID);
$deviceCheck->execute();
$deviceResult = $deviceCheck->get_result();

if ($deviceResult->num_rows === 0) {
    $countStmt = $conn1->prepare("SELECT COUNT(*) as total FROM user_devices WHERE user_id = ?");
    $countStmt->bind_param("i", $user['id']);
    $countStmt->execute();
    $countResult = $countStmt->get_result()->fetch_assoc();
    $totalDevices = $countResult['total'];

    if ($totalDevices >= $user['max_devices']) {
        echo json_encode([
            "success" => false,
            "message" => "Device limit reached. Please contact admin.",
            "msg_id"  => "device_limit_reached"
        ]);
        exit;
    }

    // Register new device
    $registerDevice = $conn1->prepare("INSERT INTO user_devices (user_id, chrome_store_id) VALUES (?, ?)");
    $registerDevice->bind_param("is", $user['id'], $chromeStoreID);
    $registerDevice->execute();
    $registerDevice->close();
}

// Generate token
$token = bin2hex(random_bytes(16));

// Save token (usando sentencia preparada)
$updateToken = $conn1->prepare("UPDATE users SET token = ? WHERE id = ?");
$updateToken->bind_param("si", $token, $user['id']);
$updateToken->execute();
$updateToken->close();

// Response
$response = [
    "success" => true,
    "message" => "Login successful",
    "msg_id" => "login_success",
    "token"   => $token,
    "user"    => [
        "id"            => $user['id'],
        "email"         => $email,
        "plan"          => "premium",
        "expiry"        => $user['plan_expiry_date'],
        "chromeStoreID" => $chromeStoreID
    ]
];

$jsonResponse = json_encode($response, JSON_PRETTY_PRINT);

// Send response
echo $jsonResponse;

?>