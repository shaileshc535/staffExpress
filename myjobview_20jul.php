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
$today = date('Y-m-d');
$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):"";
$myjob = dbQuery($dbConn, "SELECT a.*,b.name,b.phone,b.business_name,b.company_img,c.category,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join category c on a.catid=c.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$id."'");
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

	<div class="works works_my_job_view  works_success">
		<div class="container">
			<div class="row">
				<?php include_once "staff_left.php";?>
				<div class="col-lg-9">
					<div class="works_heading">
                        <!--<h4><?php echo stripslashes($fetch['title']);?></h4>-->
						<div class="table-responsive jobdetlstabl">
							
                        <div class="sucespagtab my_jobview_success">
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							  
							  <li class="nav-item" role="presentation">
								<button class="nav-link active" id="pills-detls-tab" data-bs-toggle="pill" data-bs-target="#pills-detls" type="button" role="tab" aria-controls="pills-detls" aria-selected="true">Job Details</button>
							  </li>
							  
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Contract</button>
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
								<button class="nav-link" id="pills-msgstaff-tab" data-bs-toggle="pill" data-bs-target="#pills-msgstaff" type="button" role="tab" aria-controls="pills-msgstaff" aria-selected="false">Help</button>
							  </li>
                              <?php
                              //}
                              ?>
							</ul>
							<div class="tab-content" id="pills-tabContent">
							
								<div class="tab-pane fade show active" id="pills-detls" role="tabpanel" aria-labelledby="pills-detls-tab">
								<div class="successtable" style="padding-top:20px;">
								<?php
								if($fetch['worktype'] == 1)
								$wtype = "Casual";
								else if($fetch['worktype'] == 2)
								$wtype = "Contract";
								else if($fetch['worktype'] == 3)
								$wtype = "Part-time";
								else if($fetch['worktype'] == 4)
								$wtype = "Full-time";

								if($fetch['postdate'] == date('Y-m-d'))
								$posted = "Today";
								else
								$posted = "On ".date('M j, Y', strtotime($fetch['postdate']));
								?>
									<div class="viewmyjobtabonedetails">
									<?php
									if($fetch['company_img']){
										?>
										<div class="clientlogo"><img src="<?php echo SITEURL;?>uploads/<?php echo $fetch['company_img'];?>" alt=""></div>
										<?php
									}
									?>
										<h4><?php echo stripslashes($fetch['title']);?></h4>
										<div class="viewthejobmaindescribe">
											<div class="row">
												<div class="col-sm-9 col-lg-9 col-md-8">
													<ul>							
														<li><?php echo stripslashes($fetch['state']);?></li>
														<li><?php echo stripslashes($fetch['category']);?></li>
														<li><?php echo $wtype;?></li>
													</ul>							
													<ul>							
														<li>Posted <?php echo $posted;?></li>
														
													</ul>
													<ul>
														<li>Job Date: <?php echo date('M j, Y', strtotime($fetch['jobdate']));?> at <?php echo date('h:i A', strtotime($fetch['starttime']));?> - <?php echo date('M j, Y', strtotime($fetch['jobdate2']));?> at <?php echo date('h:i A', strtotime($fetch['endtime']));?></li>
													</ul>
													<ul>
														<li>
															<span>Status:</span> 
															<span><?php 
																if($fetch['isclosed'] == 0){
																	if(strtotime($currtime) <= $jobendttime)
																	echo "<span class='opengrn'>Open</span>";
																	else
																	echo "<span class='closered'>Closed</span>";
																}
																else
																echo "<span class='closered'>Closed</span>";
																?></span>
														</li>
													</ul>
												</div>
												<div class="col-sm-3 col-lg-3 col-md-4">
													<div class="jobrighthour">
													<?php
														if($fetch['paytype'] == 1)
														$paytype = "Annual Salary";
														else if($fetch['paytype'] == 2)
														$paytype = "Hourly Rate";
														else if($fetch['paytype'] == 3)
														$paytype = "Commission";
														else if($fetch['paytype'] == 4)
										 				$paytype = "Annual and Commission";
													?>
														<p><?php echo $paytype;?></p>
														<?php
														if($fetch['paytype'] == 1){
															?>
															<h5><?php echo $fetch['annual'];?> AUD/Year</h5>
															<?php
														}
														if($fetch['paytype'] == 2){
														?>
														<h5><?php echo $fetch['payperhr'];?> AUD</h5>
														<?php
														}
														if($fetch['paytype'] == 3){
														?>
														<h5>Commission: <?php echo $fetch['commission_perctg'];?>%</h5>
														<?php
														}
														if($fetch['paytype'] == 4){
															?>
														<h5><?php echo $fetch['annualamt'];?> AUD/Year <span class="onlyconnidiontext">& Commission <?php echo $fetch['commission_perctg_amt'];?>%</span></h5>
														<?php }
														?>
														
													</div>
												</div>
											</div>
											
											<div class="viwmorejobapli">
												<p>
														<span class="pertiwidth">
														<?php
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
													
													<span id="success" <?php echo $msgstyle;?>>Applied</span>

													<span <?php echo $hiredmsgstyle;?>>You are hired for this job.</span></span>
														
												</p>
												<style>#more {display: none;}</style>
												<div class="content viethejobbk">
													<div class="alljbdts">
													<span id="dots"></span>
													<div id="more">
														<p>
															<span class="pertiwidth"><strong>Description:</strong></span>
															<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['description']);?></span>
														</p>
														<p>
															<span class="pertiwidth"><strong>Employer:</strong></span>
															<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['name']);?></span>
														</p>
														<p>
															<span class="pertiwidth"><strong>Address:</strong></span>
															<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['street_address']);?></span>
														</p>
														<p>
															<span class="pertiwidth"><strong>Category:</strong></span>
															<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['category']);?></span>
														</p>
															<?php
																if($fetch['qualification']){
															?>
														<p>
															<span class="pertiwidth"><strong>Qualification Required:</strong></span>
															<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['qualification']);?></span>
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
														</div>
													</div>
													<button class="rdmrles" onclick="myFunction()" id="myBtn">View more <i class="fa fa-angle-down" aria-hidden="true"></i></button>
												</div>
											</div>
											<!--<div class="voewjobmeaasgeall">
												<h5>Message</h5>
												<div class="allmeg">
													<?php
													$fetchimg = dbQuery($dbConn, "SELECT image from users where id = '".$_SESSION['loginUserId']."'");
													$rowimg = dbFetchArray($fetchimg);
													$getcandts = dbQuery($dbConn, "SELECT a.jobid,a.senderid,a.id as msgid,a.msg,b.title,c.id,c.name from messages a inner join job_details b on a.jobid=b.id inner join users c on b.employer_id=c.id where (a.senderid = '".$_SESSION['loginUserId']."') and a.jobid = '".$id."' and a.parent_id=0 order by a.id desc");
													if(dbNumRows($getcandts) > 0){
														while($rowRenter = dbFetchArray($getcandts)){
														
			
															if($rowRenter['senderid'] == $_SESSION['loginUserId'])
															$lastsender = "You";
															else if($rowRenter['senderid'] == $rowRenter['id']){
																$lastsender = $rowRenter['name'];
															}


																$unread = dbQuery($dbConn, "SELECT id from messages where receiverid = '".$_SESSION['loginUserId']."' and jobid='".$id."' and isread=0 and parent_id != 0");
																$unread_count = dbNumRows($unread);

																$staff = dbQuery($dbConn, "SELECT suburb from staff_details where staff_id = '".$_SESSION['loginUserId']."'");
																$staffrow = dbFetchArray($staff);
					
																$getclock = dbQuery($dbConn, "SELECT clockouttime from 
																staff_job_payment where job_id = '".$id."' and staff_id = '".$_SESSION['loginUserId']."'");
																$clock = dbFetchArray($getclock);
													?>
													<div class="singlemeg">
														<div class="row">
															<div class="col-sm-1"><div class="clinima">
															<?php
															if($rowimg['image']){
																?>
																<img src="<?php echo SITEURL;?>uploads/<?php echo $rowimg['image'];?>" alt="">
																<?php
															}
															else{
																?>
																<img src="<?php echo SITEURL;?>images/sercovernoimg.png" alt="">
																<?php
															}
															?>
															</div></div>
															<div class="col-sm-7">
																<div class="clndls">
																	<h6><?php echo $lastsender;?></h6>
																	<p><i><?php echo stripslashes($staffrow['suburb']);?></i></p>
																</div>
															</div>
															<?php
															if($unread_count > 0){
															?>
															<div class="col-sm-4">
															<div style="color:#EB6363;" id="unread_<?php echo $rowRenter['id'];?>_<?php echo $rowRenter['jobid'];?>"><?php echo $unread_count;?> unread</div>
															</div>
															<?php
															}
															?>
														</div>
														<div class="mainmeg">
														<?php echo stripslashes($rowRenter['msg']);?>
														</div>
														<ul class="homebtn" style="margin:15px 0;">
														<li style="border-right:0px;padding-right:0;"><a id="view_<?php echo $rowRenter['id'];?>_<?php echo $rowRenter['jobid'];?>" class="msgview viewstaffmsgs" href="javascript:void(0);" style="padding:0.4rem 1.5rem;background-color:#3176B4;color:#fff;">View</a></li>
														<?php if(isset($clock['clockouttime']) && $clock['clockouttime']!='0000-00-00 00:00:00'){}
														else{
														?>
														<li><a id="rly_<?php echo $rowRenter['id'];?>_<?php echo $rowRenter['jobid'];?>_<?php echo $rowRenter['msgid'];?>" class="msgreply msgemp" href="javascript:void(0);" style="padding:0.4rem 1.5rem;">Message Employer</a></li>
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
														<div class="singlemeg"><div class="mainmeg">No messages so far.</div></div>
														<ul class="homebtn" style="margin:20px 0;">
														<li>
														<a href="javascript:void(0);" id="msg_job_emp" style="padding:0.4rem 1.5rem;">Message Employer</a></td>
														</li>
														</ul>
														<?php
													}
													
													?>
													
													<div class="replyboxmeg" style="display:none;">
														<div class="row">
															<p>Send a message to <span id="msgto"><?php echo $fetch['name'];?></span></p>
															<div class="col-sm-1">
															<div class="clinima">
															<?php
															if($rowimg['image']){
																?>
																<img src="<?php echo SITEURL;?>uploads/<?php echo $rowimg['image'];?>" alt="">
																<?php
															}
															else{
																?>
																<img src="<?php echo SITEURL;?>images/sercovernoimg.png" alt="">
																<?php
															}
															?>
															</div>
															</div>
															<div class="col-sm-11">
																<div class="rplfrm">
																	<form action="" method="post" id="replyform">
																	<input type="hidden" name="action" value="sendmsg">
																	<input type="hidden" id="usertype" name="usertype" value="<?php echo $usertype;?>">
																	<input type="hidden" id="jobid" name="jobid" value="<?php echo $id;?>">
																	<input type="hidden" id="msgid" name="msgid" value="">
																	<input type="hidden" id="staff_id" name="userid" value="<?php echo $_SESSION['loginUserId'];?>">
																	<input type="hidden" id="emp_id" name="employerid" value="<?php echo $fetch['employer_id'];?>">
																		<div class="row">
																			<div class="col-sm-12">
																				<textarea class="rplara required" id="reply" name="mymsg"></textarea>
																			</div>
																			
																			<div class="col-sm-12">
																				<input type="submit" name="sub" id="mysendmsg" value="Reply" style="float:right;">
																				<span id="msgsuccess" style="color:#69A268;font-weight:bold;"></span>
																			</div>
																		</div>
																		
																	</form>
																</div>
															</div>
														</div>
													</div>

												</div>
											</div>-->
										</div>
									</div>
							
								</div>
							  </div>
							
							<!-- Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:#3176B4;">Messages between <span id="msgstaff"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  		<div class="messgbk">
				<div id="existingmsg" class="onlyscrol"></div>
			</div>
      </div>
      
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
												$workchild = "Must have completed working with children check";
												else if($fetch['work_with_child'] == 3)
												$workchild = "Does not need to have working with children check";
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
                              <?php
							  $usertype = getUserType($dbConn, $_SESSION['loginUserId']);
							  ?>
                              <div class="tab-pane fade" id="pills-clock" role="tabpanel" aria-labelledby="pills-clock-tab">
								<div class="table-responsive successtable">
									<table class="table" cellpadding="5" cellspacing="5">
                                    <tr class="table-light">
                                    <td>
									<input type="hidden" id="usertype" name="usertype" value="<?php echo $usertype;?>">
									<input type="hidden" id="jobid" name="jobid" value="<?php echo $id;?>">
									<input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['loginUserId'];?>">
                                    <?php
                                    $clockoutstyle = '';
                                    
                                    if($getstat['hired'] == 1){
										$total_diff = 0;
										$clockouttimes = array();
										//if(strtotime($currtime) >= $jobstrttime){
											$gethours = dbQuery($dbConn, "SELECT clockincode,clockintime,clockouttime from staff_job_clock where staff_id = '".$_SESSION['loginUserId']."' and job_id = '".$id."' and jobstartdate = '".$today."'");
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
													echo "<span id='prevtext'> You have not clocked out today yet.</span><br>";
												}
												if($hours['clockouttime'] != '0000-00-00 00:00:00'){
													
												}
											}
											$gethours2 = dbQuery($dbConn, "SELECT clockintime,clockouttime from staff_job_clock where staff_id = '".$_SESSION['loginUserId']."' and job_id = '".$id."'");

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
											$getstffbreak = dbQuery($dbConn, "SELECT id,brkstarttime from staff_job_breaks where staff_id = '".$_SESSION['loginUserId']."' and job_id = '".$id."'");
											if(dbNumRows($getstffbreak) > 0){

												$getstffbreak2 = dbQuery($dbConn, "SELECT brkstarttime,brkendtime from staff_job_breaks where staff_id = '".$_SESSION['loginUserId']."' and job_id = '".$id."'");
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
											
											if($total_diff > 0){
											$diffwork = $total_diff - $total_diff_brk;
											$total_hours = gmdate("H:i:s", $diffwork);
											echo $total_hours."<br>";
											}
                                        $getclock = dbQuery($dbConn, "SELECT clockintime,clockincode,clockouttime from staff_job_clock where staff_id = '".$_SESSION['loginUserId']."' and job_id = '".$id."' and jobstartdate = '".$today."'");
										//if(dbNumRows($getclock) > 0){
                                        $clock = dbFetchArray($getclock);
										
										//&& $clock['clockincode'] == '' && $clock['clockouttime']=='0000-00-00 00:00:00'
                                        if(dbNumRows($getclock) == 0 && strtotime($currtime) <= $jobendttime){
											if($fetch['jobdate2'] >= $today){
                                            ?>
                                            <ul class="homebtn" id="clckin" style="margin:20px 0;">
                                                <li><a href="javascript:void(0);" id="clockin" style="padding:0.4rem 1.5rem;">Start Work</a></li>
                                            </ul>
											<div id="clockindiv" style="display:none;">
											<label>Enter code got in SMS for clock-in</label>
											<div class="mybrkinput myclockin">
                                            <input type="text" class="form-control clockincode">
											<a href="javascript:void(0);" class="clockcodesub">Submit</a>
                                            </div>
											</div>
											<span id="clockinsucces" style="display:none;">Clock has started.</span>
											
											<div id="breakdiv" style="display:none;">

											<div class="row">
												<div class="col-sm-6">
												<ul class="homebtn" style="margin:20px 0;">
												<li>
												<a href="javascript:void(0);" id="startbreak">Start Break</a>
												</li>
												</ul>
												
												<div id="breakstartdiv" style="display:none;">
												<br>
												<label>Enter code got in SMS to start break</label>
												<div class="mybrkinput">
												<input type="text" class="form-control brkstartcode">
												<a href="javascript:void(0);" id="breakcodesub">Submit</a>
												</div>
												</div>
												<br>
												<span id="breaksucces" style="display:none;">Break has started.</span>
												</div>
												<div class="col-sm-6">
												<ul class="homebtn" style="margin:20px 0;">
													<li>
													<a href="javascript:void(0);" id="endbreak">End Break</a>
													</li>
													</ul>
													
													<div id="breakenddiv" style="display:none;">
													<br>
													<label>Enter code got in SMS to end break</label>
													<div class="mybrkinput">
													<input type="text" class="form-control breakendcode">
													<a href="javascript:void(0);" id="breakendcodesub">Submit</a>
													</div>
													</div>
													
													<br>
													<span id="endbreaksuccess" style="display:none;">Break has ended.</span>
												</div>
											</div>

											</div>

											<!--<div id="breaksucces" style="display:none;">
											
											</div>-->
											
											
											<br>
											<ul class="homebtn" id="myclockout" style="margin:20px 0;display:none;">
												<li>
												<a href="javascript:void(0);" id="clockout" >Clock out for today</a>
												</li>
												</ul>
												<span id="clockoutsucces" style="display:none;">You have clocked out.</span>
                                            <?php
											}
											//else
											//echo "<span class='closered'>Job is Closed</span>";
                                        }
										//else
										//echo "<span class='closered'>Job is Closed</span>";
										$getclock = dbQuery($dbConn, "SELECT clockintime,clockincode,clockouttime from staff_job_clock where staff_id = '".$_SESSION['loginUserId']."' and job_id = '".$id."' and jobstartdate = '".$today."'");
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
											<span id="clockinsucces" <?php echo $clockinmsgstyle;?>>Clock has started.</span>
											<?php
										}
										if($clock['clockintime'] == '0000-00-00 00:00:00'){
											$getbreak = dbQuery($dbConn, "SELECT * from staff_job_breaks where staff_id = '".$_SESSION['loginUserId']."' and job_id = '".$id."' and breakday = '".$today."'");
											if(dbNumRows($getbreak) == 0){
											?>
											<!--<div id="breakdiv" style="display:none;">
                                            <ul class="homebtn" style="margin:20px 0;">
                                            <li>
											<a href="javascript:void(0);" id="startbreak">Start Break</a>
                                            </li>
                                            </ul>
											</div>
											<div id="breakstartdiv" style="display:none;">
											<label>Enter code got in SMS to start break</label>
                                            <input type="text" class="form-control brkstartcode">
                                            <ul class="homebtn" style="margin:20px 0;">
                                            <li>
											<a href="javascript:void(0);" id="breakcodesub">Submit</a>
                                            </li>
                                            </ul>
											</div>

											<div id="breaksucces" style="display:none;">
											<span>Break has started.</span>
                                            <ul class="homebtn" style="margin:20px 0;">
                                            <li>
											<a href="javascript:void(0);" id="endbreak">End Break</a>
                                            </li>
                                            </ul>
											</div>

											<div id="breakenddiv" style="display:none;">
											<label>Enter code got in SMS to end break</label>
                                            <input type="text" class="form-control breakendcode">
                                            <ul class="homebtn" style="margin:20px 0;">
                                            <li>
											<a href="javascript:void(0);" id="breakendcodesub">Submit</a>
                                            </li>
                                            </ul>
											</div>
											<br>
											<span id="endbreaksuccess" style="display:none;">Break has ended.</span>-->
											<?php
											/*if($clock['clockintime']!='0000-00-00 00:00:00')
											$clockotsyle = '';
											else
											$clockotsyle = 'display:none;';*/
											?>
											
                                            <?php
											}
											}
											?>
											
												<?php
										//}
										if($clock['clockintime'] != '0000-00-00 00:00:00' && $clock['clockouttime']=='0000-00-00 00:00:00'){
										// employee break
										$getbreak = dbQuery($dbConn, "SELECT * from staff_job_breaks where staff_id = '".$_SESSION['loginUserId']."' and job_id = '".$id."' and breakday = '".$today."' order by id desc limit 0,1");
											if(dbNumRows($getbreak) == 0){
											$strtbrkstyle = '';
											$breakcodestyle = 'style="display:none;"';
											$brkmsgstyle = 'style="display:none;"';
											$breakendstyle = 'style="display:none;"';
											$breakendmsgstyle = 'style="display:none;"';
										}
										else{
											if(dbNumRows($getbreak) == 1){
												$break = dbFetchArray($getbreak);
												if($break['brkstartcode'] && $break['brkstarttime'] == '0000-00-00 00:00:00' && $break['brkendcode']==''){
													$strtbrkstyle = 'style="display:none;"';
													$breakcodestyle = '';
													$brkmsgstyle = 'style="display:none;"';
													$breakendstyle = 'style="display:none;"';
													$breakendmsgstyle = 'style="display:none;"';
												}
												else if($break['brkstarttime'] != '0000-00-00 00:00:00' && $break['brkendcode']==''){
													$strtbrkstyle = 'style="display:none;"';
													$breakcodestyle = 'style="display:none;"';
													$brkmsgstyle = '';
													$breakendstyle = 'style="display:none;"';
													$breakendmsgstyle = 'style="display:none;"';
												}
												else if($break['brkendcode'] && $break['brkendtime'] == '0000-00-00 00:00:00'){
													$strtbrkstyle = 'style="display:none;"';
													$breakcodestyle = 'style="display:none;"';
													$brkmsgstyle = 'style="display:none;"';
													$breakendstyle = '';
													$breakendmsgstyle = 'style="display:none;"';
												}
												else if($break['brkendtime'] != '0000-00-00 00:00:00'){
													$strtbrkstyle = 'style="display:none;"';
													$breakcodestyle = 'style="display:none;"';
													$brkmsgstyle = 'style="display:none;"';
													$breakendstyle = 'style="display:none;"';
													$breakendmsgstyle = '';
												}
											}
										}
											
											?>
											<div id="breakdiv">
												<div class="row">
												<div class="col-sm-6">
												<ul class="homebtn" style="margin:20px 0;">
                                            <li>
											<a href="javascript:void(0);" id="startbreak">Start Break</a>
                                            </li>
                                            </ul>
											
											<div id="breakstartdiv" <?php echo $breakcodestyle;?>>
											<br>
											<label>Enter code got in SMS to start break</label>
											<div class="mybrkinput">
                                            <input type="text" class="form-control brkstartcode">
											<a href="javascript:void(0);" id="breakcodesub">Submit</a>
											</div>

											</div>
											<br>
											<span id="breaksucces" <?php echo $brkmsgstyle;?>>Break has started.</span>
									</div>
									<div class="col-sm-6">
									<ul class="homebtn" style="margin:20px 0;">
                                            <li>
											<a href="javascript:void(0);" id="endbreak">End Break</a>
                                            </li>
                                            </ul>
											
											<div id="breakenddiv" <?php echo $breakendstyle;?>>
											<br>
											<label>Enter code got in SMS to end break</label>
											<div class="mybrkinput">
                                            <input type="text" class="form-control breakendcode">
											<a href="javascript:void(0);" id="breakendcodesub">Submit</a>
											</div>

											</div>
											
											<br>
											<span id="endbreaksuccess" <?php echo $breakendmsgstyle;?>>Break has ended.</span>
									</div>
									</div>
											
                                            
                                            
											</div>
											
											<?php
											}
										// employee clock out
										if($clock['clockintime']!= '0000-00-00 00:00:00' && $clock['clockouttime']=='0000-00-00 00:00:00'){
											//if(strtotime($currtime) <= $jobendttime){
												if($clock['clockintime']!='0000-00-00 00:00:00' && $clock['clockouttime']=='0000-00-00 00:00:00'){
													$clockoutstyle = "";
													$clockoutmsgstyle = "style='display:none;'";
												}
												else{
													$clockoutstyle = "display:none;";
													$clockoutmsgstyle = "";
												}
												
												?>
												<br>
												<!--<span>End time: <?php echo date('M j, Y', strtotime($jobenddate));?> at <?php echo date('h:i a', strtotime($endtime));?></span>-->
												<?php
												//if(date('Y-m-d', strtotime($currtime)) == $jobenddate){
												?>
												<ul class="homebtn" id="myclockout2" style="margin:20px 0;<?php echo $clockoutstyle;?>">
												<li>
												<a href="javascript:void(0);" id="clockout" >Clock out for today</a>
												</li>
												</ul>
												<span id="clockoutsucces" <?php echo $clockoutmsgstyle;?>>You have clocked out.</span>
												<?php
										}
										if($clock['clockouttime']!='0000-00-00 00:00:00'){
											?>
											<br>
											<span>You have clocked out.</span>
											<?php
										}
									}
									
                                        /*if($clock['clockincode'] && $clock['clockintime']=='0000-00-00 00:00:00' && $clock['clockouttime']=='0000-00-00 00:00:00'){
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
										   }*/
										   
											
											
                                            
											/*}
											else{
												echo "Work time is not started yet.";
											}*/
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
	
<script>
function myFunction() {
  var dots = document.getElementById("dots");
  var moreText = document.getElementById("more");
  var btnText = document.getElementById("myBtn");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "View more <i class='fa fa-angle-down' aria-hidden='true'></i>"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "View less <i class='fa fa-angle-up' aria-hidden='true'>"; 
    moreText.style.display = "inline";
  }
}
</script>
	<?php include_once('footer.php');?>