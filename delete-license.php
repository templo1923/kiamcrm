<?php
include("include/conn.php");
include("include/function.php");

// Check if the user is logged in
$login = cekSession();
if ($login != 1) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

// Check if the ID is provided
if (isset($_POST['id'])) {
    // Sanitize the input to prevent SQL injection
    $licenseId = intval($_POST['id']);

    // Query to delete the record from the users table
    $query = "DELETE FROM `users` WHERE `id` = ?";
    
    // Prepare and bind parameters to the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $licenseId);

    // Execute the query
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // If at least one row was affected, consider it successful
        echo json_encode(['success' => true, 'message' => 'License deleted successfully']);
    } else {
        // If no rows were affected, something went wrong
        echo json_encode(['success' => false, 'error' => 'Failed to delete license or license does not exist']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No license ID provided']);
}
?>
