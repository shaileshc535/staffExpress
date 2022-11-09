<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');


if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login?job=".$id."&user=".$userid."'</script>";
	exit;
}

$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):"";
$userid = isset($_REQUEST['userid'])?base64_decode(trim($_REQUEST['userid'])):"";

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

    $myjob = dbQuery($dbConn, "SELECT a.title,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.howmnypeople,a.isclosed,a.add_time,b.name,b.business_name FROM job_details a inner join users b on a.employer_id=b.id where a.id='".$jobid."'");
    $fetch = dbFetchArray($myjob);

    dbQuery($dbConn, "UPDATE job_status set hired=1, hiredon = '".$today."' where jobid = '".$jobid."' and application_sent_to = '".$userid."'");

	$contact = dbQuery($dbConn, "SELECT avlblty from job_status where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
	$getstat = dbFetchArray($contact);
	if($getstat['avlblty'] == 1){
		if($fetch['howmnypeople'] == 1){
				//dbQuery($dbConn, "UPDATE job_details set isclosed=1 where id = '".$jobid."'");
		}
		else{
			$checkhired = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$jobid."' and hired=1");
			if(dbNumRows($checkhired) == $fetch['howmnypeople']){
				//dbQuery($dbConn, "UPDATE job_details set isclosed=1 where id = '".$jobid."'");
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

    $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid;'>
	<tr>
		<td style='padding:5px; font-size:18px; color:green; text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
	</tr>
	<tr>
	<td colspan='2'>&nbsp;</td>
	</tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Hello ".$res['name'].",</td>
    </tr>
    <tr><td colspan='2' style='padding-left:10px;'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>You are hired to perform this job: ".stripslashes($fetch['title']).".</td>
    </tr>
    <tr><td colspan='2' style='padding-left:10px;'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Your employer is: ".stripslashes($fetch['name']).".</td>
    </tr>
    <tr><td colspan='2' style='padding-left:10px;'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Job timing: ".date('M j, Y', strtotime($fetch['jobdate']))." at ".date('h:i A', strtotime($fetch['starttime']))." - ".date('M j, Y', strtotime($fetch['jobdate2']))." at ".date('h:i A', strtotime($fetch['endtime'])).".</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>
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

$myjob = dbQuery($dbConn, "SELECT a.*,b.name,b.phone,b.address,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$id."'");
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

	$getstaff = dbQuery($dbConn, "SELECT a.*,b.name,b.lname,b.email,b.phone,b.image from staff_details a inner join users b on a.staff_id=b.id where staff_id = '".$userid."'");
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
					<?php
					$latest_job_id = get_latest_job($_SESSION['loginUserId'], $dbConn);
					?>
						<ul>
							<li <?php if($page == "success.php" || $page == "viewmyjob.php" || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">Jobs</a></li>
							<li><a href="<?php echo SITEURL;?>viewjob/<?php echo $latest_job_id;?>?action=contacting">Candidates</a></li>
							<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>
							<li><a href="<?php echo SITEURL;?>emp_confirmation/<?php echo $latest_job_id;?>">Confirmation</a></li>
							<li><a href="<?php echo SITEURL."emp_calendar";?>">Calender View</a></li>
						</ul>
					</div>
					<div class="col-sm-4">
						<div class="subnavright">
							<ul>
								<li <?php if($page == "job_post1.php") echo "class='active'";?>><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>
								<li><a href="<?php echo SITEURL;?>editjob1/<?php echo $id;?>">Edit Job</a></li>
								<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>
							</ul>
						</div>
					</div>
				</div>
				
			</div>
			<div class="row">
			<?php //include_once "employer_left.php";?>
				<div class="col-sm-8 offset-sm-2">
					<div class="works_heading">

						<?php
						if(isset($_REQUEST['hired']) && $_REQUEST['hired'] == 1){
							echo "<div class='alert-success' style='padding:15px;margin-bottom:10px;'>This Staff has been hired.</div>";
						}
						?>
                        <h4 style="padding-bottom:20px;"><?php echo stripslashes($fetch['title']);?>
							<?php
							if($fetch['isclosed'] == 0){
								
							}
							else
							echo "<span class='closered'><i class='fa fa-times' aria-hidden='true'></i> Job is paused</span>";
							?>
						</h4>
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
						
						$contact = dbQuery($dbConn, "SELECT a.contacting,a.hired,a.application_sent_to,a.confirmation_sent,b.name from job_status a inner join users b on a.application_sent_to=b.id where a.jobid = '".$id."' and a.application_sent_to = '".$userid."'");
							if(dbNumRows($contact) > 0){
									
								$getstat = dbFetchArray($contact);
								if($getstat['hired'] == 1){
									$hired = "is hired";
								}
								else{
									if($getstat['contacting'] == 1){
										$hired = "contacted";
									}
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
							  if($getstat['hired'] == 0 && $getstat['confirmation_sent'] == 1){
							  ?>
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-clock-tab" data-bs-toggle="pill" data-bs-target="#pills-interview" type="button" role="tab" aria-controls="pills-clock" aria-selected="false">Interview</button>
							  </li>
                              <?php
							  }
                              //if($getstat['hired'] == 1 && ($jobendttime > strtotime($currtime))){
                              ?>
                              <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-clock-tab" data-bs-toggle="pill" data-bs-target="#pills-clock" type="button" role="tab" aria-controls="pills-clock" aria-selected="false">Hours & Clock</button>
							  </li>
							  <?php
							  if($fetch['covertype'] == 1){
							  ?>
							  <!--<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-rate-tab" data-bs-toggle="pill" data-bs-target="#pills-rate" type="button" role="tab" aria-controls="pills-rate" aria-selected="false">Review</button>
							  </li>-->
                              <?php
                              }
                              ?>
							</ul>
							
							<div class="tab-content" id="pills-tabContent">
							<div class="tab-pane fade show active" id="pills-canddtls" role="tabpanel" aria-labelledby="pills-canddtls-tab">
								<div class="successtable">
								<div class="viewmyjobtabonedetails">
									<div class="viethejobbk">
									<div class="alljbdts">
										
										<div class="personlinfrm">
											<div class="row">
												<div class="col-sm-9">
													<h4 style="float:left;"><?php echo stripslashes($fetchStaff['name'])." ".stripslashes($fetchStaff['lname']);?></h4>
													<ul class="homebtn" style="float:right;">
													<li><a href="<?php echo SITEURL;?>emp_messages?showmsg=<?php echo $userid;?>_<?php echo $id;?>_<?php echo $_SESSION['loginUserId'];?>" style="padding:0.4rem 1.5rem;">Message</a></li>
													</ul>
													<?php
													// rating
													$total_rating = 0;
													$allrec = dbQuery($dbConn, "SELECT id,rating from reviews where staff_id = '".$userid."' and givenbystaff=0");
													$ratingtotal = dbNumRows($allrec);
													if($ratingtotal > 3){
													while($rowallrevw = dbFetchArray($allrec)){
														$total_rating += $rowallrevw['rating'];
													}

														$avgraging = number_format(($total_rating / $ratingtotal),1);
														?>
														<!--<img src="<?php echo SITEURL;?>images/<?php //echo ceil($avgraging);?>.png" class="img-fluid" style="margin-top:-3px;" alt=""> (<?php //echo $avgraging;?>)-->
														<?php
													}
													?>
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
														<span class="viewthejobrighttxt onlyapplicantupmainsec">
														<?php
														if($fetchStaff['dob'] != "0000-00-00"){
														?>
															<span class="db">DOB - <?php echo date('M j, Y', strtotime($fetchStaff['dob']));?></span><br />
															
															<?php
														}
															if($mydays != ""){
															?>
															<!--<span class="wrkdys"><?php //echo $mydays;?></span><br />-->
															<?php
															}
															?>
															
															<?php
															
															$contact = dbQuery($dbConn, "SELECT * from job_status where jobid = '".$id."' and application_sent_to = '".$userid."'");
															$getstat = dbFetchArray($contact);
															
																if($getstat['avlblty'] == 1){
																?>
																	<h6 style="margin-bottom:15px;">Available for complete duration of the work</h6>
																	<?php
																	
																}
																else if($getstat['avlblty'] == 2){
																	
																	?>
																	<h6 style="margin-bottom:15px;">Available days
																	</h6>
																		<div class="row" style="margin-bottom:15px;">
																				
																		<?php 
																		if($getstat['apply_type'] == 1){
																		$avldays = dbQuery($dbConn, "SELECT * FROM staff_availability where jobid = '".$id."' and staff_id = '".$userid."' order by id asc limit 0,1");
																		$daysrow = dbFetchArray($avldays);
																		
																		$avldays2 = dbQuery($dbConn, "SELECT * FROM staff_availability where jobid = '".$id."' and staff_id = '".$userid."' order by id desc limit 0,1");
																		$daysrow2 = dbFetchArray($avldays2);
																		?>
																		<div class="col-sm-6">
																		<p class="avldts">
																		<?php echo date('M j, Y', strtotime($daysrow['availble_date']));?> - <?php echo date('M j, Y', strtotime($daysrow2['availble_date']));?><br>
																		<?php
																		if($fetch['workmode'] == 2){
																		?>
																		<?php echo date('h:i a', strtotime($daysrow['starttime']));?> - <?php echo date('h:i a', strtotime($daysrow['endtime']));
																		}
																		?>
																		</p></div>
																		<?php
																		}
																		else if($getstat['apply_type'] == 2){
																		$avldays = dbQuery($dbConn, "SELECT * FROM staff_availability where jobid = '".$id."' and staff_id = '".$userid."'");
																		while($daysrow = dbFetchArray($avldays)){
																		?>
																		<div class="col-sm-6">
																		<p class="avldts">
																		<?php
																		echo date('M j, Y', strtotime($daysrow['availble_date']));?>
																		<?php
																		if($fetch['workmode'] == 2){
																		?>
																		&nbsp;&nbsp;
																		<?php echo date('h:i a', strtotime($daysrow['starttime']));?> - <?php echo date('h:i a', strtotime($daysrow['endtime']));
																		}
																		?>
																		</p></div>
																		<?php
																		}
																		}
																		?>
																		
																		</div>
																		<?php
																	}
																	?>
																				
																		
																		<?php
																		
																			
																		
																//}
															?>
															
															<div class="othrdescrp">
																<span class="newjbdtl"><strong>Driving License -</strong> <?php
																	if($fetchStaff['license'] == '1')
																	echo "Yes";
																	else if($fetchStaff['license'] == '2')
																	echo "No";
																	?>
																</span> &nbsp; | &nbsp; 
																<span class="newjbdtl"><strong>Forklift License -</strong> 
																	<?php
																		if($fetchStaff['for_license'] == '1')
																		echo "Yes";
																		else if($fetchStaff['for_license'] == '2')
																		echo "No";
																	?>
																</span>
																<span class="newjbdtl"><strong>Responsible Service of Alcohol -</strong>  
																	<?php
																		if($fetchStaff['alcohol'] == '1')
																		echo "Yes";
																		else if($fetchStaff['alcohol'] == '2')
																		echo "No";
																	?>
																</span> &nbsp; | &nbsp;
																<span class="newjbdtl"><strong>Working with Children Check -</strong>  
																	<?php
																		if($fetchStaff['working_with_child'] == '1')
																		echo "Yes";
																		else if($fetchStaff['working_with_child'] == '2')
																		echo "No";
																	?>
																</span>
																<span class="newjbdtl"><strong>White Card -</strong>  
																	<?php
																		if($fetchStaff['white_card'] == '1')
																		echo "Yes";
																		else if($fetchStaff['white_card'] == '2')
																		echo "No";
																	?>
																</span> &nbsp; | &nbsp;
																<span class="newjbdtl"><strong>National Police Clearance -</strong>  
																	<?php
																		if($fetchStaff['police_clearnce'] == '1')
																		echo "Yes";
																		else if($fetchStaff['police_clearnce'] == '2')
																		echo "No";
																	?>
																</span>
															</div>
														</span>
													</p>
													<?php
													if($allstaff_quals != ""){
													?>
														
														<?php
													}
													?>
														
														
												</div>
												<div class="col-sm-3 mobaligncntr">
												
												<?php
												if($fetchStaff['image']){
													$staffimg = SITEURL."uploads/".$fetchStaff['image'];
												}
												else{
													$staffimg = SITEURL."images/noimage.jpg";
												}
												?>
													<img src="<?php echo $staffimg;?>" alt="" class="img-responsive applicantprofileimg" />
													<div class="interstedcandists">
														<h5>Interested?</h5>
														<div class="intrsttab">
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
														if($getstat['confirmation_sent'] == 0){
														if($getstat['avlblty'] == 1){
															
														?>
														<a href="javascript:void(0);" class="intrsttick <?php if($fetch['isclosed'] == 1) echo 'disabled';?>" <?php if($fetch['isclosed'] == 0) echo 'id="hire2"';?>><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" class="intrstcross <?php if($fetch['isclosed'] == 1) echo 'disabled';?>" <?php if($fetch['isclosed'] == 0) echo 'id="reject"';?>><i class="fa fa-times" aria-hidden="true"></i></a>
														<?php
														}
														else{
															
															?>
															<a href="javascript:void(0);" class="intrsttick <?php if($fetch['isclosed'] == 1) echo 'disabled';?>" <?php if($fetch['isclosed'] == 0) echo 'id="hire2"';?>><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" class="intrstcross <?php if($fetch['isclosed'] == 1) echo 'disabled';?>" <?php if($fetch['isclosed'] == 0) echo 'id="reject"';?>><i class="fa fa-times" aria-hidden="true"></i></a>
															
															<?php
															}
															?>
															
															<?php
														}
														else{
															if($getstat['hired'] == 1){
																?>
																<a href="javascript:void(0);" class="intrsttick active"><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" class="intrstcross"><i class="fa fa-times" aria-hidden="true"></i></a>
																<?php
															}
															if($getstat['hired'] == 2){
																?>
																<a href="javascript:void(0);" class="intrsttick"><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" class="intrstcross active"><i class="fa fa-times" aria-hidden="true"></i></a>
																<?php
															}
															if($getstat['confirmation_sent'] == 1 && $getstat['hired'] == 0){
																?>
																<a href="javascript:void(0);" class="intrsttick"><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" class="intrstcross"><i class="fa fa-times" aria-hidden="true"></i></a>
																<?php
															}
														}
														if($getstat['hired'] == 0 && $getstat['confirmation_sent'] == 1){
															echo "<p><strong>Shortlisted</strong></p>";
														}
														?>
														<p id="confrmn_sent" style="font-weight:bold;"></p>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="personlinfrm">
										<h5 class="manfhirehdng"><strong>Experience</strong></h5>
										<?php
												if($fetchStaff['experienced'] == '1'){
												$staffexp = dbQuery($dbConn, "SELECT company,designation,experience,experience_month from staff_experience where staff_id = '".$userid."' order by id");
												if(dbNumRows($staffexp) > 0){
													while($staffexprow = dbFetchArray($staffexp)){
												?>
												<p class="vwmyjobexpdetls"><i class="fa fa-briefcase" aria-hidden="true"></i> &nbsp;<span class="comnm"><?php echo stripslashes($staffexprow['designation']);?></span> at <?php echo stripslashes($staffexprow['company']);?> 
												(<?php echo stripslashes($staffexprow['experience']);?> year(s) <?php echo stripslashes($staffexprow['experience_month']);?> month(s))</p>
												
												<?php
												}
												}
												}
												else{
													echo "<p><span class='comnm'>No experience</span></p>";
												}
											?>
										</div>
										<?php
										if($fetchStaff['resume']){
										?>										
										<div class="personlinfrm">
												<h5 class="manfhirehdng"><strong>Resume</strong>
												<span class="viewpopcover">
												<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $fetchStaff['resume'];?>" target="_blank">View</a>
												</span>
												</h5>
										</div>
										<?php
										}
											$check = dbQuery($dbConn, "SELECT cover_letter from staff_documents where staff_id = '".$userid."'");
											if(dbNumRows($check) > 0){
												?>
										<div class="personlinfrm">
											
												<h5 class="manfhirehdng">Cover Letter
												<span class="viewpopcover">
												<?php
												$i=1;
												//while($getdetls = dbFetchArray($check)){
											?>
											<a href="javascript:void(0);" id="loadcl" data-id="<?php echo $userid;?>">View</a>
											
												<!--<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['cover_letter'];?>" target="_blank" class="textlnk"><?php echo $getdetls['cover_letter'];?></a> &nbsp; | &nbsp;-->
												
											<?php
												//}
												?>
												</span>
												</h5>
											
										</div>
										<?php
										}
										$check = dbQuery($dbConn, "SELECT qualifications from staff_qualifications where staff_id = '".$userid."'");
											if(dbNumRows($check) > 0){
												?>
										<div class="personlinfrm">
											<h5 class="manfhirehdng">Qualification Documents
											<span class="viewpopcover">
											<a href="javascript:void(0);" id="loadqual" data-id="<?php echo $userid;?>">View</a>
											<?php
													$i=1;
												//while($getdetls = dbFetchArray($check)){
												?>
												<!--<a href="javascript:void(0);" id="loadqual">View</a>
														<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['qualifications'];?>" target="_blank" class="textlnk"><?php echo $getdetls['qualifications'];?></a> &nbsp; | &nbsp;-->
													
												<?php
												//$i++;
												//}
												?>
												</span>
											</h5>
												
										</div>
										<?php
												}
												$check = dbQuery($dbConn, "SELECT `certificate` from staff_certificate where staff_id = '".$userid."'");
												if(dbNumRows($check) > 0){
											?>
										<div class="personlinfrm">
											<h5 class="manfhirehdng">Certificates
											<span class="viewpopcover">
											<?php
													$i=1;
												//while($getdetls = dbFetchArray($check)){
												?>
												<a href="javascript:void(0);" id="loadcert" data-id="<?php echo $userid;?>">View</a>
														<!--<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['qualifications'];?>" target="_blank" class="textlnk"><?php echo $getdetls['qualifications'];?></a> &nbsp; | &nbsp;--> 
													
												<?php
												//$i++;
												//}
												?>
												</span>
											</h5>
												<?php
													//$i=1;
													//while($getdetls = dbFetchArray($check)){
												?>
												
														<!--<a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['certificate'];?>" target="_blank" class="textlnk"><?php echo $getdetls['certificate'];?></a> &nbsp; | &nbsp;-->
													
												<?php
												//$i++;
												//}
												?>
												</div>
												<?php
												}
												
												if($fetchStaff['video'] && file_exists("uploads/video/".$fetchStaff['video'])){
											?>
											<div class="personlinfrm">
											<h5 class="manfhirehdng">Video
											<span class="viewpopcover">
											
												<a href="" data-bs-toggle="modal" data-bs-target="#videoModalStaffPage">View</a>
												
												</span>
												</h5>
												</div>
												
												<div class="modal fade" id="videoModalStaffPage" tabindex="-1" aria-labelledby="videoModalOneLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
													<video controls loop>
														<source src="<?php echo SITEURL;?>uploads/video/<?php echo $fetchStaff['video'];?>">
													</video>
													</div>
												  
													</div>
												</div>
												</div>
												<?php
												}
												?>
<div class="modal fade" id="showcoverlettr" tabindex="-1" role="dialog" aria-labelledby="coverlettrLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="coverlettrLabel" style="margin-bottom:0 !important;"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		<embed src="" frameborder="0" width="100%" height="400px" />
		<iframe src="" width="100%" height="400px"></iframe>
      </div>
    </div>
  </div>
</div>

<div class="modal fade onlyapplicantpopall" id="showdocs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="margin-bottom:0 !important;"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  <div class="mydocuments"></div>
	  
      </div>
    </div>
  </div>
</div>
												
												
												<?php
												
												//if($getstat['hired'] == 1){
												//}
												if($fetchStaff['nomedical'] || $fetchStaff['heart'] || $fetchStaff['diabetes'] || $fetchStaff['bloodpr'] || $fetchStaff['hasallgy'] || $fetchStaff['allergy'] || $fetchStaff['hasid'] || $fetchStaff['infectious'] || $fetchStaff['hasother'] || $fetchStaff['otherdis']){
											?>
										
										
										<div class="personlinfrm">
											<h5>Medical Conditions</h5>
											<?php
												if($fetchStaff['nomedical'] == 1){
												?>
													<p>
														<span class="viewthejobrighttxt"><strong>No medical issues</strong></span>
													</p>
													<?php
												}
												else{
													?>
													<p style="margin-bottom:0;">
														<span class="viewthejobrighttxt">
															<strong>Heart Conditions -</strong> <?php echo ($fetchStaff['heart']==1)?"Heart issues":"No Heart issues";?> &nbsp; | &nbsp; 
															<strong>Diabetes -</strong> <?php echo ($fetchStaff['diabetes']==1)?"Yes":"No";?> &nbsp; | &nbsp; 
															<strong>High Blood Pressure -</strong> <?php echo ($fetchStaff['bloodpr']==1)?"Yes":"No";?> &nbsp; | &nbsp; 
															<?php
													if($getdetls['allergy']){
													?>
															<strong>Has Allergy -</strong> <?php echo $fetchStaff['allergy'];?>
															 &nbsp; | &nbsp; 
															 <?php
													}
													if($getdetls['infectious']){
													?>
															<strong>Has Infectious Disease -</strong> <?php echo $fetchStaff['infectious'];?> &nbsp; | &nbsp; 
															<?php
													}
													if($getdetls['otherdis']){
													?>
															<strong>Has Other Issues -</strong> <?php echo $fetchStaff['otherdis'];?>
															<?php
													}		
													?>														
														</span>
													</p>
													<?php
													
												}
												?>
												<p>
													<span class="viewthejobrighttxt">
													<strong>Covid Number of Doses -</strong> <?php echo $fetchStaff['no_of_doses'];?> &nbsp; | &nbsp;
													<strong>Had Covid before -</strong> <?php echo ($fetchStaff['had_covid_before']==1)?"Yes":"No";?> &nbsp; | &nbsp;
													<strong>Had Covid in the past 3 months -</strong> <?php echo ($fetchStaff['had_covid_before_past']==1)?"Yes":"No";?>
													</span>
												</p>
										</div>		
										<?php
												}
												if($getstat['hired'] == 1){
													if($fetchStaff['bsb'] && $fetchStaff['accno']){
													?>
										
										
												<?php
												}
												if($fetchStaff['superannuation']){
												?>
										
										
											<?php
											}
											}
											
											if($getstat['avlblty'] > 0){
											?>
										
									<?php
									}
									
									?>
									<?php
									if($fetch['isclosed'] == 0 && $jobendttime > strtotime($currtime)){
									?>
									
										<!--<p>
											<span id="rejected" <?php echo $rejctmsgstyle;?>>You have rejected this Staff. Staff is notified.</span>
										</p>-->
										
										<div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		  <p style="text-align:center;">Staff is shortlisted.</p>
      </div>
    </div>
  </div>
</div>
										
<!--<div class="modal fade" id="availability" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
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
</div>-->
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
									
							
                        //}
                        ?>
										
									</div>
									</div>
								</div>
								</div>
							  </div>
							  
							  <div class="tab-pane fade" id="pills-interview" role="tabpanel" aria-labelledby="pills-profile-tab">
									<div class="table-responsive successtable">
										<div class="viewmyjobtabonedetails">
										<form action="" method="post" id="send_invitn">
										<input type="hidden" name="jobid" value="<?php echo $id;?>">
										<input type="hidden" name="userid" value="<?php echo $userid;?>">
											<div class="viethejobbk">
												<h4><?php echo stripslashes($fetchStaff['name'])." ".stripslashes($fetchStaff['lname']);?></h4>
												<p class="fntadjst"><?php echo $staffcatstr;?></p>
												<div class="setupintrvw">
													<h5>Set Up Interview</h5>
													<div class="row">
														<div class="col-sm-4">
															<div class="intrwdetls">
																<label>Duration</label>
																<select class="intrveduration required" name="intview_durtn">
																	<option value="">Select Duration</option>
																	<option value="15 min">15 min</option>
																	<option value="30 min">30 min</option>
																	<option value="45 min">45 min</option>
																	<option value="1 hr">01 hr</option>
																	<option value="2 hrs">02 hrs</option>
																</select>
															</div>
														</div>
														<div class="col-sm-4">
															<div class="intrwdetls">
																<label>Date</label>
																<input type="text" name="intview_date" class="dtintrvw required" placeholder="Select Date" id="interview_cal" />
															</div>
														</div>
														<div class="col-sm-4">
															<div class="intrwdetls">
																<label>Start Time</label>
																<select class="intrveduration required" name="intview_time">
																	<option value="">Select slot</option>
																	<option value="09:00 am">09:00 am</option>
																	<option value="09:30 am">09:30 am</option>
																	<option value="10:00 am">10:00 am</option>
																	<option value="10:30 am">10:30 am</option>
																	<option value="11:00 am">11:00 am</option>
																	<option value="11:30 am">11:30 am</option>
																	<option value="12:00 pm">12:00 pm</option>
																	<option value="12:30 pm">12:30 pm</option>
																</select>
															</div>
														</div>
													</div>
													
													<div class="intrvtyp">
														<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
														  <li class="nav-item" role="presentation">
															<button class="nav-link active" id="pills-home-tab-int" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"><i class="fa fa-video-camera" aria-hidden="true"></i> &nbsp; Video Call</button>
														  </li>
														  <li class="nav-item" role="presentation">
															<button class="nav-link" id="pills-profile-tab-int" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false"><i class="fa fa-phone" aria-hidden="true"></i> &nbsp; Phone Call</button>
														  </li>
														  <li class="nav-item" role="presentation">
															<button class="nav-link" id="pills-contact-tab-int" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false"><i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp; In-person</button>
														  </li>
														</ul>
														
														<input type="hidden" id="contact_type" name="contact_type" value="video">
														<div class="tab-content" id="pills-tabContent">
														  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
															
																<div class="mntxtinvcall">
																	<p>Paste your video link <span class="redstar">*</span></p>
																	<input type="text" name="videolink" id="videolink" class="intphno required" value="" />
																</div>
														  </div>
														  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
																<div class="mntxtinvcall">
																	<p>Interviewer's phone number <span class="redstar">*</span></p>
																	<input type="text" name="phoneno" id="phoneno" class="intphno" value="<?php echo $fetch['phone'];?>" />
																</div>
														  </div>
														  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
																<div class="mntxtinvcall">
																	<p>Physical address <span class="redstar">*</span></p>
																	<input type="text" name="address" id="address" class="intphno" value="<?php echo stripslashes($fetch['address']);?>" />
																</div>
														  </div>
														</div>

													</div>
													
													<div class="messoficial">
														<label>Message to <?php echo stripslashes($fetchStaff['name'])." ".stripslashes($fetchStaff['lname']);?></label>
														<span class="sublbl">Include information to have ready, agenda, dress code, and any additional information.</span>
														<textarea class="mescand required" name="mescand"></textarea>
														<div class="row">
															<div class="col-sm-2"><input type="submit" name="" class="sndinvi" value="Send invitation" /></div>
															<div class="col-sm-2" id="invitation_success"></div>
														</div>
													</div>
													
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
												echo "This staff is not hired.";
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
                                    //if($fetch['isclosed'] == 0){
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
										echo "This staff is not hired.";
									}
									/*}
									else{
									echo "The job is paused.";
									}*/
                                    ?>
                                    </td>
                                    </tr>
                                    </table>
								</div>
							  </div>
							
							<?php
							  if($fetch['covertype'] == 1){
							  ?>
							<div class="tab-pane fade" id="pills-rate" role="tabpanel" aria-labelledby="pills-rate-tab">
								<div class="table-responsive successtable">
									<?php
									if($getstat['hired'] == 1){
										$checkworkstat = dbQuery($dbConn, "SELECT id,clockouttime from staff_job_clock where job_id = '".$id."' and staff_id = '".$userid."' order by id desc limit 0,1");
										if(dbNumRows($checkworkstat) > 0){
										$rowWork = dbFetchArray($checkworkstat);
										$lastworked_day = date('Y-m-d', strtotime($rowWork['clockouttime']));
										$date1 = new DateTime($today);
										$date2 = new DateTime($lastworked_day);
										$diff = $date1->diff($date2)->format("%a");
										
										if($diff > 30){
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
                                    <td>Did the employee attend the job?</td>
									<td><input type="radio" name="did_attend" required value="1">&nbsp;Yes
									<input type="radio" name="did_attend" value="2">&nbsp;No</td>
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
											<td colspan="2">Work is ongoing.</td>
											</tr>
											</table>
											<?php
										}
										}
										else{
											?>
											<table class="table" cellpadding="5" cellspacing="5">
											<tr class="table-light">
											<td colspan="2">Staff has not started work yet.</td>
											</tr>
											</table>
											<?php
										}
									}
									else{
										?>
										<table class="table" cellpadding="5" cellspacing="5">
										<tr class="table-light">
										<td colspan="2">This staff is not hired.</td>
										</tr>
										</table>
										<?php
									}
									?>
								</div>
							</div>
							<?php
							  }
							  ?>
							  
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