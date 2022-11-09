<?php

include('config/config.php'); 

include_once "config/common.php";
include "phpmailer/class.phpmailer.php";

$dbConn = establishcon();



if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId']!=""){
	
	$usertype = getUserType($dbConn, $_SESSION['loginUserId']);
	
	if($usertype == 1)
	echo "<script>location.href='".SITEURL."myjobs'</script>";
	if($usertype == 2)
	echo "<script>location.href='".SITEURL."staff_details'</script>";

	exit;

}

include_once('header.php');

$current_year = date('Y');

if(count($_POST) >0 ){
	
	$allowadd = 1;
	
	$secret = '6Ld-C9QhAAAAAGLF3O17s5pX-uWmubjoBGHwU1XB';

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
	
	//$dob = isset($_POST['dob'])?strip_tags(tep_db_input($_POST['dob'])):"";

	$job = isset($_POST['jobid'])?trim($_POST['jobid']):"";

	$user = isset($_POST['userid'])?trim($_POST['userid']):"";

	$do = isset($_POST['do'])?trim($_POST['do']):"";
	
	$birth_year = date('Y', strtotime($dob));
	
	/*if($mytype == 2){
		if(($current_year - $birth_year) >= 16){
			$allowadd = 1;
		}
		else{
			$allowadd = 0;
		}
	}
	else if($mytype == 1){
		$allowadd = 1;
	}*/
	
	if($allowadd == 1){

		if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) 
		{
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if($responseData->success){
		if($pass == $cpass){

			$check = dbQuery($dbConn, "select id from users where email = '".$email."'");

			if(dbNumRows($check) == 0){

				dbQuery($dbConn, "insert into users set name = '".$name."', lname = '".$lname."', email = '".$email."', password = '".md5($pass)."', type = '".$mytype."', regdate = '".date('Y-m-d')."'");

				$insert_id = dbInsertId($dbConn);

				dbQuery($dbConn, "INSERT into staff_details set staff_id = '".$insert_id."', dob = '".$dob."'");

				$_SESSION['loginUserId'] = $insert_id;

				$_SESSION['loginUserName'] = $name;



				if($mytype == 1){
					
					$jobpost_link = SITEURL."jobpost1";
					
					$mail = new PHPMailer();
					$mail->IsSMTP();

					$mail->Host       = "cp-wc12.syd02.ds.network"; // cp-wc12.syd02.ds.network
					$mail->SMTPAuth   = true;  
					$mail->SMTPSecure = "ssl";                
					$mail->Port       = 465;                    
					$mail->Username   = "contact@staffexpress.com.au";            
					$mail->Password   = "QYhi[=Aoor{t";

					$mail->From = ADMINEMAIL;
					$mail->FromName = "Staff Express";
					$mail->Subject = "Successfully Registered in Staff Express";
					$mail->isHTML(true);
					$mail->AddAddress($email);
					
					//$to = $email;
					//$subject = "Welcome to Staff Express";

					$content='<table cellpadding="0" cellspacing="0" width="600px" style="margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;">
							<tr>
							<td colspan="2" style="padding-left:10px;padding-top:10px;">Dear '.$name.',</td>
							</tr>
							<tr>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
							<td colspan="2" style="padding-left:10px;">Thank You for Registering with Staff Express.</td>
							</tr>
							<tr>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
							<td colspan="2" style="padding-left:10px;">To post your jobs please click <a href="'.$jobpost_link.'" target="_blank">here</a>.</td>
							</tr>
							<tr>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
						  <td colspan="2" style="padding-left:10px;padding-bottom:10px;">Thanks,<br>Staff Express</td>
						  </tr>
						  </table>';
						  
						  $mail->Body = $content;
						  if(!$mail->Send()) {
							echo $mail->ErrorInfo;
						  } else {
							
						  }

						  /*$fromemail = ADMINEMAIL;
						  $headers = "MIME-Version: 1.0" . "\r\n";
						  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						  $headers .= 'From: <'.$fromemail.'>' . "\r\n";
						  mail($to, $subject, $content, $headers);*/
						  
						  // mail to admin
						  $mail2 = new PHPMailer();
							$mail2->IsSMTP();

							$mail2->Host       = "cp-wc12.syd02.ds.network"; // cp-wc12.syd02.ds.network
							$mail2->SMTPAuth   = true;  
							$mail2->SMTPSecure = "ssl";                
							$mail2->Port       = 465;                    
							$mail2->Username   = "contact@staffexpress.com.au";            
							$mail2->Password   = "QYhi[=Aoor{t";

							$mail2->From = ADMINEMAIL;
							$mail2->FromName = "Staff Express";
							$mail2->Subject = "New Employer Registered";
							$mail2->isHTML(true);
							$mail2->AddAddress(ADMINEMAIL);
							
						  //$to = ADMINEMAIL;
		  
						//$subject = "New Employer Registered";
				
						$content='<table cellpadding="0" cellspacing="0" width="600px" style="margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;">
								<tr>
								<td colspan="2" style="padding-left:10px;padding-top:10px;">Hi Admin,</td>
								</tr>
								<tr>
								<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:10px;">A new employer has registered in the Staff Express.</td>
								</tr>
								<tr>
								<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:10px;">Details are:</td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:10px;"><strong>Name:</strong> '.$name.'</td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:10px;"><strong>Email:</strong> '.$email.'</td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
								<td colspan="2" style="padding-left:10px;padding-bottom:10px;">Thanks,<br>Staff Express</td>
								</tr>
								</table>';
				
						/*$fromemail = ADMINEMAIL;
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$headers .= 'From: <'.$fromemail.'>' . "\r\n";
						mail($to, $subject, $content, $headers);*/
						$mail2->Body = $content;
						  if(!$mail2->Send()) {
							echo $mail2->ErrorInfo;
						  } else {
							
						  }
					

					if(isset($_SESSION['SESSID']) && $_SESSION['SESSID'] != "")

					echo "<script>location.href='".SITEURL."jobpost1'</script>";

					else

					echo "<script>location.href='".SITEURL."employer_details'</script>";

				}

				else if($mytype == 2){
					
					$profile_link = SITEURL."staff_details";
					$upload_link = SITEURL."staff_payment4";
					
					$fullname = $name." ".$lname;

					//$to = $email;
					//$subject = "Welcome to Staff Express";
					$mail = new PHPMailer();
					$mail->IsSMTP();

					$mail->Host       = "cp-wc12.syd02.ds.network"; // cp-wc12.syd02.ds.network
					$mail->SMTPAuth   = true;  
					$mail->SMTPSecure = "ssl";                
					$mail->Port       = 465;                    
					$mail->Username   = "contact@staffexpress.com.au";            
					$mail->Password   = "QYhi[=Aoor{t";

					$mail->From = ADMINEMAIL;
					$mail->FromName = "Staff Express";
					$mail->Subject = "Successfully Registered in Staff Express";
					$mail->isHTML(true);
					$mail->AddAddress($email);

					$content='<table cellpadding="0" cellspacing="0" width="600px" style="margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;">
							<tr>
							<td colspan="2" style="padding-left:10px;padding-top:10px;">Dear '.$name.',</td>
							</tr>
							<tr>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
							<td colspan="2" style="padding-left:10px;">Thank You for Registering with Staff Express.</td>
							</tr>
							<tr>
							<td colspan="2" style="padding-left:10px;">We have been designed and created to match employers needing short term cover with available staff.</td>
							</tr>
							<tr>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
							<td colspan="2" style="padding-left:10px;">In the meantime, you may always change your settings, availability and radius of notifications on the site at any time. Simply access this page <a href="'.$profile_link.'" target="_blank">here</a>.</td>
							</tr>
							<tr>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
							<td colspan="2" style="padding-left:10px;">We highly recommend uploading a profile picture, a 20 second video of yourself <a href="'.$upload_link.'" target="_blank">here</a> and your job experience here if you haven\'t already done so. From candidates who apply, employers will tend to choose someone with a profile picture, a video of themselves and work experience listed. We recommend you do this to be able to get access to the best paying roles.</td>
							</tr>
							<tr>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
							<td colspan="2" style="padding-left:10px;">Click <a href="'.$upload_link.'" target="_blank">here</a> to access this page.</td>
							</tr>
							<tr>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
							<td colspan="2" style="padding-left:10px;">Also so that you know Staff Express does have a free clock in and out feature which you may use on your day of work which would be useful in recording your hours. Also do keep your own records in the event of any issues.</td>
							</tr>
							<tr>
							<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
						  <td colspan="2" style="padding-left:10px;padding-bottom:10px;">Thanks,<br>Staff Express</td>
						  </tr>
						  </table>';
						  
						  $mail->Body = $content;
						  if(!$mail->Send()) {
							echo $mail->ErrorInfo;
						  } else {
							
						  }

						  /*$fromemail = ADMINEMAIL;
						  $headers = "MIME-Version: 1.0" . "\r\n";
						  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						  $headers .= 'From: <'.$fromemail.'>' . "\r\n";
						  mail($to, $subject, $content, $headers);*/
						  
						  
						  // mail to admin
						  //$to = ADMINEMAIL;
						  $mail2 = new PHPMailer();
							$mail2->IsSMTP();

							$mail2->Host       = "cp-wc12.syd02.ds.network"; // cp-wc12.syd02.ds.network
							$mail2->SMTPAuth   = true;  
							$mail2->SMTPSecure = "ssl";                
							$mail2->Port       = 465;                    
							$mail2->Username   = "contact@staffexpress.com.au";            
							$mail2->Password   = "QYhi[=Aoor{t";

							$mail2->From = ADMINEMAIL;
							$mail2->FromName = "Staff Express";
							$mail2->Subject = "New Staff Registered";
							$mail2->isHTML(true);
							$mail2->AddAddress(ADMINEMAIL);
		  
						//$subject = "New Staff Registered";
				
						$content='<table cellpadding="0" cellspacing="0" width="600px" style="margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;">
								<tr>
								<td colspan="2" style="padding-left:10px;padding-top:10px;">Hi Admin,</td>
								</tr>
								<tr>
								<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:10px;">A new staff has registered in the Staff Express.</td>
								</tr>
								<tr>
								<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:10px;">Details are:</td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:10px;"><strong>Name:</strong> '.$fullname.'</td>
								</tr>
								<tr>
								<td colspan="2" style="padding-left:10px;"><strong>Email:</strong> '.$email.'</td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
								<td colspan="2" style="padding-left:10px;padding-bottom:10px;">Thanks,<br>Staff Express</td>
								</tr>
								</table>';
								
								$mail2->Body = $content;
								if(!$mail2->Send()) {
									echo $mail2->ErrorInfo;
								 } else {
									
								  }
				
						/*$fromemail = ADMINEMAIL;
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$headers .= 'From: <'.$fromemail.'>' . "\r\n";
						mail($to, $subject, $content, $headers);*/

					if($do == "search"){

						echo "<script>location.href='".SITEURL."viewmyjob/".base64_encode($job)."'</script>";

					}

					else{

					echo "<script>location.href='".SITEURL."thankyou'</script>";

					
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
		else{
			echo "<script>location.href='".SITEURL."signup/?error=3'</script>";
		}
		}
		else{
			echo "<script>location.href='".SITEURL."signup/?error=3'</script>";
		}
	}

}

$job = isset($_REQUEST['job'])?trim($_REQUEST['job']):"";



$user = isset($_REQUEST['user'])?trim($_REQUEST['user']):"";

$do = isset($_REQUEST['do'])?trim($_REQUEST['do']):"";

?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 3){

						echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Invalid captcha.</div>";

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

									<option value="1" <?php if((isset($_SESSION['SESSID']) && $_SESSION['SESSID'] != "") ||(isset($_POST['mytype']) && $_POST['mytype'] == '1')) echo "selected";?>>Employer</option>

									<option value="2" <?php if((isset($_REQUEST['do']) && $_REQUEST['do'] == "search") || (isset($_POST['mytype']) && $_POST['mytype'] == '2')) echo "selected";?>>Staff</option>

								  </select>

							</div>

							<div class="col-sm-12">

								<div <?php echo $usrnamestyle;?> class="row forstaff">

									<div class="col-sm-6" >

										<lable>First Name</lable>

										<input type="text" placeholder="First Name" name="fname" id="fname" class="form-control required" value="<?php if(isset($_POST['fname'])) echo stripslashes($_POST['fname']);?>">

									</div>

									<div class="col-sm-6">

										<lable>Last Name</lable>

										<input type="text" placeholder="Last Name" name="lname" id="lname" class="form-control required" value="<?php if(isset($_POST['lname'])) echo stripslashes($_POST['lname']);?>">

									</div>

								</div>

							</div>

							<div class="col-sm-6" id="foremp" <?php echo $compnamestyle;?>>

								<lable>Business Name</lable>

								<input type="text" placeholder="Business Name" name="name" id="name" class="form-control" value="<?php if(isset($_POST['name'])) echo stripslashes($_POST['name']);?>">

							</div>

							

							<div class="col-sm-6">

								<lable>Email Address</lable>

								<input type="email" placeholder="Email" name="email" id="email" class="form-control required" value="<?php if(isset($_POST['email'])) echo stripslashes($_POST['email']);?>">

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

							<div style="clear:both;"></div>
							<div class="col-sm-6">

							<div class="g-recaptcha" data-sitekey="6Ld-C9QhAAAAAFb5QR3J3fayR6MI4sbu6tIWlFEH"></div>

							</div>
							<div style="clear:both;"></div>
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