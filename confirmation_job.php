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

$latest_job_id = get_latest_job($_SESSION['loginUserId'], $dbConn);
$id = isset($_REQUEST['id'])?trim($_REQUEST['id']):$latest_job_id;

$myjob = dbQuery($dbConn, "SELECT a.*,b.name,b.email,b.name,b.phone,b.business_name,b.company_img,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$id."'");

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
					
					?>
						<ul>
							<li <?php if($page == "success.php" || ($page == "viewmyjob.php" && !$action) || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">Jobs</a></li>
							<li <?php if($action=='contacting') echo "class='active'";?>><a href="<?php echo SITEURL;?>viewjob/<?php echo $latest_job_id;?>?action=contacting">Candidates</a></li>
							<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>
							<li <?php if($page == "confirmation_job.php") echo "class='active'";?>><a href="<?php echo SITEURL;?>confirmation/<?php echo $latest_job_id;?>">Confirmation</a></li>
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

				<div class="col-lg-12">

					<div class="works_heading">



						<!--<ul class="homebtn" style="margin:20px 0;text-align:right;">

						<li><a href="<?php echo SITEURL;?>jobpost1" style="padding:0.4rem 1.5rem;">Post a job</a></li>

						</ul>-->

						

                        <!--<h4 style="padding-bottom:20px;"><?php echo stripslashes($fetch['title']);?></h4>-->

						

                        <div class="sucespagtab my_jobview_success">

						<div class="tab-content" id="pills-tabContent">


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


							<div class="">
							
							
								<div class="successtable">									
									<div class="newcnfrmjob">
										<p>Please use this page to confirm all details to be sent to candidates once finalized</p>
										<ul>
											<li>Send confirmation of date, time, location & details.</li>
											<li>Get access to staff superannuation, tax file and bank details.</li>
											<li>Staff clock In/Out feature to track their time on day of work.</li>
										</ul>
										<div class="myalljobs">									
											<?php
											$alljobs = dbQuery($dbConn, "SELECT id,title from job_details where employer_id = '".$_SESSION['loginUserId']."' and postcomplete=1 order by id desc");
											?>
											<label>My Jobs</label>
											<select name="myjobs" id="myjobs_conf" class="form-select">
												<?php
												while($rowjobs = dbFetchArray($alljobs)){
													?>
													<option value="<?php echo $rowjobs['id'];?>" <?php if($id==$rowjobs['id']) echo "selected";?>><?php echo stripslashes($rowjobs['title']);?></option>
													<?php
												}
												?>
											</select>
										</div>
										<?php
										$myjob = dbQuery($dbConn, "SELECT a.*,b.name,d.name as cname FROM job_details a inner join users b on a.employer_id=b.id inner join oc_country d on a.country=d.iso_code_2 where a.id='".$id."'");
										$fetch = dbFetchArray($myjob);
										?>
										<div class="jobopnnew">
											<div class="table-responsive">
												<table class="table">
													<tr>
														<td><strong>Work Address:</strong></td>
														<td><?php echo stripslashes($fetch['street_address']);?></td>
													</tr>
													<tr>
														<td><strong>Dates:</strong></td>
														<td><?php 
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
														if($fetch['add_time']==1){
														echo " at ".date('h:i A', strtotime($fetch['starttime']));
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
														echo date('h:i A', strtotime($fetch['endtime']));
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
														<?php
							$staffexp = dbQuery($dbConn, "SELECT starttime,endtime from shift_times where jobid = '".$id."' order by id");
							if(dbNumRows($staffexp) > 0){
								?>
								<?php
								echo "Shift timings: ";
								while($staffexprow = dbFetchArray($staffexp)){
									?>
									<?php echo date('h:i A', strtotime($staffexprow['starttime']));?> - <?php echo date('h:i A', strtotime($staffexprow['endtime']));?>&nbsp;&nbsp;
									<?php
								}
								?>
								<?php
							}
							?>
							</td>
													</tr>
													
													<tr>
														<td><strong>Rate:</strong></td>
														<td><?php
														if($fetch['paytype'] == 1){

															?>

															Annual Salary <?php echo $fetch['annual'];?> AUD/Year

															<?php

														}

														if($fetch['paytype'] == 2){

														?>

														Hourly Rate <?php echo $fetch['payperhr'];?> AUD

														<?php

														}

														if($fetch['paytype'] == 3){

														?>

														Commission: <?php echo $fetch['commission_perctg'];?>%

														<?php

														}

														if($fetch['paytype'] == 4){

															?>

														Annual <?php echo $fetch['annualamt'];?> AUD/Year <span class="onlyconnidiontext">& Commission <?php echo $fetch['commission_perctg_amt'];?>%</span>

														<?php 
														}
														?>
														</td>
													</tr>
												</table>
											</div>
											
										</div>
									</div>	
									<input type="hidden" id="jobid" value="<?php echo $id;?>">
									<input type="hidden" id="myseldates" value="">
									<?php
									$confrm_dates = array();
									$confrm_date_str = '';
									$jobs = dbQuery($dbConn, "SELECT a.id as userid,a.name,a.lname,b.senton,b.hired,b.confirmation_sent,b.application_sent_to,b.emp_option_sent,b.employer_note,c.title,c.id from users a left join job_status b on a.id=b.application_sent_to inner join job_details c on b.jobid=c.id where b.jobid = '".$id."' and b.contacting=1 and b.confirmation_sent=1 order by b.id desc");

									if(dbNumRows($jobs) > 0 && $fetch['workmode'] == 2){
										?>
									<div class="newcnfrmjob">
										<div class="canddtsvw">
											<div class="table-responsive">
												<table class="table">
													<tr>
														<!--<td width="35%">Date</td>
														<td width="20%">Timing</td>-->
														<td width="50%">Candidate</td>
														<td width="50%" style="text-align:center;">Confirmed</td>
													</tr>
													<?php
													while($row = dbFetchArray($jobs)){
														/*$confrmtn = dbQuery($dbConn, "SELECT confirm_date FROM staff_confirmation WHERE jobid = '".$id."' and staff_id = '".$row['userid']."'");
														while($row_confrmtn = dbFetchArray($confrmtn)){
															$confrm_dates[] = $row_confrmtn['confirm_date'];
														}
														if(count($confrm_dates) > 0){
															$confrm_date_str = implode(", ", $confrm_dates);
														}*/
													?>
													<tr>
														
														<td>
															<?php echo $row['name']." ".$row['lname'];?><br>
															<?php
															if($row['emp_option_sent'] == 1 && $row['hired'] == 0){
															if($row['employer_note']==''){
															?>
															<textarea id="empnote_<?php echo $id;?>_<?php echo $row['userid'];?>"></textarea><br>
															<a href="javascript:void(0);" id="sendnote_<?php echo $id;?>_<?php echo $row['userid'];?>" class="sndcnfmsn sendnote" style="margin:0;">Send Note</a>
															<?php
															}
															else{
																?>
																Note: <?php echo stripslashes($row['employer_note']);?>
																<?php
															}
															}
															?>
														</td>
														<td>
														
															<!--<div class="intrsttab">-->
															<?php
															if($row['hired'] == 1){
															?>
																<a href="javascript:void(0);" class="sndcnfmsn" style="margin:0;">Hired</a>
																<?php
															}
															if($row['hired'] == 2){
															?>
																<a href="javascript:void(0);" class="sndcnfmsn" style="margin:0;">Rejected</a>
																<?php
															}
															if($row['hired'] == 0 && $row['emp_option_sent'] == 0){
															?>
																<a href="javascript:void(0);" id="sendconf_<?php echo $id;?>_<?php echo $row['userid'];?>" class="sndcnfmsn sendconfrm" style="margin:0;">Send Confirmation</a>
																<?php
															}
															if($row['hired'] == 0 && $row['emp_option_sent'] == 1){
															?>
															<a href="javascript:void(0);" class="sndcnfmsn" style="margin:0;">Confirmation Sent</a>
															<?php
															}
															?>
															<!--</div>
															<p id="conf_<?php echo $id;?>_<?php echo $row['userid'];?>"></p>-->
														</td>
													</tr>
													<?php
													$j=0;
													}
													?>
												</table>
											</div>
										</div>
									</div>
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
		  <p style="text-align:center;">Confirmation request sent.</p>
      </div>
    </div>
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

	<?php include_once('footer.php');?>