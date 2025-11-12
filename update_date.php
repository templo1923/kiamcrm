<?php
include("include/conn.php");
header('Content-Type: application/json');

// Check if connection is established
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Validate POST inputs
if (isset($_POST['id'], $_POST['field'], $_POST['value'])) {
    $id = intval($_POST['id']); // Safe integer casting
    $field = $_POST['field'];
    $value = $_POST['value'];

    // Allow only specific editable fields
    $allowedFields = ['act_date', 'end_date'];

    if (in_array($field, $allowedFields)) {
        // Convert to valid datetime format
        $datetime = date('Y-m-d H:i:s', strtotime($value));

        // Prepare dynamic query safely
        $query = "UPDATE users SET `$field` = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $datetime, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Date updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid field']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
