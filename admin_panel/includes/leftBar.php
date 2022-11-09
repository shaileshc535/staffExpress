<?php $activePage=basename($_SERVER['PHP_SELF']);

?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!--<a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
    </a>-->

    <!-- Sidebar -->
    <div class="sidebar blueback">
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
              <!--<li class="nav-item">
                <a href="user_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add User</p>
                </a>
              </li>-->
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "pages.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
                Pages
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Pages</p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "categories.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
               Categories
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="categories.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p> Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="subcat.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p> Subcategories</p>
                </a>
              </li>
             
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "jobs.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
               Jobs
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="jobs.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p> All Jobs</p>
                </a>
              </li>
            </ul>
          </li>

          <!--<li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if($activePage == "disputes.php") echo 'active';?>">
              <i class="nav-icon fas fa-mobile-alt"></i>
              <p>
              Disputes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="disputes.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p> All Disputes</p>
                </a>
              </li>
            </ul>
          </li>-->

          <li class="nav-item">
              <a href="logout.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Logout</p>
                </a>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>