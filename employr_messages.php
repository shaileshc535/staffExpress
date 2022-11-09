<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login'</script>";
	exit;
}
?>

	<div class="works works_success my_job_sty">
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
								<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>
							</ul>
						</div>
					</div>
				</div>			
			</div>
			<div class="row">
			<?php //include_once "employer_left.php";?>
			
			<!--<div class="col-lg-12">
				<ul class="onlyemployernav">
					<li <?php if($page == "success.php" || $page == "viewmyjob.php" || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">My Jobs</a></li>
					<li <?php if($page == "job_post1.php") echo "class='active'";?>><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>
					<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>
					<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>
					
				</ul>
			</div>-->
			
				<div class="col-lg-12">
					<div class="works_heading">

						<div class="sucespagtab">
						<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="margin-bottom:20px !important;">
							<li class="nav-item" role="presentation">
							<!--<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true" style="padding-top:0;">Messages</button>-->
							</li>						  
						</ul>
						<div class="row">
						<div class="col-sm-4 lesspadd_rt">
						<input type="hidden" id="clickedmsg" value="">
										<ul class="messleftshort messemployee">
										<?php
										/*"SELECT d.jobid,b.title,c.id,c.name from job_details b inner join messages d on d.jobid=b.id inner join users c on d.senderid=c.id where b.employer_id = '".$_SESSION['loginUserId']."' group by d.senderid order by d.id desc"
										
										"SELECT a.jobid,b.title,c.id,c.name from job_status a inner join job_details b on a.jobid=b.id inner join users c on a.application_sent_to=c.id inner join messages d on d.senderid=c.id where b.employer_id = '".$_SESSION['loginUserId']."' group by d.senderid order by d.id desc"*/

										$getcandts = dbQuery($dbConn, "SELECT d.jobid,d.id as msgid,b.title,c.id,c.name,c.lname,c.image from job_details b inner join messages d on d.jobid=b.id inner join users c on d.senderid=c.id where (d.receiverid = '".$_SESSION['loginUserId']."') and d.parent_id = 0 order by d.id desc");
										if(dbNumRows($getcandts) > 0){
											$i=0;
											while($rowRenter = dbFetchArray($getcandts)){
											$getMsg = dbQuery($dbConn, "SELECT senderid,receiverid,msg,msgdate from messages where (senderid = '".$_SESSION['loginUserId']."' OR receiverid = '".$_SESSION['loginUserId']."') AND (senderid = '".$rowRenter['id']."' OR receiverid = '".$rowRenter['id']."') and (parent_id='".$rowRenter['msgid']."' or id='".$rowRenter['msgid']."') order by msgdate desc limit 0,1");
											$rowMsg = dbFetchArray($getMsg);

												if($rowMsg['senderid'] == $_SESSION['loginUserId'])
												{
													$fetch = dbQuery($dbConn, "SELECT name from users where id = '".$_SESSION['loginUserId']."'");
													$row = dbFetchArray($fetch);
													$lastsender = $row['name'];
												}
												else if($rowMsg['senderid'] == $rowRenter['id']){
													$lastsender = strtoupper(substr($rowRenter['name'], 0, 1))." ".strtoupper(substr($rowRenter['lname'], 0, 1));
												}

												$unread = dbQuery($dbConn, "SELECT id from messages where receiverid = '".$_SESSION['loginUserId']."' and jobid='".$rowRenter['jobid']."' and senderid = '".$rowRenter['id']."' and isread=0");
												$unread_count = dbNumRows($unread);
												if($unread_count > 0)
												$style = "style='font-weight:bold;'";
												else
												$style = "";
											?>
											<div class="row">
											<div class="col-lg-12">
												<a id="view_<?php echo $rowRenter['id'];?>_<?php echo $rowRenter['jobid'];?>_<?php echo $rowRenter['msgid'];?>" class="<?php if($i==0) echo 'loadmsgs';?> viewempmsgs2" href="javascript:void(0);">
												
													<li>
														<div class="clinima">
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
														</div>
														<div class="emplomesslefttxtdetls">
															<div class="namedate">
																<h5 <?php echo $style;?>><?php echo stripslashes($rowRenter['name'])." ".stripslashes($rowRenter['lname']);?></h5>
																<p class="messdt"><?php echo date('M j, Y', strtotime($rowMsg['msgdate']));?></p>
															</div>
															<p class="frmfstname"><?php echo stripslashes($rowRenter['title']);?></p>
															<span class="shrtdescrip" <?php echo $style;?>><?php echo stripslashes($lastsender);?>: <?php echo substr(stripslashes($rowMsg['msg']), 0, 10);?>
															<?php
															if(strlen($rowMsg['msg'])>10)
															echo "...";
															?>
															</span>
															
															<?php
																if($unread_count > 0){
																?>
																<span class='unread' id="unrd_<?php echo $rowRenter['id'];?>_<?php echo $rowRenter['jobid'];?>_<?php echo $rowRenter['msgid'];?>"><?php echo $unread_count;?></span>
																<?php
																}
															?>
															
															
														</div>
														
													</li>
												</a>
											</div>
													
												</div>
												<?php
												$i++;
												}
												}
												else{
													?>
													<li>
													<span class="frmfstname">No messages so far.</span>
													</li>
													<?php
												}
												?>
											</ul>
									
						</div>
						<div class="col-sm-8 lesspadd_lt">
						<?php
						$usertype = getUserType($dbConn, $_SESSION['loginUserId']);
						?>
						<input type="hidden" id="usertype" name="usertype" value="<?php echo $usertype;?>">
						<input type="hidden" id="jobid" name="jobid" value="">
						<input type="hidden" id="msgid" name="msgid" value="">
						<input type="hidden" id="staff_id" name="userid" value="">
						<input type="hidden" id="emp_id" name="employerid" value="<?php echo $_SESSION['loginUserId'];?>">
							<div class="messmainall">
								<h5 id="msgstaff"></h5>
								<div id="mymsgbox" style="display:none;">
									<div class="messgbk">
										<div id="existingmsg" class="onlyscrol" style="margin-top:0px;">
										
										</div>
											<!--<h6 style='text-align:center;margin-top:0px;margin-bottom:0;' class='nomsg'>No messages so far.</h6>-->
										<div class="messsentbx" style="display: inline-block;width: 100%;">

											<div id="showsendbox">
											<input type="text" class="form-control" id="mymsg" placeholder="Type a message">
											<a href="javascript:void(0);" class="chkotcls" id="sendmsg" style="float:right;margin-top:10px;padding: 0.4rem 30px;">Send Message</a>
											<br>
											<span id="success" style="color:#69A268;font-weight:bold;"></span>
											</div>
											
										</div>
									</div>
								</div>
							</div>
						</div>
						</div>

						</div>
						
					</div>

				</div>
				
			</div>
		</div>
	</div>
	
	<?php include_once('footer.php');?>