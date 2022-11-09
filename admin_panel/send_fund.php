<?php include('../config/config.php'); 
include('includes/session.php');
include_once "../config/common.php";
$dbConn = establishcon();

	
	$success = 0;
	
	$STRIPE_API_KEY = STRIPE_API_KEY;

	require_once '../stripe/init.php';
	
	
	$stripe = new \Stripe\StripeClient(
        $STRIPE_API_KEY
      );
      
      $bal = $stripe->balance->retrieve();
      $chargeJson2 = $bal->jsonSerialize();
      $avl_balance = $chargeJson2['available']['0']['amount'];

	if(count($_POST) > 0){
		$amount = $_POST['amount'];

        if($avl_balance > ($amount*100)){
          // transfer to bank account

             $payout = $stripe->payouts->create([
              'amount' => ($amount*100),
              'currency' => 'nzd'
            ]);

            $res = $payout->jsonSerialize();
            if($res['id'] != "" && $res['failure_code'] == ""){
                echo "<script>location.href='send_fund.php?success=1'</script>";
                exit;
            }
            else{
                echo "<script>location.href='send_fund.php?error=1'</script>";
                exit;
            }
            
        }
        else{
            echo "<script>location.href='send_fund.php?error=2&balance=".$avl_balance."'</script>";
			exit;
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
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?php include_once "includes/header.php"; ?>

  <?php include_once "includes/leftBar.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
  <?php
	if(isset($_REQUEST['success']) && $_REQUEST['success']==1)
	{
	?>
	<div style="margin:5px 0; padding:10px; background:#C1DACA; border:1px solid #2C6035; color:#2C6035; font-weight:bold;">Fund sent to bank account successfully.</div>
	<?php }
	 if(isset($_REQUEST['error']) && $_REQUEST['error']==1)
     {
     ?>
     <div style="margin:5px 0; padding:10px; background:#F3D6D6; border:1px solid #f00; color:#f00; font-weight:bold;">Sorry. Some problem occurs.</div>
     <?php }
    if(isset($_REQUEST['error']) && $_REQUEST['error']==2)
	{
    $balance = $_REQUEST['balance']/100;
	?>
	<div style="margin:5px 0; padding:10px; background:#F3D6D6; border:1px solid #f00; color:#f00; font-weight:bold;">Sorry. Your available balance is <?php echo $balance;?> NZD</div>
	<?php }
	?>
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Send fund to bank</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Send fund to bank</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	
        <!-- Main row -->
        <div class="row">
          
          <section class="col-lg-12 connectedSortable">

            <!-- Map card -->
            <div class="card masteraudit">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-edit"></i>
                  Available balance: <?php echo ($avl_balance/100);?> NZD.
                </h3>
                <!-- card tools -->
                <div class="card-tools">                  
                  <button type="button"
                          class="btn btn-primary btn-sm"
                          data-card-widget="collapse"
                          data-toggle="tooltip"
                          title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
				  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <form role="form" id="quickForm" method="post" action="">
              <div class="card-body">
					<div class="row">
                        <div class="col-sm-6">
						  <div class="form-group">
                          <label for="">Enter amount to send to bank account</label>
                          <br />
                          <input type="text" name="amount" class="form-control required number">
							</div>
						</div>
					</div>
                    <div class="row">
                        <div class="col-sm-6">
						  <div class="form-group">
                          <button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>
					</div>
                    
            </div>
            </form>
              <!-- /.card-body-->
             
            </div>
            <!-- /.card -->

          </section>
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

<!-- overlayScrollbars -->
<script src="js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="js/dashboard.js"></script>-->
<!-- AdminLTE for demo purposes -->
<script src="js/demo.js"></script>
<script>
function onError(data, status) {
    alert('Network error has occurred, please try again!');
}
$(function(){
  $('#quickForm').validate();
    
});

</script>
</body>
</html>
