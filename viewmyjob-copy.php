<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login'</script>";
	exit;
}

$quals = array();
$addcomp = '';
$benefit = '';
$myquals = '';
$currtime = date('Y-m-d H:i:s');
$id = isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
$myjob = dbQuery($dbConn, "SELECT a.*,b.name,b.email,b.name,b.phone,b.business_name,b.company_img,c.category,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join category c on a.catid=c.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$id."'");
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
?>

	<div class="works works_success">
		<div class="container">
			<div class="row">
			<?php include_once "employer_left.php";?>
				<div class="col-lg-9">
					<div class="works_heading">

						<!--<ul class="homebtn" style="margin:20px 0;text-align:right;">
						<li><a href="<?php echo SITEURL;?>jobpost1" style="padding:0.4rem 1.5rem;">Post a job</a></li>
						</ul>-->
						
                        <!--<h4 style="padding-bottom:20px;"><?php echo stripslashes($fetch['title']);?></h4>-->
						
                        <div class="sucespagtab my_jobview_success">
						
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							
							  <li class="nav-item" role="presentation">
								<button class="nav-link active" id="pills-jobdetls-tab" data-bs-toggle="pill" data-bs-target="#pills-jobdetls" type="button" role="tab" aria-controls="pills-jobdetls" aria-selected="true">Job Details</button>
							  </li>
							
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Job Applicants</button>
							  </li>
							  <!--<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Messages</button>
							  </li>
                              <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-contract-tab" data-bs-toggle="pill" data-bs-target="#pills-contract" type="button" role="tab" aria-controls="pills-contract" aria-selected="false">Contract</button>
							  </li>-->
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-msgstaff-tab" data-bs-toggle="pill" data-bs-target="#pills-msgstaff" type="button" role="tab" aria-controls="pills-msgstaff" aria-selected="false">Help</button>
							  </li>
							</ul>
						<div class="tab-content" id="pills-tabContent">
						
							<div class="tab-pane fade show active" id="pills-jobdetls" role="tabpanel" aria-labelledby="pills-jobdetls-tab">
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
												<div class="col-sm-9">
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
												<div class="col-sm-3">
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
													$getcandts = dbQuery($dbConn, "SELECT d.id as msgid,d.msg,d.jobid,b.title,c.id,c.name,c.image,c.lname from job_details b inner join messages d on d.jobid=b.id inner join users c on d.senderid=c.id where (d.receiverid = '".$_SESSION['loginUserId']."') and d.jobid='".$id."' and d.parent_id=0 group by d.senderid order by d.id desc");
													if(dbNumRows($getcandts) > 0){
														while($rowRenter = dbFetchArray($getcandts)){
															/*$getMsg = dbQuery($dbConn, "SELECT senderid,receiverid,msg,msgdate from messages where (senderid = '".$_SESSION['loginUserId']."' OR receiverid = '".$_SESSION['loginUserId']."') AND (senderid = '".$rowRenter['id']."' OR receiverid = '".$rowRenter['id']."') order by msgdate desc limit 0,1");
															$rowMsg = dbFetchArray($getMsg);*/
					
																//if($rowMsg['senderid'] == $_SESSION['loginUserId'])
																//$lastsender = "You";
																//else if($rowMsg['senderid'] == $rowRenter['id']){
																	$lastsender = $rowRenter['name']." ".$rowRenter['lname'];
																//}

																$unread = dbQuery($dbConn, "SELECT id from messages where receiverid = '".$_SESSION['loginUserId']."' and senderid = '".$rowRenter['id']."' and jobid='".$id."' and isread=0 and parent_id != 0");
																$unread_count = dbNumRows($unread);

																$staff = dbQuery($dbConn, "SELECT suburb from staff_details where staff_id = '".$rowRenter['id']."'");
																$staffrow = dbFetchArray($staff);
					
																$getclock = dbQuery($dbConn, "SELECT clockouttime from 
																staff_job_payment where job_id = '".$id."' and staff_id = '".$rowRenter['id']."'");
																$clock = dbFetchArray($getclock);
													?>
													<div class="singlemeg">
														<div class="row">
															<div class="col-sm-1"><div class="clinima">
															<?php
															if($rowRenter['image']){
																?>
																<img src="<?php echo SITEURL;?>uploads/<?php echo $rowRenter['image'];?>" alt="">
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
														<li style="border-right:0px;padding-right:0;"><a id="view_<?php echo $rowRenter['id'];?>_<?php echo $rowRenter['jobid'];?>" class="msgview viewempmsgs" href="javascript:void(0);" style="padding:0.4rem 1.5rem;background-color:#3176B4;color:#fff;">View</a></li>
														<?php if(isset($clock['clockouttime']) && $clock['clockouttime']!='0000-00-00 00:00:00'){}
														else{
														?>
														<li><a id="rly_<?php echo $rowRenter['id'];?>_<?php echo $rowRenter['jobid'];?>_<?php echo $rowRenter['msgid'];?>_<?php echo $lastsender;?>" class="msgreply msgstaff" href="javascript:void(0);" style="padding:0.4rem 1.5rem;">Reply</a></li>
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
														<?php
													}
													$usertype = getUserType($dbConn, $_SESSION['loginUserId']);
													?>
													
													<div class="replyboxmeg" style="display:none;">
														<div class="row">
															<p>Send a message to <span id="msgto"></span></p>
															<div class="col-sm-1">
															<div class="clinima">
															<?php
															if($fetch['company_img']){
																?>
																<img src="<?php echo SITEURL;?>uploads/<?php echo $fetch['company_img'];?>" alt="">
																<?php
															}
															else{
															?>
															<img src="<?php echo SITEURL;?>images/sercovernoimg.png" alt="" />
															<?php
															}
															?>
															</div></div>
															<div class="col-sm-11">
																<div class="rplfrm">
																	<form action="" method="post" id="replyform">
																	<input type="hidden" name="action" value="sendmsg">
																	<input type="hidden" id="usertype" name="usertype" value="<?php echo $usertype;?>">
																	<input type="hidden" id="jobid" name="jobid" value="">
																	<input type="hidden" id="msgid" name="msgid" value="">
																	<input type="hidden" id="staff_id" name="userid" value="">
																	<input type="hidden" id="emp_id" name="employerid" value="<?php echo $_SESSION['loginUserId'];?>">
																		<div class="row">
																			<div class="col-sm-12">
																				<textarea class="rplara required" id="reply" name="mymsg"></textarea>
																			</div>
																			
																			<div class="col-sm-12">
																				<input type="submit" name="sub" id="mysendmsg" value="Reply" style="float:right;">
																				<span id="success" style="color:#69A268;font-weight:bold;"></span>
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
										<tr>
											<th style="text-align:left;">Staff</th>
											<th>Job Category</th>
											<th style="text-align:center;">Status</th>
										</tr>
										<?php
										$staff_quals = array();
										$staffqualstr = '';
										$catids = array();
										$staffcatstr = '';
										$jobs = dbQuery($dbConn, "SELECT a.id as userid,a.name,a.lname,b.hired,b.application_sent_to,c.title,c.id from users a left join job_status b on a.id=b.application_sent_to inner join job_details c on b.jobid=c.id where b.jobid = '".$id."' and b.contacting=1 order by b.id desc");
										if(dbNumRows($jobs) > 0){
										while($row = dbFetchArray($jobs)){
											$getcat = dbQuery($dbConn, "select b.category from staff_details a inner join category b on a.catid=b.id where a.staff_id = '".$row['application_sent_to']."'");
											$rowcat = dbFetchArray($getcat);

											$mycats = dbQuery($dbConn, "SELECT catid from staff_job_cat where staff_id = '".$row['application_sent_to']."'");
												while($mycatsrow = dbFetchArray($mycats)){
													$staffcats = dbQuery($dbConn, "select category from category where id = '".$mycatsrow['catid']."'");
													$stffcatrow = dbFetchArray($staffcats);
													$catids[] = $stffcatrow['category'];
												}
												if(count($catids) > 0){
													$staffcatstr = implode(", ", $catids);
												}

											if($row['hired'] == 1)
											$hired = "Hired";
											else
											$hired = "Not Hired";

											$myqual = dbQuery($dbConn, "SELECT qualifictn from qualifications a inner join staff_qualification b on a.id=b.qualification where b.staff_id = '".$row['application_sent_to']."'");
											while($qualfetch = dbFetchArray($myqual)){
												$staff_quals[] = $qualfetch['qualifictn'];
											}
											if(count($staff_quals) > 0){
												$staffqualstr = implode(", ", $staff_quals);
											}
											if($staffcatstr != "")
											$staffallcat = $staffcatstr;
											else
											$staffallcat = $rowcat['category'];
											?>
											<tr>
											<td><span class="frmfstname"><a href="<?php echo SITEURL;?>managehire/?id=<?php echo base64_encode(stripslashes($row['id']));?>&userid=<?php echo base64_encode(stripslashes($row['userid']));?>"><?php echo stripslashes($row['name'])." ".stripslashes($row['lname']);?></a></span></td>
											<td style="text-align:center;"><?php echo $staffallcat;?></td>
											<td style="text-align:center;"><span class="openstatus"><?php echo $hired;?></span></td>
											</tr>
											<?php
											$staff_quals = array();
											$catids = array();
										}
										}
										else{
											?>
											<tr>
											<td colspan="4" style="text-align:center;">No staff applied so far.</td>
											</tr>
											<?php
										}
										?>
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
											<input type="text" placeholder="Name" name="name" id="name" class="form-control required" value="<?php echo $fetch['name'];?>">
											<br>
											<input type="email" placeholder="Email" name="email" id="email" class="form-control required" value="<?php echo $fetch['email'];?>">
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

						
							<ul class="homebtn" style="margin:20px 0;">
							<li><a href="<?php echo SITEURL;?>myjobs" style="padding:0.4rem 1.5rem;">Back</a></li>
							</ul>
						</div>
						
						
					</div>

				</div>
				
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