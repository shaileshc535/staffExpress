<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."staff_login'</script>";
	exit;
}

$quals = array();
$myquals = '';
$addcomp = '';
$benefit = '';
$currtime = date('Y-m-d H:i:s');
$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):"";
$myjob = dbQuery($dbConn, "SELECT a.*,b.phone,b.business_name,c.category,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join category c on a.catid=c.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$id."'");
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

	$checkdetls = dbQuery($dbConn, "SELECT name,lname,email from users where id = '".$_SESSION['loginUserId']."'");
	$getdetls = dbFetchArray($checkdetls);
?>

	<div class="works works_my_job_view">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="works_heading">
                        <h4><?php echo stripslashes($fetch['title']);?></h4>
						<div class="table-responsive jobdetlstabl">
							
                        <div class="sucespagtab my_jobview_success">
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							<?php
                              //if($getstat['hired'] == 1){
                              ?>
							  
							  <li class="nav-item" role="presentation">
								<button class="nav-link active" id="pills-detls-tab" data-bs-toggle="pill" data-bs-target="#pills-detls" type="button" role="tab" aria-controls="pills-detls" aria-selected="true">Job Details</button>
							  </li>
							  
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Contract</button>
							  </li>
							  <?php
							  //}
							  ?>
							  <!--<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Hours</button>
							  </li>
                              <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-escrow-tab" data-bs-toggle="pill" data-bs-target="#pills-escrow" type="button" role="tab" aria-controls="pills-escrow" aria-selected="false">Escrow</button>
							  </li>-->
							  <?php
                              //if($getstat['hired'] == 1){
                              ?>
                              <!--<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-dispute-tab" data-bs-toggle="pill" data-bs-target="#pills-dispute" type="button" role="tab" aria-controls="pills-dispute" aria-selected="false">Disputes</button>
							  </li>-->
							  <?php
							  if(isset($_SESSION['loginUserId'])){
								$getclock = dbQuery($dbConn, "SELECT clockouttime from staff_job_payment where job_id = '".$id."' and staff_id = '".$_SESSION['loginUserId']."'");
								$clock = dbFetchArray($getclock);
							  }
							  ?>
                              <!--<li class="nav-item" role="presentation">
								<button class="nav-link <?php if(isset($clock['clockouttime']) && $clock['clockouttime']!='0000-00-00 00:00:00') echo 'disabled';?>" id="pills-help-tab" data-bs-toggle="pill" data-bs-target="#pills-help" type="button" role="tab" aria-controls="pills-help" aria-selected="false">Messages</button>
							  </li>-->
                              <?php
							  //}
                              //if($getstat['hired'] == 1 && ($jobendttime > strtotime($currtime))){
                              ?>
                              <!--<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-clock-tab" data-bs-toggle="pill" data-bs-target="#pills-clock" type="button" role="tab" aria-controls="pills-clock" aria-selected="false">Clock</button>
							  </li>-->
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-msgstaff-tab" data-bs-toggle="pill" data-bs-target="#pills-msgstaff" type="button" role="tab" aria-controls="pills-msgstaff" aria-selected="false">Help</button>
							  </li>
                              <?php
                              //}
                              ?>
							</ul>
							<div class="tab-content" id="pills-tabContent">
							
								<div class="tab-pane fade show active" id="pills-detls" role="tabpanel" aria-labelledby="pills-detls-tab">
								<div class="table-responsive successtable">
								
									<div class="alljbdts">
										<p>
											<span class="pertiwidth"><strong>Description:</strong></span>
											<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['description']);?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Employer:</strong></span>
											<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['business_name']);?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Working Address:</strong></span>
											<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['street_address']);?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Postcode:</strong></span>
											<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['location']);?>, <?php echo stripslashes($fetch['cname']);?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Job Date:</strong></span>
											<span class="viewthejobrighttxt"><?php echo date('M j, Y', strtotime($fetch['jobdate']));?> at <?php echo date('h:i A', strtotime($fetch['starttime']));?> - <?php echo date('M j, Y', strtotime($fetch['jobdate2']));?> at <?php echo date('h:i A', strtotime($fetch['endtime']));?></span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Category:</strong></span>
											<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['category']);?></span>
										</p>
										<?php
										if($fetch['qualification'] != ""){
										?>
										<p>
											<span class="pertiwidth"><strong>Qualification Required:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetch['qualification'];?></span>
										</p>
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
										$paytype = "Commission";
										else if($fetch['paytype'] == 4)
										$paytype = "Annual and Commission";
										?>
										<p>
											<span class="pertiwidth"><strong>Job Type:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $wtype;?></span>
										</p>
										<?php
										if($fetch['paytype'] == 2){
										?>
										<p>
											<span class="pertiwidth"><strong><?php echo $paytype;?>:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetch['payperhr'];?> AUD</span>
										</p>
										<?php
										}
										if($fetch['paytype'] == 1){
											?>
										<p>
											<span class="pertiwidth"><strong><?php echo $paytype;?>:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetch['annual'];?> AUD/Year</span>
										</p>
										<?php
											}
											if($fetch['paytype'] == 3){
												?>
										<p>
											<span class="pertiwidth"><strong><?php echo $paytype;?>:</strong></span>
											<span class="viewthejobrighttxt">Commission: <?php echo $fetch['commission_perctg'];?>%</span>
										</p>
										<?php
												}
												if($fetch['paytype'] == 4){
													?>
										<p>
											<span class="pertiwidth"><strong><?php echo $paytype;?>:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetch['annualamt'];?> AUD/Year & Commission <?php echo $fetch['commission_perctg_amt'];?>%</span>
										</p>
										<?php
													}
										if($addcomp != ""){
										?>
										<p>
											<span class="pertiwidth"><strong>Additional Compensation:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $addcomp;?></span>
										</p>
										<?php
										}
											if($fetch['covid19']){
											?>
										<p>
											<span class="pertiwidth"><strong>Any COVID-19 Precaution:</strong></span>
											<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['covid19']);?></span>
										</p>
										<?php
											}
											?>

										<?php
										if($fetch['exp_type'] == 1)
										$exptype = "Compulsory";
										else if($fetch['exp_type'] == 2)
										$exptype = "Preferred";
										?>
										<p>
											<span class="pertiwidth"><strong>Experience Required:</strong></span>
											<span class="viewthejobrighttxt"><?php echo $fetch['experience'];?> years (<?php echo $exptype;?>)</span>
										</p>
										<p>
											<span class="pertiwidth"><strong>Status:</strong></span>
											<span class="viewthejobrighttxt"><?php 
												if($fetch['isclosed'] == 0){
													if($jobendttime > strtotime($currtime))
													echo "<span class='opengrn'>Open</span>";
													else
													echo "<span class='closered'>Closed</span>";
												}
												else
												echo "<span class='closered'>Closed</span>";
												?></span>
										</p>
										<p>
											<span class="pertiwidth"><?php
										$contact = dbQuery($dbConn, "SELECT contacting,hired from job_status where jobid = '".$id."' and application_sent_to = '".$_SESSION['loginUserId']."'");
										$getstat = dbFetchArray($contact);
										if($getstat['hired'] == 1){
											$acceptstyle = "style='display:none;'";
											$hiredmsgstyle = "";
											$msgstyle = "style='display:none;'";
										}
										else{
											if($getstat['contacting'] == 1){
												$acceptstyle = "style='display:none;'";
												$msgstyle = "";
												$hiredmsgstyle = "style='display:none;'";
											}
											else{
												$acceptstyle = "";
												$msgstyle = "style='display:none;'";
												$hiredmsgstyle = "style='display:none;'";
											}
										}
										?>
										
										<span id="success" <?php echo $msgstyle;?>>Notification is sent to employer. This is not confirmation of 
										the role. The employer would also need to confirm on their end.</span>

										<span <?php echo $hiredmsgstyle;?>>You are hired for this job.</span></span>
											
										</p>
									</div>
								
								</div>
							  </div>
							
							
							
							  <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
								<div class="table-responsive successtable">
									<table class="table" cellpadding="5" cellspacing="5">
										<?php
                                        if($fetch['custom_contract']){
                                            ?>
                                            <tr>
                                            <td>Contract</td>
                                            <td><a href="<?php echo SITEURL."contract/".$fetch['custom_contract'];?>" target="_blank">View contract</a></td>
                                            </tr>
                                            <?php
                                        }
                                        else{
                                            if($fetch['righttowork']){
												if($fetch['otherrightto'])
												$rightto = stripslashes($fetch['otherrightto']);
												else
												$rightto = "Must have the right to work in Australia";
												?>
												<tr class="table-light">
												<td width="20%">Right to Work</td>
												<td width="80%"><?php echo $rightto;?></td>
												</tr>
											<?php
											}
											if($fetch['work_with_child']){
												if($fetch['otherchild'])
												$workchild = stripslashes($fetch['otherchild']);
												else if($fetch['work_with_child'] == 1)
												$workchild = "Must have completed working with Children";
												else if($fetch['work_with_child'] == 3)
												$workchild = "Does not need to have working with children";
												?>
												<tr class="table-light">
												<td>Working With Children</td>
												<td><?php echo $workchild;?></td>
												</tr>
											<?php
											}
											if($fetch['work_timeframe']){
												if($fetch['othertmfr'])
											$timefr = stripslashes($fetch['othertmfr']);
											else
											$timefr = "Work is solely casual and for this period only. There is no ongoing work.";
												?>
												<tr class="table-light">
												<td>Work Time Frame</td>
												<td><?php echo $timefr;?></td>
												</tr>
											<?php
											}
											if($fetch['uniform']){
												if($fetch['otherunifm'])
												$uniform = stripslashes($fetch['otherunifm']);
												else
												$uniform = $fetch['uniform'];
                                            ?>
                                            <tr class="table-light">
                                            <td>Uniform</td>
                                            <td><?php echo $uniform;?></td>
                                            </tr>
											<?php
											}
											if($fetch['lunchbrk']){
												if($fetch['otherlunch'])
												$otherlunch = stripslashes($fetch['otherlunch']);
												else
												$otherlunch = '';
											$mylunch = "There will be a ".$fetch['lunchbrk']." mins lunch break";
											?>
                                            <tr>
                                            <td>Lunch Break</td>
                                            <td><?php echo $mylunch.", ".$otherlunch;?></td>
                                            </tr>
                                            <?php
											}
                                            if($fetch['otherinfo']){
                                            ?>
                                            <tr class="table-light">
                                            <td>Other Information</td>
                                            <td><?php echo $fetch['otherinfo'];?></td>
                                            </tr>
                                            <?php
                                            }
                                        }
                                        ?>
									</table>
								</div>							  
							  </div>
							  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
								<div class="table-responsive successtable">
									<table class="table" cellpadding="5" cellspacing="5">
                                    <tr class="table-light">
									<?php
									if($getstat['hired'] == 1){
									?>
                                            <td width="20%">Hours</td>
                                            <td width="80%"><?php 
                                            $duration = ceil(($jobendttime - $jobstrttime)/3600);
                                            echo $duration;
                                            ?></td>
									<?php
									}
									else{
										?>
										<td colspan="2">You are not hired.</td>
										<?php
									}
									?>
                                    </tr>
									</table>
								</div>
							  </div>
                              <div class="tab-pane fade" id="pills-escrow" role="tabpanel" aria-labelledby="pills-escrow-tab">
								<div class="table-responsive successtable">
									<table class="table" cellpadding="5" cellspacing="5">
                                        <tr class="table-light">
                                            <td>Amount</td>
                                            <td><?php 
                                            if($fetch['escrow_amt'] > 0)
                                            echo $fetch['escrow_amt'];
                                            else
                                            echo "0";
                                            ?> AUD
											<?php
											$getextra = dbQuery($dbConn, "SELECT escrow_amt,extra_amt_paidon from job_extra_escrow where jobid = '".$id."' and userid = '".$_SESSION['loginUserId']."'");
											$rowextra = dbFetchArray($getextra);
											if($rowextra['escrow_amt'] != 0){
												?>
												<br><br>
												Extra amount <?php echo $rowextra['escrow_amt'];?> AUD deposited on <?php echo date("M j, Y h:i a", strtotime($rowextra['extra_amt_paidon']));?>.
												<?php
											}
											?>
											</td>
                                        </tr>
									</table>
								</div>
							  </div>
                              <div class="tab-pane fade" id="pills-dispute" role="tabpanel" aria-labelledby="pills-dispute-tab">
								<div class="table-responsive successtable">
								<?php
								if($getstat['hired'] == 1){
								?>
								<form action="" method="post" id="dispute">
									<table class="table" cellpadding="5" cellspacing="5">
									<tr class="table-light">
										<td colspan="2">Please fill the form to raise dispute</td>
									</tr>
									<tr>
										<td width="20%">Select Reason</td>
										<td width="80%"><select name="reason" id="reason" class="form-control required">
												<option value="5">Work location not as described</option>
												<option value="6">Insufficient training/support on site</option>
												<option value="7">Tasks not as described</option>
											</select>
											<br>
											<input type="text" placeholder="Other reason" name="other_reason" id="other_reason" style="display:none;">
											<ul class="homebtn" style="margin:20px 0;">
                                            <li><a href="javascript:void(0);" id="sub_dispute" style="padding:0.4rem 1.5rem;">Submit</a></li>
                                            </ul>
											<br/>
											<span id="disputesucces" style="display:none;">Your dispute is submitted to us. We will get back shortly.</span>
										</td>
									</tr>
                                    </table>
									</form>
									<?php
									}
									else{
										echo "You are not hired.";
									}
									?>
								</div>
							  </div>
                              <div class="tab-pane fade" id="pills-help" role="tabpanel" aria-labelledby="pills-help-tab">
								<div class="table-responsive successtable">
								<table class="table" cellpadding="5" cellspacing="5">
										<tr>
											<th>Sender</th>
											<th>Message</th>
                                            <th>Date</th>
											<th style="text-align:center;">View</th>
										</tr>
										<?php
										/*echo "SELECT a.jobid,b.title,c.id,c.name from job_status a inner join job_details b on a.jobid=b.id inner join users c on b.employer_id=c.id inner join messages d on d.senderid=c.id where a.application_sent_to = '".$_SESSION['loginUserId']."' and a.contacting=1 group by d.senderid order by a.id desc";*/
										$getcandts = dbQuery($dbConn, "SELECT a.jobid,a.senderid,b.title,c.id,c.name from messages a inner join job_details b on a.jobid=b.id inner join users c on b.employer_id=c.id where (a.senderid = '".$_SESSION['loginUserId']."' OR a.receiverid = '".$_SESSION['loginUserId']."') and a.jobid = '".$id."' group by a.jobid order by a.id desc");
										if(dbNumRows($getcandts) > 0){
											while($rowRenter = dbFetchArray($getcandts)){
											$getMsg = dbQuery($dbConn, "SELECT senderid,receiverid,msg,msgdate from messages where (senderid = '".$_SESSION['loginUserId']."' OR receiverid = '".$_SESSION['loginUserId']."') AND (senderid = '".$rowRenter['senderid']."' OR receiverid = '".$rowRenter['senderid']."') order by msgdate desc");
											$rowMsg = dbFetchArray($getMsg);

												if($rowMsg['senderid'] == $_SESSION['loginUserId'])
												$lastsender = "You";
												else if($rowMsg['senderid'] == $rowRenter['id']){
													$lastsender = $rowRenter['name'];
												}
											?>
											<tr>
											<td><span class="frmfstname"><?php echo stripslashes($lastsender);?></span></td>
											<td><?php echo stripslashes($rowMsg['msg']);?></td>
											<td><?php echo date('M j, Y h:i a', strtotime($rowMsg['msgdate']));?></td>
											<td style="text-align:center;"><a href="<?php echo SITEURL;?>viestaffwmsg/<?php echo $rowRenter['id'];?>/<?php echo base64_encode($rowRenter['jobid']);?>"><span class="openstatus">View</span></a></td>
											</tr>
											<?php
										}
										}
										else{
											?>
											<tr>
											<td colspan="5" style="text-align:center;">
											<ul class="homebtn" style="margin:20px 0;">
											<li>
											<a href="javascript:void(0);" id="msg_employr" style="padding:0.4rem 1.5rem;">Message Employer</a></td>
											</li>
											</ul>
											</tr>
											<?php
										}
										?>
									</table>
												<form action="<?php echo SITEURL;?>message" method="post" id="message">
													<input type="hidden" id="jobid" name="jobid" value="<?php echo $id;?>">
													<input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['loginUserId'];?>">
													<input type="hidden" name="msgtoemp" value="1">
												</form>
								</div>
							  </div>
                              <div class="tab-pane fade" id="pills-clock" role="tabpanel" aria-labelledby="pills-clock-tab">
								<div class="table-responsive successtable">
									<table class="table" cellpadding="5" cellspacing="5">
                                    <tr class="table-light">
                                    <td>
                                    <?php
                                    $clockoutstyle = '';
                                    
                                    if($getstat['hired'] == 1){
										if(strtotime($currtime) >= $jobstrttime){
                                        
                                        $getclock = dbQuery($dbConn, "SELECT clockintime,clockincode,clockouttime from staff_job_payment where staff_id = '".$_SESSION['loginUserId']."' and job_id = '".$id."'");
										//if(dbNumRows($getclock) > 0){
                                        $clock = dbFetchArray($getclock);
										//&& $clock['clockincode'] == '' && $clock['clockouttime']=='0000-00-00 00:00:00'
                                        if(dbNumRows($getclock) == 0){
                                            ?>
                                            <ul class="homebtn" id="clckin" style="margin:20px 0;">
                                                <li><a href="javascript:void(0);" id="clockin" style="padding:0.4rem 1.5rem;">Start Work</a></li>
                                            </ul>
                                            <?php
                                        }
                                        if($clock['clockincode'] && $clock['clockintime']=='0000-00-00 00:00:00' && $clock['clockouttime']=='0000-00-00 00:00:00'){
                                            $clockoutstyle = '';
                                        }
                                        else
                                        $clockoutstyle = 'display:none;';
                                            ?>
                                           <span id="clckot" style="<?php echo $clockoutstyle;?>">Clock in code has been sent to employer. Clock will start after employer submits the code.</span>
										   <?php
										   if($clock['clockintime']!='0000-00-00 00:00:00' && $clock['clockouttime']=='0000-00-00 00:00:00'){
												?>
												<span>Your work has been started.</span>
												<?php
										   }
										   if(($jobendttime - strtotime($currtime)) <= 3600 && ($jobendttime - strtotime($currtime)) > 0){
											$check = dbQuery($dbConn, "SELECT extra_hours,extra_hours_approved from staff_job_payment where job_id = '".$id."' and staff_id = '".$_SESSION['loginUserId']."'");
											$morehr = dbFetchArray($check);
											if($morehr['extra_hours'] != 0){
												$hourstyle = 'style="display:none;"';
												$hourmsgstyle = '';
												if($morehr['extra_hours_approved'] == 1){
													$hourstyle = 'style="display:none;"';
													$hourmsgstyle = 'style="display:none;"';
													$approvedmsgstyle = "";
													$msgtext = "Your request for ".$morehr['extra_hours']." more hours is accepted.";
												}
												else if($morehr['extra_hours_approved'] == 2){
													$hourstyle = 'style="display:none;"';
													$hourmsgstyle = 'style="display:none;"';
													$approvedmsgstyle = '';
													$msgtext = "Your request for ".$morehr['extra_hours']." more hours is rejected.";
												}
												else{
													$hourstyle = 'style="display:none;"';
													$hourmsgstyle = '';
													$approvedmsgstyle = 'style="display:none;';
													$msgtext = "";
												}
											}
											else{
												$hourstyle = '';
												$hourmsgstyle = 'style="display:none;"';
												$approvedmsgstyle = 'style="display:none;"';
												$msgtext = "";
											}
                                            ?>
                                                <br>
                                                <div id="reqmorehr" <?php echo $hourstyle;?>>
												<span>Your work will end in 1 hour at <?php echo date('h:i a', strtotime($endtime));?>.</span>
                                                <span>If your employer has asked you to stay longer, please advise them to approve.</span>

                                                <label style="margin-top:10px;">Enter how many hours you need to work more</label>
												<input type="text" class="form-control" id="morehours">
												<ul class="homebtn" style="margin:20px 0;">
												<li>
												<a href="javascript:void(0);" id="submorehour">Submit</a>
												</li>
												</ul>
                                                </div>
												<span id="extrahour" <?php echo $hourmsgstyle;?>>You have requested for <?php echo $morehr['extra_hours'];?> extra hour(s). It is subject to approval of the employer.</span>
												<span id="extrahourstatus" <?php echo $approvedmsgstyle;?>><?php echo $msgtext;?></span>
                                                <?php
                                            }
                                            if($clock['clockincode'] && $clock['clockouttime'] != '0000-00-00 00:00:00'){
                                                $endstyle = '';
                                            }
                                            else
                                            $endstyle = 'display:none;';
                                            ?>
                                            <span id="workend" style="<?php echo $endstyle;?>">You are clocked out by employer.</span>
                                            <?php
											
											}
											else{
												echo "Work time is not started yet.";
											}
                                    }
									else{
										echo "You are not hired.";
									}
                                    ?>
                                    </td>
                                    </tr>
                                    </table>
								</div>
							  </div>
							  <div class="tab-pane fade" id="pills-msgstaff" role="tabpanel" aria-labelledby="pills-msgstaff-tab">
								<div class="table-responsive successtable">
								<form action="" method="post" id="contactus">
									<table class="table" cellpadding="5" cellspacing="5">
									<tr class="table-light">
										<td colspan="2">Call us: <a href="tel:0893374756">(08) 9337 4756</a></td>
									</tr>
									<tr>
										<td colspan="2">Please fill the fields to contact us</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="text" placeholder="Name" name="name" id="name" class="form-control required" value="<?php echo $getdetls['name']." ".$getdetls['lname'];?>">
											<br>
											<input type="email" placeholder="Email" name="email" id="email" class="form-control required" value="<?php echo $getdetls['email'];?>">
											<br>
											<textarea placeholder="Message" name="msg" id="msg" class="form-control required"></textarea>
											<br>
											<input type="submit" name="sub" id="sub_help" value="Submit">
											<br/>
											<span id="helpsucces" style="display:none;">Your message is sent to us. We will get back shortly.</span>
										</td>
									</tr>
                                    </table>
									</form>
								</div>
							  </div>

							</div>
						</div>
                        
							<ul class="homebtn" style="margin:20px 0;">
							<li><a href="<?php echo SITEURL;?>myappliedjobs" style="padding:0.4rem 1.5rem;">Back</a></li>
							</ul>
						</div>
						
						
					</div>

				</div>
				<!--<div class="col-lg-4">
					<div class="works_my_job_view_img">
						<img src="../images/wk.png" class="img-fluid" alt="">
					</div>
				</div>-->
			</div>
		</div>
	</div>
	
	<?php include_once('footer.php');?>