<?php
include("include/conn.php");
include("include/function.php");

$login = cekSession();
if ($login != 1) {
    redirect("login.php");
}

$user_id = intval($_SESSION['id']);
$user_type = $_SESSION['user_type'];



function getCounts($conn, $user_id, $user_type)
{
    $counts = array();

    if ($user_type === 'admin') {

        $counts['total_users'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM admin where admin_id = $user_id"))['total_users'];


       // Get total licenses
        $reseller_ids = [];
        $reseller_query = mysqli_query($conn, "SELECT id FROM admin WHERE admin_id = $user_id AND deleted != 'yes'");
        while ($row = mysqli_fetch_assoc($reseller_query)) {
            $reseller_ids[] = $row['id'];
        }
        $all_ids = array_merge([$user_id], $reseller_ids);
        $id_list = implode(',', array_map('intval', $all_ids));
        $count_query = mysqli_query($conn, "SELECT COUNT(*) AS total_licenses FROM users WHERE deleted_key != 'yes' AND user_id IN ($id_list)");
        $count_result = mysqli_fetch_assoc($count_query);
        $counts['total_licenses'] = $count_result['total_licenses'];

        // Get total inactive licenses
        $inactive_lic_query = mysqli_query($conn, "SELECT COUNT(*) AS total_inactive_licenses FROM users WHERE status = 0 AND user_id IN ($id_list)");
        $counts['total_inactive_licenses'] = mysqli_fetch_assoc($inactive_lic_query)['total_inactive_licenses'];


        // Get total active licenses
        $active_lic_query = mysqli_query($conn, "SELECT COUNT(*) AS total_active_licenses FROM users WHERE status = 1 AND deleted_key != 'yes' AND user_id IN ($id_list)");
        $counts['total_active_licenses'] = mysqli_fetch_assoc($active_lic_query)['total_active_licenses'];


        $counts['total_resellers'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total_resellers FROM admin WHERE user_type = 'reseller' AND deleted = 'no' AND admin_id = $user_id"))['total_resellers'];

    } elseif ($user_type === 'reseller') {


        $counts['total_licenses'] = mysqli_query($conn, "SELECT COUNT(*) AS total_licenses FROM users WHERE deleted_key != 'yes' AND user_id =$user_id");

        $counts['total_inactive_licenses'] = mysqli_query($conn, "SELECT COUNT(*) AS total_inactive_licenses FROM users WHERE status = 'false' AND deleted_key != 'yes' AND user_id = $user_id");

        $counts['total_active_licenses'] = mysqli_query($conn, "SELECT COUNT(*) AS total_active_licenses FROM users WHERE status = 'true' AND deleted_key != 'yes' AND user_id = $user_id");
        
    } else { //for super admin or other user types
       $counts['total_users'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM admin "))['total_users'];

        // Get total licenses
        $count_query = mysqli_query($conn, "SELECT COUNT(*) AS total_licenses FROM users WHERE deleted_key != 'yes'");
        $count_result = mysqli_fetch_assoc($count_query);
        $counts['total_licenses'] = $count_result['total_licenses'];


        // Get total inactive licenses
        $inactive_lic_query = mysqli_query($conn, "SELECT COUNT(*) AS total_inactive_licenses FROM users WHERE status = 0");
        $counts['total_inactive_licenses'] = mysqli_fetch_assoc($inactive_lic_query)['total_inactive_licenses'];


        // Get total active licenses
        $active_lic_query = mysqli_query($conn, "SELECT COUNT(*) AS total_active_licenses FROM users WHERE status = 1");
        $counts['total_active_licenses'] = mysqli_fetch_assoc($active_lic_query)['total_active_licenses'];


        // Get total resellers
        $resellers_lic_query = mysqli_query($conn, "SELECT COUNT(*) AS total_resellers FROM admin WHERE user_type = 'reseller' AND deleted != 'yes'");
        $counts['total_resellers'] = mysqli_fetch_assoc($resellers_lic_query)['total_resellers'];

        // Get total admins
        $admins_lic_query = mysqli_query($conn, "SELECT COUNT(*) AS total_admins FROM admin WHERE user_type = 'admin' AND deleted != 'yes'  ");
        $counts['total_admins'] = mysqli_fetch_assoc($admins_lic_query)['total_admins'];


    }

    // echo "result : " . json_encode($counts);

    return $counts;
}

$counts = getCounts($conn, $user_id, $user_type);

?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Dashboard">
    <meta name="keywords" content="admin template, dashboard, metrics">
    <meta name="author" content="ThemeSelect">
    <title>Dashboard</title>
    <link rel="apple-touch-icon" href="assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="images/<?= $favicon_logo; ?>">
    <link
        href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700"
        rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" href="assets/css/app-lite.css">
    <link rel="stylesheet" type="text/css" href="assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="assets/css/core/colors/palette-gradient.css">
    
    <style>
        .boxDesign{
            border-radius:15px;box-shadow:3px 3px 2px grey;color:white;background: linear-gradient(to right, #ff7e5f, #feb47b);
        }
        
        .sliderContainer{
            background:yelow;
            border-radius:20px;
            height:300px;*/
            width:600px;
        }
         * { margin: 0; padding: 0; box-sizing: border-box; }
        .gd-slider {
            width: 100%;
            max-width: 95%;
            margin: auto;
            overflow: hidden;
            position: relative;
        }
        .gd-slides {
            display: flex;
            width: 100%;
            transition: transform 0.5s ease-in-out;
        }
        .gd-slide {
            min-width: 100%;
            transition: 0.5s;
        }
        .gd-slide img {
            width: 100%;
            display: block;
        }
        .gd-navigation {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }
        .gd-prev, .gd-next {
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 20px;
            border: none;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }
        .gd-prev { left: 10px; }
        .gd-next { right: 10px; }
        
        
        .count{
            font-size:15px;
            font-weight:bold;
        }
        
    </style>
</head>

<body>
    <?php include("include/header.php"); ?>
    <?php include("include/sidebar.php"); ?>

    <div class="app-content content" style='margin-top:-41px;'>
        <div class="content-wrapper">
            <div  <?= $style; ?> class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-12 mb-2">
                    <!--<h3 class="content-header-title">Dashboard</h3>-->
                    <br><br>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <?php if ($user_type === 'admin') { ?>
                        <div class="col-md-3">
                            <div  class=" card">
                                <div  class="card-content">
                                    <div class=" card-body">
                                        <a href="">
                                            <h4 class="card-title">Total Users</h4>
                                            <p class="card-text count" ><?= $counts['total_users']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-licenses.php">
                                            <h4 class="card-title">Total Licenses</h4>
                                            <p class="card-text"><?= $counts['total_licenses']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-inactivelicenses.php">
                                            <h4 class="card-title">Total <span class="badge badge-danger">Inactive</span> Licenses</h4>
                                            <p class="card-text"><?= $counts['total_inactive_licenses']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-activelicenses.php">
                                            <h4 class="card-title">Total <span class="badge badge-success">Active</span> Licenses</h4>
                                            <p class="card-text"><?= $counts['total_active_licenses']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-resellers.php">
                                            <h4 class="card-title">Total Resellers</h4>
                                            <p class="card-text"><?= $counts['total_resellers']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($user_type === 'reseller') { ?>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="">
                                            <h4 class="card-title">Total Users</h4>
                                            <p class="card-text"><?php echo $counts['total_users']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-licenses.php">
                                            <h4 class="card-title">Total Licenses</h4>
                                            <p class="card-text"><?php echo $counts['total_licenses']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-inactivelicenses.php">
                                            <h4 class="card-title">Total <span class="badge badge-danger">Inactive</span> Licenses</h4>
                                            <p class="card-text"><?php echo $counts['total_inactive_licenses']; ?>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-activelicenses.php">
                                            <h4 class="card-title">Total <span class="badge badge-success">Active</span> Licenses</h4>
                                            <p class="card-text"><?php echo $counts['total_active_licenses']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-reseller.php">
                                            <h4 class="card-title">Total Resellers</h4>
                                            <p class="card-text"><?php echo $counts['total_resellers']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } else { ?>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-licenses.php">
                                            <h4 class="card-title">Total Licenses Created</h4>
                                            <p class="card-text"><?php echo $counts['total_licenses']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-inactivelicenses.php">
                                            <h4 class="card-title">Total <span class="badge badge-danger">Inactive</span> Licenses</h4>
                                            <p class="card-text"><?php echo $counts['total_inactive_licenses']; ?>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-activelicenses.php">
                                            <h4 class="card-title">Total <span class="badge badge-success">Active</span> Licenses</h4>
                                            <p class="card-text"><?php echo $counts['total_active_licenses']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-admins.php">
                                            <h4 class="card-title">Total Admins</h4>
                                            <p class="card-text"><?php echo $counts['total_admins']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <a href="all-resellers.php">
                                            <h4 class="card-title">Total Resellers</h4>
                                            <p class="card-text"><?php echo $counts['total_resellers']; ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <hr>
                    
                  <!--------------------------Images slider-------------------->
                  
                  
                  <div class="row">
                      
                      <div class="col col-md-12">
                          
                          <div class="sliderContainer">
                             
                              <div class="gd-slider">
                                <div class="gd-slides">
                                    
                                    <?php if($config['slider_img_1']!= ""){ ?>
                                      <a href="<?= $external_link; ?>" target="_blank" class="gd-slide" ><img src="images/slider_images/<?= $config['slider_img_1']; ?>" alt=""></a>  
                                    <?php } ?>
                                    
                                    
                                    <?php if($config['slider_img_2']!= ""){ ?>
                                      <a href="<?= $external_link; ?>" target="_blank" class="gd-slide" ><img src="images/slider_images/<?= $config['slider_img_2']; ?>" alt=""></a>  
                                    <?php } ?>
                                    
                                    
                                    
                                    <?php if($config['slider_img_3']!= ""){ ?>
                                      <a href="<?= $external_link; ?>" target="_blank" class="gd-slide" ><img src="images/slider_images/<?= $config['slider_img_3']; ?>" alt=""></a>  
                                    <?php } ?>
                                    
                                    
                                    <?php if($config['slider_img_4']!= ""){ ?>
                                      <a href="<?= $external_link; ?>" target="_blank" class="gd-slide" ><img src="images/slider_images/<?= $config['slider_img_4']; ?>" alt=""></a>  
                                    <?php } ?>
                                    
                                    
                                    
                                    <?php if($config['slider_img_5']!= ""){ ?>
                                      <a href="<?= $external_link; ?>" target="_blank" class="gd-slide" ><img src="images/slider_images/<?= $config['slider_img_5']; ?>" alt=""></a>  
                                    <?php } ?>
                                    
                                    
                                </div>
                                <button class="gd-prev" onclick="gdPrevSlide()">&#10094;</button>
                                <button class="gd-next" onclick="gdNextSlide()">&#10095;</button>
                            </div>
                              
                             <script>
                                let gdIndex = 0;
                                const gdSlides = document.querySelector(".gd-slides");
                                const gdTotalSlides = document.querySelectorAll(".gd-slide").length;
                                
                                function gdNextSlide() {
                                    gdIndex++;
                                    if (gdIndex >= gdTotalSlides) gdIndex = 0;
                                    gdSlides.style.transform = `translateX(-${gdIndex * 100}%)`;
                                }
                                
                                function gdPrevSlide() {
                                    gdIndex--;
                                    if (gdIndex < 0) gdIndex = gdTotalSlides - 1;
                                    gdSlides.style.transform = `translateX(-${gdIndex * 100}%)`;
                                }
                                
                                setInterval(gdNextSlide, 3000); // Auto slide every 3 seconds
                            </script>
                              
                          </div>
                          
                      </div>
                      
                  </div>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                    
                    
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
    <?php include("include/footer.php"); ?>

    <script src="assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <script src="assets/js/core/app-menu-lite.js" type="text/javascript"></script>
    <script src="assets/js/core/app-lite.js" type="text/javascript"></script>
</body>

</html>