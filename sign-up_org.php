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

	$name = isset($_POST['name'])?tep_db_input($_POST['name']):"";

	$fname = isset($_POST['fname'])?tep_db_input($_POST['fname']):"";

	if($fname)

	$name = $fname;

	else

	$name = $name;

	$lname = isset($_POST['lname'])?tep_db_input($_POST['lname']):"";

	$email = isset($_POST['email'])?tep_db_input($_POST['email']):"";

	//$phone = isset($_POST['phone'])?tep_db_input($_POST['phone']):"";

	$pass = isset($_POST['pass'])?tep_db_input($_POST['pass']):"";

	$cpass = isset($_POST['cpass'])?tep_db_input($_POST['cpass']):"";

	$mytype = isset($_POST['mytype'])?tep_db_input($_POST['mytype']):"";



	$job = isset($_POST['jobid'])?trim($_POST['jobid']):"";

	$user = isset($_POST['userid'])?trim($_POST['userid']):"";

	$do = isset($_POST['do'])?trim($_POST['do']):"";



	if($pass == $cpass){

		$check = dbQuery($dbConn, "select id from users where email = '".$email."'");

		if(dbNumRows($check) == 0){

			dbQuery($dbConn, "insert into users set name = '".$name."', lname = '".$lname."', email = '".$email."', password = '".md5($pass)."', type = '".$mytype."', regdate = '".date('Y-m-d')."'");

			$insert_id = dbInsertId($dbConn);



			$_SESSION['loginUserId'] = $insert_id;

            $_SESSION['loginUserName'] = $name;



			if($mytype == 1){

				if(isset($_SESSION['SESSID']) && $_SESSION['SESSID'] != "")

				echo "<script>location.href='".SITEURL."jobpost1'</script>";

				else

				echo "<script>location.href='".SITEURL."employer_details'</script>";

			}

			else if($mytype == 2){

				if($do == "search"){

					echo "<script>location.href='".SITEURL."viewmyjob/".base64_encode($job)."'</script>";

				}

				else{

				echo "<script>location.href='".SITEURL."staff_details'</script>";

				
			}
			}

			

            exit;



		}

		else{

			if($do == "search"){

				echo "<script>location.href='".SITEURL."signup/error?job=".$job."&do=search'</script>";

			}

			else{

				echo "<script>location.href='".SITEURL."signup/error'</script>";

			}

            exit;

		}

	}

	else{

		if($do == "search"){

			echo "<script>location.href='".SITEURL."signup/error2?job=".$job."&do=search'</script>";

		}

		else{

			echo "<script>location.href='".SITEURL."signup/error2'</script>";

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

				<img src="<?php echo SITEURL;?>images/regis2_img.jpg" class="img-responsive" alt="loginimg" />

			</div>

			<div class="col-sm-6 lesspadd">

				<div class="login">

					<h4>Registration</h4>

					<?php

					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){

						echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Email already exists!! Please try another.</div>";

					}

					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 2){

						echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Password and confirm password must be same.</div>";

					}

					if(isset($_SESSION['SESSID']) && $_SESSION['SESSID'] != ""){

						$compnamestyle = "";

						$usrnamestyle = 'style="display:none;"';

					}

					else{

						$compnamestyle = 'style="display:none;"';

						$usrnamestyle = "";

					}

					?>

					<form action="" method="post" id="regform" class="allfrmdesgn">

						<div class="row">

							<input type="hidden" name="jobid" value="<?php echo $job;?>">

							<input type="hidden" name="userid" value="<?php echo $user;?>">

							<input type="hidden" name="do" value="<?php echo $do;?>">

							<div class="col-sm-12">

							<?php

							if(!isset($_SESSION['SESSID']) && !isset($_REQUEST['do'])){

							?>

								<lable>Choose option</lable>

								<?php

							}

							?>

								<select class="form-select required" name="mytype" id="mytype">

									<option value="">I am an/a</option>

									<option value="1" <?php if(isset($_SESSION['SESSID']) && $_SESSION['SESSID'] != "") echo "selected";?>>Employer</option>

									<option value="2" <?php if(isset($_REQUEST['do']) && $_REQUEST['do'] == "search") echo "selected";?>>Staff</option>

								  </select>

							</div>

							<div class="col-sm-12">

								<div id="forstaff" <?php echo $usrnamestyle;?> class="row">

									<div class="col-sm-6" >

										<lable>First Name</lable>

										<input type="text" placeholder="First Name" name="fname" id="fname" class="form-control required">

									</div>

									<div class="col-sm-6">

										<lable>Last Name</lable>

										<input type="text" placeholder="Last Name" name="lname" id="lname" class="form-control required">

									</div>

								</div>

							</div>

							<div class="col-sm-6" id="foremp" <?php echo $compnamestyle;?>>

								<lable>Company Name</lable>

								<input type="text" placeholder="Company Name" name="name" id="name" class="form-control">

							</div>

							

							<div class="col-sm-6">

								<lable>Email Address</lable>

								<input type="email" placeholder="Email" name="email" id="email" class="form-control required">

							</div>



							<div class="col-sm-6">

								<lable>Password</lable>

								<input type="password" placeholder="Password" name="pass" id="pass" class="form-control required">

							</div>

							<div class="col-sm-6">

								<lable>Confirm Password</lable>

								<input type="password" placeholder="Confirm Password" name="cpass" id="cpass" class="form-control required">

								<span id="error" style="color:red;"></span>

							</div>

							<div class="col-sm-4">	

								<lable>&nbsp;</lable>

								<input type="submit" value="Submit">

							</div>

							<hr>

							<div class="login_info" style="display:inline-block;">

								<p style="text-align:center;">Already registered?</p>

								<p style="text-align:center;">If you are a staff, <a href="<?php echo SITEURL;?>staff_login?job=<?php echo $job;?>&user=<?php echo $user;?>&do=<?php echo $do;?>" style="color:#0a58ca;text-decoration:underline;">Login</a> here.</p>

								<p style="text-align:center;">If you are an employer, <a href="<?php echo SITEURL;?>employer_login?job=<?php echo $job;?>&user=<?php echo $user;?>" style="color:#0a58ca;text-decoration:underline;">Login</a> here.</p>

								

							</div>

						</div>

					</form>

					

				</div>

			</div>

		</div>

	</div>

</section>

	

<?php include_once('footer.php');?>