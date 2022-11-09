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
	$token  = $_POST['stripeToken'];
	$jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
	$userid = isset($_POST['userid'])?tep_db_input($_POST['userid']):"";

	$getemp = dbQuery($dbConn, "SELECT name,email,phone from users where id = '".$_SESSION['loginUserId']."'");
	$rowemp = dbFetchArray($getemp);

	$getuser = dbQuery($dbConn, "SELECT name,email,phone from users where id = '".$userid."'");
	$rowuser = dbFetchArray($getuser);

	$myjob = dbQuery($dbConn, "SELECT title,jobdate,jobdate2,starttime,endtime,payperhr FROM job_details where id='".$jobid."'");
	$fetch = dbFetchArray($myjob);
	$jobsrtdate = $fetch['jobdate'];
	$jobenddate = $fetch['jobdate2'];
	$strttime = $fetch['starttime'];
	$endtime = $fetch['endtime'];

	$jobstrttime = $jobsrtdate." ".$strttime;
	$jobendttime = $jobenddate." ".$endtime;

	$jobstrttime = strtotime($jobstrttime);
	$jobendttime = strtotime($jobendttime);
	$duration = ceil(($jobendttime - $jobstrttime)/3600);
	$total = ($duration * $fetch['payperhr']);
	$commission = $total * 0.05;
	$total_money = $total + $commission;

	$itemName = "Escrow for job: ".$fetch['title'];
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
        $itemPrice = ($total_money*100); 
        
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
				dbQuery($dbConn, "UPDATE job_details set charge_id = '".$charge_id."', transactionID = '".$transactionID."', total_amt = '".$total."', escrow_amt = '".$total_money."' where id = '".$jobid."'");

				$link = SITEURL."job_hire/";

				$to = $rowuser['email'];
				$subject = "Amount is submitted to escrow";

				$message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid;'>
				<tr>
					<td style='padding:5px; font-size:18px; color:green; text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
				</tr>
				<tr>
				<td colspan='2'>&nbsp;</td>
				</tr>
				<tr>
				<td colspan='2' style='padding-left:10px;'>Hello ".$rowuser['name'].",</td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<tr>
				<td colspan='2' style='padding-left:10px;'>Amount is deposited to escrow for this job: ".stripslashes($fetch['title']).".</td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<tr>
				<td colspan='2' style='padding-left:10px;'>Your employer is: ".stripslashes($rowemp['name']).".</td>
				</tr>
				<tr>
				<td colspan='2' style='padding-left:10px;'>Please make sure BSB number, account number and account name are there in your account. You have to onboard to Stripe interface to receive payment when job is complete. You can click on Onboard menu to start onboarding.</td>
				</tr>
				<tr>
				<td colspan='2' style='padding-left:10px;'>Please select the job from your account to go to the details page of the job. Please start work.</td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>
				</tr>
				</table>";

				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

				mail($to,$subject,$message,$headers);
				echo "<script>location.href='".SITEURL."managehire/?id=".base64_encode($jobid)."&userid=".base64_encode($userid)."'</script>";
				exit;
			}
			else{
				echo "<form action='".SITEURL."confirmation' method='post' id='gotoconfirm'><input type='hidden' name=jobid value='".$jobid."'><input type='hidden' name='userid' value='".$userid."'><input type='hidden' name='error' value='1'></form><script>document.getElementById('gotoconfirm').submit();</script>";
			}
		}
		else{
			echo "<form action='".SITEURL."confirmation' method='post' id='gotoconfirm'><input type='hidden' name=jobid value='".$jobid."'><input type='hidden' name='userid' value='".$userid."'><input type='hidden' name='error' value='1'></form><script>document.getElementById('gotoconfirm').submit();</script>";
		}
	}
	else{
		echo "<form action='".SITEURL."confirmation' method='post' id='gotoconfirm'><input type='hidden' name=jobid value='".$jobid."'><input type='hidden' name='userid' value='".$userid."'><input type='hidden' name='error' value='1'></form><script>document.getElementById('gotoconfirm').submit();</script>";
	}
}

$jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
$userid = isset($_POST['userid'])?tep_db_input($_POST['userid']):"";

$myjob = dbQuery($dbConn, "SELECT a.*,b.phone,b.business_name,c.category FROM job_details a inner join users b on a.employer_id=b.id inner join category c on a.catid=c.id where a.id='".$jobid."'");
$fetch = dbFetchArray($myjob);
$jobsrtdate = $fetch['jobdate'];
$jobenddate = $fetch['jobdate2'];
$strttime = $fetch['starttime'];
$endtime = $fetch['endtime'];

$jobstrttime = $jobsrtdate." ".$strttime;
$jobendttime = $jobenddate." ".$endtime;

$jobstrttime = strtotime($jobstrttime);
$jobendttime = strtotime($jobendttime);
$duration = ceil(($jobendttime - $jobstrttime)/3600);

$getstaff = dbQuery($dbConn, "SELECT a.*,b.name,b.email,b.phone,c.category from staff_details a inner join users b on a.staff_id=b.id inner join category c on a.catid=c.id where staff_id = '".$userid."'");
$fetchStaff = dbFetchArray($getstaff);

$total = ($duration * $fetch['payperhr']);
$total_max = ($duration * $fetch['payperhr_max']);
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
							<h4>Confirmation</h4>
							<h6>You have hired the candidate <?php echo $fetchStaff['name'];?> for the job: <?php echo stripslashes($fetch['title']);?>.<h6>
							<div class="table-responsive jobdetlstabl" style="padding-top:30px;">
								<table class="table table-bordered">
									<tbody>
										<tr class="table-light">
											<td width="75%"><strong>Job duration:</strong></td>
											<td width="25%" style="text-align:right;"><?php echo $duration;?> Hours</td>
										</tr>
										<tr>
											<td width="75%" style="text-align:right;"><strong>Total to be paid to employee on job completion:</strong></td>
											<td width="25%" style="text-align:right;"><strong><?php echo $total;?> AUD - <?php echo $total_max;?> AUD</strong></td>
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
	
	
	<?php include_once('footer.php');?>