<?php include('../config/config.php'); 
include_once "../config/common.php";
$dbConn = establishcon();
if(isset($_SESSION['msg']) && $_SESSION['msg']!=''){ $_SESSION['msg']=''; }

//if(isset($_SESSION['adminUserId']) && $_SESSION['adminUserId']!=''){ echo "<script>location.href='index.php'</script>"; exit;	}

if(count($_POST)>0)
{
	
	$email=isset($_POST['email'])?$_POST['email']:"";
	$password=isset($_POST['password'])?$_POST['password']:"";
	$encPassword = md5($password);
	$LogSql=dbQuery($dbConn,"select id,name from `admin_user` where `username`='".tep_db_input($email)."' and `password`='".$encPassword."'");
	$RsSql=dbFetchObject($LogSql);
      if(dbNumRows($LogSql)>0)
			{
				$_SESSION['adminUserId']=$RsSql->id;
				$_SESSION['adminUser']=$RsSql->name;
				echo "<script>location.href='index.php'</script>";
				exit;
			}
			else
			{
        $_SESSION['msg']='The login details that you have entered are invalid!';
			}

}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo FOOTERTITLE;?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="css/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="css/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="css/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="css/summernote-bs4.css">
  
  <!---- custom style ----->
  <link rel="stylesheet" href="css/style.css">
  
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini" style="background-color:#f4f6f9;">
<div class="wrapper">
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper_login">
    <!-- Content Header (Page header) -->
   
    <!-- Main content -->
    <section class="content">
      <div class="container">
        <div class="row">
          <!-- left column -->
          <div class="col-sm-6 offset-3">
			<div class="logomainlogin">
				<img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image" style="width:30%;" />
			</div>
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Login</h3>
              </div>
              <!-- /.card-header -->
              <?php
              if($_SESSION['msg'] != ""){
                echo '<div class="alert-danger" style="padding:15px;">'.$_SESSION['msg'].'</div>';
                $_SESSION['msg'] = '';
              }
              ?>
              <!-- form start -->
              <form role="form" id="quickForm" method="post" action="">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                    <input type="text" name="email" class="form-control required" id="exampleInputEmail1" placeholder="Username">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control required" id="exampleInputPassword1" placeholder="Password">
                  </div>
                  
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
         
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="footer">
    <p style="text-align:center;padding-top:30px;"><strong>Copyright &copy; <?php echo date('Y');?>-<?php echo (date('Y')+1);?> <a href="#"><?php echo FOOTERTITLE;?></a>.</strong> All rights reserved.</p>
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script>
$(function(){
  $('#quickForm').validate();
});
</script>
<?php closeconn($dbConn); ?>
</body>
</html>
