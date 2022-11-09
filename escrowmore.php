<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login'</script>";
	exit;
}
$STRIPE_API_KEY = STRIPE_API_KEY;
require_once 'stripe/init.php';
if($_POST['toescrow'] == '1'){
	if(!empty($_POST['stripeToken'])){
    $currtime = date('Y-m-d H:i:s');
	$token  = $_POST['stripeToken'];
	$jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
	$userid = isset($_POST['userid'])?tep_db_input($_POST['userid']):"";

	$getemp = dbQuery($dbConn, "SELECT name,email,phone from users where id = '".$_SESSION['loginUserId']."'");
	$rowemp = dbFetchArray($getemp);

	$getuser = dbQuery($dbConn, "SELECT name,email,phone from users where id = '".$userid."'");
	$rowuser = dbFetchArray($getuser);

	$myjob = dbQuery($dbConn, "SELECT a.title,a.payperhr FROM job_details a where a.id='".$jobid."'");
    $fetch = dbFetchArray($myjob);

    $moreamt = dbQuery($dbConn, "SELECT extra_hours,extra_hours_approved from staff_job_payment where job_id = '".$jobid."' and staff_id = '".$userid."'");
    $fetchMoreAmt = dbFetchArray($moreamt);

    $getstaff = dbQuery($dbConn, "SELECT b.name from users b where b.id = '".$userid."'");
    $fetchStaff = dbFetchArray($getstaff);

    $total = ($fetchMoreAmt['extra_hours'] * $fetch['payperhr']);

	$itemName = "Extra amount Escrow for job: ".$fetch['title'];
	\Stripe\Stripe::setApiKey($STRIPE_API_KEY); 
    // Set API key 
        
        // Add customer to stripe 
        $customer = \Stripe\Customer::create(array( 
            'email' => $rowemp['email'],
            'source'  => $token 
        ));
        
        // Unique order ID 
        $orderID = strtoupper(str_replace('.','',uniqid('', true))); 
        
        // Convert price to cents 
        $itemPrice = ($total*100); 
        
        // Charge a credit or a debit card 
        $charge = \Stripe\Charge::create(array( 
            'customer' => $customer->id, 
            'amount'   => $itemPrice, 
            'currency' => 'aud',
            'description' => $itemName,
            'metadata' => array( 
                'order_id' => $orderID 
            ) 
        )); 

        $charge_id = $charge->id;
        
        // Retrieve charge details 
        $chargeJson = $charge->jsonSerialize();

        if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code'])){
          // Order details  
          $transactionID = $chargeJson['balance_transaction']; 
          $payment_status = $chargeJson['status'];

			if($payment_status == 'succeeded'){
				dbQuery($dbConn, "INSERT INTO job_extra_escrow set jobid = '".$jobid."', userid = '".$userid."', escrow_amt = '".$total."', charge_id = '".$charge_id."', transactionID = '".$transactionID."', extra_amt_paidon = '".$currtime."'");

				$link = SITEURL."job_hire/";

				$to = $rowuser['email'];
				$subject = "Extra Amount is submitted to escrow";

				$message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid;'>
                        <tr>
                            <td style='padding:5px; font-size:18px; color:green;text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
                        </tr>
                        <tr>
                        <td colspan='2'>&nbsp;</td>
                        </tr>
				<tr>
				<td colspan='2' style='padding-left:10px;'>Hello ".$rowuser['name'].",</td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<tr>
				<td colspan='2' style='padding-left:10px;'>Extra amount is deposited to escrow for this job: ".stripslashes($fetch['title']).".</td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>
				</tr>
				</table>";

				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

				mail($to,$subject,$message,$headers);
				echo "<script>location.href='".SITEURL."managehire/?id=".base64_encode($jobid)."&userid=".base64_encode($userid)."&action=extraamount'</script>";
				exit;
			}
			else{
				echo "<form action='".SITEURL."escrowmore' method='post' id='gotoconfirm'><input type='hidden' name=id value='".$jobid."'><input type='hidden' name='userid' value='".$userid."'><input type='hidden' name='error' value='1'></form><script>document.getElementById('gotoconfirm').submit();</script>";
			}
		}
		else{
			echo "<form action='".SITEURL."escrowmore' method='post' id='gotoconfirm'><input type='hidden' name=id value='".$jobid."'><input type='hidden' name='userid' value='".$userid."'><input type='hidden' name='error' value='1'></form><script>document.getElementById('gotoconfirm').submit();</script>";
		}
	}
	else{
		echo "<form action='".SITEURL."escrowmore' method='post' id='gotoconfirm'><input type='hidden' name=id value='".$jobid."'><input type='hidden' name='userid' value='".$userid."'><input type='hidden' name='error' value='1'></form><script>document.getElementById('gotoconfirm').submit();</script>";
	}
}

$jobid = isset($_REQUEST['id'])?tep_db_input($_REQUEST['id']):"";
$userid = isset($_REQUEST['userid'])?tep_db_input($_REQUEST['userid']):"";

$myjob = dbQuery($dbConn, "SELECT a.* FROM job_details a where a.id='".$jobid."'");
$fetch = dbFetchArray($myjob);

$moreamt = dbQuery($dbConn, "SELECT id,extra_hours,extra_hours_approved from staff_job_payment where job_id = '".$jobid."' and staff_id = '".$userid."'");
$fetchMoreAmt = dbFetchArray($moreamt);

$getstaff = dbQuery($dbConn, "SELECT b.name from users b where b.id = '".$userid."'");
$fetchStaff = dbFetchArray($getstaff);

if($fetchMoreAmt['extra_hours_approved'] == 1){
$total = ($fetchMoreAmt['extra_hours'] * $fetch['payperhr']);
$commission = $total * 0.05;
$total_money = $total + $commission;
?>
<script src="https://js.stripe.com/v2/"></script>
	<div class="works">
		<div class="container">
			<div class="inneremploypg">
				<div class="row">
					<?php 
					include_once "employer_left.php";
					?>
					<div class="col-lg-9">
						<div class="works_heading innerhding">
							<h4>Escrow More Amount</h4>
							<h6>You have approved <?php echo $fetchMoreAmt['extra_hours'];?> extra hour for the job: <?php echo stripslashes($fetch['title']);?>.<h6>
							<div class="table-responsive jobdetlstabl" style="padding-top:30px;">
								<table class="table table-bordered">
									<tbody>
										<tr class="table-light">
											<td width="75%" style="text-align:right;"><strong>Extra money to be deposited to escrow:</strong></td>
											<td width="25%" style="text-align:right;"><strong><?php echo $total;?> AUD</strong></td>
										</tr>
										<tr>
											<td colspan="2">
											<div class="login">
											<h4 style="padding-bottom:20px;">Enter card details below to deposit money</h4>
											<?php
											if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){
												echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Payment failed. Please try again.</div>";
											}
											?>
											<form action="" method="post" id="checkout_form">
												<input type="hidden" id="jobid" name="jobid" value="<?php echo $jobid;?>">
												<input type="hidden" id="userid" name="userid" value="<?php echo $userid;?>">
												<input type="hidden" name="toescrow" value="1">
												<div class="form-group">
													<input type="text" placeholder="Card Number" name="card_number" id="card_number" class="form-control required" autocomplete="off" maxlength="19">
												</div>
												<div class="form-group crddtls">
													<input type="text" placeholder="Expiry Month (MM)" name="card_exp_month" id="card_exp_month" class="form-control required digits" autocomplete="off">
													<input type="text" placeholder="Expiry Year (YYYY)" name="card_exp_year" id="card_exp_year" class="form-control required digits" autocomplete="off">
													<input type="text" placeholder="CVC Code" name="card_cvc" id="card_cvc" class="form-control required digits" autocomplete="off">
												</div>
												<div class="form_control">
													<input type="submit" value="Submit" id="payBtn">
												</div>
												<div class="payment-status" style="color:#f00;font-weight:bold;"></div>
											</form>
											
										</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
	
	<?php 
    }
    include_once('footer.php');?>