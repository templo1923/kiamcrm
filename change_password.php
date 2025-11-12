<?php
include("include/conn.php");
include("include/function.php");
$login = cekSession();
if ($login != 1) {
    redirect("login.php");
}
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Chameleon Admin is a modern Bootstrap 4 webapp &amp; admin dashboard html template with a large number of components, elegant design, clean and organized code.">
    <meta name="keywords" content="admin template, Chameleon admin template, dashboard template, gradient admin template, responsive admin template, webapp, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
    <title id="title"></title>
    <link rel="apple-touch-icon" href="assets/images/ico/apple-icon-120.png">
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
    <!-- END Custom CSS-->
     
</head>
<?php
include("include/header.php");
include("include/sidebar.php");
?>
<style>
    #response-message {
        text-align: center;
        /* Center-align the text horizontally */
        padding: 20px;
        /* Add padding for spacing */
        border: 1px solid #ddd;
        /* Add a border */
        border-radius: 5px;
        /* Round the corners */
        margin-top: 20px;
        /* Add some margin to separate it from other elements */
        font-size: 18px;
        /* Set the font size */
        font-weight: bold;
        /* Make the text bold */
    }

    /* Style for success messages */
    .success {
        background-color: #4CAF50;
        /* Green background color */
        color: #fff;
        /* White text color */
        border: 2px solid #45A049;
        /* Green border */
    }

    /* Style for error messages */
    .error {
        background-color: #FF5733;
        /* Red background color */
        color: #fff;
        /* White text color */
        border: 2px solid #D73925;
        /* Red border */
    }


    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        padding-right: 40px;
        /* space for the icon */
    }

    .password-wrapper .toggle-password {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #888;
    }

    .password-wrapper .toggle-password:hover {
        color: #000;
    }
</style>

<div class="app-content content">
    <div class="content-wrapper">
        <div <?= $style; ?> class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title">Change Password</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">
                <div class="breadcrumbs-top float-md-right">
                    <div class="breadcrumb-wrapper mr-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Change Password
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body"><!-- Basic Inputs start -->

            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                    <div class="card-block">
                        <form id="changePassword">
                            <div class="card-body">

                                <fieldset class="form-group">
                                    <h6 class="card-title">New Password</h6>
                                    <div class="password-wrapper">
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="newPassword"
                                            name="newPassword"
                                            placeholder="Enter your new password" />
                                        <i class="fa fa-eye toggle-password" onclick="togglePassword(this)"></i>
                                    </div>
                                </fieldset>

                                <script>
                                    function togglePassword(icon) {
                                        const input = document.getElementById("newPassword");
                                        const isPassword = input.type === "password";
                                        input.type = isPassword ? "text" : "password";
                                        icon.classList.toggle("fa-eye");
                                        icon.classList.toggle("fa-eye-slash");
                                    }
                                </script>



                                <button <?= $style; ?> type="button" id="changePassword" class="btn btn-info btn-min-width mr-1 mb-1">Update Password</button>
                                </fieldset>
                            </div>
                        </form>
                        <div id="response-message"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->

<?php include("include/footer.php"); ?>
<!-- BEGIN VENDOR JS-->
<script src="assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<!-- END PAGE VENDOR JS-->
<!-- BEGIN CHAMELEON  JS-->
<script src="assets/js/core/app-menu-lite.js" type="text/javascript"></script>
<script src="assets/js/core/app-lite.js" type="text/javascript"></script>
<!-- END CHAMELEON  JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="assets/vendors/js/forms/tags/form-field.js" type="text/javascript"></script>
<!-- END PAGE LEVEL JS-->

<script>
    $(document).ready(function() {
        $('#title').html('Change Password')
    });
    document.getElementById("sid-add").classList.add("active");
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#changePassword').click(function() {
            // Get form data
            var newPassword = $('#newPassword').val();
            // var email = $('#email').val();

            // Check for empty or null values
            // if (!wnumber || !endDate || !cname || !email) {
            if (!newPassword) {

                var errorMessage = "Please fill in all required fields.";
                $('#response-message').html('<div class="error">' + errorMessage + '</div>');
                return;
            }



            // Send AJAX request to your PHP script
            $.ajax({
                type: 'POST',
                url: 'function/change_password.php', // Replace with the correct path to your PHP script
                data: {
                    new_password: newPassword
                },
                success: function(response) {
                    if (response.status === true) {
                        $('#response-message').html('<div class="success">' + response.message + '</div>');
                    } else {
                        $('#response-message').html('<div class="error">' + response.message + '</div>');
                    }
                },
                error: function() {
                    $('#response-message').html('<div class="error">An error occurred while processing your request.</div>');
                }
            });
        });
    });
</script>

</body>

</html>