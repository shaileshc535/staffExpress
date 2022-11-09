<?php

include('config/config.php'); 

include_once "config/common.php";

$dbConn = establishcon();

include_once('header.php');

if(!isset($_SESSION['loginUserId'])){

	echo "<script>location.href='".SITEURL."employer_login'</script>";

	exit;

}


$cats = array();
$quals = array();

$addcomp = '';

$benefit = '';

$myquals = '';

$currtime = date('Y-m-d H:i:s');

$id = isset($_REQUEST['id'])?trim($_REQUEST['id']):"";

$myjob = dbQuery($dbConn, "SELECT a.*,b.name,b.email,b.name,b.phone,b.business_name,b.company_img,c.states,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join states c on a.state=c.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$id."'");

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

	$action = isset($_REQUEST['action'])?trim($_REQUEST['action']):"";
	
	$mycats = dbQuery($dbConn, "SELECT category from job_cat a inner join category b on a.catid=b.id where jobid = '".trim($id)."'");

	while($mycatsrow = dbFetchArray($mycats)){

		$cats[] = stripslashes($mycatsrow['category']);

	}
	if(count($cats) > 0){
		$cats = implode(", ", $cats);
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
							<li <?php if($page == "success.php" || ($page == "viewmyjob.php" && !$action) || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">Jobs</a></li>
							<li <?php if($action=='contacting') echo "class='active'";?>><a href="<?php echo SITEURL;?>viewjob/<?php echo $latest_job_id;?>?action=contacting">Candidates</a></li>
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



						<!--<ul class="homebtn" style="margin:20px 0;text-align:right;">

						<li><a href="<?php echo SITEURL;?>jobpost1" style="padding:0.4rem 1.5rem;">Post a job</a></li>

						</ul>-->

						

                        <!--<h4 style="padding-bottom:20px;"><?php echo stripslashes($fetch['title']);?></h4>-->

						

                        <div class="sucespagtab my_jobview_success">

						
							<?php
							if(!$action){
							?>
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">


							  <li class="nav-item" role="presentation">

								<button class="nav-link <?php if(!$action) echo 'active';?>" id="pills-jobdetls-tab" data-bs-toggle="pill" data-bs-target="#pills-jobdetls" type="button" role="tab" aria-controls="pills-jobdetls" aria-selected="true">Job Details</button>

							  </li>
							  <li class="nav-item" role="presentation">

								<button class="nav-link" id="pills-msgstaff-tab" data-bs-toggle="pill" data-bs-target="#pills-msgstaff" type="button" role="tab" aria-controls="pills-msgstaff" aria-selected="false">Help</button>

							  </li>

							  <!--<li class="nav-item" role="presentation">

								<button class="nav-link <?php if($action=='contacting') echo 'active';?>" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Job Applicants</button>

							  </li>

							  <li class="nav-item" role="presentation">

								<button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Messages</button>

							  </li>

                              <li class="nav-item" role="presentation">

								<button class="nav-link" id="pills-contract-tab" data-bs-toggle="pill" data-bs-target="#pills-contract" type="button" role="tab" aria-controls="pills-contract" aria-selected="false">Contract</button>

							  </li>-->


							</ul>
							<?php
							}
							?>

						<div class="tab-content" id="pills-tabContent">

							<div class="tab-pane fade <?php if(!$action) echo 'show active';?>" id="pills-jobdetls" role="tabpanel" aria-labelledby="pills-jobdetls-tab">

								<div class="successtable">

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
										
											<span class="viewmyjobstatus">Status: &nbsp;<?php 

												if($fetch['isclosed'] == 0){

													echo "<span class='opengrn'><i class='fa fa-circle' aria-hidden='true'></i> Open</span>";
													
												}

												else

												echo "<span class='closered2'><i class='fa fa-circle' aria-hidden='true'></i> Paused</span>";

												?></span>
										</h4>

										<div class="viewthejobmaindescribe">

											<div class="row">

												<div class="col-sm-9">

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
														 <?php 
														 if($fetch['covertype']==1 && $fetch['jobdate2'] != "0000-00-00")
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
												echo "&nbsp;&nbsp;(".$myshifts.")";
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

														<!--<p>

															<span class="pertiwidth"><strong>Category:</strong></span>

															<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['category']);?></span>

														</p>-->

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


							<div class="tab-pane fade <?php if($action=='contacting') echo 'show active';?>" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
							
							
								<div class="successtable">
									<div class="applicantsingle">
									<div class="myalljobs" style="margin:0;width:100%;">
										<?php
										$alljobs = dbQuery($dbConn, "SELECT id,title from job_details where employer_id = '".$_SESSION['loginUserId']."' and postcomplete=1 order by id desc");
										?>
										<label>My Jobs</label>
										<select name="myjobs" id="myjobs" class="form-select">
											<?php
											while($rowjobs = dbFetchArray($alljobs)){
												?>
												<option value="<?php echo $rowjobs['id'];?>" <?php if($id==$rowjobs['id']) echo "selected";?>><?php echo stripslashes($rowjobs['title']);?></option>
												<?php
											}
											?>
										</select>
									</div>
									</div>
								<?php
								$staff_quals = array();
								$staffqualstr = '';
								$catids = array();

								$staffcatstr = '';
								$today = date('Y-m-d');
								$jobs = dbQuery($dbConn, "SELECT a.id as userid,a.name,a.lname,b.senton,b.hired,b.confirmation_sent,b.application_sent_to,c.title,c.id from users a left join job_status b on a.id=b.application_sent_to inner join job_details c on b.jobid=c.id where b.jobid = '".$id."' and b.contacting=1 order by b.id desc");

								if(dbNumRows($jobs) > 0){
								while($row = dbFetchArray($jobs)){

									$getcat = dbQuery($dbConn, "select a.resume,a.experienced from staff_details a  where a.staff_id = '".$row['application_sent_to']."'");

									$rowcat = dbFetchArray($getcat);

									$mycats = dbQuery($dbConn, "SELECT catid from staff_job_cat where staff_id = '".$row['application_sent_to']."'");

										while($mycatsrow = dbFetchArray($mycats)){

											$staffcats = dbQuery($dbConn, "select category from category where id = '".$mycatsrow['catid']."'");
											if(dbNumRows($staffcats) > 0){
											$stffcatrow = dbFetchArray($staffcats);

											$catids[] = '<span class="allskls">'.$stffcatrow['category'].'</span>';
											}

										}
										if(count($catids) > 0){
											$staffcatstr = implode(" ", $catids);
										}

											/*if($staffcatstr != "")
											$staffallcat = $staffcatstr;
											else
											$staffallcat = '<span class="allskls">'.$rowcat['category'].'</span>';*/


											/*if($row['hired'] == 1)
											$hired = "Hired";
											else
											$hired = "Not Hired";*/
											$date1 = new DateTime($today);
											$date2 = new DateTime($row['senton']);
											$diff = $date1->diff($date2)->format("%a");

											/*$getStaff = dbQuery($dbConn, "select resume,cover_letter from staff_details where staff_id = '".$row['application_sent_to']."'");
											$rowStaff = dbFetchArray($getStaff);*/
											
										?>
									<div class="applicantsingle">
										<h3>
											<?php echo stripslashes($row['name'])." ".stripslashes($row['lname']);?> 
											<?php
											$total_rating = 0;
											$allrec = dbQuery($dbConn, "SELECT id,rating from reviews where staff_id = '".$row['userid']."' and givenbystaff=0");
											$ratingtotal = dbNumRows($allrec);
											if($ratingtotal > 3){
											while($rowallrevw = dbFetchArray($allrec)){
												$total_rating += $rowallrevw['rating'];
											}

												$avgraging = number_format(($total_rating / $ratingtotal),1);
												?>
												<!--<img src="<?php echo SITEURL;?>images/<?php echo ceil($avgraging);?>.png" class="img-fluid" style="margin-top:-3px;" alt=""> (<?php //echo $avgraging;?>)-->
												<?php
											}
											?>
											<span class="mnthago">Applied 
											<?php 
											if($diff > 0)
											echo $diff." days ago";
											else
											echo "Today";
											?> </span>
										</h3>
										
										
										<div class="row">
											<div class="col-sm-9">
												<?php
												if($rowcat['experienced'] == '1'){
												$staffexp = dbQuery($dbConn, "SELECT company,designation,experience,experience_month from staff_experience where staff_id = '".$row['application_sent_to']."' order by id");
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
												<!--<p>Skills and attributes in applicant's application that SEEK thinks are a match for your job:</p>-->
												
												<!--<p><?php //echo $staffallcat;?></p>-->
											</div>
											<div class="col-sm-3">
												<div class="interstedcandists">
													<h5>Interested?</h5>
													<div class="intrsttab">
													<?php
													if($row['hired'] == 1){
													?>
														<a href="javascript:void(0);" class="intrsttick active"><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" class="intrstcross"><i class="fa fa-times" aria-hidden="true"></i></a>
													<?php
													}
													if($row['hired'] == 2){
													?>
														<a href="javascript:void(0);" class="intrsttick"><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" class="intrstcross active"><i class="fa fa-times" aria-hidden="true"></i></a>
														<?php
													}
													if($row['confirmation_sent'] == 1 && $row['hired'] == 0){
													?>
														<a href="javascript:void(0);" class="intrsttick"><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" class="intrstcross"><i class="fa fa-times" aria-hidden="true"></i></a>
													<?php
													}
													if($row['confirmation_sent'] == 0){
													?>
														<a href="javascript:void(0);" <?php if($fetch['isclosed'] == 0) echo 'id="hire2_'.$id.'_'.$row['userid'].'"';?> class="intrsttick sendhire <?php if($fetch['isclosed'] == 1) echo 'disabled';?>"><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" <?php if($fetch['isclosed'] == 0) echo 'id="reject_'.$id.'_'.$row['userid'].'"';?> class="intrstcross rejectstaff <?php if($fetch['isclosed'] == 1) echo 'disabled';?>"><i class="fa fa-times" aria-hidden="true"></i></a>
														<?php
													}
													if($row['hired'] == 0 && $row['confirmation_sent'] == 1){
															echo "<p><strong>Shortlisted</strong></p>";
														}
													?>
													<p id="conf_<?php echo $id;?>_<?php echo $row['userid'];?>" style="font-weight:bold;"></p>
													</div>
												</div>
											</div>
										</div>
										<div class="splsecton">
											<div class="row">
											<?php
											if($rowcat['resume']){
											?>
												<div class="col-sm-4 col-6">
													<div class="resumpop"><a class="btn myresume" href="javascript:void(0);" id="resume_<?php echo $row['application_sent_to'];?>"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp; Resume</a></div>
												</div>
												<?php
											}
												//if($rowStaff['cover_letter']){
												?>
												<!--<div class="col-sm-4">
													<div class="resumpop2"><a class="btn mycl" href="javascript:void(0);" id="cl_<?php echo $row['application_sent_to'];?>"><i class="fa fa-file-text" aria-hidden="true"></i> &nbsp; Cover Letter</a></div>
												</div>-->
												<?php
												//}
												?>
												<div class="col-sm-4 col-6">
													<div class="resumpop3"><a class="btn myeml" href="javascript:void(0);" id="eml_<?php echo $row['application_sent_to'];?>"><i class="fa fa-envelope" aria-hidden="true"></i> &nbsp; Email</a></div>
												</div>
											</div>
										</div>
										<div class="applicantsinglelastpart">
											<p><a href="<?php echo SITEURL;?>managehire/?id=<?php echo base64_encode(stripslashes($row['id']));?>&userid=<?php echo base64_encode(stripslashes($row['userid']));?>" class="rdmrles">View Applicant Details</a></p>
										</div>
									</div>
									<?php
											$staff_quals = array();
											$catids = array();
										}
										}
										else{
											?>
											<div class="applicantsingle" style="text-align:center;">
											<label>No staff applied for this job.</label></div>
											<?php
										}
										?>
									
									
								</div>


							  </div>

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

<div class="modal fade" id="resume" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Resume</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <embed src="" frameborder="0" width="100%" height="400px" />
		<iframe src="" width="100%" height="400px"></iframe>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="letter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Cover Letter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  <embed src="" frameborder="0" width="100%" height="400px" />
	  <iframe src="" width="100%" height="400px"></iframe>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="eml" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">Email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
    </div>
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