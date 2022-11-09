<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId']!=""){
	echo "<script>location.href='".SITEURL."welcome'</script>";
	exit;
}
include_once('header.php');

if(count($_POST) >0 ){
	$email = isset($_POST['email'])?tep_db_input($_POST['email']):"";
	$job = isset($_REQUEST['jobid'])?trim($_REQUEST['jobid']):"";
	$user = isset($_REQUEST['userid'])?trim($_REQUEST['userid']):"";

		$check = dbQuery($dbConn, "select name,email,type from users where email = '".$email."'");
		if(dbNumRows($check) > 0){
            $fetch = dbFetchArray($check);

            $pass = mt_rand('100000', '999999');

            dbQuery($dbConn, "UPDATE users SET `password` = '".md5($pass)."' where email = '".$email."'");

            $to = $fetch['email'];
            $subject = "Password has been reset";

            $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
			<tr>
            <td colspan='2'>&nbsp;</td>
        	</tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Hello ".$fetch['name'].",</td>
            </tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Your password is set with a new password.</td>
            </tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Please login with the credentials:</td>
            </tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Email: ".$fetch['email'].", Password: ".$pass."</td>
            </tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            <tr>
            <td colspan='2' style='padding-left:10px;padding-bottom:10;'>Thanks,<br>Staff Express</td>
            </tr>
            </table>";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

            mail($to,$subject,$message,$headers);

			/*if($job != "" && $user != ""){
				echo "<script>location.href='".SITEURL."view_application/?job=".base64_encode($job)."&user=".base64_encode($user)."&do=viewdetails'</script>";
			}
			else{*/
				echo "<script>location.href='".SITEURL."forgot_password/success?type=".$fetch['type']."'</script>";
			//}
			exit;
		}
		else{
			/*if($job != "" && $user != ""){
				echo "<script>location.href='".SITEURL."employer_login/error?job=".$job."&user=".$user."'</script>";
			}
			else{*/
				echo "<script>location.href='".SITEURL."forgot_password/error'</script>";
			//}
            exit;
		}
}

$job = isset($_REQUEST['job'])?trim($_REQUEST['job']):"";
$user = isset($_REQUEST['user'])?trim($_REQUEST['user']):"";
?>
<section class="login_page">
	<div class="container2">
		<div class="row lessmar">
			<div class="col-sm-6 lesspadd">
				<img src="<?php echo SITEURL;?>images/stafflog_img.jpg" class="img-responsive" alt="loginimg" />
			</div>
			<div class="col-lg-6 lesspadd">
				<div class="login">
					<h4>Retrieve Your Password</h4>
					<?php
					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){
						echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Sorry!! This email does not exist.</div>";
					}
                    if(isset($_REQUEST['success']) && $_REQUEST['success'] == 1){
						$type = $_REQUEST['type'];
						if($type == 1)
						$link = SITEURL."employer_login";
						if($type == 2)
						$link = SITEURL."staff_login";
						echo "<div class='alert-success' style='padding:15px;margin-bottom:15px;'>An auto-generated password has been sent to your email.<br> Click <a href='".$link."'>here</a> to login.</div>";
					}
					if(!isset($_REQUEST['success'])){
					?>
					<form action="" method="post" id="login" class="allfrmdesgn">
						<div class="row">
							
							<input type="hidden" name="jobid" value="<?php echo $job;?>">
							<input type="hidden" name="userid" value="<?php echo $user;?>">
							<div class="col-sm-12">
								<lable>Email Address</lable>
								<input type="email" placeholder="Enter Your Email" name="email" class="form-control required">
							</div>
							<div class="col-sm-4">
								<input type="submit" value="Submit">
							</div>
						</div>
						
					</form>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
	
<?php include_once('footer.php');?>