<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId']!=""){
	echo "<script>location.href='".SITEURL."myjobs'</script>";
	exit;
}
include_once('header.php');

if(count($_POST) >0 ){
	$email = isset($_POST['email'])?tep_db_input($_POST['email']):"";
	$pass = isset($_POST['pass'])?tep_db_input($_POST['pass']):"";
	$job = isset($_REQUEST['jobid'])?trim($_REQUEST['jobid']):"";
	$user = isset($_REQUEST['userid'])?trim($_REQUEST['userid']):"";

		$check = dbQuery($dbConn, "select id,name from users where email = '".$email."' and password = '".md5($pass)."' and type=1 and isdeactivated=0");
		if(dbNumRows($check) > 0){
			$row = dbFetchArray($check);
			$_SESSION['loginUserId'] = $row['id'];
            $_SESSION['loginUserName'] = $row['name'];

			if(isset($_SESSION['SESSID']) && $_SESSION['SESSID'] != ""){
				dbQuery($dbConn, "UPDATE job_details set employer_id = '".$_SESSION['loginUserId']."' where sessid = '".$_SESSION['SESSID']."'");
				$_SESSION['SESSID'] = '';
				unset($_SESSION['SESSID']);
				echo "<script>location.href='".SITEURL."jobpost1'</script>";
			}
			else{
				if($job != "" && $user != ""){
					echo "<script>location.href='".SITEURL."managehire/?id=".base64_encode($job)."&userid=".base64_encode($user)."'</script>";
				}
				else{
					echo "<script>location.href='".SITEURL."myjobs'</script>";
				}
			}
			exit;
		}
		else{
			if($job != "" && $user != ""){
				echo "<script>location.href='".SITEURL."employer_login/error?job=".$job."&user=".$user."'</script>";
			}
			else{
				echo "<script>location.href='".SITEURL."employer_login/error'</script>";
			}
            exit;
		}
}

$job = isset($_REQUEST['job'])?trim($_REQUEST['job']):"";
$user = isset($_REQUEST['user'])?trim($_REQUEST['user']):"";
?>
<section class="login_page">
	<div class="container2">
		<div class="row lessmar">
			<div class="col-sm-6 lesspadd tabbackbg">
				<img src="<?php echo SITEURL;?>images/regis_img.jpg" class="img-responsive" alt="loginimg" />
			</div>
			<div class="col-sm-6 lesspadd">
				<div class="login">
					<h4>Employer Login</h4>
					<?php
					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){
						echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Invalid login credentials.</div>";
					}
					?>
					<form action="" method="post" id="login" class="allfrmdesgn">
						<div class="row">
							<input type="hidden" name="jobid" value="<?php echo $job;?>">
							<input type="hidden" name="userid" value="<?php echo $user;?>">
							<div class="col-sm-12">
								<lable>Email Address</lable>
								<input type="text" placeholder="Enter Your Email" name="email" class="form-control required">
							</div>
							<div class="col-sm-12">
								<lable>Password</lable>
								<input type="password" placeholder="Enter Your Password" name="pass" class="form-control required">
							</div>
							<div class="pass_forgot">
								<a href="<?php echo SITEURL;?>forgot_password">Forgot Password?</a>
							</div>
							<div class="col-sm-4">
								<input type="submit" value="Submit">
							</div>
							<hr>
							<div class="login_info">
								<p>Don't have an account?</p>
								<a href="<?php echo SITEURL;?>signup?job=<?php echo $job;?>&user=<?php echo $user;?>">Sign up</a>
							</div>
						</div>
						
					</form>
					
				</div>
			</div>
		</div>
	</div>
</section>
	
<?php include_once('footer.php');?>