<?php $activePage=basename($_SERVER['PHP_SELF']);

$checkuser = "SELECT `type` from `users` where id = '".$_SESSION['adminUserId']."'";
$sql_res = dbQuery($dbConn,$checkuser);
$sql_res_fetch = dbFetchAssoc($sql_res);
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Mystery Audit</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/avatar3.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo ucfirst(@$_SESSION['adminUser']); ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <?php
          if($sql_res_fetch["type"] == 0){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "users.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="users.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="user_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add User</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "districts.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
                Districts
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="districts.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Districts</p>
                </a>
              </li>
              <?php
            if($sql_res_fetch["type"] == 0 || $sql_res_fetch["type"] == 1){
            ?>
              <li class="nav-item">
                <a href="dist_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add District</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "areas.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
                Areas
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="areas.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Areas</p>
                </a>
              </li>
              <?php
            if($sql_res_fetch["type"] == 0 || $sql_res_fetch["type"] == 1){
            ?>
              <li class="nav-item">
                <a href="area_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Area</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "companies.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
               Brands
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="companies.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Brands</p>
                </a>
              </li>
              <?php
            if($sql_res_fetch["type"] == 0 || $sql_res_fetch["type"] == 1){
            ?>
              <li class="nav-item">
                <a href="comp_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Brand</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "distributors.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
               Distributors
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="distributors.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Distributors</p>
                </a>
              </li>
              <?php
            if($sql_res_fetch["type"] == 0 || $sql_res_fetch["type"] == 1){
            ?>
              <li class="nav-item">
                <a href="distributor_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Distributor</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "outlets.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
               Outlets
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="outlets.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Outlets</p>
                </a>
              </li>
              <?php
            if($sql_res_fetch["type"] == 0 || $sql_res_fetch["type"] == 1){
            ?>
              <li class="nav-item">
                <a href="outlet_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Outlet</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "models.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
               Models
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="models.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Models</p>
                </a>
              </li>
              <?php
            if($sql_res_fetch["type"] == 0 || $sql_res_fetch["type"] == 1){
            ?>
              <li class="nav-item">
                <a href="model_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Model</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "assign_auditor.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
               Assigning Auditors
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="assign_auditor.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p> Assigned Auditors</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "entries.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
               Auditor Entries
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="entries.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p> Auditor Entries</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
          if($sql_res_fetch["type"] == 1){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "daily_report.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
               Daily Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="daily_report.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p> Daily Report</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
          }
          ?>

          <!--<li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Widgets
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>-->
		      <!--<li class="nav-header">EXAMPLES</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Calendar
                <span class="badge badge-info right">2</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-image"></i>
              <p>
                Gallery
              </p>
            </a>
          </li>-->
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-plus-square"></i>
              <p>
                Extras
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!--<li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Change Password</p>
                </a>
              </li>-->
              <li class="nav-item">
                <a href="logout.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Logout</p>
                </a>
              </li>
              
            </ul>
          </li>
          
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>