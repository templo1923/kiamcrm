<?php
include("include/conn.php");
include("include/function.php");
$login = cekSession();






//if your not login so rediret to login page
if ($login != 1) {
    redirect("login.php");
}




if (isset($_POST['save'])) {
    
    $msg = "<span style='color:red;font-weight:bold;'>Wait : </span><span style='color:gray;'>Your Setting saving......</span>";




    // Helper function to update config safely
    function updateConfig($key, $value, $conn) {
        $stmt = $conn->prepare("UPDATE `config` SET `config_value` = ? WHERE `config_key` = ?");
        $stmt->bind_param("ss", $value, $key);
        $stmt->execute();
        $stmt->close();
    }



    // Filter & Update text inputs
    $website_name = filter_input(INPUT_POST, 'website_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!empty($website_name)) updateConfig('site_name', $website_name, $conn);
    
    

    $support_mobile = filter_input(INPUT_POST, 'support_mobile', FILTER_SANITIZE_NUMBER_INT);
    if (!empty($support_mobile)) updateConfig('support_mobile', $support_mobile, $conn);
    
    
     $trial_days = filter_input(INPUT_POST, 'trial_days', FILTER_SANITIZE_NUMBER_INT);
    if (!empty($trial_days)) updateConfig('trial_days', $trial_days, $conn);
    
    

    $support_email = filter_input(INPUT_POST, 'support_email', FILTER_SANITIZE_EMAIL);
    if (!empty($support_email)) updateConfig('support_email', $support_email, $conn);
    
    

    $bg_color = filter_input(INPUT_POST, 'bg_color', FILTER_SANITIZE_STRING);
    if (!empty($bg_color)) updateConfig('color_background', $bg_color, $conn);
    
    

    $txt_color = filter_input(INPUT_POST, 'txt_color', FILTER_SANITIZE_STRING);
    if (!empty($txt_color)) updateConfig('color_text', $txt_color, $conn);
    
    

    $external_link = filter_input(INPUT_POST, 'external_link', FILTER_SANITIZE_URL);
    if (!empty($external_link)) updateConfig('external_link', $external_link, $conn);
    
    

    // Handle file uploads (image, extension)
    function handleFileUpload($fileKey, $targetFolder, $configKey, $conn) {
        if (!empty($_FILES[$fileKey]['name'])) {
            $originalName = basename($_FILES[$fileKey]['name']);
            $safeName = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\.\-]/', '_', $originalName);
            $targetPath = $targetFolder . $safeName;

            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetPath)) {
                updateConfig($configKey, $safeName, $conn);
            }
        }
    }
    
    

    handleFileUpload('main_logo', 'images/', 'main_logo', $conn);
    handleFileUpload('favicon_logo', 'images/', 'favicon_logo', $conn);
    handleFileUpload('extension_file', 'downloads/', 'extension_file_name', $conn);
    
    
    

    // Slider Images
    for ($i = 1; $i <= 5; $i++) {
        handleFileUpload("slider_img_$i", 'images/slider_images/', "slider_img_$i", $conn);
    }
    
    
    

   
  echo "<script>
    
    setTimeout(function() {
        successNotification('Saved','Setting saved successfully !!');
    }, 1500);
</script>";

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
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- END Custom CSS-->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    
</head>

<body>
<?php
include("include/header.php");
include("include/sidebar.php");
?>
<style>
    #response-message {
        text-align: center;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-top: 20px;
        font-size: 18px;
        font-weight: bold;
    }

    .success {
        background-color: #4CAF50;
        color: #fff;
        border: 2px solid #45A049;
    }

    .error {
        background-color: #FF5733;
        color: #fff;
        border: 2px solid #D73925;
    }
    
    .box {
      background-color: gray;
      color: white;
      padding: 13px;
      text-align: center;
      border-radius: 5px;
      margin-bottom: 15px;
      border:1px dashed <?= $color_text; ?>;
    }
    .box_2 {
      background-color: #4f4f4f;
      color: white;
      padding: 12px;
      text-align: center;
      border-radius: 5px;
      margin-bottom: 15px;
      border:1px dashed <?= $color_text; ?>;
    }
    
    .box:hover {
      background-color: <?= $color_background; ?>;
      cursor: pointer;
      color: <?= $color_text; ?>;
      transform: translateY(-4px) scale(1.03);
      transition: all 0.35s ease-in-out;
      box-shadow: 0 12px 25px rgba(0,0,0,0.2);
      border-radius: 12px;
      margin-bottom: 15px;
    }
    
    .box_2:hover {
      background-color: <?= $color_background; ?>;
      cursor: pointer;
      color: <?= $color_text; ?>;
      transform: translateY(-4px) scale(1.03);
      transition: all 0.35s ease-in-out;
      box-shadow: 0 12px 25px rgba(0,0,0,0.2);
      border-radius: 12px;
      margin-bottom: 15px;
    }

</style>

<div class="app-content content">
    <div class="content-wrapper">
        <div  <?= $style; ?> class="content-wrapper-before"></div>
        <div class="content-header row">
            <div class="content-header-left col-md-4 col-12 mb-2">
                <h3 class="content-header-title">Configuration</h3>
            </div>
            <div class="content-header-right col-md-8 col-12">
                <div class="breadcrumbs-top float-md-right">
                    <div class="breadcrumb-wrapper mr-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Configuration</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        
  
        
        
        
        
        
        
        
        <div class="content-body"><!-- Basic Inputs start -->

            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card">
                    
                    <div class="card-header">
                        <h4 class="card-title">Configuration</h4>
                    </div>
                    <div class="card-block">
                        <form action="#" method='POST'  enctype="multipart/form-data">
                            <div class="card-body">
                                
                                
                                <div class="container my-4">
                                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                                        
                                    <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                                <h6 class="card-title">Website Name</h6>
                                                <input type="text" class="form-control" id="website_name" name="website_name" placeholder='' value='<?= $website_name; ?>'>
                                          </fieldset>
                                      </div></div>
                                        
                                        
                                      <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                                <h6 class="card-title">Support Mobile</h6>
                                                <input type="number" class="form-control" id="support_mobile" name="support_mobile" placeholder='' value='<?= $supportPhoneNumber; ?>'>
                                          </fieldset>
                                      </div></div>
                                      
                                      
                                      <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Support Email</h6>
                                            <input type="text" class="form-control" id="support_email" name="support_email" placeholder='' value='<?= $support_email; ?>'>
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                       <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">External Link</h6>
                                            <input type="text" class="form-control" id="external_link" name="external_link" placeholder='' value='<?= $external_link; ?>'>
                                        </fieldset>
                                      </div></div>
                                      
                                      <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Trial Days</h6>
                                            <input type="number" class="form-control" id="trial_days" name="trial_days" placeholder='' value='<?= $trial_days; ?>'>
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                      
                                      <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Background Color</h6>
                                            <input type="color"  id="bg_color" name="bg_color" value='<?= $color_background; ?>' >
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                      
                                      <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Text Color</h6>
                                            <input type="color"  id="txt_color" name="txt_color"  value='<?= $color_text; ?>'>
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                      <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Main Logo (280 × 90 )</h6>
                                            <input type="file"  id="main_logo" name="main_logo"  >
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                       <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Favicon Icon</h6>
                                            <input type="file"  id="favicon_logo" name="favicon_logo"  >
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                      <div class="col"><div class="box">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Upload Extension</h6>
                                            <input type="file"  id="extension_file" name="extension_file"  >
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                      <!---------------Slider Images ----------------------->
                                      <div class="col"><div class="box_2">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Slider Image 1</h6>
                                            <input type="file"  id="slider_img_1" name="slider_img_1"  >
                                        </fieldset>
                                      </div></div>
                                      
                                      <div class="col"><div class="box_2">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Slider Image 2</h6>
                                            <input type="file"  id="slider_img_2" name="slider_img_2"  >
                                        </fieldset>
                                      </div></div>
                                      
                                      <div class="col"><div class="box_2">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Slider Image 3</h6>
                                            <input type="file"  id="slider_img_3" name="slider_img_3" >
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                      <div class="col"><div class="box_2">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Slider Image 4</h6>
                                            <input type="file"  id="slider_img_4" name="slider_img_4"  >
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                      
                                      <div class="col"><div class="box_2">
                                          <fieldset class="form-group">
                                            <h6 class="card-title">Slider Image 5</h6>
                                            <input type="file"  id="slider_img_5" name="slider_img_5"  >
                                        </fieldset>
                                      </div></div>
                                      
                                      
                                      
                                      
                                      
                                    </div>
                                 </div>
                                
                                
                                <br>
                                <fieldset class="form-group">
                                    <button  <?= $style; ?> type="submit" id="save-config" class="btn btn-info btn-min-width mr-1 mb-1" name='save'>Save Setting</button>
                                </fieldset>
                            </div>
                        </form>
                        <!--<div id="response-message"><?= $msg; ?></div>-->

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
<!-- BEGIN PAGE VENDOR JS-->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- BEGIN CHAMELEON  JS-->
<script src="assets/js/core/app-menu-lite.js" type="text/javascript"></script>
<script src="assets/js/core/app-lite.js" type="text/javascript"></script>
<!-- END CHAMELEON  JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="assets/vendors/js/forms/tags/form-field.js" type="text/javascript"></script>
<!-- END PAGE LEVEL JS-->











</body>

</html>
