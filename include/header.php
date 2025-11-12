<?php


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


?>

<!-----------------Custom css ------------------>
<style>
/*======== Messge box ok button -------*/
    .swal2-confirm.swal2-styled {
                background:<?= $color_background; ?> !important; 
                color: white !important;           
                border: none;
                box-shadow: none;
            }
            
            
            
        /* ✅ Change outer ring color */
        .swal2-icon.swal2-success {
          border-color: <?= $color_background; ?> !important; /* Your desired color */
        }
        
        /* ✅ Change the checkmark lines */
        .swal2-success-line-tip,
        .swal2-success-line-long {
          background-color: <?= $color_background; ?> !important;  /* Your desired color */
        }
        
        /* ✅ Change animated ring */
        .swal2-success-ring {
          border: 4px solid <?= $color_background; ?> !important;  /* Your desired color */
        }
</style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="2-columns">


    <nav  class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light">
      <div  class="navbar-wrapper">
        <div <?= $style; ?> class="navbar-container content">
          <div class="collapse navbar-collapse show" id="navbar-mobile">
            <ul class="nav navbar-nav mr-auto float-left">
              <li class="nav-item d-block d-md-none"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
              <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
              <li  class="nav-item dropdown navbar-search"><a class="nav-link dropdown-toggle hide" data-toggle="dropdown" href="#"><i class="ficon ft-search"></i></a>
                <ul class="dropdown-menu">
                  <li class="arrow_box">
                    <form>
                      <div  class="input-group search-box">
                        <div class="position-relative has-icon-right full-width">
                          <input class="form-control" id="search" type="text" placeholder="Search here...">
                          <div class="form-control-position navbar-search-close"><i class="ft-x">   </i></div>
                        </div>
                      </div>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
            
            <ul class="nav navbar-nav float-right">
             
              <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="avatar avatar-online"><img src="images/<?= $main_logo; ?>" alt="avatar"><i></i></span></a>
                <div class="dropdown-menu dropdown-menu-right">
                  <div class="arrow_box_right">
                    <a class="dropdown-item" href="#"><span class="avatar avatar-online"><span class="user-name text-bold-400 ml-1" style='font-weight:bold;'><?= $website_name; ?></span></span></a>
                  
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="change_password.php"><i class=""></i> Change Password</a>
                    <a class="dropdown-item" href="logout.php"><i class="ft-power"></i> Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
