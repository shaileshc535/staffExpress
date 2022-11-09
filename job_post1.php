<?php

include('config/config.php'); 

include_once "config/common.php";

$dbConn = establishcon();

include_once('header.php');



if(!isset($_SESSION['loginUserId'])){

	echo "<script>location.href='".SITEURL."employer_login'</script>";

	exit;

}



$user = dbQuery($dbConn, "SELECT name,email from users where id = '".$_SESSION['loginUserId']."'");

$row = dbFetchArray($user);

$lat = '';

$lon = '';

if(count($_POST) > 0 && isset($_POST['location'])){

	$jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";

	$title = isset($_POST['title'])?tep_db_input($_POST['title']):"";

	//$catid = isset($_POST['catid'])?tep_db_input($_POST['catid']):"";
	$catid = isset($_POST['catid'])?$_POST['catid']:array();
	$maincatid = isset($_POST['maincatid'])?$_POST['maincatid']:array();


	$qualification = isset($_POST['qualification'])?strip_tags(tep_db_input($_POST['qualification'])):"";

	$state = isset($_POST['state'])?tep_db_input($_POST['state']):"";


	$location = isset($_POST['location'])?tep_db_input($_POST['location']):"";

    $street_address = isset($_POST['street_address'])?tep_db_input($_POST['street_address']):"";

	$working_country = isset($_POST['working_country'])?tep_db_input($_POST['working_country']):"";
	
	$suburb = isset($_POST['suburb'])?tep_db_input($_POST['suburb']):"";


	$worktype = isset($_POST['worktype'])?tep_db_input($_POST['worktype']):"";

	$covertype = isset($_POST['covertype'])?tep_db_input($_POST['covertype']):"";
	
	$want_add_time = isset($_POST['want_add_time'])?tep_db_input($_POST['want_add_time']):"";
	$longstartdt = isset($_POST['longstartdt'])?tep_db_input($_POST['longstartdt']):"";
	
	$is_shift = isset($_POST['is_shift'])?tep_db_input($_POST['is_shift']):"";
	
	//$shifttype = isset($_POST['shifttype'])?tep_db_input($_POST['shifttype']):"";
	$shifttype = isset($_POST['shifttype'])?$_POST['shifttype']:array();
	
	if(count($shifttype) > 0){
		$shifttype = implode(",", $shifttype);
	}
	else{
		$shifttype = "";
	}

	$busdate = isset($_POST['busdate'])?tep_db_input($_POST['busdate']):"";

	$add_time = isset($_POST['add_time'])?tep_db_input($_POST['add_time']):"";

	$starttime = isset($_POST['starttime'])?tep_db_input($_POST['starttime']):"";

	$busdate_arr = explode(" to ", $busdate);
	$busdate = $busdate_arr[0];
	$busdate2 = $busdate_arr[1];
    //$busdate2 = isset($_POST['busdate2'])?tep_db_input($_POST['busdate2']):"";

	$endtime = isset($_POST['endtime'])?tep_db_input($_POST['endtime']):"";
	
	$noshiftsrttime = isset($_POST['noshiftsrttime'])?tep_db_input($_POST['noshiftsrttime']):"";
	
	$noshiftendtime = isset($_POST['noshiftendtime'])?tep_db_input($_POST['noshiftendtime']):"";

	$noshifttext = isset($_POST['noshifttext'])?tep_db_input($_POST['noshifttext']):"";
	
    $qual = isset($_POST['qual'])?$_POST['qual']:array();
	$shiftstrt = isset($_POST['shiftstrt'])?$_POST['shiftstrt']:array();
	$shiftend = isset($_POST['shiftend'])?$_POST['shiftend']:array();

	$experience = isset($_POST['experience'])?tep_db_input($_POST['experience']):"";
	$experience_month = isset($_POST['experience_month'])?tep_db_input($_POST['experience_month']):"";

    $exp_type = isset($_POST['exp_type'])?tep_db_input($_POST['exp_type']):"";

	$howmnypeople = isset($_POST['howmnypeople'])?tep_db_input($_POST['howmnypeople']):"";



	$jobstrttime = $busdate." ".$starttime;

	$jobendttime = $busdate2." ".$endtime;



	$jobstrttime = strtotime($jobstrttime);

	$jobendttime = strtotime($jobendttime);



	if(count($catid) > 0 && count($maincatid) > 0){


		$myaddress = $street_address.", ".$location;

		$mylatlon = get_latlon_from_address($myaddress);

		if($mylatlon != ""){

			$mylatlon_arr = explode("--", $mylatlon);

			$lat = $mylatlon_arr[0];

			$lon = $mylatlon_arr[1];

		}


		$today = date("Y-m-d");

		if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId'] != ""){
			
			$checkifjob = dbQuery($dbConn, "SELECT id from job_details where id = '".$jobid."'");

			if(dbNumRows($checkifjob) > 0){
				dbQuery($dbConn, "UPDATE job_details set employer_id = '".$_SESSION['loginUserId']."', title = '".$title."', qualification = '".$qualification."', street_address = '".$street_address."', suburb = '".$suburb."', worktype = '".$worktype."', `state` = '".$state."', location = '".$location."', country = '".$working_country."', lat = '".$lat."', lon = '".$lon."', covertype = '".$covertype."', want_add_time = '".$want_add_time."', longstartdt = '".$longstartdt."', is_shift = '".$is_shift."', shifttype = '".$shifttype."', jobdate = '".$busdate."', add_time = '".$add_time."', starttime = '".$starttime."', jobdate2 = '".$busdate2."', endtime = '".$endtime."', noshiftsrttime = '".$noshiftsrttime."', noshiftendtime = '".$noshiftendtime."', noshifttext = '".$noshifttext."', howmnypeople = '".$howmnypeople."', exp_type = '".$exp_type."', experience = '".$experience."', experience_month = '".$experience_month."' where id = '".$jobid."'");

				$jobid = $jobid;
				
			}
			else{

				dbQuery($dbConn, "INSERT into job_details set employer_id = '".$_SESSION['loginUserId']."', title = '".$title."', qualification = '".$qualification."', street_address = '".$street_address."', suburb = '".$suburb."', worktype = '".$worktype."', `state` = '".$state."', location = '".$location."', country = '".$working_country."', lat = '".$lat."', lon = '".$lon."', covertype = '".$covertype."', want_add_time = '".$want_add_time."', longstartdt = '".$longstartdt."', is_shift = '".$is_shift."', shifttype = '".$shifttype."', jobdate = '".$busdate."', add_time = '".$add_time."', starttime = '".$starttime."', jobdate2 = '".$busdate2."', endtime = '".$endtime."', noshiftsrttime = '".$noshiftsrttime."', noshiftendtime = '".$noshiftendtime."', noshifttext = '".$noshifttext."', howmnypeople = '".$howmnypeople."', exp_type = '".$exp_type."', experience = '".$experience."', experience_month = '".$experience_month."', postdate = '".$today."'");

				$jobid = dbInsertId($dbConn);
			}
			
			if($is_shift == 1){
				if(count($shiftstrt) > 0){
				dbQuery($dbConn, "DELETE FROM shift_times where jobid = '".$jobid."'");
				$j=0;
				foreach($shiftstrt as $val){
					if($val != "" && $shiftend[$j] != ""){
					dbQuery($dbConn, "INSERT into shift_times set jobid = '".$jobid."', starttime = '".$val."', endtime = '".$shiftend[$j]."'");
					}
					$j++;
				}
				}
			}
			else{
				dbQuery($dbConn, "DELETE FROM shift_times where jobid = '".$jobid."'");
			}
			
			
			$_SESSION['SESSID'] = '';
	
			unset($_SESSION['SESSID']);

		}
		else if(isset($_SESSION['SESSID']) && $_SESSION['SESSID'] != ""){

			$checkifjob = dbQuery($dbConn, "SELECT id from job_details where sessid = '".$_SESSION['SESSID']."'");
	
				if(dbNumRows($checkifjob) > 0){
	
					$jobdets = dbFetchArray($checkifjob);
	
					dbQuery($dbConn, "UPDATE job_details set employer_id = '".$_SESSION['loginUserId']."', title = '".$title."', qualification = '".$qualification."', street_address = '".$street_address."', suburb = '".$suburb."', worktype = '".$worktype."', `state` = '".$state."', location = '".$location."', country = '".$working_country."', lat = '".$lat."', lon = '".$lon."', covertype = '".$covertype."', want_add_time = '".$want_add_time."', longstartdt = '".$longstartdt."', is_shift = '".$is_shift."', shifttype = '".$shifttype."', jobdate = '".$busdate."', add_time = '".$add_time."', starttime = '".$starttime."', jobdate2 = '".$busdate2."', endtime = '".$endtime."', noshiftsrttime = '".$noshiftsrttime."', noshiftendtime = '".$noshiftendtime."', noshifttext = '".$noshifttext."', howmnypeople = '".$howmnypeople."', exp_type = '".$exp_type."', experience = '".$experience."', experience_month = '".$experience_month."' where sessid = '".$_SESSION['SESSID']."'");
	
					$jobid = $jobdets['id'];
					
					if($is_shift == 1){
						if(count($shiftstrt) > 0){
						dbQuery($dbConn, "DELETE FROM shift_times where jobid = '".$jobid."'");
						$j=0;
						foreach($shiftstrt as $val){
							if($val != "" && $shiftend[$j] != ""){
							dbQuery($dbConn, "INSERT into shift_times set jobid = '".$jobid."', starttime = '".$val."', endtime = '".$shiftend[$j]."'");
							}
							$j++;
						}
						}
					}
					else{
						dbQuery($dbConn, "DELETE FROM shift_times where jobid = '".$jobid."'");
					}
	
					$_SESSION['SESSID'] = '';
	
					unset($_SESSION['SESSID']);
	
				}
				
			}
		
		if(count($maincatid) > 0){

			dbQuery($dbConn, "DELETE FROM job_parent_cat where jobid = '".$jobid."'");

			foreach($maincatid as $val){

				dbQuery($dbConn, "INSERT into job_parent_cat set jobid = '".$jobid."', catid = '".$val."'");

			}

		}
		
		if(count($catid) > 0){

			dbQuery($dbConn, "DELETE FROM job_cat where jobid = '".$jobid."'");

			foreach($catid as $val){

				dbQuery($dbConn, "INSERT into job_cat set jobid = '".$jobid."', catid = '".$val."'");

			}

		}



		if(count($qual) > 0){

			foreach($qual as $val){

				dbQuery($dbConn, "INSERT into qualifictn_required set jobid = '".$jobid."', qualifications = '".$val."'");

			}

		}



        echo "<form action='".SITEURL."jobpost2' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'></form><script>document.getElementById('gotostep2').submit();</script>";

		exit;

	}

	/*else{

		echo "<script>location.href='".SITEURL."jobpost1?error=1'</script>";

	}*/

	//exit;



}



$checkdetls = dbQuery($dbConn, "SELECT address,suburb,postcode,state,country from users where id = '".$_SESSION['loginUserId']."'");

$mydetls = dbFetchArray($checkdetls);


if(isset($_SESSION['SESSID']) && $_SESSION['SESSID'] != ""){
	die('aaaa'); 

	$checkifjob = dbQuery($dbConn, "SELECT id from job_details where sessid = '".$_SESSION['SESSID']."'");

	$jobdets = dbFetchArray($checkifjob);

	$myjobid = $jobdets['id'];
	

}
else if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId'] != ""){
	
	if(!isset($_POST['jobid'])){
	
	
	$checkifjob = dbQuery($dbConn, "SELECT id from job_details where employer_id = '".$_SESSION['loginUserId']."' and postcomplete = 0 order by id desc limit 0,1");

	$jobdets = dbFetchArray($checkifjob);

	$myjobid = $jobdets['id'];
	}
	else{
		$myjobid = $_POST['jobid'];
	}
	//echo $myjobid; die;

}


$checkifjob = dbQuery($dbConn, "SELECT * from job_details where id = '".trim($myjobid)."'");

$getdetls = dbFetchArray($checkifjob);


$catids = array();
$maincatids = array();

$mycats = dbQuery($dbConn, "SELECT catid from job_cat where jobid = '".trim($myjobid)."'");

while($mycatsrow = dbFetchArray($mycats)){

	$catids[] = $mycatsrow['catid'];

}
$mymaincats = dbQuery($dbConn, "SELECT catid from job_parent_cat where jobid = '".trim($myjobid)."'");

while($mycatsrow = dbFetchArray($mymaincats)){

	$maincatids[] = $mycatsrow['catid'];

}

if($_POST['title'])

$title = $_POST['title'];

else if($getdetls['title'])

$title = $getdetls['title'];


if(count($catids) > 0)

$catids = $catids;

if(isset($_POST['catid']))

$catids = $_POST['catid'];

if(count($maincatids) > 0)

$maincatids = $maincatids;

if(isset($_POST['maincatid']))

$maincatids = $_POST['maincatid'];

/*if($_POST['catid'])

$mycat = $_POST['catid'];

else if($getdetls['catid'])

$mycat = $getdetls['catid'];*/


if($_POST['qualification'])

$qualification = $_POST['qualification'];

else if($getdetls['qualification'])

$qualification = $getdetls['qualification'];


if($_POST['street_address'])

$street_address = $_POST['street_address'];

else if($getdetls['street_address'])

$street_address = $getdetls['street_address'];

if($_POST['suburb'])

$suburb = $_POST['suburb'];

else if($getdetls['suburb'])

$suburb = $getdetls['suburb'];


if($_POST['location'])

$location = $_POST['location'];

else if($getdetls['location'])

$location = $getdetls['location'];



if($_POST['state'])

$state = $_POST['state'];

else if($getdetls['state'])

$state = $getdetls['state'];



if($_POST['working_country'])

$working_country = $_POST['working_country'];

else if($getdetls['country'])

$working_country = $getdetls['country'];



if($_POST['worktype'])

$worktype = $_POST['worktype'];

else if($getdetls['worktype'])

$worktype = $getdetls['worktype'];


if($_POST['covertype'])

$covertype = $_POST['covertype'];

else if($getdetls['covertype'])

$covertype = $getdetls['covertype'];

if($_POST['want_add_time'])

$want_add_time = $_POST['want_add_time'];

else if($getdetls['want_add_time'])

$want_add_time = $getdetls['want_add_time'];

if($_POST['longstartdt'])

$longstartdt = $_POST['longstartdt'];

else if($getdetls['longstartdt'])

$longstartdt = $getdetls['longstartdt'];


if($_POST['is_shift'])

$is_shift = $_POST['is_shift'];

else if($getdetls['is_shift'])

$is_shift = $getdetls['is_shift'];


if($_POST['shifttype'])

$shifttype = $_POST['shifttype'];

else if($getdetls['shifttype'])

$shifttype = $getdetls['shifttype'];

if($_POST['busdate'])

$busdate = $_POST['busdate'];

else if($getdetls['jobdate'])

$busdate = $getdetls['jobdate'];


if($_POST['starttime'])

$starttime = $_POST['starttime'];

else if($getdetls['starttime'])

$starttime = $getdetls['starttime'];

if($_POST['add_time'])

$add_time = $_POST['add_time'];

else if($getdetls['add_time'])

$add_time = $getdetls['add_time'];


if($_POST['busdate2'])

$busdate2 = $_POST['busdate2'];

else if($getdetls['jobdate2'])

$busdate2 = $getdetls['jobdate2'];



if($_POST['endtime'])

$endtime = $_POST['endtime'];

else if($getdetls['endtime'])

$endtime = $getdetls['endtime'];

if($_POST['noshiftsrttime'])

$noshiftsrttime = $_POST['noshiftsrttime'];

else if($getdetls['noshiftsrttime'])

$noshiftsrttime = $getdetls['noshiftsrttime'];

if($_POST['noshiftendtime'])

$noshiftendtime = $_POST['noshiftendtime'];

else if($getdetls['noshiftendtime'])

$noshiftendtime = $getdetls['noshiftendtime'];

if($_POST['noshifttext'])

$noshifttext = $_POST['noshifttext'];

else if($getdetls['noshifttext'])

$noshifttext = stripslashes($getdetls['noshifttext']);



if($_POST['experience'])

$experience = $_POST['experience'];

else if($getdetls['experience'])

$experience = $getdetls['experience'];

if($_POST['experience_month'])
$experience_month = $_POST['experience_month'];
else if($getdetls['experience_month'])
$experience_month = $getdetls['experience_month'];

if($_POST['exp_type'])

$exp_type = $_POST['exp_type'];

else if($getdetls['exp_type'])

$exp_type = $getdetls['exp_type'];



if($_POST['howmnypeople'])

$howmnypeople = $_POST['howmnypeople'];

else if($getdetls['howmnypeople'])

$howmnypeople = $getdetls['howmnypeople'];



?>



<section class="login_page job_post">

	<div class="container">

	

		<div class="splnav">

			<ul class="onlyemployernav">

				<li <?php if($page == "success.php" || $page == "viewmyjob.php" || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">My Jobs</a></li>

				<li <?php if($page == "job_post1.php") echo "class='active'";?>><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>

				<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>

				<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>


			</ul>

		</div>

	

		<div class="stepbystp">

			<ul>

				<li class="actv">Job Details</li>

				<li><img src="images/dividr.png" alt="" /></li>

				<li>Compensation</li>

				<li><img src="images/dividr.png" alt="" /></li>

				<li>Additional Details</li>

				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li>Post Job</li>
				
				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li>Upload</li>

			</ul>

		</div>

	

		<div class="row">

			<!--<div class="col-lg-6 tabhiddn">

				<div class="job_post_img">

					<img src="<?php echo SITEURL;?>/images/jobpostimag.png" class="img-fluid" alt="">

				</div>

			</div>-->

			<div class="col-sm-8 offset-sm-2">

				<div class="login onlyscrol jobpostnewbgdesn">

					<h4>Job Details</h4>

					<form action="" method="post" id="jobpost1" class="eplyfrm">
					
					<input type="hidden" value="<?php echo trim($myjobid);?>" name="jobid">

						<?php

						if(isset($_POST['location'])){


							$jobstrttime = $_POST['busdate']." ".$_POST['starttime'];

							$jobendttime = $_POST['busdate2']." ".$_POST['endtime'];



							$jobstrttime = strtotime($jobstrttime);

							$jobendttime = strtotime($jobendttime);



							/*if($jobendttime - $jobstrttime < 3600){

							echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Job duration must be minimum 1 hour.</div>";

							}*/
							if(count($catid) == 0 || count($maincatid) == 0){
								echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Please select job category and subcategory.</div>";
								
							}


					}

						?>

						

						<div class="form-group">

							<label>Job Title</label><br>

							<input type="text" placeholder="Enter Title" name="title" class="form-control required" value="<?php echo $title;?>">

						</div>

						<div class="form-group">

							<label>Company Address</label><br>

							<input type="text" placeholder="Company Address" id="company_address" name="company_address" class="form-control required" value="<?php echo $mydetls['address'];?>">

						</div>
						
						<div class="form-group">

						<label>Suburb</label><br>

							<input type="text" placeholder="Suburb" id="company_suburb" name="company_suburb" class="form-control required" value="<?php echo $mydetls['suburb'];?>">

						</div>

						<div class="form-group">

							<div class="fullfrmtime">

								<div class="timlt">

									<label>State</label><br>

									<!--<input type="text" placeholder="State" id="company_state" name="company_state" class="form-control required" value="<?php //echo $mydetls['state'];?>">-->
									<select class="form-select required" name="company_state" id="company_state">
									<option value="">State</option>
									<?php
									$states = dbQuery($dbConn, "select * from states order by states");

									while($staterow = dbFetchArray($states)){
										?>
										<option value="<?php echo $staterow['id'];?>" <?php if($staterow['id']==$mydetls['state']) echo "selected";?>><?php echo stripslashes($staterow['states']);?></option>
										<?php
										
									}
									?>
									</select>

								</div>

								<div class="timrt">

									<label>Postcode</label><br>

									<input type="text" placeholder="Postcode" id="postcode" name="postcode" class="form-control required" value="<?php echo $mydetls['postcode'];?>">

								</div>

							</div>

						</div>

						<div class="form-group">

							<label>Country</label><br>

							<select class="form-select required" name="country" id="country">

								<option selected value="">Country</option>

								<?php

								$cat = dbQuery($dbConn, "select iso_code_2,name from oc_country order by name");

								while($catrow = dbFetchArray($cat)){

								?>

								<option value="<?php echo $catrow['iso_code_2'];?>" <?php if($catrow['iso_code_2']==$mydetls['country']) echo "selected";?>><?php echo stripslashes($catrow['name']);?></option>

								<?php

								}

								?>

							</select>

						</div>
						
						<div class="form-group">

							<div class="f_column_bx">

								<div style="width:100%;" class="add_custom_sty">

									<input type="checkbox" id="same" <?php if($street_address==$mydetls['address']) echo "checked";?>> <span>Same as Company</span>

								</div>

							</div>

						</div>

						<div class="form-group">

						<label>Working Address (Where Staff Will Work)</label><br>

							<input type="text" placeholder="Working Address" id="street_address" name="street_address" class="form-control required" value="<?php echo $street_address;?>">

						</div>
						
						<div class="form-group">

						<label>Suburb</label><br>

							<input type="text" placeholder="Suburb" id="suburb" name="suburb" class="form-control required" value="<?php echo $suburb;?>">

						</div>

						<div class="form-group">

							<div class="fullfrmtime">

								<div class="timlt">

									<label>State</label><br>

									<!--<input type="text" placeholder="State" id="state" name="state" class="form-control required" value="<?php //echo $state;?>">-->
									<select class="form-select required" name="state" id="state">
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

								<div class="timrt">

									<label>Postcode</label><br>

									<input type="text" placeholder="Working Postcode" id="location" name="location" class="form-control required" value="<?php echo $location;?>">

								</div>

							</div>

						</div>

						<div class="form-group">

						

								<!--<div style="width:100%;" class="add_custom_sty">

									<input type="checkbox" id="same"> <span>Same as company address?</span>

								</div>-->

								<label>Country</label><br>

								<select class="form-select required" name="working_country" id="working_country">

								<option selected value="">Country</option>

								<?php

								$cat = dbQuery($dbConn, "select iso_code_2,name from oc_country order by name");

								while($catrow = dbFetchArray($cat)){

								?>

								<option value="<?php echo $catrow['iso_code_2'];?>" <?php if($catrow['iso_code_2']==$working_country) echo "selected";?>><?php echo stripslashes($catrow['name']);?></option>

								<?php

								}

								?>

								</select>

						</div>

						

						<div class="form-group">

							<label>Type Of Work</label><br>

							<select class="form-select required" name="worktype" id="worktype">

								<option value="">Type Of Work</option>

								<option value="1" <?php if($worktype==1) echo "selected";?>>Casual</option>

								<option value="2" <?php if($worktype==2) echo "selected";?>>Contract</option>

								<option value="4" <?php if($worktype==4) echo "selected";?>>Full-time</option>

								<option value="3" <?php if($worktype==3) echo "selected";?>>Part-time</option>

							  </select>

							  <br>

                              <label style="text-transform:none;">StaffExpress is designed for urgent cover and short term work. "Casual" work is often the most appropriate category however you may select the other categories based on your needs.</label>

						</div>

						

						<div class="form-group">

							<label>Select Category</label><br>
							<div class="mycatsec onlylivecatgrydesgnfix">
							
							<?php
							if(dbNumRows($mycats) == 0){
							?>
							<input type="hidden" id="myselectedcats" value="">
							  <input class="form-control" id="showcats" name="showcats" type="text" placeholder="Job Category" readonly />
								<div id="allcats" style="display:none;">
								<ul class="items">

							<?php

								$cat = dbQuery($dbConn, "select * from category where parent_id=0 order by category");

								while($catrow = dbFetchArray($cat)){
									?>
									<li>
									<input id="maincat_<?php echo $catrow['id'];?>" type="checkbox" class="employercats" name="maincatid[]" value="<?php echo $catrow['id'];?>" data-value="<?php echo stripslashes($catrow['category']);?>" />&nbsp; <?php echo stripslashes($catrow['category']);?>
									<div id="maincatli_<?php echo $catrow['id'];?>">
									
									</div>
								</li>
								<?php

							}

							?>

							</ul>
							</div>
							<?php
							}
							else{
								if(is_array($maincatids) && count($maincatids)>0){
								if(count($maincatids)> 1)
									$noofclassfn = count($maincatids)." classifications";
								else
									$noofclassfn = count($maincatids)." classification";
								}
								?>
								<input type="hidden" id="myselectedcats" value="<?php if(is_array($maincatids)) echo count($maincatids);?>">
								<input class="form-control" id="showcats" name="showcats" type="text" placeholder="Job Category" readonly value="" />
								<div id="allcats">
								<ul class="items">

								<?php

								$cat = dbQuery($dbConn, "select * from category where parent_id=0 order by category");

								while($catrow = dbFetchArray($cat)){
									?>
									<li>
									<input id="maincat_<?php echo $catrow['id'];?>" type="checkbox" class="employercats" name="maincatid[]" data-value="<?php echo stripslashes($catrow['category']);?>" value="<?php echo $catrow['id'];?>" <?php if(in_array($catrow['id'],$maincatids)) echo "checked";?> />&nbsp; <?php echo stripslashes($catrow['category']);?>
									<div id="maincatli_<?php echo $catrow['id'];?>" <?php if(in_array($catrow['id'],$maincatids)) echo "style=''"; else echo "style='display:none;'";?>>
									<ul>
									<?php
									$subcat = dbQuery($dbConn, "select id,category from category where parent_id='".$catrow['id']."' order by category");

									while($suncatrow = dbFetchArray($subcat)){
									?>
										<li><input type="checkbox" class="myempresctcats" name="catid[]" value="<?php echo $suncatrow['id'];?>" <?php if(in_array($suncatrow['id'],$catids)) echo "checked";?> />&nbsp; <?php echo stripslashes($suncatrow['category']);?></li>
									<?php
									}
									?>
									</ul>
									</div>
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


						<!--<div class="form-group">

							<?php

								$qualifications = array();

								

								$checkqual = dbQuery($dbConn, "SELECT * FROM qualifictn_required WHERE jobid = '".$myjobid."'");

								while($myquals = dbFetchArray($checkqual)){

									$qualifications[] = $myquals['qualifications'];

								}



								$qualficatns = dbQuery($dbConn, "SELECT * from qualifications where catid = '".$mycat."'");



								if(count($qualifications) > 0){

									$showdiv = "";

									$qualifications = $qualifications;

								}

								else if(isset($_POST['qual'])){

									$showdiv = "";

									$qualifications = $_POST['qual'];

								}

								else if(dbNumRows($qualficatns) > 0){

									$showdiv = "";

								}

								else

								$showdiv = "display:none;";

							?>

								<div class="input_box" id="qual_req" style="<?php echo $showdiv;?>">

									<label>Qualifications Required</label>

									<div class="check_box">

									<?php

											while($row2 = dbFetchArray($qualficatns)){

											?>

										<div class="form-check"><input name="qual[]" class="form-check-input" type="checkbox" value="<?php echo $row2['id'];?>" <?php if(in_array($row2['id'], $qualifications)) echo "checked";?>><label class="form-check-label"><?php echo $row2['qualifictn'];?></label></div>

										<?php

										}

										?>



									</div>

								</div>	

						</div>-->


						<div class="form-group">

                            <div class="fullfrmtime">
							<label>Is This a Short Term Cover or Long Term?</label>
							<div class="row">
							<div class="col-sm-6 col-6">
								<div class="covertype">
								<input type="radio" name="covertype" required value="1" <?php if($covertype == 1) echo "checked";?>>
								<p>Short Term<br>(&lt; 2 months)</p>
								</div>
							</div>
								<div class="col-sm-6 col-6">
									<div class="covertype">
									<input type="radio" name="covertype" value="2" <?php if($covertype == 2) echo "checked";?>>
									<p>Long Term<br>(&gt; 2 months)</p>
									</div>
								</div>
							</div>
							
							<?php
							if($covertype == 1){
								$datestyle = 'style=""';
								$addtimestyle = 'style="display:none"';
							}
							else if($covertype == 2){
								$datestyle = 'style="display:none"';
								$addtimestyle = 'style=""';
							}
							else{
								$datestyle = 'style="display:none"';
								$addtimestyle = 'style="display:none"';
							}
							if($want_add_time == 1){
								$longdatestyle = 'style=""';
							}
							else if($want_add_time == 2){
								$longdatestyle = 'style="display:none"';
							}
							else{
								$longdatestyle = 'style="display:none"';
							}
							?>
							
                                <div class="timlt" id="shwdaterange" <?php echo $datestyle;?>>

                                <label>Select Date Range</label><br>

                                    <input type="text" id="from" placeholder="Date Range" name="busdate" class="form-control <?php if($covertype == 1) echo 'required';?>" value="<?php if($busdate!="" && $busdate2!="" && $busdate!="0000-00-00" && $busdate2!="0000-00-00") echo $busdate. " to ".$busdate2;?>">

                                </div>
								
								<div class="timlt" id="wanttoaddtm" <?php echo $addtimestyle;?>>

                                <label>Want to include an approximate start date? </label>
								<p>
								<input type="radio" name="want_add_time" id="want_add_time" value="1" <?php if($want_add_time == 1) echo "checked";?>> Yes&nbsp;&nbsp;
								<input type="radio" name="want_add_time" value="2" <?php if($want_add_time == 2) echo "checked";?>> No
								</p>
								
								<div id="showlongstartdt" <?php echo $longdatestyle;?>>
								<label>Select Start Date</label><br>

                                    <input type="text" id="longstartdt" placeholder="Start Date" name="longstartdt" class="form-control" value="<?php if($longstartdt!="" && $longstartdt!="0000-00-00") echo $longstartdt;?>">
								</div>

                                </div>

                                </div>


						</div>

						<div class="form-group">

							<div class="fullfrmtime">
							<label>Is this a fixed time job? </label>
							<p>
							<input type="radio" name="add_time" required value="1" <?php if($add_time == 1) echo "checked";?>> Yes&nbsp;&nbsp;
							<input type="radio" name="add_time" value="2" <?php if($add_time == 2) echo "checked";?>> No
							</p>
							<?php
							if($add_time == 1){
								$timeshow = 'style=""';
								$shiftshow = 'style="display:none"';
							}
							else if($add_time == 2){
								$timeshow = 'style="display:none"';
								$shiftshow = 'style=""';
							}
							else{
								$timeshow = 'style="display:none"';
								$shiftshow = 'style="display:none"';
							}
							?>
							<div id="addtime" <?php echo $timeshow;?>>
								<div class="timlt">

                                    <label>Start Time</label>

                                    <input type="text" placeholder="Start Time" name="starttime" id="starttime" class="form-control timepicker" value="<?php if($starttime!="00:00:00") echo $starttime;?>" autocomplete="off">

                                </div>
								<div class="timrt">

									<label>End Time</label><br>

									<input type="text" placeholder="End Time" name="endtime" id="endtime" class="form-control timepicker" value="<?php if($endtime!="00:00:00") echo $endtime;?>" autocomplete="off">

								</div>
							</div>
							
							</div>

						</div>
						<?php
							if($is_shift == 1){
								$myshifttype = 'style=""';
								$timeshownoshift = 'style="display:none"';
							}
							else if($is_shift == 2){
								$myshifttype = 'style="display:none"';
								$timeshownoshift = '';
							}
							else{
								$myshifttype = 'style="display:none"';
								$timeshownoshift = 'style="display:none"';
							}
							?>
						<div id="shiftjobdiv" <?php echo $shiftshow;?>>
								<div class="form-group">

								<div class="fullfrmtime">
								<label>Is this a shift job? </label>
								<p>
								<input type="radio" name="is_shift" id="is_shift" value="1" <?php if($is_shift == 1) echo "checked";?>> Yes&nbsp;&nbsp;
								<input type="radio" name="is_shift" value="2" <?php if($is_shift == 2) echo "checked";?>> No
								</p>
								
								<div id="shifttype" <?php echo $myshifttype;?>>
								<label>Shift-Based</label>
								<p class="myshiftoptns">
								<input type="checkbox" name="shifttype[]" id="shiftopn" value="1" <?php if(strpos($shifttype, '1') !== false) echo "checked";?>> Day Shift&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="checkbox" name="shifttype[]" value="2" <?php if(strpos($shifttype, '2') !== false) echo "checked";?>> Night Shift&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="checkbox" name="shifttype[]" value="3" <?php if(strpos($shifttype, '3') !== false) echo "checked";?>> Overnight Shift
								</p>
								
								<?php
									$staffexp = dbQuery($dbConn, "SELECT starttime,endtime from shift_times where jobid = '".trim($_POST['jobid'])."' order by id");
									if(dbNumRows($staffexp) == 0){
								?>
								<div class="row">
									<div class="col-sm-5 col-5">
										<div class="timlt" style="width:100%;">
											<label>Start Time</label>
											<input type="text" placeholder="Start Time" name="shiftstrt[]" class="form-control timepicker shifttm shiftstrt required" value="" autocomplete="off">

										</div>
									</div>
									<div class="col-sm-5 col-5">
									<div class="timrt" style="width:100%;">
										<label>End Time</label><br>
										<input type="text" placeholder="End Time" name="shiftend[]" class="form-control timepicker shifttm shiftend required" value="" autocomplete="off">

									</div>
									</div>
									<div class="col-sm-2 col-2">
										<label>&nbsp;</label>
											<a href="javascript:void(0);" class="addmyshifttime"><i class="fa fa-plus"></i></a>
									</div>
								</div>
								<div id="myshifttimes"></div>
								<?php
									}
									else{
										$i=0;
										while($staffexprow = dbFetchArray($staffexp)){
										?>
										<div class="row">
											<div class="col-sm-5 col-4">
											<div class="timlt" style="width:100%;">
												<label>Start Time</label>
												<input type="text" placeholder="Start Time" name="shiftstrt[]" class="form-control timepicker shifttm shiftstrt required" value="<?php if($staffexprow['starttime']!="00:00:00") echo $staffexprow['starttime'];?>" autocomplete="off">

											</div>
											</div>
											<div class="col-sm-5 col-4">
											<div class="timrt" style="width:100%;">
												<label>End Time</label><br>
												<input type="text" placeholder="End Time" name="shiftend[]" class="form-control timepicker shifttm shiftend required" value="<?php if($staffexprow['endtime']!="00:00:00") echo $staffexprow['endtime'];?>" autocomplete="off">

											</div>
											</div>
											<div class="col-sm-2 col-4">
												<label>&nbsp;</label>
													<a href="javascript:void(0);" class="addmyshifttime"><i class="fa fa-plus"></i></a> 
													<?php
													if($i > 0){
													?>
													&nbsp;<a href="javascript:void(0);" class="removemytime"><i class="fa fa-minus"></i></a>
													<?php
													}
													?>
											</div>
										</div>
										<?php
										$i++;
										}
										?>
										<div id="myshifttimes"></div>
										<?php
									}
									?>
								</div>
								</div>
								
								<div class="fullfrmtime" id="addtimenoshift" <?php echo $timeshownoshift;?>>
								<!--<div>
									<div class="timlt">

										<label>Start Time</label>

										<input type="text" placeholder="Start Time" name="noshiftsrttime" id="noshiftsrttime" class="form-control timepicker" value="<?php if($noshiftsrttime!="00:00:00") echo $noshiftsrttime;?>" autocomplete="off">

									</div>
									<div class="timrt">

										<label>End Time</label><br>

										<input type="text" placeholder="End Time" name="noshiftendtime" id="noshiftendtime" class="form-control timepicker" value="<?php if($noshiftendtime!="00:00:00") echo $noshiftendtime;?>" autocomplete="off">

									</div>
								</div>-->
									<div class="form-group">
									<label>Type Time if Known</label><br>
									
									<input type="text" name="noshifttext" class="form-control" value="<?php echo $noshifttext;?>">
									
									</div>
								</div>

							</div>
							</div>

                        <div class="form-group">
						<div class="fullfrmtime">
							<label>Select Experience Required</label><br>
							
							<div class="timlt">

							<select class="form-select required" name="experience">

								<option selected value="">Experience Required (Years)</option>

								<?php

								for($i=0; $i<=10; $i++){

								?>

								<option value="<?php echo $i;?>" <?php if($i==$experience && $experience!="") echo "selected";?>><?php echo $i;?></option>

								<?php

								}

								?>

								<option value="10+" <?php if($experience=='10+' && $experience!="") echo "selected";?>>10+</option>

							  </select>
							  </div>
							  <div class="timrt">
							  <select class="form-select required" name="experience_month">

								<option selected value="">Months</option>

								<?php

								for($i=0; $i<12; $i++){

								?>

								<option value="<?php echo $i;?>" <?php if($i==$experience_month && $experience_month!="") echo "selected";?>><?php echo $i;?></option>

								<?php

								}

								?>

							  </select>
							</div>
							</div>
							
						</div>

						<div class="form-group">

							

						<input type="radio" name="exp_type" value="1" <?php if($exp_type==1) echo "checked";?>> <span>Compulsory <i class="fa fa-question-circle" aria-hidden="true" title="All staffs who do not have required experience will NOT be notified"></i></span>&nbsp;&nbsp;

							  <input type="radio" name="exp_type" value="2" <?php if(!$exp_type || $exp_type==2) echo "checked";?> required> <span>Preferred <i class="fa fa-question-circle" aria-hidden="true" title="All staffs with or without experience will be notified but will be informed that 'experience is preferred'"></i></span>

                              <!--<label>We can have a line below each start and end date to advise it does not need to be exact, however a very close estimate would be ideal.</label>-->

						</div>

                        <div class="form-group">

							<label>How Many People Do You Want to Hire for This Role?</label><br>

							<input type="text" placeholder="Enter how many people" name="howmnypeople" id="howmnypeople" class="form-control required digits" value="<?php echo $howmnypeople;?>">

						</div>

						<div class="row">
							<div class="col-sm-3">
								<input type="submit" value="Next" class="onlybutfulwdt">
							</div>
						</div>

					</form>


				</div>

			</div>

		</div>

	</div>

</section>

	

<?php include_once('footer.php');?>