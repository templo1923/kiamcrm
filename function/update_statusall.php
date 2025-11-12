<?php
include("../include/conn.php");
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input sanitization
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = ($_POST['status'] === 'true') ? 'true' : 'false';

    if ($id > 0) {
        // Update reseller status
        $stmt1 = $conn->prepare("UPDATE `admin` SET `status` = ? WHERE `id` = ?");
        $stmt1->bind_param("si", $status, $id);
        $reseller_updated = $stmt1->execute();
        $stmt1->close();

        if ($reseller_updated) {
            // Update user licenses created by this reseller
            $stmt2 = $conn->prepare("UPDATE `users` SET `status` = ? WHERE `user_id` = ?");
            $stmt2->bind_param("si", $status, $id);
            $users_updated = $stmt2->execute();
            $stmt2->close();

            if ($users_updated) {
                $response['status'] = true;
                $response['message'] = 'Reseller and associated licenses updated successfully.';
            } else {
                $response['status'] = false;
                $response['message'] = 'Reseller updated, but license update failed.';
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'Failed to update reseller status.';
        }
    } else {
        $response['status'] = false;
        $response['message'] = 'Invalid ID.';
    }
} else {
    $response['status'] = false;
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
