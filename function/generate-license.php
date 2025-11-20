<?php
// Ajusta la ruta si es necesario según tu estructura de carpetas
include("../include/conn.php");
include("../include/function.php");

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wnumber = trim($_POST['wnumber'] ?? '');
    $cname = trim($_POST['cname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $license_key_input = trim($_POST['license_key'] ?? '');
    $expiry_date_input = trim($_POST['expiry_date'] ?? '');
    
    // Obtener ID del admin
    $admin_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

    // Validación básica
    if (empty($wnumber) || empty($expiry_date_input) || empty($cname) || empty($license_key_input)) {
        $response['status'] = false;
        $response['message'] = "Por favor complete todos los campos requeridos.";
    } else {
        // CORRECCIÓN: Usar password_hash en lugar de sha1 para máxima seguridad compatible con el login
        $secure_password = password_hash($license_key_input, PASSWORD_DEFAULT);

        // Verificar si el usuario ya existe (opcional pero recomendado)
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $response['status'] = false;
            $response['message'] = "El email ya está registrado.";
            $check->close();
        } else {
            $check->close();
            
            // Insertar nuevo usuario
            // Nota: Asegúrate de que la columna 'password' en tu DB sea de al menos 60 caracteres (VARCHAR 255 recomendado)
            $stmt = $conn->prepare("INSERT INTO `users` (`user_id`, `client_name`, `mobile_no`, `password`, `plan_expiry_date`, `email`, `status`) VALUES (?, ?, ?, ?, ?, ?, 1)");
            
            if ($stmt) {
                $stmt->bind_param("isssss", $admin_id, $cname, $wnumber, $secure_password, $expiry_date_input, $email);

                if ($stmt->execute()) {
                    $response['status'] = true;
                    $response['message'] = "Licencia creada exitosamente. Contraseña: $license_key_input";
                } else {
                    $response['status'] = false;
                    $response['message'] = "Error al insertar en base de datos: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['status'] = false;
                $response['message'] = "Error en la preparación de la consulta: " . $conn->error;
            }
        }
    }
} else {
    $response['status'] = false;
    $response['message'] = "Método de solicitud inválido.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>