<?php

include('config/config.php'); 

include_once "config/common.php";

$dbConn = establishcon();

include_once('header.php');



if(!isset($_SESSION['loginUserId'])){

	echo "<script>location.href='".SITEURL."staff_login'</script>";

	exit;

}


$current_year = date('Y');

$working_days = '';

if(count($_POST) > 0){

	$address = isset($_POST['address'])?strip_tags(tep_db_input($_POST['address'])):"";

	$postcode = isset($_POST['postcode'])?strip_tags(tep_db_input($_POST['postcode'])):"";

	$suburb = isset($_POST['suburb'])?strip_tags(tep_db_input($_POST['suburb'])):"";
	
	$state = isset($_POST['state'])?strip_tags(tep_db_input($_POST['state'])):"";

	$country = isset($_POST['country'])?strip_tags(tep_db_input($_POST['country'])):"";

	$dob = isset($_POST['dob'])?strip_tags(tep_db_input($_POST['dob'])):"";

	$phone = isset($_POST['phone'])?strip_tags(tep_db_input($_POST['phone'])):"";

	

	$catid = isset($_POST['catid'])?$_POST['catid']:array();
	$maincatid = isset($_POST['maincatid'])?$_POST['maincatid']:array();

	$experience = isset($_POST['experience'])?tep_db_input($_POST['experience']):"";

	$distance = isset($_POST['distance'])?strip_tags(tep_db_input($_POST['distance'])):"";



	$working_days = isset($_POST['working_days'])?$_POST['working_days']:array();

	$timesavl = isset($_POST['timesavl'])?tep_db_input($_POST['timesavl']):"";

	$notified = isset($_POST['notified'])?tep_db_input($_POST['notified']):"";
	
	$notified_msg = isset($_POST['notified_msg'])?tep_db_input($_POST['notified_msg']):"";
	$notified_job_type = isset($_POST['notified_job_type'])?tep_db_input($_POST['notified_job_type']):"";

	$license = isset($_POST['license'])?tep_db_input($_POST['license']):"";
	$for_license = isset($_POST['for_license'])?tep_db_input($_POST['for_license']):"";
	$alcohol = isset($_POST['alcohol'])?tep_db_input($_POST['alcohol']):"";
	$working_with_child = isset($_POST['working_with_child'])?tep_db_input($_POST['working_with_child']):"";

	$white_card = isset($_POST['white_card'])?tep_db_input($_POST['white_card']):"";
	$police_clearnce = isset($_POST['police_clearnce'])?tep_db_input($_POST['police_clearnce']):"";
	$int_job_type = isset($_POST['int_job_type'])?tep_db_input($_POST['int_job_type']):"";
	
	$qual = isset($_POST['qual'])?$_POST['qual']:array();

	$birth_year = date('Y', strtotime($dob));

	if(count($working_days) > 0){

		$working_days = implode(",", $working_days);

	}

	

	$phone = str_replace(" ", "", $phone);

	$firstdigit = substr($phone, 0, 1);

	if($firstdigit == 0)

	$phone = "61".substr($phone, 1, 10);

	else

	$phone = "61".$phone;

	if(($current_year - $birth_year) >= 16)

	{

	$check = dbQuery($dbConn, "select id from staff_details where staff_id = '".$_SESSION['loginUserId']."'");

	if(dbNumRows($check) > 0){

		dbQuery($dbConn, "UPDATE staff_details set dob = '".$dob."', address = '".$address."', postcode = '".$postcode."', suburb = '".$suburb."', state = '".$state."', country = '".$country."', distance = '".$distance."', working_days = '".$working_days."', timesavl = '".$timesavl."', notified = '".$notified."', notified_msg = '".$notified_msg."', notified_job_type = '".$notified_job_type."', license = '".$license."', for_license = '".$for_license."', alcohol = '".$alcohol."', working_with_child = '".$working_with_child."', white_card = '".$white_card."', police_clearnce = '".$police_clearnce."', int_job_type = '".$int_job_type."' where staff_id = '".$_SESSION['loginUserId']."'");

		dbQuery($dbConn, "UPDATE users set phone = '".$phone."' where id = '".$_SESSION['loginUserId']."'");

	}

	else{

		dbQuery($dbConn, "INSERT into staff_details set staff_id = '".$_SESSION['loginUserId']."', dob = '".$dob."', address = '".$address."', postcode = '".$postcode."', suburb = '".$suburb."', state = '".$state."', country = '".$country."', distance = '".$distance."', working_days = '".$working_days."', timesavl = '".$timesavl."', notified = '".$notified."', notified_msg = '".$notified_msg."',  notified_job_type = '".$notified_job_type."', license = '".$license."', for_license = '".$for_license."', alcohol = '".$alcohol."', working_with_child = '".$working_with_child."', white_card = '".$white_card."', police_clearnce = '".$police_clearnce."', int_job_type = '".$int_job_type."'");

		dbQuery($dbConn, "UPDATE users set phone = '".$phone."' where id = '".$_SESSION['loginUserId']."'");

	}


	if(count($maincatid) > 0){

		dbQuery($dbConn, "DELETE FROM staff_job_parent_cat where staff_id = '".$_SESSION['loginUserId']."'");

		foreach($maincatid as $val){

			dbQuery($dbConn, "INSERT into staff_job_parent_cat set staff_id = '".$_SESSION['loginUserId']."', catid = '".$val."'");

		}

	}
	
	if(count($catid) > 0){

		dbQuery($dbConn, "DELETE FROM staff_job_cat where staff_id = '".$_SESSION['loginUserId']."'");

		foreach($catid as $val){

			dbQuery($dbConn, "INSERT into staff_job_cat set staff_id = '".$_SESSION['loginUserId']."', catid = '".$val."'");

		}

	}

	if(count($qual) > 0){

		dbQuery($dbConn, "DELETE FROM staff_qualification where staff_id = '".$_SESSION['loginUserId']."'");

		foreach($qual as $val){

			dbQuery($dbConn, "INSERT into staff_qualification set staff_id = '".$_SESSION['loginUserId']."', qualification = '".$val."'");

		}

	}



	$mytime=time();

	$size = 500*1024;

	if(isset($_FILES["image"]) && $_FILES["image"]["size"]>0 && $_FILES["image"]["size"] <= $size){

		

		$updimgnm='';



		$srcfile = $_FILES['image']['type'];

		$imageinfo = getimagesize($_FILES['image']['tmp_name']); //check image size

		

		$mflname = strtolower($_FILES['image']['name']);

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

			if(move_uploaded_file($_FILES["image"]["tmp_name"],"uploads/" . $mytime.'_'.$mflname))

			{

				

			$sqlequip = dbQuery($dbConn, "SELECT `image` from users where id = '".$_SESSION['loginUserId']."'");

			$row = dbFetchArray($sqlequip);

			@unlink("uploads/".$row['image']);

	

			$updimgnm = $mytime.'_'.$mflname;



				dbQuery($dbConn, "UPDATE users set `image` = '".$updimgnm."' where id='".$_SESSION['loginUserId']."'");

		

			}

		}

	}

	if(isset($_FILES["resume"]) && $_FILES["resume"]["size"]>0)

        {

            $updimgnm='';

            

                $mytime=time();

                

                $srcfile = $_FILES['resume']['type'];


                

                $mflname = strtolower($_FILES['resume']['name']);

                $mflname = str_replace(" ", "_", $mflname);




                if($srcfile == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $srcfile == "application/pdf")

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

                    

                    if(move_uploaded_file($_FILES["resume"]["tmp_name"], "uploads/resumes/" . $mytime.'_'.$mflname))

                    {

                        $myfile = $mytime.'_'.$mflname;

                        dbQuery($dbConn, "UPDATE staff_details set resume = '".$myfile."' where staff_id = '".$_SESSION['loginUserId']."'");

                    }

                }


        }

		dbQuery($dbConn, "UPDATE staff_details set profile_step = 1 where staff_id = '".$_SESSION['loginUserId']."'");

		echo "<script>location.href='".SITEURL."staff_experience'</script>";

		exit;

	}

	/*else{

		echo "<script>location.href='".SITEURL."staff_details?error=1'</script>";

	}*/



}



?>

<?php

$catids = array();
$maincatids = array();

$checkdetls = dbQuery($dbConn, "SELECT email,phone,image from users where id = '".$_SESSION['loginUserId']."'");

$getdetls = dbFetchArray($checkdetls);



$mycats = dbQuery($dbConn, "SELECT catid from staff_job_cat where staff_id = '".$_SESSION['loginUserId']."'");

while($mycatsrow = dbFetchArray($mycats)){

	$catids[] = $mycatsrow['catid'];

}

$mymaincats = dbQuery($dbConn, "SELECT catid from staff_job_parent_cat where staff_id = '".$_SESSION['loginUserId']."'");

while($mycatsrow = dbFetchArray($mymaincats)){

	$maincatids[] = $mycatsrow['catid'];

}


?>

	<div class="works works_success i_am_staff_sty">

		<div class="container">

		<div class="stepbystp">

			<ul>

				<li class="actv">My Profile</li>

				<li><img src="images/dividr.png" alt="" /></li>

				<li>Experience</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Health Info</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Optional Upload</li>
				
			</ul>

		</div>

			<div class="row">

			<?php 

					include_once "staff_left.php";

					?>		

				<div class="col-lg-9">

					<h4>My Profile

					</h4>

					<div class="sign_up" style="margin-top:20px;">

						

						<?php

						$staffdetls = dbQuery($dbConn, "SELECT * from staff_details where staff_id = '".$_SESSION['loginUserId']."'");
						$row = dbFetchArray($staffdetls);
						if(isset($_REQUEST['success']) && $_REQUEST['success'] == 1){

							?>

								<div class='alert-success' style='padding:15px;margin-bottom:15px;'>Profile updated successfully.</div>

							<?php

						}

						$dob = isset($_POST['dob'])?tep_db_input($_POST['dob']):"";

						$birth_year = date('Y', strtotime($dob));

						if(($current_year - $birth_year) < 16){

							echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Must be minimum 16 years old.</div>";

						}



						if($row['address'])

						$address = $row['address'];

						if(isset($_POST['address']))

						$address = $_POST['address'];



						if($row['suburb'])

						$suburb = $row['suburb'];

						if(isset($_POST['suburb']))

						$suburb = $_POST['suburb'];
						
						
						if($row['state'])

						$state = $row['state'];

						if(isset($_POST['state']))

						$state = $_POST['state'];



						if($row['postcode'])

						$postcode = $row['postcode'];

						if(isset($_POST['postcode']))

						$postcode = $_POST['postcode'];



						if($row['country'])

						$country = $row['country'];

						if(isset($_POST['country']))

						$country = $_POST['country'];



						if($row['dob'])

						$dob = $row['dob'];

						if(isset($_POST['dob']))

						$dob = $_POST['dob'];



						if(count($catids) > 0)

						$catids = $catids;

						if(isset($_POST['catid']))

						$catids = $_POST['catid'];
						
						if(count($maincatids) > 0)

						$maincatids = $maincatids;

						if(isset($_POST['maincatid']))

						$maincatids = $_POST['maincatid'];



						if($row['experience'])

						$experience = $row['experience'];

						if(isset($_POST['experience']))

						$experience = $_POST['experience'];



						if($row['distance'])

						$distance = $row['distance'];

						if(isset($_POST['distance']))

						$distance = $_POST['distance'];

						

						if($row['working_days'])

						$working_days = $row['working_days'];

						if(isset($_POST['working_days'])){

							$working_days = $_POST['working_days'];

							$working_days = implode(",", $working_days);

						}



						if($row['notified'])

						$notified = $row['notified'];

						if(isset($_POST['notified']))

						$notified = $_POST['notified'];
						
						
						if($row['notified_msg'])

						$notified_msg = $row['notified_msg'];

						if(isset($_POST['notified_msg']))

						$notified_msg = $_POST['notified_msg'];
						
						if($row['notified_job_type'])

						$notified_job_type = $row['notified_job_type'];

						if(isset($_POST['notified_job_type']))

						$notified_job_type = $_POST['notified_job_type'];


						if($getdetls['phone'])

						$phone = substr($getdetls['phone'], 2, 10);

						if(isset($_POST['phone']))

						$phone = $_POST['phone'];

						if($row['license'])

						$license = $row['license'];

						if(isset($_POST['license']))

						$license = $_POST['license'];

						if($row['for_license'])

						$for_license = $row['for_license'];

						if(isset($_POST['for_license']))

						$for_license = $_POST['for_license'];

						if($row['alcohol'])

						$alcohol = $row['alcohol'];

						if(isset($_POST['alcohol']))

						$alcohol = $_POST['alcohol'];

						if($row['working_with_child'])

						$working_with_child = $row['working_with_child'];

						if(isset($_POST['working_with_child']))

						$working_with_child = $_POST['working_with_child'];

						if($row['white_card'])

						$white_card = $row['white_card'];

						if(isset($_POST['white_card']))

						$white_card = $_POST['white_card'];

						if($row['police_clearnce'])

						$police_clearnce = $row['police_clearnce'];

						if(isset($_POST['police_clearnce']))

						$police_clearnce = $_POST['police_clearnce'];
						
						
						
						?>

						<form action="" method="post" id="stffprofile" class="eplyfrm" enctype="multipart/form-data">

							<div class="row">

								<div class="col-sm-6">

									<input type="text" placeholder="Address" name="address" class="form-control required" value="<?php echo $address;?>">

								</div>

								<div class="col-sm-6">

									<input type="text" placeholder="Suburb" name="suburb" class="form-control required" value="<?php echo $suburb;?>">

								</div>

								<div class="col-sm-6">

									<input type="text" placeholder="Postcode" name="postcode" class="form-control required digits" value="<?php echo $postcode;?>">

								</div>
								
								<div class="col-sm-6">

									<!--<input type="text" placeholder="State" name="state" class="form-control required" value="<?php //echo $state;?>">-->
									<select class="form-select required splselectgapmob" name="state" id="state">

									<option value="">State</option>
									<?php
									$states = dbQuery($dbConn, "select * from states order by states");

									while($staterow = dbFetchArray($states)){
										?>
										<option value="<?php echo $staterow['id'];?>" <?php if($staterow['id']==$state) echo "selected";?>><?php echo stripslashes($staterow['states']);?></option>
										<?php
										
									}
									?>
									</select>

								</div>

								<div class="col-sm-6">

									<select class="form-select required" name="country" id="country">

									<option selected value="">Country</option>

									<?php

									$cat = dbQuery($dbConn, "select iso_code_2,name from oc_country order by name");

									while($catrow = dbFetchArray($cat)){

									?>

									<option value="<?php echo $catrow['iso_code_2'];?>" <?php if($catrow['iso_code_2']==$country) echo "selected";?>><?php echo stripslashes($catrow['name']);?></option>

									<?php

									}

									?>

								  </select>

								</div>
								
								</div>
							<hr class="frmsecdevid" />
							<div class="row">

								<div class="col-sm-6">

									<label>Date of Birth (Must be minimum 16 years)</label>

									<input type="text" placeholder="Date of birth" name="dob" id="dob" class="form-control required content_m_b_10 profldatbirthadjut" value="<?php echo $dob;?>">

								</div>

								<div class="col-sm-6">		

									<label>Email address</label>

									<input type="email" placeholder="Email" class="form-control" value="<?php echo $getdetls['email'];?>" readonly>

								</div>
								</div>
							<hr class="frmsecdevid" />
							<div class="row onlystdetlsdrpdwn">

								<div class="col-sm-6">
								<div class="dropdown_box" style="width:100%">
									<label>Driving License</label>

									<select class="form-select content_m_b_10 required" name="license">

										<option value="">Select</option>

											<option value="1" <?php if($license!="" && $license==1) echo "selected";?>>Yes</option>
											<option value="2" <?php if($license!=""  && $license==2) echo "selected";?>>No</option>

										</select>
								</div>
								</div>

								<div class="col-sm-6">		
								<div class="dropdown_box" style="width:100%">
									<label>Forklift License</label>

									<select class="form-select content_m_b_10 required" name="for_license">

										<option value="">Select</option>

											<option value="1" <?php if($for_license!="" && $for_license==1) echo "selected";?>>Yes</option>
											<option value="2" <?php if($for_license!=""  && $for_license==2) echo "selected";?>>No</option>

										</select>
								</div>
								</div>

								<div class="col-sm-6">
								<div class="dropdown_box" style="width:100%">
									<label>Responsible Service of Alcohol</label>

									<select class="form-select content_m_b_10 required" name="alcohol">

										<option value="">Select</option>

											<option value="1" <?php if($alcohol!="" && $alcohol==1) echo "selected";?>>Yes</option>
											<option value="2" <?php if($alcohol!=""  && $alcohol==2) echo "selected";?>>No</option>

										</select>
								</div>
								</div>

								<div class="col-sm-6">
								<div class="dropdown_box" style="width:100%">

									<label>Working with Children Check</label>

									<select class="form-select content_m_b_10 required" name="working_with_child">

										<option value="">Select</option>

											<option value="1" <?php if($working_with_child!="" && $working_with_child==1) echo "selected";?>>Yes</option>
											<option value="2" <?php if($working_with_child!=""  && $working_with_child==2) echo "selected";?>>No</option>

										</select>
								</div>
								</div>

								<div class="col-sm-6">
								<div class="dropdown_box" style="width:100%">
									<label>White Card</label>

									<select class="form-select content_m_b_10 required" name="white_card">

										<option value="">Select</option>

											<option value="1" <?php if($white_card!="" && $white_card==1) echo "selected";?>>Yes</option>
											<option value="2" <?php if($white_card!=""  && $white_card==2) echo "selected";?>>No</option>

										</select>
								</div>
								</div>

									<div class="col-sm-6">
										<div class="dropdown_box" style="width:100%">

											<label>National Police Clearance</label>

											<select class="form-select content_m_b_10 required" name="police_clearnce">

												<option value="">Select</option>

													<option value="1" <?php if($police_clearnce!="" && $police_clearnce==1) echo "selected";?>>Yes</option>
													<option value="2" <?php if($police_clearnce!=""  && $police_clearnce==2) echo "selected";?>>No</option>

												</select>
										</div>
									</div>
									<!--<div class="col-sm-6">
										<div class="dropdown_box" style="width:100%">
											<label>Interested Job Type</label>

											<select class="form-select content_m_b_10 required" name="int_job_type">

												<option value="">Select</option>

													<option value="2" <?php if($int_job_type!="" && $int_job_type==2) echo "selected";?>>In Person</option>
													<option value="1" <?php if($int_job_type!=""  && $int_job_type==1) echo "selected";?>>Remote</option>

												</select>
										</div>
									</div>-->
								</div>
								<hr class="frmsecdevid" />
								<div class="row">
							
								<div class="col-sm-6">

									<label>Select Interested Categories</label>

										<div class="mycatsec onlylivecatgrydesgnfix">
										<input type="hidden" id="myselectedcats" value="<?php if(is_array($maincatids)) echo count($maincatids);?>">
										<?php
										if(dbNumRows($mycats) == 0){
											if(count($maincatids)==0){
												?>
												<input class="form-control" id="showcats" name="showcats" type="text" placeholder="Job Category" readonly />
											<div id="allcats" style="display:none;">
											<ul class="items">

										<?php

											$cat = dbQuery($dbConn, "select * from category where parent_id=0 order by category");

											while($catrow = dbFetchArray($cat)){
												?>
												<li>
												<input id="maincat_<?php echo $catrow['id'];?>" type="checkbox" class="mycats" name="maincatid[]" value="<?php echo $catrow['id'];?>" data-value="<?php echo stripslashes($catrow['category']);?>" />&nbsp; <?php echo stripslashes($catrow['category']);?>
												<!--<div id="maincatli_<?php echo $catrow['id'];?>">
												</div>-->
											</li>
											<?php

										}

										?>

										</ul>
										</div>
										<?php
											}
											else{
												$noofclassfn = '';
											if(is_array($maincatids) && count($maincatids)>0){
												if(count($maincatids)> 1)
													$noofclassfn = count($maincatids)." classifications";
												else
													$noofclassfn = count($maincatids)." classification";
											}
											?>
											<input class="form-control" id="showcats" name="showcats" type="text" placeholder="Job Category" readonly value="<?php echo $noofclassfn;?>" />
											<div id="allcats">
											<ul class="items">

										<?php

											$cat = dbQuery($dbConn, "select * from category where parent_id=0 order by category");

											while($catrow = dbFetchArray($cat)){
												?>
												<li>
												<input id="maincat_<?php echo $catrow['id'];?>" type="checkbox" class="mycats" name="maincatid[]" value="<?php echo $catrow['id'];?>" data-value="<?php echo stripslashes($catrow['category']);?>" <?php if(in_array($catrow['id'],$maincatids)) echo "checked";?> />&nbsp; <?php echo stripslashes($catrow['category']);?>
												
											</li>
											<?php

										}

										?>

										</ul>
										</div>
												<?php
											}
										?>
										  
										<?php
										}
										else{
											$noofclassfn = '';
											if(is_array($maincatids) && count($maincatids)>0){
												if(count($maincatids)> 1)
													$noofclassfn = count($maincatids)." classifications";
												else
													$noofclassfn = count($maincatids)." classification";
											}
											?>
											<input class="form-control" id="showcats" name="showcats" type="text" placeholder="Job Category" readonly value="<?php echo $noofclassfn;?>" />
											<div id="allcats">
											<ul class="items">

										<?php

											$cat = dbQuery($dbConn, "select * from category where parent_id=0 order by category");

											while($catrow = dbFetchArray($cat)){
												?>
												<li>
												<input id="maincat_<?php echo $catrow['id'];?>" type="checkbox" class="mycats" name="maincatid[]" value="<?php echo $catrow['id'];?>" data-value="<?php echo stripslashes($catrow['category']);?>" <?php if(in_array($catrow['id'],$maincatids)) echo "checked";?> />&nbsp; <?php echo stripslashes($catrow['category']);?>
												
											</li>
											<?php

										}

										?>

										</ul>
										</div>
										<?php
										}
										?>
									</div>						

								</div>
								<?php
								if(dbNumRows($mycats) == 0){
									if(count($catids) == 0){
								?>
								<div class="col-sm-6 mysubcatsec" style="display:none;" id="selectdcats">
									<label>Select Interested Job Categories</label>
										<ul class="items">
										</ul>
								</div>
								<?php
									}
									else{
									?>
									<div class="col-sm-6 mysubcatsec" id="selectdcats">
									<label>Select Interested Job Categories</label>
										<ul class="items">
										<?php
										for($i=0; $i<count($maincatids); $i++){
										$myselcatsmain = dbQuery($dbConn, "select id,category from category where id='".$maincatids[$i]."' order by category");
										$catsrowmain = dbFetchArray($myselcatsmain);
										//while($catsrowmain = dbFetchArray($myselcatsmain)){
										?>
											<li style="margin-bottom:10px;" id="subcatli_<?php echo $catsrowmain['catid'];?>">
												<span><?php echo stripslashes($catsrowmain['category']);?></span>
												<?php
												$subcat = dbQuery($dbConn, "select id,category from category where parent_id='".$maincatids[$i]."' order by category");

												while($suncatrow = dbFetchArray($subcat)){
												?>
													<div>&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" id="myresctcats_<?php echo $suncatrow['id'];?>" class="myresctcats" name="catid[]" value="<?php echo $suncatrow['id'];?>" <?php if(in_array($suncatrow['id'],$catids)) echo "checked";?> data-value="<?php echo stripslashes($suncatrow['category']);?>" />&nbsp; <?php echo stripslashes($suncatrow['category']);?></div>
												<?php
												}
												?>
											</li>
											<?php
										}
										?>
										</ul>
									</div>
									<?php
									}
								}
								else{
									?>
									<div class="col-sm-6 mysubcatsec" id="selectdcats">
									<label>Select Interested Job Categories</label>
										<ul class="items">
										<?php
										$myselcatsmain = dbQuery($dbConn, "SELECT a.catid,b.category from staff_job_parent_cat a inner join category b on a.catid=b.id where a.staff_id = '".$_SESSION['loginUserId']."'");

										while($catsrowmain = dbFetchArray($myselcatsmain)){
										?>
											<li style="margin-bottom:10px;" id="subcatli_<?php echo $catsrowmain['catid'];?>">
												<span><?php echo stripslashes($catsrowmain['category']);?></span>
												<?php
												$subcat = dbQuery($dbConn, "select id,category from category where parent_id='".$catsrowmain['catid']."' order by category");

												while($suncatrow = dbFetchArray($subcat)){
												?>
													<div>&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" id="myresctcats_<?php echo $suncatrow['id'];?>" class="myresctcats" name="catid[]" value="<?php echo $suncatrow['id'];?>" <?php if(in_array($suncatrow['id'],$catids)) echo "checked";?> data-value="<?php echo stripslashes($suncatrow['category']);?>" />&nbsp; <?php echo stripslashes($suncatrow['category']);?></div>
												<?php
												}
												?>
											</li>
											<?php
										}
										?>
										</ul>
									</div>
									<?php
								}
								?>
								
								</div>
								
								<!--<div class="modal fade" id="myexp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
								  <div class="modal-dialog" role="document">
									<div class="modal-content">
									  <div class="modal-header">
										<h5>Select Experience in <span></span></h5>
										<button type="button" id="modal_close" aria-label="Close">X</button>
									  </div>
									  <div class="modal-body">
										  <select id="myexpselect">
										  </select>
										  <input type="hidden" id="mycatid" value="">
										  <br>
										  <ul class="homebtn" style="margin:20px 0;">
											<li><a href="javascript:void(0);" id="add_exp" style="padding:0.4rem 1.5rem;">Add</a></li>
										</ul>
									  </div>
									</div>
								  </div>
								</div>-->

								<div class="row">
								<div class="col-sm-6">
								<label>Distance in KM</label>
									<div class="dropdown_box" style="width:100%;margin-bottom:30px;">

										<input type="text" placeholder="Distance happy to travel (km)" name="distance" class="form-control required content_m_b_10 digits" value="<?php echo $distance;?>">

										<small style="color:#666;font-weight: 400;">Select how far you are willing to travel for work from your address, to be sent any notifications on job openings. We recommend starting with 19km and you can always adjust this in the future.</small><br/>

									</div>

								</div>
							</div>
							<hr class="frmsecdevid" />
							<div class="row">
							
								<div class="col-sm-12">

									<label>Available Days:</label><br>


									  <small style="color:#666;font-weight: 400;text-transform: capitalize;">Please tick the days your are available each week.</small>

									  <div class="mydays">

									  <div class="form-check"><input type="checkbox" value="1" name="working_days[]" class="form-check-input" <?php if(!is_array($working_days) && strpos($working_days,'1')!==false) echo "checked";?>> <label class="form-check-label">Monday</label></div>

									  <div class="form-check"><input type="checkbox" value="2" name="working_days[]" class="form-check-input" <?php if(!is_array($working_days) && strpos($working_days,'2')!==false) echo "checked";?>> <label class="form-check-label">Tuesday</label></div>

									  <div class="form-check"><input type="checkbox" value="3" name="working_days[]" class="form-check-input" <?php if(!is_array($working_days) && strpos($working_days,'3')!==false) echo "checked";?>> <label class="form-check-label">Wednesday</label></div>

									  <div class="form-check"><input type="checkbox" value="4" name="working_days[]" class="form-check-input" <?php if(!is_array($working_days) && strpos($working_days,'4')!==false) echo "checked";?>> <label class="form-check-label">Thursday</label></div>

									  <div class="form-check"><input type="checkbox" value="5" name="working_days[]" class="form-check-input" <?php if(!is_array($working_days) && strpos($working_days,'5')!==false) echo "checked";?>> <label class="form-check-label">Friday</label></div>

									  <div class="form-check"><input type="checkbox" value="6" name="working_days[]" class="form-check-input" <?php if(!is_array($working_days) && strpos($working_days,'6')!==false) echo "checked";?>> <label class="form-check-label">Saturday</label></div>

									  <div class="form-check"><input type="checkbox" value="7" name="working_days[]" class="form-check-input" <?php if(!is_array($working_days) && strpos($working_days,'7')!==false) echo "checked";?>> <label class="form-check-label">Sunday</label></div>

									  </div>

								</div>
								
								</div>
							<hr class="frmsecdevid" />
							<div class="row">
							
								<div class="col-sm-12"> 
								<label>Notification Settings</label>
									<label>How would you like to be notified of job openings?</label><br>

									<div class="radio_box">

									<div class="radio_box_single">

									<input type="radio" name="notified" value="Email" required <?php if($notified=='Email') echo "checked";?>> <span>Email</span>

									</div>



									<div class="radio_box_single">

									<input type="radio" name="notified" value="SMS" <?php if($notified=='SMS') echo "checked";?>> <span>SMS</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified" value="Both" <?php if($notified=='Both') echo "checked";?>> <span>Both</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified" value="Off" <?php if($notified=='Off') echo "checked";?>> <span>Disable</span>

									</div>

									</div>
									
									<label>How would you like to be notified for new messages?</label><br>

									<div class="radio_box">

									<div class="radio_box_single">

									<input type="radio" name="notified_msg" value="Email" required <?php if($notified_msg=='Email') echo "checked";?>> <span>Email</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified_msg" value="SMS" <?php if($notified_msg=='SMS') echo "checked";?>> <span>SMS</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified_msg" value="Both" <?php if($notified_msg=='Both') echo "checked";?>> <span>Both</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified_msg" value="Off" <?php if($notified_msg=='Off') echo "checked";?>> <span>Disable</span>

									</div>

									</div>
									
									<label>Job Type</label><br>

									<div class="radio_box">

									<div class="radio_box_single">

									<input type="radio" name="notified_job_type" value="1" required <?php if($notified_job_type=='1') echo "checked";?>> <span>Short Term</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="notified_job_type" value="2" <?php if($notified_job_type=='2') echo "checked";?>> <span>Long Term</span>

									</div>
									
									<div class="radio_box_single">

									<input type="radio" name="notified_job_type" value="3" <?php if($notified_job_type=='3') echo "checked";?>> <span>Both</span>

									</div>

									</div>

									<?php

									if($notified)

									$style = "";

									else

									$style = "display:none;";

									?>

								</div>
								</div>
								<div id="notified" style="<?php echo $style;?>" class="col-sm-12">

									<hr class="frmsecdevid" />
									<div class="row">

										<div class="col-sm-6">

										<div class="input_box" style="width:100%">

											<label>The Registered Email is:</label>

											<input type="text" placeholder="Email" class="form-control required" readonly value="<?php echo $getdetls['email'];?>"> 

										</div>

										</div>

										<div class="col-sm-6">

										<div class="input_box" style="width:100%">

											<label>For Notifications and Mobile is:</label>

											<input type="text" name="phone" placeholder="Phone number" class="form-control required" value="<?php echo $phone;?>">

										</div>

										</div>

									</div>

								</div>
								
								<hr class="frmsecdevid" />
								<div class="row">
							
								<div class="col-sm-6">

								<label>Profile Picture</label>

								<input type="file" name="image" id="image" class="form-control" accept="image/*" style="margin-bottom:10px;">

								<span>(JPG, PNG or GIF image up to 500KB)</span>
								<?php
								if($getdetls['image']){
									?>
									<div style="clear:both;"></div>
									<img src="<?php echo SITEURL;?>uploads/<?php echo $getdetls['image'];?>" alt="" width="136" style="margin-bottom:20px;">

									<?php
								}

								?>

								</div>

								<div class="col-sm-6">
								<label>Resume</label>

								<input type="file" name="resume" id="resume" class="form-control" style="margin-bottom:10px;">

								<span>(PDF or Docx file)</span>
								<?php

								if($row['resume']){

									?>
									<div style="clear:both;"></div>
									<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $row['resume'];?>" target="_blank">View Resume</a>

									<?php

								}

								?>


								</div>

								<div class="col-sm-4"> 

									<input type="submit" value="Next">

								</div>

							

						</div>

						</form>

					</div>

					

				</div>

				</div>

		</div>

	</div>

	

	

	<?php include_once('footer.php');?>