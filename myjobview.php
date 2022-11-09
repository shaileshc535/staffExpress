<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'confirmjob'){
	echo "<script>location.href='".SITEURL."staff_login?job=".$id."&do=confirmjob'</script>";
	}
	else{
		echo "<script>location.href='".SITEURL."staff_login'</script>";
	}
	exit;
}

$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):"";

$quals = array();
$cats = array();
$myquals = '';
$addcomp = '';
$benefit = '';
$currtime = date('Y-m-d H:i:s');
$today = date('Y-m-d');


$myjob = dbQuery($dbConn, "SELECT a.*,b.name,b.phone,b.business_name,b.company_img,c.states,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join states c on a.state=c.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$id."'");
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
	
	$mycats = dbQuery($dbConn, "SELECT category from job_cat a inner join category b on a.catid=b.id where jobid = '".trim($id)."'");

	while($mycatsrow = dbFetchArray($mycats)){

		$cats[] = stripslashes($mycatsrow['category']);

	}
	if(count($cats) > 0){
		$cats = implode(", ", $cats);
	}
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
						<?php
						if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'confirmjob'){
							$tab6_active = "active";
							$tab1_active = "";
							$tab6_content_active = "show active";
							$tab1_content_active = "";
						}
						else{
							$tab6_active = "";
							$tab1_active = "active";
							$tab6_content_active = "";
							$tab1_content_active = "show active";
						}
						?>
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							  
							  <li class="nav-item" role="presentation">
								<button class="nav-link <?php echo $tab1_active;?>" id="pills-detls-tab" data-bs-toggle="pill" data-bs-target="#pills-detls" type="button" role="tab" aria-controls="pills-detls" aria-selected="true">Job Details</button>
							  </li>
							  <?php
							  $checkUploads = dbQuery($dbConn, "SELECT id from job_documents where job_id = '".$id."'");
							  if(dbNumRows($checkUploads) > 0){
							  ?>
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Additional Details</button>
							  </li>
							  <?php
							  }
							  ?>
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
							  if($fetch['covertype'] == 1){
							  ?>
							  <!--<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-rate-tab" data-bs-toggle="pill" data-bs-target="#pills-rate" type="button" role="tab" aria-controls="pills-rate" aria-selected="false">Review</button>
							  </li>-->
							  <?php
							  }
							  ?>
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-msgstaff-tab" data-bs-toggle="pill" data-bs-target="#pills-msgstaff" type="button" role="tab" aria-controls="pills-msgstaff" aria-selected="false">Help</button>
							  </li>
							  <?php
								//if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'confirmjob'){
									?>
									<!--<li class="nav-item" role="presentation">
									<button class="nav-link <?php echo $tab6_active;?>" id="pills-confirm-tab" data-bs-toggle="pill" data-bs-target="#pills-confirm" type="button" role="tab" aria-controls="pills-confirm" aria-selected="false">Confirmation</button>
									</li>-->
									<?php
								//}
							  ?>
                              <?php
                              //}
                              ?>
							</ul>
							<div class="tab-content" id="pills-tabContent">
							
								<div class="tab-pane fade <?php echo $tab1_content_active;?>" id="pills-detls" role="tabpanel" aria-labelledby="pills-detls-tab">
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
										<h4><?php echo stripslashes($fetch['title']);?>
											<span class="viewmyjobstatus">
												Status: &nbsp;<?php 
												if($fetch['isclosed'] == 0){
													//if(strtotime($currtime) <= $jobendttime)
													echo "<span class='opengrn'>Open</span>";
													//else
													//echo "<span class='closered2'>Closed</span>";
												}
												else
												echo "<span class='closered2'>Paused</span>";
												?>
											</span>
										</h4>
										<div class="viewthejobmaindescribe">
											<div class="row">
												<div class="col-sm-9 col-lg-9 col-md-8">
													<ul>							
														<?php
														if($fetch['suburb'] != ""){
														?>
														<li><?php echo stripslashes($fetch['suburb']);?></li>
														<?php
														}
														if($fetch['states'] != ""){
														?>
														<li><?php echo stripslashes($fetch['states']);?></li>
														<?php
														}
														?>
														<li><?php echo $cats;?></li>
														<li><?php echo $wtype;?></li>
													</ul>							
													<ul>							
														<li>Posted <?php echo $posted;?></li>
														
													</ul>
													<ul>
														<li>Job Date: <?php 
														if($fetch['covertype']==1)
														echo date('M j, Y', strtotime($fetch['jobdate']));
														else if($fetch['covertype']==2){
															if($fetch['longstartdt'] != "0000-00-00")
																echo "From ".date('M j, Y', strtotime($fetch['longstartdt']));
															else
																echo "TBD";
														}
														?>
														<?php
											if($fetch['add_time'] == 1){
											?>
											at <?php echo date('h:i A', strtotime($fetch['starttime']));?>
											<?php
											}
											?>
											
											<?php if($fetch['covertype']==1 && $fetch['jobdate2'] != "0000-00-00")
												echo " - ".date('M j, Y', strtotime($fetch['jobdate2']));
												?>
											<?php
											if($fetch['add_time'] == 1){
												if($fetch['jobdate2'] != "0000-00-00")
												echo "at";
												else
													echo "-";
											?>
														<?php echo date('h:i A', strtotime($fetch['endtime']));?>
														<?php
														}
														if($fetch['is_shift'] == 1){
															$shifttype = explode(",", $fetch['shifttype']);
															$myshifts = array();
															foreach($shifttype as $val){
																if($val == '1')
																	$value = "Day Shift";
																if($val == '2')
																	$value = "Night Shift";
																if($val == '3')
																	$value = "Overnight Shift";
																
																$myshifts[] = $value;
															}
															if(count($myshifts) > 0){
																$myshifts = implode(", ", $myshifts);
															}
															echo "&nbsp;&nbsp;(".$myshifts.").";
														}
														else if($fetch['is_shift'] == 2){
															//echo "No shift: ".date('h:i A', strtotime($fetch['noshiftsrttime']))." - ".date('h:i A', strtotime($fetch['noshiftendtime']));
															if($fetch['noshifttext'])
															echo ". ".stripslashes($fetch['noshifttext']);
														}
														?>
														</li>
													</ul>
													<?php
							$staffexp = dbQuery($dbConn, "SELECT starttime,endtime from shift_times where jobid = '".$id."' order by id");
							if(dbNumRows($staffexp) > 0){
								?>
								<ul>							
								<?php
								while($staffexprow = dbFetchArray($staffexp)){
									?>
									<li>
									<?php echo date('h:i A', strtotime($staffexprow['starttime']));?> - <?php echo date('h:i A', strtotime($staffexprow['endtime']));?>
									</li>
									<?php
								}
								?>
								</ul>
								<?php
							}
							?>
													<!--<ul>
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
													</ul>-->
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
														<p class="viewthejobhiretxt">
															<span id="success" <?php echo $msgstyle;?>><i class="fa fa-check" aria-hidden="true"></i> &nbsp;Applied</span>
														</p>
														<p class="viewthejobhiretxt">
															<span class="opengrn" <?php echo $hiredmsgstyle;?>><i class="fa fa-check" aria-hidden="true"></i> &nbsp;You are hired for this job.</span>
														</p>
													</span>
														
												</p>
												<style>#more {display: none;}</style>
												<div class="content viethejobbk">
													<div class="alljbdts">
													<span id="dots"></span>
													<div id="more">
														<div class="onlyvwjobvw1">
															<p>
																<span class="pertiwidth"><strong>Description:</strong></span>
																<span class="viewthejobrighttxt onlydescrprdmr" style="float:left;"><?php echo stripslashes($fetch['description']);?></span>
															</p>
														</div>
														<p>
															<span class="pertiwidth"><strong>Employer:</strong></span>
															<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['name']);?></span>
														</p>
														<p>
															<span class="pertiwidth"><strong>Address:</strong></span>
															<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['street_address']);?></span>
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
															<span class="viewthejobrighttxt"><?php echo $fetch['experience'];?> year(s) <?php echo $fetch['experience_month'];?> month(s) (<?php echo $exptype;?>)</span>
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
						$checkUploads = dbQuery($dbConn, "SELECT * from job_documents where job_id = '".$id."'");
						if(dbNumRows($checkUploads) > 0){

							?>
                                            <tr>
                                            <td width="100%">
											<?php
											while($resUploads = dbFetchArray($checkUploads)){
											?>

												<span class="" style="margin-right:20px;"><a style="color:#4396BA;text-decoration:underline;" href="<?php echo SITEURL."contract/".$resUploads['jobupload'];?>" target="_blank"><?php echo $resUploads['jobupload'];?></a></span>
											<?php
											}
											?>
											</td>
                                            </tr>
                                            <?php
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
                                    //if($fetch['isclosed'] == 0){
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
											if($clock['clockintime']=='0000-00-00 00:00:00'){
											?>
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
										$checkworkstat = dbQuery($dbConn, "SELECT id,clockouttime from staff_job_clock where job_id = '".$id."' and staff_id = '".$_SESSION['loginUserId']."' order by id desc limit 0,1");
										if(dbNumRows($checkworkstat) > 0){
										$rowWork = dbFetchArray($checkworkstat);
										$lastworked_day = date('Y-m-d', strtotime($rowWork['clockouttime']));
										$date1 = new DateTime($today);
										$date2 = new DateTime($lastworked_day);
										$diff = $date1->diff($date2)->format("%a");
										
										if($diff > 30){
										$checkrevw = dbQuery($dbConn, "SELECT id from reviews where job_id = '".$id."' and emp_id = '".$fetch['employer_id']."' and staff_id = '".$_SESSION['loginUserId']."' and givenbystaff=1");
										if(dbNumRows($checkrevw) == 0){
									?>
									<form action="" method="post" id="staffreview">
									<input type="hidden" name="jobid" value="<?php echo $id;?>">
									<input type="hidden" name="userid" value="<?php echo $_SESSION['loginUserId'];?>">
									<input type="hidden" name="emp_id" value="<?php echo $fetch['employer_id'];?>">
									<table class="table" cellpadding="5" cellspacing="5">
                                    <tr class="table-light">
                                    <td colspan="2">How was the Employer? This is shared with us for quality monitoring purposes.</td>
									</tr>
									<tr>
                                    <td colspan="2"><input type="radio" name="myrate" required value="1">&nbsp;Unsatisfactory: Extremely poor, completely no training or assistance provided. Unrealistic expectations.<br>
									<input type="radio" name="myrate" value="4">&nbsp;Ok: Some guidance and assistance provided. Decently good team and overall ok.<br>
									<input type="radio" name="myrate" value="5">&nbsp;Excellent: Supportive company and team, willing to answer my questions, would recommend working.
									</td>
									</tr>
									<tr>
                                    <td valign="top">Comments (Your comments are shared only with Staff Express for quality and monitoring purposes)</td>
									<td><textarea name="mycomment" id="comment" class="form-control required"></textarea></td>
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
											<td colspan="2">You have not started work yet.</td>
											</tr>
											</table>
											<?php
										}
									}
									else{
										?>
										<table class="table" cellpadding="5" cellspacing="5">
										<tr class="table-light">
										<td colspan="2">You are not hired.</td>
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
							  
							  <div class="tab-pane fade <?php echo $tab6_content_active;?>" id="pills-confirm" role="tabpanel" aria-labelledby="pills-confirm-tab">
								<!--<div class="table-responsive successtable">
								<table class="table" cellpadding="5" cellspacing="5">
									<tr class="table-light">
										<td colspan="2">
										Confirm the Request?
										</td>
									</tr>
									
									<tr class="table-light">
										<td colspan="2">
										<?php
										if($getstat['hired'] == 0){
										?>
										<ul class="homebtn" id="confirmreq" style="margin:20px 0;">
											<li><a href="javascript:void(0);" id="yes_confirm" style="padding:0.4rem 1.5rem;">Yes</a></li>
											<li><a href="javascript:void(0);" id="no_confirm" style="padding:0.4rem 1.5rem;">No</a></li>
										</ul>
										
										<span class="opengrn" style="display:none;">You have confirmed the hiring.</span>
										<span class="closered2" style="display:none;">You have rejected the request.</span>
										<?php
										}
										else{
											if($getstat['hired'] == 1){
												?>
												<span class="opengrn">You have confirmed the hiring.</span>
												<?php
											}
											if($getstat['hired'] == 2){
												?>
												<span class="closered2">You have rejected the request.</span>
												<?php
											}
										}
										?>
									</td>
									</tr>
								</table>
								</div>-->
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