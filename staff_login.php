<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId']!=""){
	echo "<script>location.href='".SITEURL."staff_details'</script>";
	exit;
}
include_once('header.php');

if(count($_POST) >0 ){
	$email = isset($_POST['email'])?tep_db_input($_POST['email']):"";
	$pass = isset($_POST['pass'])?tep_db_input($_POST['pass']):"";
	$job = isset($_POST['jobid'])?trim($_POST['jobid']):"";
	$user = isset($_POST['userid'])?trim($_POST['userid']):"";
	$do = isset($_POST['do'])?trim($_POST['do']):"";

		$check = dbQuery($dbConn, "select id,name from users where email = '".$email."' and password = '".md5($pass)."' and type=2 and isdeactivated=0");
		if(dbNumRows($check) > 0){
			$row = dbFetchArray($check);
			$_SESSION['loginUserId'] = $row['id'];
            $_SESSION['loginUserName'] = $row['name'];

			if($do == "search"){
				echo "<script>location.href='".SITEURL."viewmyjob/".base64_encode($job)."'</script>";
			}
			else if($do == "confirmjob"){
				echo "<script>location.href='".SITEURL."myappliedjobs/".base64_encode($job)."/?do=confirmjob'</script>";
			}
			else{
				$getstep = dbQuery($dbConn, "select profile_step from staff_details where staff_id = '".$row['id']."'");
				$row2 = dbFetchArray($getstep);
				if($row2['profile_step'] == 1){
					echo "<script>location.href='".SITEURL."staff_experience'</script>";
				}
				else if($row2['profile_step'] == 2){
					echo "<script>location.href='".SITEURL."staff_payment2'</script>";
				}
				else if($row2['profile_step'] == 3){
					echo "<script>location.href='".SITEURL."staff_payment4'</script>";
				}
				else{
					echo "<script>location.href='".SITEURL."staff_details'</script>";
				}
			}
			exit;
		}
		else{
			if($do == "search"){
				echo "<script>location.href='".SITEURL."staff_login/error?job=".$job."&do=search'</script>";
			}
			else if($do == "confirmjob"){
				echo "<script>location.href='".SITEURL."staff_login/error?job=".$job."&do=confirmjob'</script>";
			}
			else{
				
					echo "<script>location.href='".SITEURL."staff_login/error'</script>";
			}
            exit;
		}
}

$job = isset($_REQUEST['job'])?trim($_REQUEST['job']):"";
$user = isset($_REQUEST['user'])?trim($_REQUEST['user']):"";
$do = isset($_REQUEST['do'])?trim($_REQUEST['do']):"";
?>
<section class="login_page">
	<div class="container2">
		<div class="row lessmar">
			<div class="col-sm-6 lesspadd tabbackbg">
				<img src="<?php echo SITEURL;?>images/stafflog_img.jpg" class="img-responsive" alt="loginimg" />
			</div>
			<div class="col-sm-6 lesspadd">
				<div class="login">
					<h4>Staff Login</h4>
					<?php
					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){
						echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Invalid login credentials.</div>";
					}
					?>
					<form action="" method="post" id="login" class="allfrmdesgn">
						<div class="row">
							<input type="hidden" name="jobid" value="<?php echo $job;?>">
							<input type="hidden" name="userid" value="<?php echo $user;?>">
							<input type="hidden" name="do" value="<?php echo $do;?>">
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
								<a href="<?php echo SITEURL;?>signup?job=<?php echo $job;?>&user=<?php echo $user;?>&do=<?php echo $do;?>">Sign up</a>
							</div>
						</div>
						
					</form>
					
				</div>
			</div>
		</div>
	</div>
</section>
	
	
<?php include_once('footer.php');?>