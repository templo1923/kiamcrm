<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("include/conn.php");
include("include/function.php");

// Check session
$login = cekSession();
if ($login != 1) {
    redirect("login.php");
}

// Fetch license details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `id` = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $license = $result->fetch_assoc();
    if (!$license) die("License not found!");

    $end_date = new DateTime($license['plan_expiry_date']);
    $today = new DateTime();
    $validity = $today->diff($end_date)->days;

} else {
    die("No license ID provided!");
}

// Handle form submission
if (isset($_POST['update'])) {
    $customer_name = $_POST['customer_name'] ?? '';
    $mobile_no = $_POST['whatsapp_number'] ?? ''; // ensure POST field name matches form
    $password = $_POST['license_key'] ?? '';
    $license_key = $password != "" ? sha1($password) : null;
    $end_date_input = $_POST['end_date'] ?? '';
    $email = $_POST['email'] ?? '';
    $status = 'true';

    $datetime = date('Y-m-d H:i:s', strtotime($end_date_input));

    if ($license_key) {
        $stmt = $conn->prepare("UPDATE `users` SET `client_name` = ?, `mobile_no` = ?, `password` = ?, `plan_expiry_date` = ?, `email` = ?, `status` = ? WHERE `id` = ?");
        if (!$stmt) die("Prepare failed: " . $conn->error);
        $stmt->bind_param("ssssssi", $customer_name, $mobile_no, $license_key, $datetime, $email, $status, $id);
    } else {
        $stmt = $conn->prepare("UPDATE `users` SET `client_name` = ?, `mobile_no` = ?, `plan_expiry_date` = ?, `email` = ?, `status` = ? WHERE `id` = ?");
        if (!$stmt) die("Prepare failed: " . $conn->error);
        $stmt->bind_param("sssssi", $customer_name, $mobile_no, $datetime, $email, $status, $id);
    }

    if ($stmt->execute()) {
        echo "License updated successfully!";
        header("Location: all-licenses.php");
        exit;
    } else {
        die("Error updating license: " . $stmt->error);
    }
}


// Generate license key function
function generateLicenseKey() {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $key = '';
    for ($i = 0; $i < 4; $i++) {
        $key .= implode('', array_map(function () use ($chars) {
            return $chars[random_int(0, strlen($chars) - 1)];
        }, range(1, 4)));
        if ($i < 3) $key .= '-';
    }
    return $key;
}
?>


<!DOCTYPE html>
<html lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Chameleon Admin is a modern Bootstrap 4 webapp &amp; admin dashboard html template with a large number of components, elegant design, clean and organized code.">
    <meta name="keywords" content="admin template, Chameleon admin template, dashboard template, gradient admin template, responsive admin template, webapp, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
    <title id="title"></title>
   <link rel="shortcut icon" type="image/x-icon" href="images/<?= $favicon_logo; ?>">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN CHAMELEON  CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/app-lite.css">
    <!-- END CHAMELEON  CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="assets/css/core/colors/palette-gradient.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- END Custom CSS-->
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: calc(100% - 120px);
            /* Adjust width to leave space for the button */
            padding: 8px;
            box-sizing: border-box;
            display: inline-block;
        }

        .btn-submit,
        .btn-generate {
            padding: 10px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
            vertical-align: middle;
        }

        .btn-submit {
            background-color: #007bff;
            color: #fff;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .btn-generate {
            background-color: #28a745;
            color: #fff;
            width: 100px;
            /* Adjust button width */
            margin-left: 10px;
        }

        .btn-generate:hover {
            background-color: #218838;
        }

        .input-container {
            position: relative;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<?php
include("include/header.php");
include("include/sidebar.php");
?>
<div class="app-content content">
    <div class="content-wrapper">
        <div <?= $style; ?> class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title">Edit License</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">
                <div class="breadcrumbs-top float-md-right">
                    <div class="breadcrumb-wrapper mr-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="index.php">All Licenses</a></li>
                            <li class="breadcrumb-item active">Edit License</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit License Details</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <form method="post" action="">
                                    <div class="form-group">
                                        <label for="customer_name">Client Name:</label>
                                        <input type="text" id="customer_name" name="customer_name"
                                            value="<?php echo htmlspecialchars($license['client_name']); ?>" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="text" id="email" name="email"
                                            value="<?php echo htmlspecialchars($license['email']); ?>"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="whatsapp_number">Whatsapp Number:</label>
                                        <input type="text" id="whatsapp_number" name="whatsapp_number"
                                            value="<?php echo htmlspecialchars($license['mobile_no']); ?>"
                                            required>
                                    </div>

                                    <div class="form-group input-container">
                                        <label for="license_key">Password :</label>
                                        <input type="text" id="license_key" name="license_key"
                                            value="" >
                                        <button <?= $style; ?> type="button" class="btn-generate" title="Generate Password" id="generate_key"><i class="fa fa-undo">&nbsp;</i></button>
                                    </div>

                                    <fieldset class="form-group">
                                         <label for="end_date">End Date :</label>
                                        <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="<?php echo htmlspecialchars(date('Y-m-d',strtotime($license['plan_expiry_date']))); ?>"
                                            required>
                                    </fieldset>


                                    <!-- Status hidden input -->
                                    <input type="hidden" name="status" value="true">

                                    <button <?= $style; ?> type="submit" name='update' class="btn-submit">Update License</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit License end -->
        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->

<!-- BEGIN VENDOR JS-->
<script src="assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN PAGE VENDOR JS-->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- BEGIN CHAMELEON  JS-->
<script src="assets/js/core/app-menu-lite.js" type="text/javascript"></script>
<script src="assets/js/core/app-lite.js" type="text/javascript"></script>
<!-- END CHAMELEON  JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="assets/vendors/js/forms/tags/form-field.js" type="text/javascript"></script>
<!-- END PAGE LEVEL JS-->


<script>
      $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd', // Format for the date
        minDate: 0 // Prevents selection of past dates
    });
</script>


<script>
    // Generate new license key and set it in the license key input field
    document.getElementById('generate_key').addEventListener('click', function () {
        function generateLicenseKey() {
            const chars = '0123456789';
            let key = '';
            for (let i = 0; i < 2; i++) {
                key += Array.from({ length: 4 }, () => chars.charAt(Math.floor(Math.random() * chars.length))).join('');
                if (i < 3) {
                    key += '';
                }
            }
            return key;
        }
        document.getElementById('license_key').value = generateLicenseKey();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const endDateInput = document.getElementById("end_date");
        if (!endDateInput.value) {  // If no date-time is set, set the current date and time
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            const formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`; // Format: YYYY-MM-DDTHH:MM
            endDateInput.value = formattedDateTime;
        }
    });
</script>
</body>

</html>