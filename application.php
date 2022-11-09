<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
$job = isset($_REQUEST['job'])?base64_decode($_REQUEST['job']):"";
$user = isset($_REQUEST['user'])?base64_decode($_REQUEST['user']):"";
$sent = array();
    $check = dbQuery($dbConn, "SELECT application_sent_to from job_status where jobid = '".$job."'");
    while($row = dbFetchArray($check)){
        $sent[] = $row['application_sent_to'];
    }
//if(in_array($user, $sent) && $_REQUEST['do'] == "viewdetails"){
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login?job=".$job."&user=".$user."'</script>";
	exit;
}
else{
	$usertype = getUserType($dbConn, $_SESSION['loginUserId']);
	if($usertype != 1){
		echo "<script>location.href='".SITEURL."employer_login?job=".$job."&user=".$user."'</script>";
		exit;
	}
}

if(count($_POST) > 0){
	$today = date('Y-m-d');
    $jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?tep_db_input($_POST['userid']):"";

    $mystaff = dbQuery($dbConn, "SELECT a.id,a.name,a.email,a.phone,b.notified from users a inner join staff_details b on a.id=b.staff_id where a.id = '".$userid."'");
    $res = dbFetchArray($mystaff);

    $myjob = dbQuery($dbConn, "SELECT a.title,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.howmnypeople,b.name,b.business_name FROM job_details a inner join users b on a.employer_id=b.id where a.id='".$jobid."'");
    $fetch = dbFetchArray($myjob);

    dbQuery($dbConn, "UPDATE job_status set hired=1, hiredon = '".$today."' where jobid = '".$jobid."' and application_sent_to = '".$userid."'");

	if($fetch['howmnypeople'] == 1){
		dbQuery($dbConn, "UPDATE job_details set isclosed=1 where jobid = '".$jobid."'");
	}
	else{
		$checkhired = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$jobid."' and hired=1");
		if(dbNumRows($checkhired) == $fetch['howmnypeople']){
			dbQuery($dbConn, "UPDATE job_details set isclosed=1 where jobid = '".$jobid."'");
		}
	}

    // send mail to candidate
	if($res['notified'] == "Email" || $res['notified'] == "Both"){
    $to = $res['email'];
    $subject = "You are Hired";

    $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid;'>
                        <tr>
                            <td style='padding:5px; font-size:18px; color:green;text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
                        </tr>
                        <tr>
                        <td colspan='2'>&nbsp;</td>
                        </tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Hello ".$res['name'].",</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>You are hired to perform this job: ".stripslashes($fetch['title']).".</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Your employer is: ".stripslashes($fetch['name']).".</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
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

    echo "<form action='".SITEURL."confirmation' method='post' id='gotoconfirm'><input type='hidden' name=jobid value='".$jobid."'><input type='hidden' name='userid' value='".$userid."'></form><script>document.getElementById('gotoconfirm').submit();</script>";
}

$quals = array();
$myquals = '';
$addcomp = '';
$benefit = '';
$staff_quals = array();
$allstaff_quals = '';
$myjob = dbQuery($dbConn, "SELECT a.*,b.phone,b.business_name,c.category,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join category c on a.catid=c.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$job."'");
    $fetch = dbFetchArray($myjob);

    $requrdqual = dbQuery($dbConn, "SELECT qualifictn from qualifications a inner join qualifictn_required b on a.id=b.qualifications where b.jobid = '".$job."'");
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

    $getstaff = dbQuery($dbConn, "SELECT a.*,b.name,b.email,b.phone,c.category from staff_details a inner join users b on a.staff_id=b.id inner join category c on a.catid=c.id where staff_id = '".$user."'");
    $fetchStaff = dbFetchArray($getstaff);
    if($fetchStaff['working_days'] == '1')
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
    $working_day = "Fridays";

    $myqual = dbQuery($dbConn, "SELECT qualifictn from qualifications a inner join staff_qualification b on a.id=b.qualification where b.staff_id = '".$user."'");
    while($qualfetch = dbFetchArray($myqual)){
        $staff_quals[] = $qualfetch['qualifictn'];
    }
    if(count($staff_quals) > 0){
        $allstaff_quals = implode(", ", $staff_quals);
    }
?>

	<div class="works application_sty">
		<div class="container">
			<div class="row">
			<?php include_once "employer_left.php";?>
				<div class="col-lg-9">
					
					<div class="works_heading">
						<h6>View Application Details</h6>
						<ul class="homebtn" style="margin:20px 0;text-align:right;">
							<li><a href="<?php echo SITEURL;?>employer_details" style="padding:0.4rem 1.5rem;">Post a job</a></li>
						</ul>
						
						<div class="row">
							<div class="col-sm-4">
								<h5><?php echo stripslashes($fetch['title']);?></h5>
								<div class="jobimageleft"><img src="<?php echo SITEURL;?>images/nursejob.png" alt="" class="img-responsive" /></div>
							</div>
							<div class="col-sm-8">
								<div class="table-responsive jobdetlstabl">
									<table class="table">
										<tbody>
											<tr>
												<td width="35%"><strong>Job Details:</strong></td>
												<td width="62%"><?php echo stripslashes($fetch['description']);?></td>
											</tr>
											<tr class="table-light">
												<td width="35%"><strong>Employer:</strong></td>
												<td width="62%"><?php echo stripslashes($fetch['business_name']);?></td>
											</tr>
											<tr class="">
												<td><strong>Address:</strong></td>
												<td><?php echo stripslashes($fetch['street_address']);?></td>
											</tr>
											<tr class="table-light">
												<td><strong>Postcode:</strong></td>
												<td><?php echo stripslashes($fetch['location']);?>, <?php echo stripslashes($fetch['cname']);?></td>
											</tr>
											<tr class="">
												<td><strong>Job Date:</strong></td>
												<td><?php echo date('M j, Y', strtotime($fetch['jobdate']));?> at <?php echo date('h:i A', strtotime($fetch['starttime']));?> - <?php echo date('M j, Y', strtotime($fetch['jobdate2']));?> at <?php echo date('h:i A', strtotime($fetch['endtime']));?></td>
											</tr>
											<tr class="table-light">
												<td><strong>Category:</strong></td>
												<td><?php echo stripslashes($fetch['category']);?></td>
											</tr>
											<?php
											if($myquals != ""){
											?>
											<tr class="">
												<td><strong>Qualification Required:</strong></td>
												<td><?php echo $myquals;?></td>
											</tr>
											<?php
											}
											if($fetch['worktype'] == 1)
											$wtype = "Casual";
											else if($fetch['worktype'] == 2)
											$wtype = "Contract";
											else if($fetch['worktype'] == 3)
											$wtype = "Part-time";
											else if($fetch['worktype'] == 4)
											$wtype = "Full-time";

											if($fetch['paytype'] == 1)
											$paytype = "Annual Salary";
											else if($fetch['paytype'] == 2)
											$paytype = "Hourly Rate";
											else if($fetch['paytype'] == 3)
											$paytype = "Annual and Commission";
											?>
											<tr class="table-light">
												<td><strong>Job Type:</strong></td>
												<td><?php echo $wtype;?></td>
											</tr>
											<tr>
												<td><strong><?php echo $paytype;?>:</strong></td>
												<td>Range: <?php echo $fetch['payperhr'];?> AUD - <?php echo $fetch['payperhr_max'];?> AUD</td>
											</tr>
											<?php
											if($addcomp != ""){
											?>
											<tr >
												<td><strong>Additional Compensation:</strong></td>
												<td><?php echo $addcomp;?></td>
											</tr>
											<?php
											}
											?>
											<!--<tr class="">
												<td><strong>Benefit(s):</strong></td>
												<td><?php echo $benefit;?></td>
											</tr>-->
											<?php
										if($fetch['covid19']){
										?>
									<tr class="table-light">
										<td><strong>Any COVID-19 Precaution:</strong></td>
										<td><?php echo stripslashes($fetch['covid19']);?></td>
									</tr>
									<?php
										}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="works_heading innerhding" style="padding-top:30px;">
						
							<div class="row">
								<div class="col-sm-4">
									<h4>Candidate Details</h4>									
									<div class="jobimageleft"><img src="<?php echo SITEURL;?>images/canddtes.png" alt="" class="img-responsive" /></div>
								</div>
								<div class="col-sm-8">
									<div class="table-responsive jobdetlstabl">
										<table class="table">
											<tbody>
												<tr>
													<td width="35%"><strong>Name:</strong></td>
													<td width="62%"><?php echo stripslashes($fetchStaff['name']);?></td>
												</tr>
												<!--<tr class="table-light">
													<td><strong>Contact details:</strong></td>
													<td>Email: <?php //echo stripslashes($fetchStaff['email']);?><br />Phone: <?php //echo stripslashes($fetchStaff['phone']);?></td>
												</tr>
												<tr>
													<td><strong>Address:</strong></td>
													<td><?php //echo stripslashes($fetchStaff['address']);?></td>
												</tr>
												<tr class="table-light">
													<td><strong>Post code:</strong></td>
													<td><?php //echo stripslashes($fetchStaff['postcode']);?></td>
												</tr>-->
												<tr>
													<td><strong>Date of Birth:</strong></td>
													<td><?php echo date('M j, Y', strtotime($fetchStaff['dob']));?></td>
												</tr>
												<tr class="table-light">
													<td><strong>Category:</strong></td>
													<td><?php echo stripslashes($fetchStaff['category']);?></td>
												</tr>
												<?php
												if($allstaff_quals != ""){
												?>
												<tr>
													<td><strong>Qualification(s):</strong></td>
													<td><?php echo $allstaff_quals;?></td>
												</tr>
												<?php
												}
												?>
												<tr class="table-light">
													<td><strong>Working Days:</strong></td>
													<td><?php echo $working_day;?></td>
												</tr>
												<!--<tr>
													<td><strong>Time per day:</strong></td>
													<td><?php echo $fetchStaff['timesavl'];?> Hrs.</td>
												</tr>-->
												<tr>
													<td colspan="2"><strong>Medical Conditions</strong></td>
												</tr>
												<?php
												if($fetchStaff['nomedical'] == 1){
												?>
												<tr>
													<td colspan="2"><strong>No medical issues</strong></td>
												</tr>
												<?php
												}
												else{
													?>
													<tr class="table-light">
													<td><strong>Heart Conditions:</strong></td>
													<td><?php echo ($fetchStaff['heart']==1)?"Heart issues":"No Heart issues";?></td>
													</tr>
													<tr>
													<td><strong>Diabetes:</strong></td>
													<td><?php echo ($fetchStaff['diabetes']==1)?"Yes":"No";?></td>
													</tr>
													<tr class="table-light">
													<td><strong>High Blood Pressure:</strong></td>
													<td><?php echo ($fetchStaff['bloodpr']==1)?"Yes":"No";?></td>
													</tr>
													<?php
													if($getdetls['allergy']){
													?>
													<tr>
													<td><strong>Allergy:</strong></td>
													<td><?php echo $fetchStaff['allergy'];?></td>
													</tr>
													<?php
													}
													if($getdetls['infectious']){
													?>
													<tr class="table-light">
													<td><strong>Infectious Disease:</strong></td>
													<td><?php echo $fetchStaff['infectious'];?></td>
													</tr>
													<?php
													}
													if($getdetls['otherdis']){
														?>
													<tr>
													<td><strong>Other Issues:</strong></td>
													<td><?php echo $fetchStaff['otherdis'];?></td>
													</tr>
													<?php
													}
												}
												?>
												<tr>
													<td>&nbsp;</td>
													<td>
                                                    <?php
                                                    $contact = dbQuery($dbConn, "SELECT hired from job_status where jobid = '".$job."' and application_sent_to = '".$user."'");
                                                    $getstat = dbFetchArray($contact);
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
                                                    <h5>Hire this candidate?</h5>
                                                    <ul class="homebtn" style="margin:20px 0;">
                                                    <li><a href="javascript:void(0);" id="hire" style="padding:0.4rem 1.5rem;">Yes</a></li>
													<li><a href="javascript:void(0);" id="reject" style="padding:0.4rem 1.5rem;">No</a></li>
                                                    </ul>
                                                    </div>
                                                    <span id="success" <?php echo $msgstyle;?>>You have hired this candidate. Candidate is notified.</span>
													<span id="rejected" <?php echo $rejctmsgstyle;?>>You have rejected this candidate. Candidate is notified.</span>
                                                    </td>
												</tr>
												
											</tbody>
										</table>
									</div>
									
									<form action="" method="post" id="myhire">
									<input type="hidden" id="jobid" name="jobid" value="<?php echo $job;?>">
									<input type="hidden" id="userid" name="userid" value="<?php echo $user;?>">
									</form>
									
								</div>
							</div>
						
						
						</div>
						
					</div>

				</div>
				
			</div>
		</div>
	</div>
	
	<?php 
    include_once('footer.php');
//}
?>