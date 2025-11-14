<?php
// Incluimos la conexión a la base de datos
include("../../include/conn.php");
include("../../include/db.php");
$db = new mysqliDB();

// --- SOLUCIÓN CORS (Permisos de entrada) ---
header("Access-Control-Allow-Origin: https://web.whatsapp.com");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// ------------------------------------------

header('Content-Type: application/json');

// 1. Leer los datos que envía la extensión
// (Asumimos que envía el email y un token que se guardó al iniciar sesión)
$data = json_decode(file_get_contents('php://input'), true);
$email = $db->esc($data['email']);
$token = $db->esc($data['token']);

// 2. Buscar al usuario en la base de datos usando el email y token
$user_data = $db->select("users", "*", "email = '$email' AND token = '$token'");

if (count($user_data) > 0) {
    $user = $user_data[0];
    
    // 3. ¡LA LÓGICA DE DÍAS GRATIS!
    $today = date('Y-m-d');
    $expiry_date = $user['plan_expiry_date'];

    if ($expiry_date >= $today) {
        // La licencia está ACTIVA (es Premium)
        $plan = "PREMIUM";
        $multiagent = true; // O lo que defina tu plan
    } else {
        // La licencia está VENCIDA (es Gratis)
        $plan = "FREE";
        $multiagent = false;
    }
    
    // 4. Construir la respuesta (igual que antes, pero con datos REALES)
    $response = [
        "status" => true,
        "success" => true,
        "message" => "Validado con éxito",
        "user" => [
            "id" => (int)$user['id'],
            "name" => $user['client_name'],
            "email" => $user['email'],
            "status" => "active",
            "plan" => $plan, // Variable
            "user_type" => "user",
            "expiration_date" => $user['plan_expiry_date'], // Variable
            "permissions" => [
                "crm" => true,
                "funnel" => true,
                "schedule" => ($plan == "PREMIUM"), // Solo Pro
                "check_sp" => true,
                "multiagent" => $multiagent, // Solo Pro
                "audio_transcription" => ($plan == "PREMIUM") // Solo Pro
            ]
        ],
        "plan" => $plan, // Variable
        "valid" => true
    ];

} else {
    // Si el email o token no coinciden, se rechaza
    $response = [
        "status" => false, 
        "success" => false, 
        "message" => "Token de sesión inválido o usuario no encontrado."
    ];
}

// 5. Enviar la respuesta
echo json_encode($response);
?>