<?php
include("../include/conn.php");
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input sanitization
    $admin_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = ($_POST['status'] === 'true') ? 'true' : 'false';

    if ($admin_id > 0) {

        // Step 1: Update Admin status
        $stmt1 = $conn->prepare("UPDATE `admin` SET `status` = ? WHERE `id` = ?");
        $stmt1->bind_param("si", $status, $admin_id);
        $admin_updated = $stmt1->execute();
        $stmt1->close();

        if ($admin_updated) {
            // Step 2: Update all resellers under this admin
            $stmt2 = $conn->prepare("UPDATE `admin` SET `status` = ? WHERE `admin_id` = ?");
            $stmt2->bind_param("si", $status, $admin_id);
            $reseller_updated = $stmt2->execute();
            $stmt2->close();

            if ($reseller_updated) {
                // Step 3: Get all reseller IDs under this admin
                $stmt3 = $conn->prepare("SELECT id FROM `admin` WHERE `admin_id` = ?");
                $stmt3->bind_param("i", $admin_id);
                $stmt3->execute();
                $result = $stmt3->get_result();
                
                $allUserUpdatesSuccessful = true;
                
                while ($row = $result->fetch_assoc()) {
                    $reseller_id = $row['id'];
                    $updateSuccess = updateUsers($conn, $status, $admin_id, $reseller_id);
                    if (!$updateSuccess) {
                        $allUserUpdatesSuccessful = false;
                        break;
                    }
                }

                $stmt3->close();

                if ($allUserUpdatesSuccessful) {
                    $response['status'] = true;
                    $response['message'] = "Admin, Reseller, and User status updated successfully.";
                } else {
                    $response['status'] = false;
                    $response['message'] = "User status update failed.";
                }

            } else {
                $response['status'] = false;
                $response['message'] = "Failed to update resellers under the admin.";
            }

        } else {
            $response['status'] = false;
            $response['message'] = "Failed to update admin status.";
        }

    } else {
        $response['status'] = false;
        $response['message'] = "Invalid admin ID.";
    }

} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method.";
}


// Function to update users created by admin or their resellers
function updateUsers($conn, $status, $admin_id, $reseller_id)
{
    $stmt = $conn->prepare("UPDATE `users` SET `status` = ? WHERE `user_id` = ? OR `user_id` = ?");
    $stmt->bind_param("sii", $status, $admin_id, $reseller_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

echo json_encode($response);
