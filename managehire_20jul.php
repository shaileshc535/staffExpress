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

    // send mail to Staff
	if($res['notified'] == "Email" || $res['notified'] == "Both"){
    $to = $res['email'];
    $subject = "You are Hired";

    $message = "<table width='100%'>
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
			<div class="row">
			<?php include_once "employer_left.php";?>
				<div class="col-lg-9">
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
										<p>
											<span class="pertiwidth"><strong>Name:</strong></span>
											<span class="viewthejobrighttxt"><?php echo stripslashes($fetchStaff['name'])." ".stripslashes($fetchStaff['lname']);?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Date of Birth:</strong></span>
											<span class="viewthejobrighttxt"><?php echo date('M j, Y', strtotime($fetchStaff['dob']));?></span>
										</p>
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
										<p>
											<span class="pertiwidth"><strong>Category:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $staffcatstr;?></span>
										</p>
										<?php
									if($allstaff_quals != ""){
									?>
										<p>
											<span class="pertiwidth"><strong>Qualification(s):</strong></span>
											<span class="viewthejobrighttxt"><?php echo $allstaff_quals;?></span>
										</p>
										<?php
									}
									if($mydays != ""){
									?>
										<p>
											<span class="pertiwidth"><strong>Working Days:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $mydays;?></span>
										</p>
										<?php
									}
									?>
									<p>
											<span class="pertiwidth"><strong>Experience:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['experience'];?> years</span>
									</p>
									<?php
									$contact = dbQuery($dbConn, "SELECT * from job_status where jobid = '".$id."' and application_sent_to = '".$userid."'");
									$getstat = dbFetchArray($contact);
									if($getstat['hired'] == 1){
									if($fetchStaff['bsb'] && $fetchStaff['accno']){
									?>
										<h5>Payment Information</h5>
										<p>
											<span class="pertiwidth"><strong>BSB Number:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['bsb'];?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Account Number:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['accno'];?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Name of Bank Account:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['bankaccname'];?></span>
										</p>
										<?php
										if($fetchStaff['taxno']){
										?>
										<p>
											<span class="pertiwidth"><strong>Tax File Number:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['taxno'];?></span>
										</p>
										<?php
										}
										?>
										<p>
											<span class="pertiwidth"><strong>Claiming Tax Free Threshold for Any Work Done?</strong></span>
											<span class="viewthejobrighttxt"><?php 
										if($fetchStaff['claim_tax'] == 1)
										echo "Yes";
										if($fetchStaff['claim_tax'] == 2)
										echo "No";
										?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Have a Higher Education Loan Program (HELP), VET Student Loan (VSL), Financial Supplement (FS), Student Start-up Loan (SSL) or Trade Support Loan (TSL) Debt?</strong></span>
											<span class="viewthejobrighttxt"><?php 
										if($fetchStaff['high_edutn'] == 1)
										echo "Yes";
										if($fetchStaff['high_edutn'] == 2)
										echo "No";
										?></span>
										</p>
										<?php
									}
									}
									if($fetchStaff['nomedical'] || $fetchStaff['heart'] || $fetchStaff['diabetes'] || $fetchStaff['bloodpr'] || $fetchStaff['hasallgy'] || $fetchStaff['allergy'] || $fetchStaff['hasid'] || $fetchStaff['infectious'] || $fetchStaff['hasother'] || $fetchStaff['otherdis']){
									?>
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
											<span class="pertiwidth"><strong>Heart Conditions:</strong></span>
											<span class="viewthejobrighttxt"><?php echo ($fetchStaff['heart']==1)?"Heart issues":"No Heart issues";?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Diabetes:</strong></span>
											<span class="viewthejobrighttxt"><?php echo ($fetchStaff['diabetes']==1)?"Yes":"No";?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>High Blood Pressure:</strong></span>
											<span class="viewthejobrighttxt"><?php echo ($fetchStaff['bloodpr']==1)?"Yes":"No";?></span>
										</p>
										<?php
										if($fetchStaff['hasallgy'] || $getdetls['allergy']){
										?>
										<p>
											<span class="pertiwidth"><strong>Has Allergy:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['allergy'];?></span>
										</p>
										<?php
										}
										if($fetchStaff['hasid'] || $getdetls['infectious']){
										?>
										<p>
											<span class="pertiwidth"><strong>Has Infectious Disease:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['infectious'];?></span>
										</p>
										<?php
										}
										if($fetchStaff['hasother'] || $getdetls['otherdis']){
											?>
										<p>
											<span class="pertiwidth"><strong>Has Other Issues:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['otherdis'];?></span>
										</p>
										<?php
										}
									}
									}
									if($getstat['hired'] == 1){
									if($fetchStaff['superannuation']){
									?>
										<h5>Superannuation Information</h5>
										<?php
									if($fetchStaff['superannuation'] == 2){
									?>
										<h5>Employer Choice of Fund</h5>
										<?php
									}
									else{
										?>
										<h5>Staff's Own Superannuation Fund</h5>
										<p>
											<span class="pertiwidth"><strong>Superannuation Name:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['supname'];?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Superannuation Number:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['supnumber'];?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Superannuation ABN:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetchStaff['supabn'];?></span>
										</p>
										<?php
									}
									}
									}
									
									if($getstat['avlblty'] > 0){
									?>
										<!--<h5>Availability</h5>-->
										<?php
									if($getstat['avlblty'] == 1){
									?>
										<!--<h5>Available for complete duration of the work</h5>-->
										<?php
									}
									else if($getstat['avlblty'] == 2){
										?>
										<p>
											<span class="pertiwidth"><strong>Available From:</strong></span>
											<span class="viewthejobrighttxt"><?php echo date('M j, Y', strtotime($getstat['availble_date1']));?> at <?php echo date('h:i A', strtotime($getstat['availble_time1']));?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Available To:</strong></span>
											<span class="viewthejobrighttxt"><?php echo date('M j, Y', strtotime($getstat['availble_date2']));?> at <?php echo date('h:i A', strtotime($getstat['availble_time2']));?></span>
										</p>
										<?php
									}
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
												<li><a href="javascript:void(0);" id="hire" style="padding:0.4rem 1.5rem;">Yes</a></li>
												<li><a href="javascript:void(0);" id="reject" style="padding:0.4rem 1.5rem;">No</a></li>
											</ul>
										</div>
									
										<p>
											<span id="rejected" <?php echo $rejctmsgstyle;?>>You have rejected this Staff. Staff is notified.</span>
										</p>
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
	
																if($staffbrk2['brkendtime'] != '0000-00-00 00:00:00')
																$brkend = strtotime($staffbrk2['brkendtime']);
																else
																$brkend = 0;
	
																$diff_brk = $brkend - $brkstart;
																$total_diff_brk += $diff_brk;
															}
															
														}
														$diffwork = $total_diff - $total_diff_brk;
														$total_hours = gmdate("H:i:s", $diffwork);
														echo $total_hours."<br>";

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
                                            <input type="text" class="form-control clockincode">
                                            <ul class="homebtn" style="margin:20px 0;">
                                            <li>
											<a href="javascript:void(0);" class="clockcodesub">Submit</a>
                                            </li>
                                            </ul>
											</div>
											<span id="clockinsucces" <?php echo $clockinmsgstyle;?>>Staff is clocked in.</span>
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

							</div>
								
						</div>
                        
							<form action="" method="post" id="myhire">
									<input type="hidden" id="jobid" name="jobid" value="<?php echo $id;?>">
									<input type="hidden" id="userid" name="userid" value="<?php echo $userid;?>">
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