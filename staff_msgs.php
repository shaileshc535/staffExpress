<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."staff_login'</script>";
	exit;
}
?>

<div class="works works_success my_job_sty">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<ul class="onlyemployernav">
						<li <?php if($page == "profile.php") echo "class='active'";?>><a href="<?php echo SITEURL."staff_profile";?>">Account</a></li>
						
						<?php
						$checkonboard = dbQuery($dbConn, "SELECT isonboardsuccess from staff_details where staff_id = '".$_SESSION['loginUserId']."'");
						$row = dbFetchArray($checkonboard);
						if($row['isonboardsuccess'] == 0){
							?>
							<!--<li><a href="<?php echo SITEURL."onboard";?>">Onboard</a></li>-->
							<?php
						}
						?>
						<!--<li><a href="<?php echo SITEURL."staff_payment";?>">Payment</a></li>
						<li><a href="<?php echo SITEURL."staff_payment2";?>">Health Info</a></li>
						<li><a href="<?php echo SITEURL."staff_payment3";?>">Superannuation Info</a></li>-->
						<li <?php if($page == "myjobs.php" || $page == "myjobview.php") echo "class='active'";?>><a href="<?php echo SITEURL."myappliedjobs";?>">My Jobs</a></li>
						<li <?php if($page == "staff_msgs.php") echo "class='active'";?>><a href="<?php echo SITEURL."staff_messages";?>">Messages</a></li>
						<li <?php if($page == "i_am_staff.php") echo "class='active'";?>><a href="<?php echo SITEURL."staff_details";?>">My Profile</a></li>
						<!--<li><a href="<?php echo SITEURL."logout";?>">Logout</a></li>-->
					</ul>
				</div>
			<?php //include_once "staff_left.php";?>
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
						
							<ul class="messleftshort messemployee">
							<?php
							$msgcond = '';
							if(isset($_REQUEST['action']) && $_REQUEST['action'] == "sendjobmsg"){
								$jobid = isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
								$getcandts = dbQuery($dbConn, "SELECT b.title,b.employer_id,c.id,c.name,c.company_img from job_details b inner join users c on b.employer_id=c.id where b.id = '".$jobid."'");
								//while($rowRenter = dbFetchArray($getcandts)){
								$rowRenterSingle = dbFetchArray($getcandts);
								
								?>
								<div class="row">
								<div class="col-lg-12">
								<a id="view_<?php echo $rowRenterSingle['id'];?>_<?php echo $jobid;?>" class="viewstaffmsgs2 current" href="javascript:void(0);">
								<li>
									<div class="clinima">
										<?php
										if($rowRenterSingle['company_img']){
												?>
												<img src="<?php echo SITEURL;?>uploads/<?php echo $rowRenterSingle['company_img'];?>" alt="">
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
											<h5><?php echo stripslashes($rowRenterSingle['name']);?></h5>
										</div>
										<p class="frmfstname"><?php echo stripslashes($rowRenterSingle['title']);?></p>
										
									</div>
									
								</li>
								</a>
								</div>
								
								</div>
								<?php
								$i++;
							//}
							$msgcond = "and a.jobid != '".$jobid."'";
							?>
							<input type="hidden" id="clickedmsg" value="view_<?php echo $rowRenterSingle['id'];?>_<?php echo $jobid;?>">
							<?php
							}
							else{
								$msgcond = "";
								?>
								<input type="hidden" id="clickedmsg" value="">
								<?php
							}	
							$getcandts = dbQuery($dbConn, "SELECT a.jobid,a.id as msgid,a.senderid,b.title,c.id,c.name,c.company_img from messages a inner join job_details b on a.jobid=b.id inner join users c on b.employer_id=c.id where (a.senderid = '".$_SESSION['loginUserId']."') ".$msgcond." group by a.jobid order by a.id desc");
							
							if(dbNumRows($getcandts) > 0){
								$i=0;
								while($rowRenter = dbFetchArray($getcandts)){
								$getMsg = dbQuery($dbConn, "SELECT senderid,receiverid,msg,msgdate from messages where (senderid = '".$_SESSION['loginUserId']."' OR receiverid = '".$_SESSION['loginUserId']."') AND (senderid = '".$rowRenter['senderid']."' OR receiverid = '".$rowRenter['senderid']."') and jobid = '".$rowRenter['jobid']."' order by msgdate desc limit 0,1");
								$rowMsg = dbFetchArray($getMsg);

									if($rowMsg['senderid'] == $_SESSION['loginUserId']){
										$fetch = dbQuery($dbConn, "SELECT name,lname from users where id = '".$_SESSION['loginUserId']."'");
										$row = dbFetchArray($fetch);
										$lastsender = strtoupper(substr($row['name'], 0, 1))." ".strtoupper(substr($row['lname'], 0, 1));
									}
									else if($rowMsg['senderid'] == $rowRenter['id']){
										$lastsender = $rowRenter['name'];
									}

									$unread = dbQuery($dbConn, "SELECT id from messages where receiverid = '".$_SESSION['loginUserId']."' and jobid='".$rowRenter['jobid']."' and isread=0 and parent_id != 0");
									$unread_count = dbNumRows($unread);
									if($unread_count > 0)
									$style = "style='font-weight:bold;'";
									else
									$style = "";
								?>
								<div class="row">
								<div class="col-lg-12">
								<a id="view_<?php echo $rowRenter['id'];?>_<?php echo $rowRenter['jobid'];?>_<?php echo $rowRenter['msgid'];?>" class="<?php if($i==0 && !isset($_REQUEST['action'])) echo 'loadmsgs';?> viewstaffmsgs2" href="javascript:void(0);">
								<li>
									<div class="clinima">
										<?php
										if($rowRenter['company_img']){
												?>
												<img src="<?php echo SITEURL;?>uploads/<?php echo $rowRenter['company_img'];?>" alt="">
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
											<h5 <?php echo $style;?>><?php echo stripslashes($rowRenter['name']);?></h5>
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
								if(!isset($_REQUEST['action'])){
								?>
								<li>
								<span class="frmfstname">No messages so far.</span>
								</li>
								<?php
								}
							}
							//}
							?>
							</ul>
									
						</div>
						<div class="col-sm-8 lesspadd_lt">
						<?php
						$usertype = getUserType($dbConn, $_SESSION['loginUserId']);
						if(isset($_REQUEST['action']) && $_REQUEST['action'] == "sendjobmsg"){
							$msgs = '';
							$jobid = isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
							$msgboxstyle = 'style=""';
						}
						else{
							$msgboxstyle = 'style="display:none;"';
							$jobid = '';
						}
						?>
						<input type="hidden" id="usertype" name="usertype" value="<?php echo $usertype;?>">
						<input type="hidden" id="jobid" name="jobid" value="<?php echo $jobid;?>">
						<?php
						$getAllMsg2 = dbQuery($dbConn, "SELECT id,senderid,receiverid,msg,msgdate,parent_id from (SELECT m.senderid,m.receiverid,m.msg,m.msgdate,m.id,m.parent_id,s.name as sender,r.name as receiver from messages m inner join users s on m.senderid=s.id inner join users r on m.receiverid=r.id where 1 AND jobid = '".$jobid."' AND (senderid = '".$rowRenterSingle['employer_id']."' OR receiverid = '".$rowRenterSingle['employer_id']."') AND  (senderid = '".$_SESSION['loginUserId']."' OR receiverid = '".$_SESSION['loginUserId']."') order by msgdate desc ) t2 order by msgdate");
						$recMsg2 = dbFetchArray($getAllMsg2);
						if($recMsg2['parent_id'] != 0)
							$msgid = $recMsg2['parent_id'];
						else
							$msgid = $recMsg2['id'];
						?>
						<input type="hidden" id="msgid" name="msgid" value="<?php echo $msgid;?>">
						<input type="hidden" id="staff_id" name="userid" value="<?php echo $_SESSION['loginUserId'];?>">
						<input type="hidden" id="emp_id" name="employerid" value="<?php echo $rowRenterSingle['employer_id'];?>">
							<div class="messmainall">
								<h5 id="msgstaff"></h5>
								<div id="mymsgbox" <?php echo $msgboxstyle;?>>
									<div class="messgbk">
										<div id="existingmsg" class="onlyscrol" style="margin-top:0px;">
										<?php
										if(isset($_REQUEST['action']) && $_REQUEST['action'] == "sendjobmsg"){
										$getAllMsg = dbQuery($dbConn, "SELECT senderid,receiverid,msg,msgdate from (SELECT m.senderid,m.receiverid,m.msg,m.msgdate,s.name as sender,r.name as receiver from messages m inner join users s on m.senderid=s.id inner join users r on m.receiverid=r.id where 1 AND jobid = '".$jobid."' AND (senderid = '".$rowRenterSingle['employer_id']."' OR receiverid = '".$rowRenterSingle['employer_id']."') AND  (senderid = '".$_SESSION['loginUserId']."' OR receiverid = '".$_SESSION['loginUserId']."') order by msgdate desc ) t2 order by msgdate");
										if(dbNumRows($getAllMsg) > 0){
										while($recMsg = dbFetchArray($getAllMsg)){
											if($recMsg['senderid'] == $rowRenterSingle['employer_id']){
												$sender = $row['name'];
												$msgstyle = "";
											}
											else if($recMsg['senderid'] == $_SESSION['loginUserId']){
												$sender = $candname;
												$msgstyle = "sendermsg";
												//$senderimg = $renterimg;
											}
												$msgs .= '<div class="client_messages" style="margin-bottom:10px;">
													<div class="client_messages_content '.$msgstyle.'">
														<div class="client_messages_name">
															<p class="messdatestyl"><span class="lintst"><span class="dtabso">'.date("M j, Y", strtotime($recMsg['msgdate'])).'</span></span></p>
															<h6>'.$sender.' <span class="messrtnamedate">'.date("h:i a", strtotime($recMsg['msgdate'])).'</span></h6>
														</div>
														<p>'.stripslashes($recMsg['msg']).'</p>
													</div>
												</div>
												<div style="clear:both;"></div>';
												$msgid++;
													}
													$msgdt++;
													
													echo $msgs;
											}
											
										}
										?>
										</div>
										<div class="messsentbx" style="display: inline-block;width: 100%;">
											<!--<h6 style='text-align:center;margin-top:0px;margin-bottom:0;' class='nomsg'>No messages so far.</h6>-->

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