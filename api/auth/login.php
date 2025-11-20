<?php
// Configuración de cabeceras para permitir peticiones desde la extensión y CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Content-Type: application/json; charset=utf-8');

// Manejo de preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 1. Incluir conexión
include('../../include/conn.php'); 

// 2. Obtener datos
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// 3. Validar campos
if (!isset($data['email'], $data['senha'], $data['chromeStoreID'])) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan campos obligatorios",
        "msg_id"  => "missing_fields"
    ]);
    exit;
}

$email         = $data['email'];
$plain_password = $data['senha'];
$chromeStoreID = $data['chromeStoreID'];

// 4. Verificar conexión DB
if (!isset($conn1) || $conn1->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión con base de datos",
        "msg_id"  => "db_connection_error"
    ]);
    exit;
}

// 5. Buscar usuario
$stmt = $conn1->prepare("SELECT id, password, plan_expiry_date, max_devices FROM users WHERE email = ? AND status = 1");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error SQL", "msg_id" => "sql_error"]);
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode([
        "success" => false,
        "message" => "Usuario no encontrado o inactivo",
        "msg_id"  => "user_login_notFund"
    ]);
    exit;
}

$user = $result->fetch_assoc();
$stored_password = $user['password'];

// 6. SISTEMA HÍBRIDO DE VALIDACIÓN (ACTUALIZADO CON SHA1)
$login_success = false;
$needs_rehash  = false;

// CASO A: Hash seguro (Usuarios nuevos o migrados)
if (password_verify($plain_password, $stored_password)) {
    $login_success = true;
} 
// CASO B: SHA1 (Usuarios creados recientemente con tu script anterior)
elseif (sha1($plain_password) === $stored_password) {
    $login_success = true;
    $needs_rehash  = true; // Actualizar a Bcrypt
}
// CASO C: MD5 (Usuarios legacy comunes)
elseif (md5($plain_password) === $stored_password) {
    $login_success = true;
    $needs_rehash  = true; // Actualizar a Bcrypt
}
// CASO D: Texto Plano (Usuarios muy antiguos)
elseif ($plain_password === $stored_password) {
    $login_success = true;
    $needs_rehash  = true; // Actualizar a Bcrypt urgentemente
}

if (!$login_success) {
    echo json_encode([
        "success" => false,
        "message" => "Contraseña incorrecta",
        "msg_id"  => "invalid_password"
    ]);
    exit;
}

// 7. AUTO-MIGRACIÓN
// Si entró con un método inseguro (B, C o D), lo actualizamos a Bcrypt
if ($needs_rehash) {
    $new_secure_hash = password_hash($plain_password, PASSWORD_DEFAULT);
    $updateStmt = $conn1->prepare("UPDATE users SET password = ? WHERE id = ?");
    if ($updateStmt) {
        $updateStmt->bind_param("si", $new_secure_hash, $user['id']);
        $updateStmt->execute();
        $updateStmt->close();
    }
}

// 8. Validar fecha
$today = date('Y-m-d');
if ($user['plan_expiry_date'] < $today) {
    echo json_encode([
        "success" => false,
        "message" => "Tu plan ha expirado",
        "msg_id"  => "plan_expired"
    ]);
    exit;
}

// 9. Validar dispositivos
$deviceCheck = $conn1->prepare("SELECT id FROM user_devices WHERE user_id = ? AND chrome_store_id = ?");
$deviceCheck->bind_param("is", $user['id'], $chromeStoreID);
$deviceCheck->execute();
$deviceResult = $deviceCheck->get_result();

if ($deviceResult->num_rows === 0) {
    $countStmt = $conn1->prepare("SELECT COUNT(*) as total FROM user_devices WHERE user_id = ?");
    $countStmt->bind_param("i", $user['id']);
    $countStmt->execute();
    $countResult = $countStmt->get_result()->fetch_assoc();
    
    // Si max_devices es 0 o null, asume 1 por defecto para evitar bloqueo, o ajusta según tu lógica
    $max_dev = ($user['max_devices'] > 0) ? $user['max_devices'] : 1;

    if ($countResult['total'] >= $max_dev) {
        echo json_encode([
            "success" => false,
            "message" => "Límite de dispositivos alcanzado",
            "msg_id"  => "device_limit_reached"
        ]);
        exit;
    }

    $registerDevice = $conn1->prepare("INSERT INTO user_devices (user_id, chrome_store_id) VALUES (?, ?)");
    $registerDevice->bind_param("is", $user['id'], $chromeStoreID);
    $registerDevice->execute();
    $registerDevice->close();
}
$deviceCheck->close();

// 10. Token y Respuesta
$token = bin2hex(random_bytes(16));
$updateToken = $conn1->prepare("UPDATE users SET token = ? WHERE id = ?");
$updateToken->bind_param("si", $token, $user['id']);
$updateToken->execute();
$updateToken->close();

echo json_encode([
    "success" => true,
    "message" => "Login exitoso",
    "msg_id"  => "login_success",
    "token"   => $token,
    "user"    => [
        "id"            => $user['id'],
        "email"         => $email,
        "plan"          => "premium",
        "expiry"        => $user['plan_expiry_date'],
        "chromeStoreID" => $chromeStoreID
    ]
], JSON_PRETTY_PRINT);

$stmt->close();
$conn1->close();
?>