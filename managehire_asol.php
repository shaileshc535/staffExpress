<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):"";
$userid = isset($_REQUEST['userid'])?base64_decode(trim($_REQUEST['userid'])):"";

if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login?job=".$id."&user=".$userid."'</script>";
	exit;
}

$quals = array();
$myquals = '';
$addcomp = '';
$benefit = '';
$staff_quals = array();
$allstaff_quals = '';
$currtime = date('Y-m-d H:i:s');
$today = date('Y-m-d');
if(count($_POST) > 0){
	
    $jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?tep_db_input($_POST['userid']):"";
	$avl_update = isset($_POST['avl_update'])?tep_db_input($_POST['avl_update']):"";

    $mystaff = dbQuery($dbConn, "SELECT a.id,a.name,a.email,a.phone,b.notified from users a inner join staff_details b on a.id=b.staff_id where a.id = '".$userid."'");
    $res = dbFetchArray($mystaff);

    $myjob = dbQuery($dbConn, "SELECT a.title,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.howmnypeople,a.isclosed,b.name,b.business_name FROM job_details a inner join users b on a.employer_id=b.id where a.id='".$jobid."'");
    $fetch = dbFetchArray($myjob);

    dbQuery($dbConn, "UPDATE job_status set hired=1, hiredon = '".$today."' where jobid = '".$jobid."' and application_sent_to = '".$userid."'");

	$contact = dbQuery($dbConn, "SELECT avlblty from job_status where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
	$getstat = dbFetchArray($contact);
	if($getstat['avlblty'] == 1){
		if($fetch['howmnypeople'] == 1){
				dbQuery($dbConn, "UPDATE job_details set isclosed=1 where id = '".$jobid."'");
		}
		else{
			$checkhired = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$jobid."' and hired=1");
			if(dbNumRows($checkhired) == $fetch['howmnypeople']){
				dbQuery($dbConn, "UPDATE job_details set isclosed=1 where id = '".$jobid."'");
			}
		}
	}
	else{
		if($avl_update == 1){
			$avldays = dbQuery($dbConn, "SELECT availble_date FROM staff_availability where jobid = '".$jobid."' and staff_id = '".$userid."' order by id desc");
			$myavldays = dbFetchArray($avldays);
			$last_available = $myavldays['availble_date'];
			
			$startdate = date('Y-m-d', strtotime('+1 day', strtotime($last_available)));
			dbQuery($dbConn, "UPDATE job_details set jobdate = '".$startdate."' where id = '".$jobid."'");
		}
	}

    // send mail to Staff
	if($res['notified'] == "Email" || $res['notified'] == "Both"){
    $to = $res['email'];
    $subject = "Congratulations!! You are Hired";

    $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid; text-align:center;'>
	<tr>
		<td style='padding:5px; font-size:18px; color:green;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
	</tr>
	<tr>
	<td colspan='2'>&nbsp;</td>
	</tr>
    <tr>
    <td colspan='2'>Hello ".$res['name'].",</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2'>You are hired to perform this job: ".stripslashes($fetch['title']).".</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2'>Your employer is: ".stripslashes($fetch['name']).".</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2'>Job timing: ".date('M j, Y', strtotime($fetch['jobdate']))." at ".date('h:i A', strtotime($fetch['starttime']))." - ".date('M j, Y', strtotime($fetch['jobdate2']))." at ".date('h:i A', strtotime($fetch['endtime'])).".</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <td colspan='2'>Thanks,<br>Staff Express</td>
    </tr>
    </table>";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

    mail($to,$subject,$message,$headers);
	}
	if($res['notified'] == "SMS" || $res['notified'] == "Both"){
        // sending sms
        $username = "ninepebblesteam@gmail.com";
        $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
        $str = $username.":".$password;
        $auth = base64_encode($str);

        $phone = $res['phone'];
        if(strpos($phone, "+") !== false)
        $phone = $phone;
        else
        $phone = "+".$phone;

        $smsbody = "You are hired to perform this job: ".stripslashes($fetch['title']).".<br>Your employer is: ".stripslashes($fetch['name']).".<br>Job timing: ".date('M j, Y', strtotime($fetch['jobdate']))." at ".date('h:i A', strtotime($fetch['starttime']))." - ".date('M j, Y', strtotime($fetch['jobdate2']))." at ".date('h:i A', strtotime($fetch['endtime'])).".";

        $data_string = '{
            "messages": [
            {
                "to": "'.$phone.'",
                "source": "sdk",
                "body": "'.$smsbody.'"
            }
            ]
        }';

        $ch = curl_init("https://rest.clicksend.com/v3/sms/send");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Authorization: Basic '.$auth.''
                            )
            );
            $result = curl_exec($ch);
            $result = json_decode($result);
    }

    echo "<script>location.href='".SITEURL."managehire/?id=".base64_encode($jobid)."&userid=".base64_encode($userid)."&hired=1'</script>";
	exit;
}

$myjob = dbQuery($dbConn, "SELECT a.*,b.name,b.phone,b.business_name,c.category,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join category c on a.catid=c.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$id."'");
$fetch = dbFetchArray($myjob);

$jobsrtdate = $fetch['jobdate'];
$jobenddate = $fetch['jobdate2'];
$strttime = $fetch['starttime'];
$endtime = $fetch['endtime'];

$jobstrttime = $jobsrtdate." ".$strttime;
$jobendttime = $jobenddate." ".$endtime;

$jobstrttime = strtotime($jobstrttime);
$jobendttime = strtotime($jobendttime);

$requrdqual = dbQuery($dbConn, "SELECT qualifictn from qualifications a inner join qualifictn_required b on a.id=b.qualifications where b.jobid = '".$id."'");
    while($qualfetch = dbFetchArray($requrdqual)){
        $quals[] = $qualfetch['qualifictn'];
    }
    if(count($quals) > 0){
        $myquals = implode(", ", $quals);
    }

    $addtl_compnstion = explode(",", $fetch['addtl_compnstion']);
    foreach($addtl_compnstion as $val){
        $comp = dbQuery($dbConn, "SELECT compensation from addtl_compnstion where id = '".$val."'");
        $res_comp = dbFetchArray($comp);
        $addcomp .= $res_comp['compensation'].", ";
    }
    $addcomp = substr($addcomp, 0, -2);

    $benefits = explode(",", $fetch['benefits']);
    foreach($benefits as $val){
        $comp = dbQuery($dbConn, "SELECT benefit from benefits where id = '".$val."'");
        $res_comp = dbFetchArray($comp);
        $benefit .= $res_comp['benefit'].", ";
    }
    $benefit = substr($benefit, 0, -2);

	$getstaff = dbQuery($dbConn, "SELECT a.*,b.name,b.lname,b.email,b.phone from staff_details a inner join users b on a.staff_id=b.id where staff_id = '".$userid."'");
    $fetchStaff = dbFetchArray($getstaff);
    
	$working_days = explode(",", $fetchStaff['working_days']);
	$mydays = '';
	for($i=0; $i<=count($working_days); $i++){
		if($working_days[$i] == 1)
		$mydays .= "Monday, ";
		if($working_days[$i] == 2)
		$mydays .= "Tuesday, ";
		if($working_days[$i] == 3)
		$mydays .= "Wednesday, ";
		if($working_days[$i] == 4)
		$mydays .= "Thursday, ";
		if($working_days[$i] == 5)
		$mydays .= "Friday, ";
		if($working_days[$i] == 6)
		$mydays .= "Saturday, ";
		if($working_days[$i] == 7)
		$mydays .= "Sunday, ";
	}
	$mydays = substr($mydays, 0, -2);
	
    /*if($fetchStaff['working_days'] == '1')
    $working_day = "Everyday";
	else if($fetchStaff['working_days'] == '2')
    $working_day = "Mon to Fri";
    else if($fetchStaff['working_days'] == '3')
    $working_day = "Sat to Sun";
    else if($fetchStaff['working_days'] == '4')
    $working_day = "Mondays";
    else if($fetchStaff['working_days'] == '5')
    $working_day = "Tuesdays";
    else if($fetchStaff['working_days'] == '6')
    $working_day = "Wednesdays";
    else if($fetchStaff['working_days'] == '7')
    $working_day = "Thursdays";
    else if($fetchStaff['working_days'] == '8')
    $working_day = "Fridays";*/

    $myqual = dbQuery($dbConn, "SELECT qualifictn from qualifications a inner join staff_qualification b on a.id=b.qualification where b.staff_id = '".$userid."'");
    while($qualfetch = dbFetchArray($myqual)){
        $staff_quals[] = $qualfetch['qualifictn'];
    }
    if(count($staff_quals) > 0){
        $allstaff_quals = implode(", ", $staff_quals);
    }
?>

	<div class="works works_success">
		<div class="container">
			<div class="inrpglink">
				<div class="row">
					<div class="col-sm-8">
						<ul>
							<li <?php if($page == "success.php" || $page == "viewmyjob.php" || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">Jobs</a></li>
							<li><a href="javascript:void(0);">Candidates</a></li>
							<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>
							<li><a href="<?php echo SITEURL."emp_calendar";?>">Calender View</a></li>
						</ul>
					</div>
					<div class="col-sm-4">
						<div class="subnavright">
							<ul>
								<li <?php if($page == "job_post1.php") echo "class='active'";?>><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>
								<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>
							</ul>
						</div>
					</div>
				</div>
				
			</div>
			<div class="row">
			<?php //include_once "employer_left.php";?>
				<div class="col-lg-12">
					<div class="works_heading">

						<?php
						if(isset($_REQUEST['hired']) && $_REQUEST['hired'] == 1){
							echo "<div class='alert-success' style='padding:15px;margin-bottom:10px;'>This Staff has been hired.</div>";
						}
						?>
                        <h4 style="padding-bottom:20px;"><?php echo stripslashes($fetch['title']);?></h4>
						<?php
						if(isset($_REQUEST['action']) && $_REQUEST['action'] == "extraamount"){
							$getextra = dbQuery($dbConn, "SELECT escrow_amt from job_extra_escrow where jobid = '".$id."' and userid = '".$userid."'");
							$rowextra = dbFetchArray($getextra);
							if($rowextra['escrow_amt'] != 0){
								?>
								<h5>Extra amount <?php echo $rowextra['escrow_amt'];?> AUD deposited to escrow.</h5>
								<?php
							}
						}
						?>
						<div class="table-responsive jobdetlstabl">
									
						
                        <div class="sucespagtab my_jobview_success">
						
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="pills-canddtls-tab" data-bs-toggle="pill" data-bs-target="#pills-canddtls" type="button" role="tab" aria-controls="pills-canddtls" aria-selected="true">Staff Details</button>
							  </li>
							  <!--<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Hours</button>
							  </li>-->
                              
                              <?php
                              //if($getstat['hired'] == 1 && ($jobendttime > strtotime($currtime))){
                              ?>
                              <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-clock-tab" data-bs-toggle="pill" data-bs-target="#pills-clock" type="button" role="tab" aria-controls="pills-clock" aria-selected="false">Hours & Clock</button>
							  </li>
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-rate-tab" data-bs-toggle="pill" data-bs-target="#pills-rate" type="button" role="tab" aria-controls="pills-rate" aria-selected="false">Review</button>
							  </li>
                              <?php
                              //}
                              ?>
							</ul>
							<div class="tab-content" id="pills-tabContent">
							<div class="tab-pane fade show active" id="pills-canddtls" role="tabpanel" aria-labelledby="pills-canddtls-tab">
								<div class="successtable" style="padding-top:20px;">
								<div class="viewmyjobtabonedetails">
									<div class="viethejobbk">
									<div class="alljbdts">
										<h5><?php echo stripslashes($fetchStaff['name'])." ".stripslashes($fetchStaff['lname']);?></h5>
										<?php
										$catids = array();
										$staffcatstr = '';
										$mycats = dbQuery($dbConn, "SELECT catid from staff_job_cat where staff_id = '".$userid."'");
												while($mycatsrow = dbFetchArray($mycats)){
													$staffcats = dbQuery($dbConn, "select category from category where id = '".$mycatsrow['catid']."'");
													$stffcatrow = dbFetchArray($staffcats);
													$catids[] = stripslashes($stffcatrow['category']);
												}
												if(count($catids) > 0){
													$staffcatstr = implode(", ", $catids);
												}
										?>
										<div class="personlinfrm">
											<p>
												<span class="viewthejobrighttxt">
													<span class="db">DOB - <?php echo date('M j, Y', strtotime($fetchStaff['dob']));?></span><br />
													<?php echo $staffcatstr;?><br />
													<span class="wrkdys"><?php echo $mydays;?></span><br />
													<span class="">Driving License - <?php
														if($fetchStaff['license'] == '1')
														echo "Yes";
														else if($fetchStaff['license'] == '2')
														echo "No";
														?>
													</span>, &nbsp; 
													<span class="">Forklift License - 
														<?php
															if($fetchStaff['for_license'] == '1')
															echo "Yes";
															else if($fetchStaff['for_license'] == '2')
															echo "No";
														?>
													</span>, &nbsp; 
													<span class="">Responsible Service of Alcohol - 
														<?php
															if($fetchStaff['alcohol'] == '1')
															echo "Yes";
															else if($fetchStaff['alcohol'] == '2')
															echo "No";
														?>
													</span>, &nbsp; 
													<span class="">Working with Children Check - 
														<?php
															if($fetchStaff['working_with_child'] == '1')
															echo "Yes";
															else if($fetchStaff['working_with_child'] == '2')
															echo "No";
														?>
													</span>
												</span>
											</p>
											<?php
											if($allstaff_quals != ""){
											?>
												
												<?php
											}
											if($mydays != ""){
											?>
												
												<?php
											}
											?>
										</div>
										<div class="personlinfrm">
											<p>
												<span class="pertiwidth"><strong>Resume:</strong></span>
												<span class="viewthejobrighttxt">
												<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $fetchStaff['resume'];?>" target="_blank">View</a>
												</span>
											</p>
										</div>
										<div class="personlinfrm">
											<?php
											$check = dbQuery($dbConn, "SELECT cover_letter from staff_documents where staff_id = '".$userid."'");
											if(dbNumRows($check) > 0){
												?>
												<h5>Cover Letter:</h5>
												<p>
												<span class="">
												<?php
												$i=1;
												while($getdetls = dbFetchArray($check)){
											?>
											
												<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['cover_letter'];?>" target="_blank" class="textlnk"><?php echo $getdetls['cover_letter'];?></a>, &nbsp; &nbsp; &nbsp; &nbsp;
												
											<?php
											$i++;
												}
												?>
												</span>
												</p>
												<?php
											}
											
											$check = dbQuery($dbConn, "SELECT qualifications from staff_qualifications where staff_id = '".$userid."'");
											if(dbNumRows($check) > 0){
												?>
										</div>
										<div class="personlinfrm">
											<h5>Qualification Document:</h5>
											<p>
													<span class="">
											<?php
													$i=1;
												while($getdetls = dbFetchArray($check)){
												?>
														<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['qualifications'];?>" target="_blank" class="textlnk"><?php echo $getdetls['qualifications'];?></a>, &nbsp; &nbsp; &nbsp; &nbsp;
													
												<?php
												$i++;
												}
												?>
												</span>
												</p>
												<?php
												}
												$check = dbQuery($dbConn, "SELECT `certificate` from staff_certificate where staff_id = '".$userid."'");
												if(dbNumRows($check) > 0){
											?>
										</div>
										<div class="personlinfrm">
											<h5>Certificate:</h5>
											<p>
													<span class="">
												<?php
													$i=1;
													while($getdetls = dbFetchArray($check)){
												?>
												
														<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['certificate'];?>" target="_blank" class="textlnk"><?php echo $getdetls['certificate'];?></a>, &nbsp; &nbsp; &nbsp; &nbsp;
													
												<?php
												$i++;
												}
												?>
												</span>
												</p>
												<?php
												}
												$contact = dbQuery($dbConn, "SELECT * from job_status where jobid = '".$id."' and application_sent_to = '".$userid."'");
												$getstat = dbFetchArray($contact);
												//if($getstat['hired'] == 1){
												//}
												if($fetchStaff['nomedical'] || $fetchStaff['heart'] || $fetchStaff['diabetes'] || $fetchStaff['bloodpr'] || $fetchStaff['hasallgy'] || $fetchStaff['allergy'] || $fetchStaff['hasid'] || $fetchStaff['infectious'] || $fetchStaff['hasother'] || $fetchStaff['otherdis']){
											?>
										</div>
										
										<div class="personlinfrm">
											<h5>Medical Conditions</h5>
											<?php
												if($fetchStaff['nomedical'] == 1){
												?>
													<p>
														<span class="pertiwidth"><strong>No medical issues</strong></span>
														<span class="viewthejobrighttxt">&nbsp;</span>
													</p>
													<?php
												}
												else{
													?>
													<p>
														<span class="viewthejobrighttxt">
															Heart Conditions: - <?php echo ($fetchStaff['heart']==1)?"Heart issues":"No Heart issues";?>, &nbsp; 
															Diabetes - <?php echo ($fetchStaff['diabetes']==1)?"Yes":"No";?>, &nbsp; 
															High Blood Pressure - <?php echo ($fetchStaff['bloodpr']==1)?"Yes":"No";?>, &nbsp; 
															Has Allergy - <?php echo $fetchStaff['allergy'];?>, &nbsp; 
															Has Infectious Disease - <?php echo $fetchStaff['infectious'];?>, &nbsp; 
															Has Other Issues - <?php echo $fetchStaff['infectious'];?>														
														</span>
													</p>
													
													<?php
													if($fetchStaff['hasallgy'] || $getdetls['allergy']){
													?>
													
													<?php
													}
													if($fetchStaff['hasid'] || $getdetls['infectious']){
													?>
													
													<?php
													}
													if($fetchStaff['hasother'] || $getdetls['otherdis']){
														?>
													
													<?php
													}
												}
												?>
										</div>		
										<?php
												}
												if($getstat['hired'] == 1){
													if($fetchStaff['bsb'] && $fetchStaff['accno']){
													?>
										
										<div class="personlinfrm">
											
												<h5>Payment Information</h5>
												<p><span class="viewthejobrighttxt">Staff Express has helped in compiling some personal information of Staff including bank details, superannuation etc. However please remember that this cannot be relied upon and are only intended as a guide. Please collect your own records for necessary reporting and regulations.</span></p>
												<p>
													<span class="viewthejobrighttxt">
														BSB Number - <?php echo $fetchStaff['bsb'];?>, &nbsp; 
														Account Number - <?php echo $fetchStaff['accno'];?>, &nbsp; 
														Name of Bank Account - <?php echo $fetchStaff['bankaccname'];?>, &nbsp; 
														<?php
												if($fetchStaff['taxno']){
												?>
														Tax File Number - <?php echo $fetchStaff['taxno'];?>, &nbsp; 
														<?php
												}
												if($fetchStaff['claim_tax'] != ""){
												?>
														Claiming Tax Free Threshold for Any Work Done? - 
														<?php 
															if($fetchStaff['claim_tax'] == 1)
															echo "Yes";
															if($fetchStaff['claim_tax'] == 2)
															echo "No";
												}
												if($fetchStaff['high_edutn'] != ""){			
															
															?>,<br /> 
														Have a Higher Education Loan Program (HELP), VET Student Loan (VSL), Financial Supplement (FS), Student Start-up Loan (SSL) or Trade Support Loan (TSL) Debt? - <?php 
															if($fetchStaff['high_edutn'] == 1)
															echo "Yes";
															if($fetchStaff['high_edutn'] == 2)
															echo "No";
															
												}
															?> &nbsp; 
														
													</span>
												</p>
										</div>
												<?php
												}
												if($fetchStaff['superannuation']){
												?>
										
										<div class="personlinfrm">
										
											<h5>Superannuation Information</h5>
											<?php
											if($fetchStaff['superannuation'] == 2){
											?>
												<h6 style="margin-bottom:20px;">Employer Choice of Fund</h6>
												<?php
											}
											else{
												?>
												<h6>Staff's Own Superannuation Fund</h6>
												<p>
													<span class="viewthejobrighttxt">
														Superannuation Name - <?php echo $fetchStaff['supname'];?>, &nbsp; 
														Superannuation Number - <?php echo $fetchStaff['supnumber'];?>, &nbsp; 
														Superannuation ABN - <?php echo $fetchStaff['supabn'];?>
													</span>
												</p>
												
												<?php
											}
											?>
											</div>
											<?php
											}
											}
											
											if($getstat['avlblty'] > 0){
											?>
										<div class="personlinfrm">
										
										<h5>Availability</h5>
										<?php
									if($getstat['avlblty'] == 1){
									?>
										<h6 style="margin-bottom:20px;">Available for complete duration of the work</h6>
										<?php
										if($getstat['notes']){
												echo "<p>".stripslashes($getstat['notes'])."</p>";
												
											}
									}
									else if($getstat['avlblty'] == 2){
										$avldays = dbQuery($dbConn, "SELECT * FROM staff_availability where jobid = '".$id."' and staff_id = '".$userid."'");
										?>
										<h6 style="margin-bottom:20px;">Available days</h6>
											
											<span class="viewthejobrighttxt">
												<?php
												while($daysrow = dbFetchArray($avldays)){
													?>
													<?php echo date('M j, Y', strtotime($daysrow['availble_date']));?>
													&nbsp;&nbsp;
													<?php echo date('h:s a', strtotime($daysrow['starttime']));?> - <?php echo date('h:s a', strtotime($daysrow['endtime']));?><br>
													<?php
												}
												?>
											</span>
											<?php
											if($getstat['notes']){
												echo "<p>".stripslashes($getstat['notes'])."</p>";
												
											}
												
											
									}
									?>
									</div>
									<?php
									}
									
									?>
									<?php
									if($fetch['isclosed'] == 0 && $jobendttime > strtotime($currtime)){
									?>
									<p></p>
									<?php
									if($getstat['hired'] == 1){
										$hirestyle = "style='display:none;'";
										$msgstyle = "";
										$rejctmsgstyle = "style='display:none;'";
									}
									else if($getstat['hired'] == 2){
										$hirestyle = "style='display:none;'";
										$msgstyle = "style='display:none;'";
										$rejctmsgstyle = "";
									}
									else{
										$hirestyle = "";
										$msgstyle = "style='display:none;'";
										$rejctmsgstyle = "style='display:none;'";
									}
									
									?>
									
										<div class="acceptoffer" <?php echo $hirestyle;?>>
											<h5>Hire this Staff?</h5>
											<ul class="homebtn" style="margin:20px 0;">
											<?php
											if($getstat['avlblty'] == 1){
											?>
												<li><a href="javascript:void(0);" id="hire" style="padding:0.4rem 1.5rem;">Yes</a></li>
												<?php
											}
											else{
												?>
												<li><a href="javascript:void(0);" id="hire2" style="padding:0.4rem 1.5rem;">Yes</a></li>
												<?php
											}
											?>
												<li><a href="javascript:void(0);" id="reject" style="padding:0.4rem 1.5rem;">No</a></li>
											</ul>
										</div>
									
										<p>
											<span id="rejected" <?php echo $rejctmsgstyle;?>>You have rejected this Staff. Staff is notified.</span>
										</p>
										
<div class="modal fade" id="availability" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">Do you want your job availability to be updated based on this new hire?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		  <ul class="homebtn" style="margin:20px 0;">
			<li><a href="javascript:void(0);" id="yes_change" style="padding:0.4rem 1.5rem;">Yes</a></li>
			<li><a href="javascript:void(0);" id="no_change" style="padding:0.4rem 1.5rem;">No</a></li>
		  </ul>
      </div>
    </div>
  </div>
</div>
										<?php
								}
								if($fetch['jobdate2'] > date('Y-m-d')){
									$showbooking = 1;
								}
								else
								$showbooking = 0;
								/*if($fetch['applicn_deadln_date'] != "0000-00-00"){
									if($fetch['applicn_deadln_date'] > date('Y-m-d'))
									$showbooking2 = 1;
									else
									$showbooking2 = 0;
								}
								else*/
								$showbooking2 = 1;
								//if($showbooking == 1 && $showbooking2 == 1){
									$contact = dbQuery($dbConn, "SELECT a.contacting,a.hired,a.application_sent_to,b.name from job_status a inner join users b on a.application_sent_to=b.id where a.jobid = '".$id."' and a.application_sent_to = '".$userid."'");
									if(dbNumRows($contact) > 0){
									?>
								
								<?php
								$getstat = dbFetchArray($contact);
								if($getstat['hired'] == 1){
									$hired = "is hired";
								}
								else{
									if($getstat['contacting'] == 1){
										$hired = "contacted";
									}
								}
								?>
										<p><span id="success"><?php echo $getstat['name'];?> <?php echo $hired;?> for the job.</span></p>
										<?php
							}
							if($fetch['isclosed'] == 0){
								if(strtotime($currtime) <= $jobendttime)
								echo "";
								else
								echo "<span class='closered'>Job is Closed</span>";
							}
							else
							echo "<span class='closered'>Job is Closed</span>";
                        //}
                        ?>
										
									</div>
									</div>
								</div>
								</div>
							  </div>
							  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
								<div class="table-responsive successtable">
									<table class="table" cellpadding="5" cellspacing="5">
                                    <tr class="table-light">
                                            <td width="20%">Hours</td>
                                            <td width="80%"><?php
											if($getstat['hired'] == 1){
                                            $duration = ceil(($jobendttime - $jobstrttime)/3600);
                                            echo $duration;
											}
											else{
												echo "This Staff is not hired.";
											}
                                            ?></td>
                                            </tr>
									</table>
								</div>
							  </div>
                              <?php
							  $usertype = getUserType($dbConn, $_SESSION['loginUserId']);
							  ?>
                              <div class="tab-pane fade" id="pills-clock" role="tabpanel" aria-labelledby="pills-clock-tab">
								<div class="table-responsive successtable">
									<table class="table" cellpadding="5" cellspacing="5">
                                    <tr class="table-light">
                                    <td>
									<input type="hidden" id="usertype" name="usertype" value="<?php echo $usertype;?>">
                                    <?php
                                    $clockoutstyle = '';
                                    
                                    if($getstat['hired'] == 1){
										//if(strtotime($currtime) >= $jobstrttime){
											$total_diff = 0;
											//if(strtotime($currtime) >= $jobstrttime){
												$gethours = dbQuery($dbConn, "SELECT clockincode,clockintime,clockouttime from staff_job_clock where staff_id = '".$getstat['application_sent_to']."' and job_id = '".$id."' and jobstartdate = '".$today."'");
												$myhours = dbNumRows($gethours);
												$hours = dbFetchArray($gethours);
												?>
												<span style="font-weight:bold;">Hours:</span> <span id="totalhour"></span>
												<?php
												if($myhours == 0){
													echo "<span id='prevtext'> Work has not started today yet.</span><br>";
												}
												else{
													if($hours['clockincode'] && $hours['clockintime'] == '0000-00-00 00:00:00' && $hours['clockouttime'] == '0000-00-00 00:00:00'){
														echo "<span id='prevtext'> Work has not started today yet.</span><br>";
													}
													if($hours['clockintime'] != '0000-00-00 00:00:00' && $hours['clockouttime'] == '0000-00-00 00:00:00'){
														echo "<span id='prevtext'> Staff has not clocked out today yet.</span><br>";
													}
													if($hours['clockouttime'] != '0000-00-00 00:00:00'){
														
													}
												}

												$gethours2 = dbQuery($dbConn, "SELECT clockintime,clockouttime from staff_job_clock where staff_id = '".$getstat['application_sent_to']."' and job_id = '".$id."'");
														while($hours2 = dbFetchArray($gethours2)){
															if($hours2['clockouttime'] != '0000-00-00 00:00:00'){
																$starttime = strtotime($hours2['clockintime']);
																$jobendtime = strtotime($hours2['clockouttime']);
																$diff = $jobendtime - $starttime;
																$total_diff += $diff;
															}
														}
														
														
														// break
														$total_diff_brk = 0;
														$getstffbreak = dbQuery($dbConn, "SELECT id,brkstarttime from staff_job_breaks where staff_id = '".$getstat['application_sent_to']."' and job_id = '".$id."'");
														if(dbNumRows($getstffbreak) > 0){
	
															$getstffbreak2 = dbQuery($dbConn, "SELECT brkstarttime,brkendtime from staff_job_breaks where staff_id = '".$getstat['application_sent_to']."' and job_id = '".$id."'");
															while($staffbrk2 = dbFetchArray($getstffbreak2)){
																if($staffbrk2['brkstarttime'] != '0000-00-00 00:00:00')
																$brkstart = strtotime($staffbrk2['brkstarttime']);
																else
																$brkstart = 0;
	
																if($staffbrk2['brkendtime'] != '0000-00-00 00:00:00'){
																	$brkend = strtotime($staffbrk2['brkendtime']);
																	//else
																	//$brkend = 0;
																	$diff_brk = $brkend - $brkstart;
																	$total_diff_brk += $diff_brk;
																}
	
															}
															
														}
														if($total_diff > 0){
															$diffwork = $total_diff - $total_diff_brk;
															if($diffwork >= 60){
																$minute = floor($diffwork/60);
																$second = $diffwork % 60;
																if($minute >= 60){
																	$hour = floor($minute/60);
																	$minute = $minute % 60;
																}
																else{
																	$hour = "00";
																	$minute = $minute;
																}
															}
															else{
																$hour = "00";
																$min = "00";
																$second = $diffwork;
															}
															if($second < 10)
															$second = "0".$second;
															else
															$second = $second;
															if($minute < 10)
															$minute = "0".$minute;
															else
															$minute = $minute;
															
															//$total_hours = gmdate("H:i:s", $diffwork);
															$total_hours = $hour.":".$minute.":".$second;
															echo $total_hours."<br>";
														}

                                        $getclock = dbQuery($dbConn, "SELECT clockintime,clockincode,clockouttime from staff_job_clock where staff_id = '".$getstat['application_sent_to']."' and job_id = '".$id."' AND jobstartdate = '".$today."'");
										if(dbNumRows($getclock) > 0){
                                        $clock = dbFetchArray($getclock);
										if($clock['clockouttime']=='0000-00-00 00:00:00'){
                                        if($clock['clockincode'] && $clock['clockintime']=='0000-00-00 00:00:00'){
											$clockinstyle = "";
											$clockinmsgstyle = "style='display:none;'";
										}
										else{
											$clockinstyle = "style='display:none;'";
											$clockinmsgstyle = "";
										}
                                            ?>
											<div id="clockindiv" <?php echo $clockinstyle;?>>
											<label>Enter code got in SMS for clock-in</label>
											<div class="mybrkinput myclockin">
                                            <input type="text" class="form-control clockincode">
											<a href="javascript:void(0);" class="clockcodesub">Submit</a>
											</div>
											</div>
											<span id="clockinsucces" <?php echo $clockinmsgstyle;?>>Staff is clocked in.</span>

											<ul id="myclockout" class="homebtn" style="margin:20px 0;display:none;">
                                            <li>
											<a href="javascript:void(0);" id="clockout">Clock out the Staff for Today</a>
											</li>
                                            </ul>
											<br>
											<span id="clockoutsucces" style="display:none;">Staff is clocked out.</span>
                                            <?php
										}

										// employee break
										$getbreak = dbQuery($dbConn, "SELECT * from staff_job_breaks where staff_id = '".$getstat['application_sent_to']."' and job_id = '".$id."' and breakday = '".$today."' order by id desc limit 0,1");
										$break = dbFetchArray($getbreak);
										if(dbNumRows($getbreak) > 0){
											if($break['brkstarttime'] != '0000-00-00 00:00:00' && $break['brkendtime'] == '0000-00-00 00:00:00'){
												?>
												<br>
												<span>Staff has taken break.</span>
												<?php
											}
											if($break['brkendtime'] != '0000-00-00 00:00:00'){
												?>
												<br>
												<span>Staff's break has been ended.</span>
												<?php
											}
										}
                                        
                                        if($clock['clockintime']!= '0000-00-00 00:00:00' && $clock['clockouttime']=='0000-00-00 00:00:00'){
                                        //if(strtotime($currtime) <= $jobendttime){
											if($clock['clockintime']!='0000-00-00 00:00:00' && $clock['clockouttime']=='0000-00-00 00:00:00'){
												$clockoutstyle = "";
												$clockoutmsgstyle = "style='display:none;'";
											}
											else{
												$clockoutstyle = "style='display:none;'";
												$clockoutmsgstyle = "";
											}
											
                                            ?>
                                            <br>
											<!--<span>End time: <?php echo date('M j, Y', strtotime($jobenddate));?> at <?php echo date('h:i a', strtotime($endtime));?></span>-->
											<?php
											//if(date('Y-m-d', strtotime($currtime)) == $jobenddate){
											?>
                                            <ul class="homebtn" style="margin:20px 0;">
                                            <li>
											<a href="javascript:void(0);" id="clockout" <?php echo $clockoutstyle;?>>Clock out the Staff for Today</a>
											</li>
                                            </ul>
											<br>
											<span id="clockoutsucces" style="display:none;">Staff is clocked out.</span>
                                            <?php
											}
											if($clock['clockouttime']!='0000-00-00 00:00:00'){
												?>
												<br>
												<span>Staff is clocked out today.</span>
												<?php
											}
										}
										else{
											echo "Staff has not started work today.";
										}
                                            
												
                                            //}
										//}
									
									
									//}
									/*else{
										echo "Work time is not started yet";
									}*/
                                    }
									else{
										echo "This Staff is not hired.";
									}
                                    ?>
                                    </td>
                                    </tr>
                                    </table>
								</div>
							  </div>
							  
							<div class="tab-pane fade" id="pills-rate" role="tabpanel" aria-labelledby="pills-rate-tab">
								<div class="table-responsive successtable">
									<?php
									if($getstat['hired'] == 1){
										$checkrevw = dbQuery($dbConn, "SELECT id from reviews where job_id = '".$id."' and emp_id = '".$_SESSION['loginUserId']."' and staff_id = '".$userid."' and givenbystaff=0");
										if(dbNumRows($checkrevw) == 0){
									?>
									<form action="" method="post" id="review">
									<input type="hidden" name="jobid" value="<?php echo $id;?>">
									<input type="hidden" name="userid" value="<?php echo $userid;?>">
									<input type="hidden" name="emp_id" value="<?php echo $_SESSION['loginUserId'];?>">
									<table class="table" cellpadding="5" cellspacing="5">
                                    <tr class="table-light">
                                    <td colspan="2">How was the staff member?</td>
									</tr>
									<tr>
                                    <td colspan="2"><input type="radio" name="myrate" required value="1">&nbsp;Unsatisfactory&nbsp;&nbsp;
									<input type="radio" name="myrate" value="4">&nbsp;Pretty Good&nbsp;&nbsp;
									<input type="radio" name="myrate" value="5">&nbsp;Excellent
									</td>
									</tr>
									<tr>
                                    <td valign="top">Comments (Your comments maybe shared with the employee or displayed publicly)</td>
									<td><textarea name="mycomment" id="comment" class="form-control required"></textarea></td>
									</tr>
									<tr>
                                    <td>Would you hire them again? This information is only for Staff Express to know and understand.</td>
									<td><input type="radio" name="hireagain" required value="1">&nbsp;Yes
									<input type="radio" name="hireagain" value="2">&nbsp;No</td>
									</tr>
									<tr>
									<td colspan="2">
										<input type="submit" value="Submit" id="subrate">
									</td>
									</tr>
									</table>
									</form>
									<span style="color:green;display:none;" id="ratesuccess">Review is submitted.</span>
									<?php
										}
										else{
											?>
											<table class="table" cellpadding="5" cellspacing="5">
											<tr class="table-light">
											<td colspan="2">You have already given review for this job.</td>
											</tr>
											</table>
											<?php
										}
									}
									else{
										?>
										<table class="table" cellpadding="5" cellspacing="5">
										<tr class="table-light">
										<td colspan="2">This Staff is not hired.</td>
										</tr>
										</table>
										<?php
									}
									?>
								</div>
							</div>

							</div>
								
						</div>
                        
							<form action="" method="post" id="myhire">
									<input type="hidden" id="jobid" name="jobid" value="<?php echo $id;?>">
									<input type="hidden" id="userid" name="userid" value="<?php echo $userid;?>">
									<input type="hidden" id="avl_update" name="avl_update" value="">
							</form>
							<ul class="homebtn" style="margin:20px 0;">
							<li><a href="<?php echo SITEURL;?>viewjob/<?php echo $id;?>" style="padding:0.4rem 1.5rem;">Back</a></li>
							</ul>
						</div>
						
						
					</div>

				</div>
				
			</div>
		</div>
	</div>
	
	<?php include_once('footer.php');?>