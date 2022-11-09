<?php 
	include('../config/config.php');
	include('includes/session.php');
	include_once "../config/common.php";
	$dbConn = establishcon();

  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
    $id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):'';
    dbQuery($dbConn,"delete from qualifictn_required where jobid = '".$id."'");
	dbQuery($dbConn,"delete from job_cat where jobid = '".$id."'");
	dbQuery($dbConn,"delete from job_parent_cat where jobid = '".$id."'");
    dbQuery($dbConn,"delete from job_details where id = '".$id."'");
    echo "<script>location.href='jobs.php'</script>";
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
            <h1 class="m-0 text-dark">Jobs</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Jobs</li>
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
                <th>Title</th>
                <th>Employer</th>
                <th>Job Date</th>
                <th>Type</th>
                <th>Added On</th>
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
           <?php
           $myshifts_str = '';
        $myjob = dbQuery($dbConn, "SELECT a.id,a.title,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.add_time,a.total_amt,a.postdate,a.covertype,a.is_shift,a.shifttype,b.name FROM job_details a inner join users b on a.employer_id=b.id order by a.id desc");
		while($sql_res_fetch = dbFetchAssoc($myjob))
		{
			if($sql_res_fetch['total_amt'] == 30)
				$jobtype = "Classic";
			else if($sql_res_fetch['total_amt'] == 33)
				$jobtype = "Premium";
			else
				$jobtype = "Not completed";
			
          $josbstat = dbQuery($dbConn, "SELECT id	from job_status where jobid = '".$sql_res_fetch['id']."' and contacting=1");
          $no_contacting = dbNumRows($josbstat);
          $josbstat2 = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$sql_res_fetch['id']."' and hired=1");
          $no_hired = dbNumRows($josbstat2);
		      ?>
            <tr>
             	<td><?php echo stripslashes($sql_res_fetch["title"]); ?><br>
               (Contacting: <?php echo $no_contacting;?>, Hired: <?php echo $no_hired;?>)
               </td>
                <td><?php echo stripslashes($sql_res_fetch["name"]); ?></td>
                <td><?php 
                if($sql_res_fetch['covertype']==1)
                echo date('M j, Y', strtotime($sql_res_fetch['jobdate']));?>
				<?php
				if($sql_res_fetch['add_time'] == 1){
				?>
				at <?php echo date('h:i A', strtotime($sql_res_fetch['starttime']));?>
				<?php
				}
				?>
				<?php 
        if($sql_res_fetch['covertype']==1)
        echo " - ".date('M j, Y', strtotime($sql_res_fetch['jobdate2']));?>
				<?php
				if($sql_res_fetch['add_time'] == 1){
				?>
				at <?php echo date('h:i A', strtotime($sql_res_fetch['endtime']));?>
				<?php
				}
        if($sql_res_fetch['is_shift'] == 1){
          $shifttype = explode(",", $sql_res_fetch['shifttype']);
          $myshifts = array();
          foreach($shifttype as $val){
            if($val == '1')
              $value = "Day Shift";
            if($val == '2')
              $value = "Night Shift";
            if($val == '3')
              $value = "Overnight Shift";
            
            $myshifts[] = $value;
          }
          if(count($myshifts) > 0){
            $myshifts_str = implode(", ", $myshifts);
          }
          echo "&nbsp;&nbsp;(".$myshifts_str.")";
        }
		else if($sql_res_fetch['is_shift'] == 2){
			echo "No shift";
		}
				?>
				</td>
                <td><?php echo $jobtype;?></td>
                <td><?php echo date("M j, Y", strtotime($sql_res_fetch["postdate"])); ?></td>

				<td><!--<a title="View" href="order_view.php?id=<?php echo base64_encode($sql_res_fetch['id']);?>"><img src="dist/img/detailview.png" alt="" width="20"></a>&nbsp;&nbsp;--><a title="Delete" href="jobs.php?id=<?php echo base64_encode($sql_res_fetch['id']);?>&action=delete" onClick="return confirm('Are you sure to delete this Job?');"><img src="dist/img/delete.png" alt="" width="20"></a>
				
				</td>
            </tr>
           <?php 
          $myshifts_str = '';
          } ?>
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
