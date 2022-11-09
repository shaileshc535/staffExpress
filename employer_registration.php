<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login'</script>";
	exit;
}


if(count($_POST) > 0){

	$name = isset($_POST['name'])?tep_db_input($_POST['name']):"";
	$business_name = isset($_POST['business_name'])?tep_db_input($_POST['business_name']):"";
	$address = isset($_POST['address'])?tep_db_input($_POST['address']):"";
	$suburb = isset($_POST['suburb'])?tep_db_input($_POST['suburb']):"";
	$state = isset($_POST['state'])?tep_db_input($_POST['state']):"";
	$postcode = isset($_POST['postcode'])?tep_db_input($_POST['postcode']):"";
	$country = isset($_POST['country'])?tep_db_input($_POST['country']):"";
	$abnacn = isset($_POST['abnacn'])?tep_db_input($_POST['abnacn']):"";
	$number = isset($_POST['number'])?tep_db_input($_POST['number']):"";
	$contact_ofc = isset($_POST['contact_ofc'])?tep_db_input($_POST['contact_ofc']):"";
	$phone = isset($_POST['phone'])?tep_db_input($_POST['phone']):"";
	$pass = isset($_POST['pass'])?tep_db_input($_POST['pass']):"";
	$cpass = isset($_POST['cpass'])?tep_db_input($_POST['cpass']):"";
	
	$notified = isset($_POST['notified'])?tep_db_input($_POST['notified']):"";
	
	$notified_msg = isset($_POST['notified_msg'])?tep_db_input($_POST['notified_msg']):"";

	if($abnacn == "")
	$number = "";
	else
	$number = $number;

	$phone = str_replace(" ", "", $phone);
	$firstdigit = substr($phone, 0, 1);
	if($firstdigit == 0)
	$phone = "61".substr($phone, 1, 10);
	else
	$phone = "61".$phone;
	
	dbQuery($dbConn, "UPDATE users set name = '".$name."', business_name = '".$business_name."', address = '".$address."', suburb = '".$suburb."', `state` = '".$state."', postcode = '".$postcode."', country = '".$country."', abnacn = '".$abnacn."', number = '".$number."', contact_ofc = '".$contact_ofc."', phone = '".$phone."', notified = '".$notified."', notified_msg = '".$notified_msg."' where id = '".$_SESSION['loginUserId']."'");

	$mytime=time();
	$size = 500*1024;
	if(isset($_FILES["company_img"]) && $_FILES["company_img"]["size"]>0 && $_FILES["company_img"]["size"] <= $size){
		
		$updimgnm='';

		$srcfile = $_FILES['company_img']['type'];
		$imageinfo = getimagesize($_FILES['company_img']['tmp_name']); //check image size
		
		$mflname = strtolower($_FILES['company_img']['name']);
		$mflname = str_replace(" ", "_", $mflname);
		
		if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))
		{
			$errstring1="";
		}
		else
		{
			$errstring1="Please Upload only image file.(.jpg,.png,.gif)";
		
		}
		
				$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
		
				if(strpos($mflname,".php")===false && strpos($mflname,".phtml")===false && strpos($mflname,".php3")===false && strpos($mflname,".php4")===false && strpos($mflname,".php5")===false && strpos($mflname,".html")===false && strpos($mflname,".htm")===false && strpos($mflname,".exe")===false) 
				{
							
					$errstring2="";
				}
				else
				{
					
					$errstring2="Please Upload only image file.(.jpg,.png,.gif)";
				}
				
				$dblchk = array();
				
				$dblchk=explode(".",$mflname);
				
				if(count($dblchk)==2) 
				{
							
					$errstring3="";
				}
				else
				{
					
					$errstring3="Please Upload only image file.(.jpg,.png,.gif)";
				}
		
		if($errstring1=="" && $errstring2=="")
		{
			if(move_uploaded_file($_FILES["company_img"]["tmp_name"],"uploads/" . $mytime.'_'.$mflname))
			{
				
			$sqlequip = dbQuery($dbConn, "SELECT company_img from users where id = '".$_SESSION['loginUserId']."'");
			$row = dbFetchArray($sqlequip);
			@unlink("uploads/".$row['company_img']);
	
			$updimgnm = $mytime.'_'.$mflname;

				dbQuery($dbConn, "UPDATE users set company_img = '".$updimgnm."' where id='".$_SESSION['loginUserId']."'");
		
			}
		}
	}
	
	if($pass && $cpass){
		if($pass == $cpass){
			dbQuery($dbConn, "UPDATE users set password = '".md5($pass)."' where id = '".$_SESSION['loginUserId']."'");
		}
		else{
			echo "<script>location.href='".SITEURL."employer_details?error=1'</script>";
			exit;
		}
	}

	echo "<script>location.href='".SITEURL."employer_details?success=1'</script>";
	exit;

}

$checkdetls = dbQuery($dbConn, "SELECT name,email,business_name,address,suburb,state,abnacn,number,contact_ofc,phone,postcode,country,company_img,notified,notified_msg from users where id = '".$_SESSION['loginUserId']."'");
$getdetls = dbFetchArray($checkdetls);
?>
<div class="works works_success my_job_sty employer_registration_sty login_page">
	<div class="container">
		<div class="splnav">

			<ul class="onlyemployernav">

				<li <?php if($page == "success.php" || $page == "viewmyjob.php" || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">My Jobs</a></li>

				<li><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>

				<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>

				<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>

				<!--<li><a href="<?php echo SITEURL."logout";?>">Logout</a></li>-->

			</ul>

		</div>
		<div class="row">
			<!--<?php include_once "employer_left.php";?>-->
			<div class="col-lg-8 m-auto">
			<div class="jobpostnewbgdesn">
			<?php
					if(isset($_REQUEST['success']) && $_REQUEST['success'] == 1){
					?>
						<div class='alert-success' style='padding:15px;margin-bottom:15px;'>Profile updated successfully.</div>
					<?php
					}
					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 2){
						echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Password and confirm password must be same.</div>";
					}
					?>
				<h4>Employer Details</h4>
				<div class="login" style="margin-top:20px;padding:0;">					
					<form action="" method="post" id="employer_info" class="eplyfrm" enctype="multipart/form-data">
						<div class="row">
							<?php
							//if($getdetls['business_name'] == '' && $getdetls['address'] == '' && $getdetls['abnacn'] == ''){
							?>
							<div class="col-sm-6">
								<label>Business Name</label>
								<input type="text" placeholder="Enter Name" value="<?php echo $getdetls['name'];?>" name="name" id="name" class="form-control required">
								</div>
							<div class="col-sm-6">
								<label>Company Name</label>
								<input type="text" placeholder="Enter Name" name="business_name" class="form-control" value="<?php echo $getdetls['business_name'];?>">
							</div>
							<div class="col-sm-6">
								<label>Email Address</label>
								<input type="text" placeholder="Enter Email" value="<?php echo $getdetls['email'];?>" class="form-control required" readonly>
							</div>
							<div class="col-sm-6">
								<label>Mobile (Main Person)</label>
								<input type="text" placeholder="Mobile (Main Person) with country code" name="phone" class="form-control required digits" value="<?php echo substr($getdetls['phone'],2,10);?>">
							</div>
							<div class="col-sm-6">
								<label>Address</label>
								<input type="text" placeholder="Enter Address" name="address" class="form-control required" value="<?php echo $getdetls['address'];?>">
							</div>
							<div class="col-sm-6">
								<label>Suburb</label>
								<input type="text" placeholder="Enter Suburb" name="suburb" class="form-control required" value="<?php echo $getdetls['suburb'];?>">
							</div>
							<div class="col-sm-6">
								<label>State</label>
									<!--<input type="text" placeholder="Enter State" id="state" name="state" class="form-control required" value="<?php //echo $getdetls['state'];?>">-->
									<select class="form-select required" name="state" id="state">

									<option value="">State</option>
									<?php
									$states = dbQuery($dbConn, "select * from states order by states");
									while($staterow = dbFetchArray($states)){
										?>
										<option value="<?php echo $staterow['id'];?>" <?php if($staterow['id']==$getdetls['state']) echo "selected";?>><?php echo stripslashes($staterow['states']);?></option>
										<?php
										
									}
									?>
									</select>
							</div>
							<div class="col-sm-6">
								<label>Postcode</label>
									<input type="text" placeholder="Enter Postcode" id="postcode" name="postcode" class="form-control required" value="<?php echo $getdetls['postcode'];?>">
							</div>
							<div class="col-sm-6">
								<label>Select Country</label>
								<select class="form-select required" name="country" id="country" style="margin-bottom:30px;">
									<option selected value="">Country</option>
									<?php
									$cat = dbQuery($dbConn, "select iso_code_2,name from oc_country order by name");
									while($catrow = dbFetchArray($cat)){
									?>
									<option value="<?php echo $catrow['iso_code_2'];?>" <?php if($catrow['iso_code_2']==$getdetls['country']) echo "selected";?>><?php echo stripslashes($catrow['name']);?></option>
									<?php
									}
									?>
								</select>
							</div>
							
							<div class="col-sm-6">
								<label>Contact (Office)</label>
								<input type="text" placeholder="Contact (Office)" name="contact_ofc" class="form-control digits" value="<?php echo $getdetls['contact_ofc'];?>">
							</div>

							<div class="col-sm-6">
								<label>ABN or ACN Number</label>
								<select class="form-select" name="abnacn" id="abnacn" style="margin-bottom:30px;">
								<option value="">Select</option>
									<option value="ABN" <?php if($getdetls['abnacn']=="ABN") echo "selected";?>>ABN</option>
									<option value="ACN" <?php if($getdetls['abnacn']=="ACN") echo "selected";?>>ACN</option>
									<option value="" <?php if($getdetls['abnacn']=="") echo "selected";?>>Don't have any</option>
								  </select>
							</div>
							<?php
							if($getdetls['abnacn'] == 'ABN' || $getdetls['abnacn'] == 'ACN')
								$style = '';
							else
								$style = 'display:none;';
							?>
							<div class="col-sm-6" style="position:relative;<?php echo $style;?>" id="abnacnno">
								<label>Number</label>
								<input type="text" placeholder="Number" id="number" name="number" class="form-control digits" value="<?php echo $getdetls['number'];?>">
								<span id="error" style="color:red;position: absolute;width:50%;text-align: right;font-size: 12px;z-index: 99;padding-top: 7px;padding-right:10px;right: 10px;top:40px;"></span>

							</div>
							
							<div class="col-sm-6">
								<label>Password</label>
								<input type="password" placeholder="Enter Password" name="pass" id="pass" class="form-control " style="margin-bottom:5px;">
								<span style="font-size:12px;padding-bottom:0px;">(Leave blank if you don't want to change password)</span>
							</div>
							<div class="col-sm-6">
								<label>Confirm Password</label>
								<input type="password" placeholder="Enter Confirm Password" name="cpass" id="cpass" class="form-control ">
								<span id="error" style="color:red;"></span>
							</div>
							
							</div>
							<hr class="frmsecdevid" />
							<div class="row">
							<div class="col-sm-12"> 
								<label>Notification Settings</label>
									<label>How would you like to be notified of candidates?</label><br>

									<div class="radio_box">

									<div class="radio_box_single">

									<input type="radio" name="notified" value="Email" required <?php if($getdetls['notified']=='Email') echo "checked";?>> <span>Email</span>

									</div>



									<div class="radio_box_single">

									<input type="radio" name="notified" value="SMS" <?php if($getdetls['notified']=='SMS') echo "checked";?>> <span>SMS</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified" value="Both" <?php if($getdetls['notified']=='Both') echo "checked";?>> <span>Both</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified" value="Off" <?php if($getdetls['notified']=='Off') echo "checked";?>> <span>Disable</span>

									</div>

									</div>
									
									<label>How would you like to be notified for new messages?</label><br>

									<div class="radio_box">

									<div class="radio_box_single">

									<input type="radio" name="notified_msg" value="Email" required <?php if($getdetls['notified_msg']=='Email') echo "checked";?>> <span>Email</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified_msg" value="SMS" <?php if($getdetls['notified_msg']=='SMS') echo "checked";?>> <span>SMS</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified_msg" value="Both" <?php if($getdetls['notified_msg']=='Both') echo "checked";?>> <span>Both</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified_msg" value="Off" <?php if($getdetls['notified_msg']=='Off') echo "checked";?>> <span>Disable</span>

									</div>

									</div>


								</div>
							</div>
							
							<hr class="frmsecdevid" />
							<div class="row">
							
							<div class="col-sm-6">
								<label>Company Logo</label>
								<?php
								if($getdetls['company_img']){
									?>
									<img src="<?php echo SITEURL;?>uploads/<?php echo $getdetls['company_img'];?>" alt="" width="136"><br>
									<?php
								}
								?>
								<input type="file" name="company_img" id="company_img" class="form-control" accept="image/*" style="margin-bottom:10px;">
								<span style="margin-bottom:30px;">(JPG, PNG or GIF image up to 500KB)</span>
							</div>

							<?php
							//}
							?>	
							<div class="col-sm-6">&nbsp;</div>
							<div class="col-sm-4">
								<input type="submit" value="Submit">
							</div>
						</div>
					</form>
					
				</div>
			</div>
			</div>
			</div>
		</div>
	</div>
</div>
	
<?php include_once('footer.php');?>