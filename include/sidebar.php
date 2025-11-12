<div  class="main-menu menu-fixed menu-light menu-accordion menu-shadow " data-scroll-to-active="true" data-img="assets/images/backgrounds/02.jpg">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">       
            <img style='width:150px;height:40px;margin-top:20px;' class="brand-logo" alt="GD admin logo" src="images/<?= $main_logo; ?>"/>
         
        </ul>
    </div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li id="sid-main" ><a href="index.php"><i <?= $style; ?> class="ft-home"></i><span class=" menu-title" data-i18n="">Dashboard</span></a>
            </li>
            <li  id="sid-add" class=" nav-item"><a href="add-license.php"><i <?= $style; ?>  class="fa fa-key" aria-hidden="true"></i><span class="menu-title" data-i18n="">Create License</span></a>
            </li>
            <li id="sid-all" class=" nav-item"><a href="all-licenses.php"><i <?= $style; ?> class="fa fa-database" aria-hidden="true"></i><span class="menu-title" data-i18n="">All Licenses</span></a>
            </li>
            
            <?php 
            if ($_SESSION['user_type'] == 'super_admin') { 
                // Super Admin: Can add admin, reseller, and view all admins
            ?>
                <li id="sid-radd" class="nav-item"><a href="add-reseller.php"><i <?= $style; ?> class="fa fa-user-circle-o" aria-hidden="true"></i><span class="menu-title" data-i18n="">Add Reseller</span></a>
                </li>
                <li id="sid-rall" class="nav-item"><a href="all-resellers.php"><i <?= $style; ?> class="ft-list"></i><span class="menu-title" data-i18n="">All Resellers</span></a>
                </li>
                <li id="sid-radd-admin" class="nav-item"><a href="add-admin.php"><i <?= $style; ?> class="fa fa-compass" aria-hidden="true"></i><span class="menu-title" data-i18n="">Add Admin</span></a>
                </li>
                <li id="sid-rall-admin" class="nav-item"><a href="all-admins.php"><i <?= $style; ?>  class="fa fa-server" aria-hidden="true"></i><span class="menu-title" data-i18n="">All Admins</span></a>
                </li>
                <li id="sid-configuration" class="nav-item"><a href="configuration.php"><i <?= $style; ?> class="fa fa-cogs" aria-hidden="true"></i><span class="menu-title" data-i18n="">Settings</span></a>
                </li>
            <?php 
            } elseif ($_SESSION['user_type'] == 'admin') { 
                // Admin: Can add reseller and view all resellers only (no access to admin functionalities)
            ?>
                <li id="sid-radd" class="nav-item"><a href="add-reseller.php"><i <?= $style; ?> class="ft-plus-circle"></i><span class="menu-title" data-i18n="">Add Reseller</span></a>
                </li>
                <li id="sid-rall" class="nav-item"><a href="all-reseller.php"><i <?= $style; ?> class="ft-list"></i><span class="menu-title" data-i18n="">All Resellers</span></a>
                </li>
            <?php 
            }
            ?>
            <li id="sid-rall-download_ex" class="nav-item"><a href="/downloads/<?= $extension_file; ?>"><i <?= $style; ?> class="ft-download"></i><span class="menu-title" data-i18n="">Extension</span></a>
                </li>
                <li id="sid-change-pass" class="nav-item"><a href="change_password.php"><i <?= $style; ?> class="fa fa-lock"></i><span class="menu-title" data-i18n="">Change Password</span></a>
                </li>
                <li id="sid-logout" class="nav-item"><a href="logout.php"><i <?= $style; ?> class="fa fa-sign-out"></i><span class="menu-title" data-i18n="">Logout</span></a>
                </li>
        </ul>
    </div>
    
</div>

