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

	$name = isset($_POST['name'])?tep_db_input($_POST['name']):"";

	$lname = isset($_POST['lname'])?tep_db_input($_POST['lname']):"";

	//$phone = isset($_POST['phone'])?tep_db_input($_POST['phone']):"";

	$pass = isset($_POST['pass'])?tep_db_input($_POST['pass']):"";

	$cpass = isset($_POST['cpass'])?tep_db_input($_POST['cpass']):"";



	dbQuery($dbConn, "UPDATE users set name = '".$name."', lname = '".$lname."' where id = '".$_SESSION['loginUserId']."'");

	

	if($pass && $cpass){

		if($pass == $cpass){

			dbQuery($dbConn, "UPDATE users set password = '".md5($pass)."' where id = '".$_SESSION['loginUserId']."'");

		}

		else{

			echo "<script>location.href='".SITEURL."staff_profile?error=1'</script>";

			exit;

		}

	}

	echo "<script>location.href='".SITEURL."staff_profile?success=1'</script>";

	exit;

}



$checkdetls = dbQuery($dbConn, "SELECT * from users where id = '".$_SESSION['loginUserId']."'");

$getdetls = dbFetchArray($checkdetls);

?>	



	<div class="works works_success staff_profile_sty">

		<div class="container">

			<div class="row">

			<?php 

					include_once "staff_left.php";

					?>

				<div class="col-lg-9">

					

					<div class="works_heading">

					<?php

					if(isset($_REQUEST['success']) && $_REQUEST['success'] == 1){

					?>

						<div class='alert-success' style='padding:15px;margin-bottom:15px;'>Profile updated successfully.</div>

					<?php

					}

					?>

					<h4>Update Account</h4>

					<div class="login login_page martopadjst">

					

					<?php

					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 2){

						echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Password and confirm password must be same.</div>";

					}

					?>

					<form action="" method="post" id="regform">

					

						<div class="form_column" id="forstaff">

							<input type="text" placeholder="First Name" name="name" id="fname" class="form-control required" value="<?php echo $getdetls['name'];?>">

							<input type="text" placeholder="Last Name" name="lname" id="lname" class="form-control required" value="<?php echo $getdetls['lname'];?>">

						</div>

						

						<div class="form-group">

							<input type="email" placeholder="Email" name="email" id="email" class="form-control required" readonly value="<?php echo $getdetls['email'];?>">

						</div>



						<div class="form-group">

							<label>Leave blank if you don't want to change password</label><br>

							<input type="password" placeholder="Password" name="pass" id="pass" class="form-control ">

						</div>

						<div class="form-group">

							<input type="password" placeholder="Confirm Password" name="cpass" id="cpass" class="form-control ">

							<span id="error" style="color:red;"></span>

						</div>

						

						<input type="submit" value="Submit">

					</form>

					

					</div>

					</div>

					

					

				</div>

				

			</div>

		</div>

	</div>

	

	

	<?php include_once('footer.php');?>