<?php
// --- SOLUCIÓN CORS ---
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}
// --- FIN CORS ---

header('Content-Type: application/json');
include "../../include/conn.php";

// Recibir datos
$data = json_decode(file_get_contents("php://input"));

// El frontend original envía 'token' o 'license_key' dependiendo de la versión.
// Este código maneja ambos casos por seguridad.
$token = null;
if (isset($data->token)) {
    $token = $data->token;
} elseif (isset($data->license_key)) {
    $token = $data->license_key;
} else {
    echo json_encode(["success" => false, "message" => "No hay token"]);
    exit;
}

// 1. Buscar al usuario dueño de este token
$stmt = $conn1->prepare("SELECT * FROM users WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // 2. Verificar si está activo
    if ($user['status'] != 1) {
        echo json_encode(["success" => false, "message" => "Usuario inactivo"]);
        exit;
    }

    // 3. Verificar fecha
    $today = date("Y-m-d");
    if ($today > $user['plan_expiry_date']) {
        echo json_encode(["success" => false, "message" => "Licencia expirada"]);
        exit;
    }

    // 4. Todo OK - Usuario válido y Premium
    echo json_encode([
        "status" => true,
        "success" => true,
        "message" => "Validado",
        "user" => [
            "id" => $user['id'],
            "name" => $user['client_name'],
            "email" => $user['email'],
            "status" => 'active',
            "plan" => "PREMIUM",
            "user_type" => "user",
            "expiration_date" => $user['plan_expiry_date'],
            "permissions" => [
                "crm" => true,
                "funnel" => true,
                "schedule" => true,
                "check_sp" => true,
                "multiagent" => true, // Apagamos esto explícitamente
                "audio_transcription" => true
            ]
        ],
        "plan" => "PREMIUM",
        "valid" => true
    ]);

} else {
    // Token no encontrado = Sesión invalida (ej: abrió sesión en otro lado)
    echo json_encode(["success" => false, "message" => "Sesión no válida"]);
}

$stmt->close();
$conn1->close();
?>