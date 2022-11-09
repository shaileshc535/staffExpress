<?php include('../config/config.php'); 
include('includes/session.php');
include_once "../config/common.php";
$dbConn = establishcon();
/*if(count($_POST) > 0)
	{
    $violation_timing = isset($_POST['violation_timing'])?$_POST['violation_timing']:"";
    $outlet_issue = isset($_POST['outlet_issue'])?$_POST['outlet_issue']:"";
    
    
		$id = isset($_POST['id'])?$_POST['id']:"";
		if($id){
      dbQuery($dbConn,"UPDATE auditor_entry set violation_timing = '".$violation_timing."', outlet_issue = '".$outlet_issue."' where id = '".$id."'");
      echo "<script>location.href='entry_view.php?success=1&id=".base64_encode($id)."'</script>";
		  exit;
    }
    
	}*/
	
	$success = 0;
	
	$STRIPE_API_KEY = STRIPE_API_KEY;

	require_once '../stripe/init.php';
	
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == "addstripeacc"){
		$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):'';
		$sqlUser = "SELECT c.id,c.name,c.email,c.country,c.currency,c.accno,c.routing_no from `orders` a inner join users c on a.providerid=c.id where a.id = '".$id."'";
		$res = dbQuery($dbConn,$sqlUser);
		$fetch = dbFetchAssoc($res);
		
		$stripe = new \Stripe\StripeClient(
			$STRIPE_API_KEY
		  );
		  
      if($fetch['routing_no']){
        $account = $stripe->accounts->create([
        'type' => 'custom',
        'country' => $fetch['country'],
        'email' => $fetch['email'],
        'capabilities' => [
          'card_payments' => ['requested' => true],
          'transfers' => ['requested' => true],
        ],
        'external_account' => [
          'object' => 'bank_account',
          'country' => $fetch['country'],
          'currency' => $fetch['currency'],
          'account_holder_name' => $fetch['name'],
          'account_holder_type' => 'individual',
          'routing_number' => $fetch['routing_no'],
          'account_number' => $fetch['accno']
          ],
        ]);
      }
      else{
        $account = $stripe->accounts->create([
          'type' => 'custom',
          'country' => $fetch['country'],
          'email' => $fetch['email'],
          'capabilities' => [
            'card_payments' => ['requested' => true],
            'transfers' => ['requested' => true],
          ],
          'external_account' => [
            'object' => 'bank_account',
            'country' => $fetch['country'],
            'currency' => $fetch['currency'],
            'account_holder_name' => $fetch['name'],
            'account_holder_type' => 'individual',
            'account_number' => $fetch['accno']
            ],
          ]);
      }

		  $account_id = $account->id;
		  if($account_id != ""){
			  dbQuery($dbConn,"UPDATE users set stripe_account = '".$account_id."' where id = '".$fetch['id']."'");

        // mail to provider
        $to = $fetch['email'];
  
        $subject = "Account added to Stripe";

        $content='<table width="90%" border="0">
              <tr>
              <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
              <td colspan="2">Hi '.$fetch['name'].',</td>
              </tr>
              <tr>
              <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
              <td colspan="2">Your account has been added to Stripe successfully.</td>
              </tr>
              <tr><td colspan="2">&nbsp;</td></tr>
              <tr>
              <td colspan="2">Please enter your business details by Stripe Onboarding by going to Business Details menu after login to receive payment.</td>
              </tr>
              <tr><td colspan="2">&nbsp;</td></tr>
              <tr>
              <td colspan="2">Thanks,<br>GearXchange</td>
              </tr>
              </table>';

              $fromemail = ADMINEMAIL;
              $headers = "MIME-Version: 1.0" . "\r\n";
              $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
              $headers .= 'From: <'.$fromemail.'>' . "\r\n";
              mail($to, $subject, $content, $headers);
				echo "<script>location.href='order_view.php?success=1&id=".base64_encode($id)."'</script>";
				exit;
		  }
	}
	
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == "paytoprovider"){
		$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):'';
		$sqlUser = "SELECT a.order_total,a.orderID,a.currency,a.charge_id,c.id,c.name,c.email,c.country,c.stripe_account,c.routing_no,e.name as equipname from `orders` a inner join order_details b on a.id=b.order_id inner join users c on a.providerid=c.id inner join equipments e on b.equipmentid=e.id where a.id = '".$id."'";
		$res = dbQuery($dbConn,$sqlUser);
		$fetch = dbFetchAssoc($res);
		
		$stripe = new \Stripe\StripeClient(
		  $STRIPE_API_KEY
		);
		$myaccount = $stripe->accounts->retrieve(
		  $fetch['stripe_account'],
		  []
		);
		
		$chargeJson = $myaccount->jsonSerialize();
    $exaccid = $chargeJson['external_accounts']['data'][0]['id'];
		$cardpayment = $chargeJson['capabilities']['card_payments'];
    $transfers = $chargeJson['capabilities']['transfers'];
		
		// checking transfer is active
		if($cardpayment == "active" || $transfers == "active"){
			$providramt = number_format((($fetch["order_total"] * 90)/100),2);

        $bal = $stripe->balance->retrieve();
        $chargeJson2 = $bal->jsonSerialize();
        $avl_balance = $chargeJson2['available']['0']['amount'];
        //echo $avl_balance; die;

        // checking available balance is more than transfer amount or not
        if($avl_balance > ($providramt*100)){
          // transfer to provider account

          if($fetch['routing_no']){
            \Stripe\Stripe::setApiKey(
              $STRIPE_API_KEY
              );
            $payment_intent = \Stripe\PaymentIntent::create([
              'payment_method_types' => ['card'],
              'amount' => 200,
              'currency' => $fetch['currency'],
              'on_behalf_of' => $fetch['stripe_account']
              ]);

              $myresponse = $payment_intent->jsonSerialize();
          }
          else{
            $transfer = $stripe->transfers->create([
              'amount' => 200,
              'currency' => $fetch['currency'],
              'destination' => $fetch['stripe_account'],
              //'source_transaction' => $fetch['charge_id'],
              'transfer_group' => 'ORDER_'.$fetch['orderID'],
            ]);
  
            $chargeJson3 = $transfer->jsonSerialize();
            $transfer = $transfer->id;
          }
          
          /*$payout = $stripe->payouts->create([
            'amount' => 200,
            'currency' => $fetch['currency'],
            'destination' => $exaccid
          ],
          ['stripe_account' => $fetch['stripe_account']]
          );

          $res = $payout->jsonSerialize();
            echo "<pre>";
            print_r($res);
            die;*/

          if($transfer != "" || $myresponse['id'] != ""){
            dbQuery($dbConn,"UPDATE orders set isproviderpaid = 1, providr_amount = '".$providramt."', provdr_pay_date = '".date('Y-m-d')."' where id = '".$id."'");
            // mail to provider
          $to = $fetch['email'];
    
          $subject = "Transfer initiated";

          $content='<table width="90%" border="0">
                <tr>
                <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="2">Hi '.$fetch['name'].',</td>
                </tr>
                <tr>
                <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="2">Transfer of amount '.strtoupper($fetch['currency']).' '.$providramt.' has been initiated to your account for your equipment: '.$fetch['equipname'].'.</td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                <td colspan="2">The amount will be credited to your account within 5-10 business days.</td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                <td colspan="2">Thanks,<br>GearXchange</td>
                </tr>
                </table>';

                $fromemail = ADMINEMAIL;
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <'.$fromemail.'>' . "\r\n";
                mail($to, $subject, $content, $headers);
            echo "<script>location.href='order_view.php?success=2&id=".base64_encode($id)."'</script>";
            exit;
          }
          else{
            echo "<script>location.href='order_view.php?error=3&id=".base64_encode($id)."'</script>";
			      exit;
          }
            
            /*$ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/accounts/'.$fetch['stripe_account'].'');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

            curl_setopt($ch, CURLOPT_USERPWD, $STRIPE_API_KEY . ':' . '');

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            echo "<pre>";
            print_r($result);
            die;
            */
          
          //}
        }
        else{
          echo "<script>location.href='order_view.php?error=2&id=".base64_encode($id)."&balance=".$avl_balance."'</script>";
			    exit;
        }

          // Create product
          /*$product = \Stripe\Product::create([
            'name' => "Paying to provider",
            'type' => 'service',
        ]);
          
        
          $price = $stripe->prices->create([
            'unit_amount' => ($providramt*100),
            'currency' => $fetch['currency'],
            'product' => $product->id,
          ]);
          */
			
			  }
        else{
          // mail to provider
          $to = $fetch['email'];
      
          $subject = "Please complete onboarding";

          $content='<table width="90%" border="0">
                <tr>
                <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="2">Hi '.$fetch['name'].',</td>
                </tr>
                <tr>
                <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="2">Please complete onboarding to receive payment. Please go to Business Details menu after login to complete the onboarding.</td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                <td colspan="2">Thanks,<br>GearXchange</td>
                </tr>
                </table>';

                $fromemail = ADMINEMAIL;
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <'.$fromemail.'>' . "\r\n";
                mail($to, $subject, $content, $headers);
          echo "<script>location.href='order_view.php?error=1&id=".base64_encode($id)."'</script>";
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
	<div style="margin:5px 0; padding:10px; background:#C1DACA; border:1px solid #2C6035; color:#2C6035; font-weight:bold;">Stripe account is created for the provider.</div>
	<?php }
	if(isset($_REQUEST['success']) && $_REQUEST['success']==2)
	{
	?>
	<div style="margin:5px 0; padding:10px; background:#C1DACA; border:1px solid #2C6035; color:#2C6035; font-weight:bold;">Transfer to Provider is initiated.</div>
	<?php }
	if(isset($_REQUEST['error']) && $_REQUEST['error']==1)
	{
	?>
	<div style="margin:5px 0; padding:10px; background:#F3D6D6; border:1px solid #f00; color:#f00; font-weight:bold;">Provider onboarding is not complete. Account is not active for transfer.</div>
	<?php }
  if(isset($_REQUEST['error']) && $_REQUEST['error']==2)
	{
    $balance = $_REQUEST['balance']/100;
	?>
	<div style="margin:5px 0; padding:10px; background:#F3D6D6; border:1px solid #f00; color:#f00; font-weight:bold;">Sorry. You have Insufficient fund (<?php echo $balance;?> NZD) in your bank account.</div>
	<?php 
  }
  if(isset($_REQUEST['error']) && $_REQUEST['error']==3)
	{
	?>
	<div style="margin:5px 0; padding:10px; background:#F3D6D6; border:1px solid #f00; color:#f00; font-weight:bold;">Transfer cannot be initiated. Please try again.</div>
	<?php 
  }
	?>
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Order Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Order Details</li>
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
		$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):'';
		$sql = "SELECT a.*,c.name as proname,c.stripe_account,c.isonboardingsuccess,d.name as renter,e.name from `orders` a inner join order_details b on a.id=b.order_id inner join users c on a.providerid=c.id inner join users d on a.userid=d.id inner join equipments e on b.equipmentid=e.id where a.id = '".$id."'";
		$sql_res = dbQuery($dbConn,$sql);
		$sql_res_fetch = dbFetchAssoc($sql_res);

    $bcoun = dbQuery($dbConn, "select name from oc_country where iso_code_2 = '".$sql_res_fetch['country']."'");
    $bcoun_row = dbFetchArray($bcoun);

    $scoun = dbQuery($dbConn, "select name from oc_country where iso_code_2 = '".$sql_res_fetch['scountry']."'");
    $scoun_row = dbFetchArray($scoun);
		?>
        <!-- Main row -->
        <div class="row">
          
          <section class="col-lg-12 connectedSortable">

            <!-- Map card -->
            <div class="card masteraudit">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-edit"></i>
                  Order ID: <?php echo $sql_res_fetch['orderID'];?>, Placed on: <?php echo date('M j, Y', strtotime($sql_res_fetch["order_date"])); ?>
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
			  <input type="hidden" name="id" value="<?php echo $id;?>">
              <div class="card-body">
					<div class="row">
                        <div class="col-sm-3">
						  <div class="form-group">
                          <label for="">Renter</label>
                          <br />
                          <?php echo stripslashes($sql_res_fetch['renter']);
                          ?>
							</div>
						</div>
					</div>
                    
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label for="">Provider</label>
                            <br />
                            <?php echo stripslashes($sql_res_fetch['proname']);
                            ?>
						    </div>
						</div>
                        <div class="col-sm-3">
						  <div class="form-group">
                          <label for="">Equipment</label>
                          <br />
                          <?php echo stripslashes($sql_res_fetch['name']);
                            ?>
						</div>
                    </div>
                    <div class="col-sm-3">
						          <div class="form-group">
                          <label for="">Duration</label>
                          <br />
                          <?php echo date('M j, Y', strtotime($sql_res_fetch["start_date"])); ?> - <?php echo date('M j, Y', strtotime($sql_res_fetch["end_date"])); ?>
						          </div>
                    </div>
                    <div class="col-sm-3">
						          <div class="form-group">
                          <label for="">Pick up time</label>
                          <br />
                          <?php echo date('h:i a', strtotime($sql_res_fetch["pickuptm"]));
                          ?>
						          </div>
                    </div>
                    
                    </div>
                    
					<div class="row">

					<div class="col-sm-3">
						          <div class="form-group">
                          <label for="">Total</label>
                          <br />
                          <?php echo strtoupper($sql_res_fetch["currency"]); ?> <?php echo $sql_res_fetch["order_total"];
                          ?>
						          </div>
                    </div>

                    <div class="col-sm-3">
                            <div class="form-group">
                            <label for="">Provider amount</label>
                            <br />
                            <?php echo strtoupper($sql_res_fetch["currency"]); ?> <?php 
							$providramt = number_format((($sql_res_fetch["order_total"] * 90)/100),2);
							echo $providramt;
							?>
						    </div>
						</div>

            
                    <div class="col-sm-3">
						  <div class="form-group">
                          <label for="">Status</label>
                          <br />
                          <?php if($sql_res_fetch["cancel_date"]){
                            if($sql_res_fetch["cancelled_by_provdr"] == 1)
                            $text = "provider";
                            else
                            $text = "borrower";
                            echo "Cancelled by ".$text." on ".date('M j, Y', strtotime($sql_res_fetch["cancel_date"]));
                        }
                        else
                        echo "Not cancelled";
                        ?>
						</div>
                    </div>
                    <div class="col-sm-3">
                    <div class="form-group">
                    <label for="">Renter refund amount</label>
                            <br />
                            <?php echo strtoupper($sql_res_fetch["currency"]); ?> <?php echo $sql_res_fetch["refund_amt"];
                          ?>
                  </div>
                    </div>  
                    
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                            <label for="">Renter Billing Address</label>
                            <br />
                            <?php echo stripslashes($sql_res_fetch['address']).", ". stripslashes($sql_res_fetch['state']).", ". stripslashes($sql_res_fetch['city']).", ". stripslashes($bcoun_row['name'])." - ". stripslashes($sql_res_fetch['zip']);
                            ?>
						      </div>
						      </div>
                        <div class="col-sm-6">
						            <div class="form-group">
                          <label for="">Renter Shipping Address</label>
                          <br />
                          <?php echo stripslashes($sql_res_fetch['saddress']).", ". stripslashes($sql_res_fetch['sstate']).", ". stripslashes($sql_res_fetch['scity']).", ". stripslashes($scoun_row['name'])." - ". stripslashes($sql_res_fetch['szip']);
                            ?>
						            </div>
                    </div>
                    
                    </div>

          <div class="row" style="margin-top:12px;">
				<div class="col-sm-4">
	
					<button type="button" class="btn btn-primary" onclick="location.href='orders.php'">Go Back</button>
				</div>
				<?php
				if($sql_res_fetch["cancel_date"]){}
				else{
					if($sql_res_fetch["stripe_account"]=="")
					{
				?>
				<div class="col-sm-4">
	
					<button type="button" class="btn btn-primary" onclick="location.href='order_view.php?action=addstripeacc&id=<?php echo base64_encode($id);?>'">Add provider account to Stripe</button>
				</div>
				<?php
					}
					else if($sql_res_fetch["stripe_account"] && $sql_res_fetch["isonboardingsuccess"] == 0)
					{
						echo "<strong>Provider account is added to Stripe. Provider has not onboarded yet.</strong>";
					}
					else if($sql_res_fetch["stripe_account"] && $sql_res_fetch["isonboardingsuccess"] == 1 && $sql_res_fetch["isproviderpaid"] == 0)
					{
				?>
				<div class="col-sm-4">
	
					<button type="button" class="btn btn-primary" onclick="location.href='order_view.php?action=paytoprovider&id=<?php echo base64_encode($id);?>'">Pay <?php echo strtoupper($sql_res_fetch["currency"]); ?> <?php echo $providramt;?> to provider</button></div>
				<?php
					}
					else if($sql_res_fetch["isproviderpaid"] == 1)
					{
				?>
				<div class="col-sm-4">
	
					Provider payment of <?php echo strtoupper($sql_res_fetch["currency"]); ?> <?php echo $sql_res_fetch["providr_amount"];?> initiated on <?php echo date('M j, Y', strtotime($sql_res_fetch["provdr_pay_date"]));?>
				</div>
				<?php
					}
				}
				?>
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
