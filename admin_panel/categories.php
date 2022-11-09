<?php 
	include('../config/config.php');
	include('includes/session.php');
	include_once "../config/common.php";
	$dbConn = establishcon();
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
    $id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):'';
    
      dbQuery($dbConn,"delete from qualifications where catid = '".$id."'");
	   $alljobs = dbQuery($dbConn,"SELECT jobid FROM job_parent_cat where catid = '".$id."'");
	   while($fetch = dbFetchAssoc($alljobs)){
			dbQuery($dbConn,"delete from job_details where id = '".$fetch['jobid']."'");
		}
	   
	  dbQuery($dbConn,"delete from job_parent_cat where catid = '".$id."'");
	  dbQuery($dbConn,"delete from category where parent_id = '".$id."'");
      dbQuery($dbConn,"delete from category where id = '".$id."'");
      echo "<script>location.href='categories.php'</script>";
		exit;
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
  <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
  
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php include_once "includes/header.php"; ?>

  <?php include_once "includes/leftBar.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Categories</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	<?php
  
	if(isset($_REQUEST['success']) && $_REQUEST['success']==2)
	{
	?>
	<div style="margin:5px 0; padding:10px; background:#C1DACA; border:1px solid #2C6035; color:#2C6035; font-weight:bold;">Category is updated.</div>
	<?php }
	if(isset($_REQUEST['success']) && $_REQUEST['success']==1)
	{
	?>
	<div style="margin:5px 0; padding:10px; background:#C1DACA; border:1px solid #2C6035; color:#2C6035; font-weight:bold;">Category is added.</div>
	<?php } ?>
    
	<button type="button" class="btn btn-info float-right" onclick="javascript:window.location = 'cat_manage.php';"><i class="fas fa-plus"></i> Add Category</button>
        
        <!-- Main row -->
        <div class="row listing">
	
    
        <table id="example" class="display" cellspacing="0" width="100%">
          <thead>
            <tr>
                <th>Category</th>
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
           <?php
				$sql = "SELECT * from `category` where parent_id = 0 order by id desc";
				$sql_res = dbQuery($dbConn,$sql);
				while($sql_res_fetch = dbFetchAssoc($sql_res))
				{
					
		   ?>
            <tr>
             	<td><?php echo stripslashes($sql_res_fetch["category"]); ?></td>
				<td><a title="Edit" href="cat_manage.php?id=<?php echo base64_encode($sql_res_fetch['id']);?>"><img src="dist/img/edit.png" alt="" width="20"></a>&nbsp;&nbsp;<a title="Delete" href="categories.php?id=<?php echo base64_encode($sql_res_fetch['id']);?>&action=delete" onClick="return confirm('Are you sure to delete this Category? Deleting this category will also delete the Subcategories and Jobs under it.');"><img src="dist/img/delete.png" alt="" width="20"></a>
				
				</td>
            </tr>
           <?php } ?>
          </tbody>
        </table>
       		
<!-- right col -->
</div>
<!-- /.row (main row) -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include_once ("includes/footer.php"); ?>
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery.validate.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="js/Chart.min.js"></script>

<!-- Summernote -->
<script src="js/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="js/dashboard.js"></script>-->
<!-- AdminLTE for demo purposes -->
<script src="js/demo.js"></script>
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
<script>
			$(document).ready(function() {
				$('#example').DataTable({
							  "bSort" : false
							});
			} );
		</script>
</body>
</html>
