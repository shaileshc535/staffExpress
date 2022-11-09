<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."staff_login'</script>";
	exit;
}

if(count($_POST) > 0){
	$STRIPE_API_KEY = STRIPE_API_KEY;
	require_once 'stripe/init.php';

	$bsb = isset($_POST['bsb'])?tep_db_input($_POST['bsb']):"";
	$accno = isset($_POST['accno'])?tep_db_input($_POST['accno']):"";
	$bankaccname = isset($_POST['bankaccname'])?tep_db_input($_POST['bankaccname']):"";
	$taxno = isset($_POST['taxno'])?tep_db_input($_POST['taxno']):"";
	$claim_tax = isset($_POST['claim_tax'])?tep_db_input($_POST['claim_tax']):"";
    $high_edutn = isset($_POST['high_edutn'])?tep_db_input($_POST['high_edutn']):"";

	dbQuery($dbConn, "UPDATE staff_details set bsb = '".$bsb."', accno = '".$accno."', bankaccname = '".$bankaccname."', taxno = '".$taxno."', claim_tax = '".$claim_tax."', high_edutn = '".$high_edutn."' where staff_id = '".$_SESSION['loginUserId']."'");
	// creating stripe account

	$users = dbQuery($dbConn, "SELECT a.name,a.email,b.country,b.accno,b.bankaccname,b.stripe_account from users a inner join staff_details b on a.id=b.staff_id where a.id = '".$_SESSION['loginUserId']."'");
    $fetch = dbFetchArray($users);

	if($fetch['stripe_account'] == ''){
	/*$stripe = new \Stripe\StripeClient(
		$STRIPE_API_KEY
		);
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
		  'currency' => 'aud',
		  'account_holder_name' => $fetch['bankaccname'],
		  'account_holder_type' => 'individual',
		  'account_number' => $fetch['accno']
		  ],
		]);

		$account_id = $account->id;
		if($account_id != ""){
				dbQuery($dbConn,"UPDATE staff_details set stripe_account = '".$account_id."' where staff_id = '".$_SESSION['loginUserId']."'");
				$dettxt = "Please enter your details by Stripe Onboarding by going to Onboard menu to receive payment.";
				$to = $fetch['email'];
				$subject = "Please Complete Onboarding";

				$message = "<table width='100%'>
				<tr>
				<td colspan='2'>Hello ".$fetch['name'].",</td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<tr>
				<td colspan='2'>".$dettxt."</td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<td colspan='2'>Thanks,<br>Staff Express</td>
				</tr>
				</table>";

				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

				mail($to,$subject,$message,$headers);
		}*/
	}

	echo "<script>location.href='".SITEURL."staff_payment3'</script>";
    exit;
	
}

$checkdetls = dbQuery($dbConn, "SELECT * from staff_details where staff_id = '".$_SESSION['loginUserId']."'");
$getdetls = dbFetchArray($checkdetls);
?>	

	<div class="works works_success i_am_staff_sty">
		<div class="container">
		<div class="stepbystp">
			<ul>
				<li>My Profile</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Health Info</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li  class="actv">Payments</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Superannuation</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Optional Upload</li>
			</ul>
		</div>
			<div class="row">
			<?php 
					include_once "staff_left.php";
					?>
				<div class="col-lg-9">
					
					<div class="works_heading">
					<?php
					if(isset($_REQUEST['success']) && $_REQUEST['success'] == 1){
					?>
						<div class='alert-success' style='padding:15px;margin-bottom:15px;'>Payment updated successfully.</div>
					<?php
					}
					?>
					<h4>Bank Details</h4>
					<div class="login login_page martopadjst">
					
					
					<form action="" method="post" id="regform" class="eplyfrm ">
						<div class="row">
							<div class="col-sm-12" id="forstaff">
							<label style="text-transform:none;font-size:14px;">Please include your Bank Details if you wish for easier payment from employers</label>

								<input type="text" placeholder="BSB" name="bsb" id="bsb" class="form-control required digits" value="<?php echo $getdetls['bsb'];?>">
								<input type="text" placeholder="Account Number" name="accno" id="accno" class="form-control required digits" value="<?php echo $getdetls['accno'];?>">
							</div>
							
							<div class="col-sm-6">
								<input type="text" placeholder="Name of Bank Account" name="bankaccname" id="bankaccname" class="form-control required" value="<?php echo $getdetls['bankaccname'];?>">
							</div>

							<div class="col-sm-6">
								<input type="text" placeholder="Tax File Number" name="taxno" id="taxno" class="form-control digits" value="<?php echo $getdetls['taxno'];?>">
							</div>
							<div class="col-sm-12" style="margin-bottom:30px;">
								<label style="text-transform:none;">Are you claiming tax free threshold for any work done?</label><br>
								<input type="radio" name="claim_tax" value="1" <?php if($getdetls['claim_tax']=='1') echo "checked";?>> <span>Yes</span> &nbsp; &nbsp; 
								<input type="radio" name="claim_tax" value="2" <?php if($getdetls['claim_tax']=='2') echo "checked";?>> <span>No</span>
							</div>
							<div class="col-sm-12" style="margin-bottom:30px;">
								<label style="text-transform:none;">Do you have a Higher Education Loan Program (HELP), VET Student Loan (VSL), Financial Supplement (FS), Student Start-up Loan (SSL) or Trade Support Loan (TSL) debt?</label><br>
								<input type="radio" name="high_edutn" value="1" <?php if($getdetls['high_edutn']=='1') echo "checked";?>> <span>Yes</span> &nbsp; &nbsp; 
								<input type="radio" name="high_edutn" value="2" <?php if($getdetls['high_edutn']=='2') echo "checked";?>> <span>No</span>
							</div>
							<div class="col-sm-2">
								<input type="button" value="Back" id="back" onclick="location.href='<?php echo SITEURL;?>staff_payment2'">
							</div>
							<div class="col-sm-2">
								<input type="submit" value="Next">
							</div>
						</div>
					</form>
					
				</div>
					</div>
					
					
				</div>
				
			</div>
		</div>
	</div>
	
	
	<?php include_once('footer.php');?>