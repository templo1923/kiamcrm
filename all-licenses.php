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
    <meta name="description"
        content="Chameleon Admin is a modern Bootstrap 4 webapp &amp; admin dashboard html template with a large number of components, elegant design, clean and organized code.">
    <meta name="keywords"
        content="admin template, Chameleon admin template, dashboard template, gradient admin template, responsive admin template, webapp, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
    <title id="title"></title>
    <link rel="apple-touch-icon" href="assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="images/<?= $favicon_logo; ?>">
    <link
        href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700"
        rel="stylesheet">
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        /* .hidden {
            display: none;
        } */
        .hidden {
            display: none !important;
        }

        .calendar-icon {
            background: none;
            border: none;
            cursor: pointer;
        }

        .copy-icon {
            cursor: pointer;
            font-size: 16px;
            color: #007bff;
            margin-left: 10px;
            transition: color 0.3s ease;
        }

        .copy-icon:hover {
            color: #0056b3;
        }

        .copy-text {
            display: flex;
            align-items: center;
        }

        .copy-text input {
            border: none;
            background: none;
            cursor: default;
            padding: 0;
            margin: 0;
            width: 100%;
        }
    </style>



    <!-- END Page Level CSS-->
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
                <h3 class="content-header-title">All Users Licenses</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">
                <div class="breadcrumbs-top float-md-right">
                    <div class="breadcrumb-wrapper mr-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a>
                            </li>
                            <li class="breadcrumb-item active">All Licenses
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Basic All Licenses start -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Licenses</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="customerTable">
                                    <thead class="bg-light text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Customer Name</th>
                                            <th>Whatsapp Number</th>
                                            <th>Email</th>
                                            <th>End Date</th>
                                            <th>Owner</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $iduser = 0;
                                        $user_id = $_SESSION['id'];
                                        $user_type = $_SESSION['user_type'];

                                        // Query based on user role
                                        if ($user_type === 'admin') {
                                            // Get total licenses
                                            $reseller_ids = [];
                                            $reseller_query = mysqli_query($conn, "SELECT id FROM admin WHERE admin_id = $user_id AND deleted != 'yes'");
                                            while ($row = mysqli_fetch_assoc($reseller_query)) {
                                                $reseller_ids[] = $row['id'];
                                            }
                                            $all_ids = array_merge([$user_id], $reseller_ids);
                                            $id_list = implode(',', array_map('intval', $all_ids));
                                            $q = mysqli_query($conn, "SELECT * FROM users WHERE deleted_key != 'yes' AND user_id IN ($id_list) ORDER By id DESC");
                                        }

                                        if ($user_type === 'reseller') {
                                            // Get total licenses for reseller
                                            $q = mysqli_query($conn, "SELECT * FROM users WHERE deleted_key != 'yes' AND user_id = '$user_id' ORDER By id DESC");
                                        }

                                        if ($user_type === 'super_admin') {
                                            // Get total licenses for subadmin
                                            $q = mysqli_query($conn, "SELECT * FROM users WHERE deleted_key != 'yes' ORDER By id DESC");
                                        }



                                        while ($row = mysqli_fetch_assoc($q)) {
                                            $iduser++;
                                            $user_id = $row['user_id'];

                                            // Get the admin username who created this license
                                            $admin_query = mysqli_query($conn, "SELECT username FROM admin WHERE id = '$user_id'");
                                            $admin_row = mysqli_fetch_assoc($admin_query);
                                            $username = $admin_row['username'] ?? 'N/A';

                                            // Calculate remaining days
                                            $endDate = new DateTime($row['end_date']);
                                            $today = new DateTime();
                                            $interval = $today->diff($endDate);
                                            // $remainingDays = $interval->invert ? 0 : $interval->days;
                                            $expireStyle = ($interval->invert || $remainingDays === 0) ? "color:red;" : "";

                                        ?>
                                            <tr>
                                                <td><?= $iduser ?></td>
                                                <td><?= htmlspecialchars($row['client_name']) ?></td>
                                                <td><?= htmlspecialchars($row['mobile_no']) ?></td>

                                                <td class="copy-text">
                                                    <input type="text" value="<?= htmlspecialchars($row['email']) ?>" id="license-key-<?= $row['id'] ?>" readonly>
                                                    <i style='color:<?= $color_background; ?>;' class="fas fa-copy copy-icon" onclick="copyToClipboard('license-key-<?= $row['id'] ?>')" title="Copy"></i>
                                                </td>

                                               

                                                <td>
                                                    <span class="display-date" id="display-end_date-<?= $row['id'] ?>"><?= $row['plan_expiry_date'] ?></span>
                                                    <input type="date" class="date-picker hidden" id="input-end_date-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>" data-field="end_date" value="<?= $row['end_date'] ?>">
                                                    <button class="calendar-icon" onclick="showDatePicker(<?= $row['id'] ?>, 'end_date')">&#128197;</button>
                                                </td>

                                                <td><?= htmlspecialchars($username) ?></td>
                                             

                                                <td>
                                                    <?php if ($row['status']): ?>
                                                        <span class="license-status" style="<?= $expireStyle ?>"><span class='badge badge-success'><i class="fa fa-check">&nbsp;</i>Active</span></span>
                                                    <?php else: ?>
                                                        <span class="license-status"><span class='badge badge-danger'><i class="fa fa-times">&nbsp;</i>Inactive</span></span>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                                                            <i class="fa fa-cogs">&nbsp;</i> Actions
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item activate-btn" href="#" data-id="<?= $row['id'] ?>" style="color:green;">
                                                                <i class="fa fa-check"></i>&nbsp;Activate
                                                            </a>
                                                            <a class="dropdown-item deactivate-btn" href="#" data-id="<?= $row['id'] ?>" style="color:maroon;">
                                                                <i class="fa fa-close"></i>&nbsp;Deactivate
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item edit-btn" href="edit_license.php?id=<?= $row['id'] ?>" style="color:#d6b306;">
                                                                <i class="fa fa-edit"></i>&nbsp;Edit
                                                            </a>
                                                            <a class="dropdown-item delete-btn" href="#" data-id="<?= $row['id'] ?>" style="color:red;">
                                                                <i class="fa fa-trash"></i>&nbsp;Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bordered table end -->
        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->

<?php include("include/footer.php"); ?>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


<script>
    $(document).ready(function() {
        $('#title').html('All License');
        $('#customerTable').DataTable({
            "paging": true, // Enables pagination
            "ordering": true, // Enables sorting
            "searching": true, // Enables searching
            "order": [
                [4, "desc"]
            ], // Default sort by Activation Date in descending order
            "columnDefs": [{
                    "orderable": false,
                    "targets": 9
                } // Disable sorting on the "Actions" column

            ]
        });

        document.getElementById("sid-all").classList.add("active");
    });
</script>

<script>
    function showDatePicker(id, field) {
        const inputId = `input-${field}-${id}`;
        const displayId = `display-${field}-${id}`;
        const dateInput = document.getElementById(inputId);
        const displayDate = document.getElementById(displayId);

        if (!dateInput) {
            console.error(`Element with ID ${inputId} not found.`);
            return;
        }

        dateInput.classList.remove('hidden');
        displayDate.classList.add('hidden');
        dateInput.focus();

        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const newDateTime = `${selectedDate} ${hours}:${minutes}`; // Format: YYYY-MM-DD HH:MM

                displayDate.textContent = newDateTime;
                dateInput.classList.add('hidden');
                displayDate.classList.remove('hidden');

                // AJAX request to update the date and time in the database
                $.ajax({
                    url: 'update_date.php',
                    type: 'POST',
                    data: {
                        id: id,
                        field: field,
                        value: newDateTime
                    },
                    success: function(response) {
                        console.log("Response from server:", response); // Debugging line

                        if (response.success === true) {
                            Swal.fire({
                                title: 'Updated!',
                                text: 'License date updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to update date. Response: ' + response,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", status, error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while updating the date and time.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });


        dateInput.addEventListener('blur', function() {
            dateInput.classList.add('hidden');
            displayDate.classList.remove('hidden');
        });
    }


    function copyToClipboard(elementId) {
        var copyText = document.getElementById(elementId);
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices

        try {
            var successful = document.execCommand('copy');
            if (successful) {
                Swal.fire({
                    title: 'Copied!',
                    text: "Copied the text: " + copyText.value,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Failed!',
                    text: "Failed to copy the text.",
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (err) {
            console.error('Oops, unable to copy', err);
            Swal.fire({
                title: 'Error!',
                text: "An error occurred while copying.",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    }
</script>

<script>
    $(document).ready(function() {
        // Activate license
        $(document).on('click', '.activate-btn', function(e) {
            e.preventDefault();
            var licenseId = $(this).data('id');
            updateLicenseStatus(licenseId, 1);
        });

        // Deactivate license
        $(document).on('click', '.deactivate-btn', function(e) {
            e.preventDefault();
            var licenseId = $(this).data('id');
            updateLicenseStatus(licenseId, 0);
        });

        // Function to update license status
        function updateLicenseStatus(id, status) {
            $.ajax({
                url: 'update-license-status.php',
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    console.log("Response from server:", response); // Debugging line

                    if (response.success === true) {
                        Swal.fire({
                            title: 'Updated!',
                            text: 'License status updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload only after alert is closed
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update license status.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while updating status.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });

    // deleted function
    $(document).ready(function() {
        // Delete license by marking it as deleted in the database
        $(document).on('click', '.delete-btn', function() {
            var licenseId = $(this).data('id');

            if (confirm('Are you sure you want to delete this license?')) {
                $.ajax({
                    url: 'delete-license.php',
                    type: 'POST',
                    data: {
                        id: licenseId
                    },
                    success: function(response) {
                    console.log("Server Response:", response); // 🔍 Debug

                    if (response.trim() === 'success') {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'License marked as deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Should reload page
                        });
                    } else {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'License marked as deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }
                },

                });
            }
        });
    });
</script>

</body>

</html>