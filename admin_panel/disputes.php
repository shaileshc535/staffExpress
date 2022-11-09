<?php 
	include('../config/config.php');
	include('includes/session.php');
	include_once "../config/common.php";
	$dbConn = establishcon();

  /*if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
    $id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):'';
    dbQuery($dbConn,"delete from qualifictn_required where jobid = '".$id."'");
    dbQuery($dbConn,"delete from job_details where id = '".$id."'");
    echo "<script>location.href='jobs.php'</script>";
		exit;
	}*/
	
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
            <h1 class="m-0 text-dark">Disputes</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Disputes</li>
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
	<div style="margin:5px 0; padding:10px; background:#C1DACA; border:1px solid #2C6035; color:#2C6035; font-weight:bold;">Job is updated.</div>
	<?php }
	?>
    
	<!--<button type="button" class="btn btn-info float-right" onclick="javascript:window.location = 'equip_manage.php';"><i class="fas fa-plus"></i> Add Equipment</button>-->
        
        <!-- Main row -->
        <div class="row listing">
	
        <table id="example" class="display" cellspacing="0" width="100%">
          <thead>
            <tr>
                <th>Jobs</th>
                <th>Raised By</th>
                <th>Raised Against</th>
                <th>Reason</th>
                <th>Posted On</th>
                <!--<th>Action</th>-->
            </tr>
          </thead>
          <tbody>
           <?php
           
        $myjob = dbQuery($dbConn, "SELECT a.id,a.reason,a.senton,a.other_reason,a.raisedusertype,b.title,c.name as raisedagainst,c.email as againstemail,c.phone as againstphone,d.name as raisedby,d.email as byemail,d.phone as byphone FROM disputes a inner join job_details b on a.job_id=b.id inner join users c on a.raisedagainst=c.id inner join users d on a.raisedby=d.id order by a.id desc");
        while($sql_res_fetch = dbFetchAssoc($myjob))
        {
            if($sql_res_fetch['other_reason'])
            $reason = $sql_res_fetch['other_reason'];
            else{
                if($sql_res_fetch['reason'] == 1)
                $reason = "Employee not turning up to job";
                else if($sql_res_fetch['reason'] == 2)
                $reason = "Employee not performing to standards";
                else if($sql_res_fetch['reason'] == 3)
                $reason = "Employee not qualified as stated";
                else if($sql_res_fetch['reason'] == 5)
                $reason = "Work location not as described";
                else if($sql_res_fetch['reason'] == 6)
                $reason = "Insufficient training/support on site";
                else if($sql_res_fetch['reason'] == 7)
                $reason = "Tasks not as described";
            }
		?>
            <tr>
             	<td><?php echo stripslashes($sql_res_fetch["title"]); ?></td>
                <td><?php echo stripslashes($sql_res_fetch["raisedby"]); ?> (<?php echo ($sql_res_fetch["raisedusertype"]==2)?"Employee":"Employer";?>)<br>
                <?php echo $sql_res_fetch['byemail'];?><br>Phone: <?php echo $sql_res_fetch['byphone'];?>
                </td>
                <td><?php echo stripslashes($sql_res_fetch["raisedagainst"]); ?><br>
                <?php echo $sql_res_fetch['againstemail'];?><br>Phone: <?php echo $sql_res_fetch['againstphone'];?></td>
                <td><?php echo $reason;?></td>

                <td><?php echo date("M j, Y", strtotime($sql_res_fetch["senton"])); ?></td>

				<!--<td><a title="View" href="order_view.php?id=<?php echo base64_encode($sql_res_fetch['id']);?>"><img src="dist/img/detailview.png" alt="" width="20"></a>&nbsp;&nbsp;
				
				</td>-->
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
