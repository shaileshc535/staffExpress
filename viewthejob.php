<?php

include('config/config.php'); 

include_once "config/common.php";

$dbConn = establishcon();

include_once('header.php');

$quals = array();

$myquals = '';

$addcomp = '';

$benefit = '';

$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):"";

$myaddress = isset($_GET['myaddress'])?tep_db_input($_GET['myaddress']):"";

$mycatid = isset($_GET['mycatid'])?tep_db_input($_GET['mycatid']):"";

$workmode = isset($_GET['workmode'])?tep_db_input($_GET['workmode']):"";


$from = isset($_GET['from'])?tep_db_input($_GET['from']):"";

$to = isset($_GET['to'])?tep_db_input($_GET['to']):"";

$currtime = date('Y-m-d H:i:s');

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
	
	$cats = array();
	$catstr = '';
	
	$mycats = dbQuery($dbConn, "SELECT category from job_cat a inner join category b on a.catid=b.id where jobid = '".$id."'");

	while($mycatsrow = dbFetchArray($mycats)){

		$cats[] = stripslashes($mycatsrow['category']);

	}
	if(count($cats) > 0){
		$catstr = implode(", ", $cats);
	}

?>
	<div class="works works_inner viethejobbk">

		<div class="container">

			<div class="row">

				<!--<div class="col-lg-6">

					<div class="works_inner_img">

						<img src="../images/wk.png" class="img-fluid" alt="">

					</div>

				</div>-->

				<div class="col-sm-8 offset-2">
				

					<div class="works_heading vwjbmainbk">

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
						<div style="text-align:right;">
						<input type="button" class="backbtntosearch" value="Back" onclick="location.href='<?php echo SITEURL;?>searchcover?myaddress=<?php echo $myaddress;?>&mycatid=<?php echo $mycatid;?>&workmode=<?php echo $workmode;?>&from=<?php echo $from;?>&to=<?php echo $to;?>'">
						</div>

						<?php

						if($fetch['company_img']){

							?>

							<div class="clientlogo"><img src="<?php echo SITEURL;?>uploads/<?php echo $fetch['company_img'];?>" alt=""></div>

							<?php

						}

						?>

                        <h4><?php echo stripslashes($fetch['title']);?></h4>

						<div class="viewthejobmaindescribe">

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

								<li><?php echo $catstr;?></li>

								<li><?php echo $wtype;?></li>

							</ul>							

							<ul>							

								<li>Posted <?php echo $posted;?></li>

								<li>Job Date: <?php 
								if($fetch['covertype']==1)
								echo date('M j, Y', strtotime($fetch['jobdate']));
								else if($fetch['covertype']==2){
									if($fetch['longstartdt'] != "0000-00-00")
										echo "&nbsp;From ".date('M j, Y', strtotime($fetch['longstartdt']));
									else
										echo "&nbsp;TBD";
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
							
							<ul>

								<li>

									<span>Status:</span> 

									<span><?php 

										if($fetch['isclosed'] == 0){

											//if($fetch['jobdate2'] >= date('Y-m-d'))

											echo "<span class='opengrn'>Open</span>";

											//else

											//echo "<span class='closered'>Closed</span>";

										}

										else

										echo "<span class='closered'>Closed</span>";

										?></span>

								</li>

							</ul>

							<div class="jobfulldescr"><?php echo stripslashes($fetch['description']);?></div>

							<p>

							<?php

									if(isset($_SESSION['loginUserId'])){

										$usertype = getUserType($dbConn, $_SESSION['loginUserId']);

										if($usertype == 2){

											$getuser = dbQuery($dbConn, "SELECT isdeactivated from users where id = '".$_SESSION['loginUserId']."'");

											$rowuser = dbFetchArray($getuser);

											if($rowuser['isdeactivated'] == 0){

												if($fetch['covertype']==1){

									if($fetch['jobdate2'] != "0000-00-00"){
										if($fetch['jobdate2'] >= date('Y-m-d'))
										$showbooking = 1;
										else
										$showbooking = 0;
									}
									else if($fetch['jobdate'] >= date('Y-m-d'))
									$showbooking = 1;
									else
									$showbooking = 0;
								}
								else{
									$showbooking = 1;
								}

									if($fetch['applicn_deadln']==1 && $fetch['applicn_deadln_date'] != "0000-00-00"){

										if($fetch['applicn_deadln_date'] >= date('Y-m-d'))

										$showbooking2 = 1;

										else

										$showbooking2 = 0;

									}

									else

									$showbooking2 = 1;

									if($showbooking == 1 && $showbooking2 = 1){

									?>

										<?php

										$contact = dbQuery($dbConn, "SELECT contacting,hired,avlblty from job_status where jobid = '".$id."' and application_sent_to = '".$_SESSION['loginUserId']."'");

										$getstat = dbFetchArray($contact);
										if(dbNumRows($contact) > 0){

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
										}
										else{
											$acceptstyle = "";

											$msgstyle = "style='display:none;'";

											$hiredmsgstyle = "style='display:none;'";

										}

										?>

										<div class="acceptoffer" <?php echo $acceptstyle;?>>

										<a href="javascript:void(0);" id="interested" class="jobapplybutn">I am interested in this cover</a>

										
										</div>

										<p class="viewthejobhiretxt">
											<span id="success" <?php echo $msgstyle;?>><i class="fa fa-check" aria-hidden="true"></i> &nbsp;Applied</span>
										</p>

										<p class="viewthejobhiretxt">
											<span class="opengrn" <?php echo $hiredmsgstyle;?>><i class="fa fa-check" aria-hidden="true"></i> &nbsp;You are hired for this job.</span>
										</p>
										<?php

										}

										}

										else{

											?>
											<p class="viewthejobhiretxt">
												<span><strong><i class="fa fa-times" aria-hidden="true"></i> &nbsp;You are not authorized to apply for the job.</strong></span>
											</p>
											<?php

										}

										}

									}

									else{

										//if($fetch['jobdate2'] >= date('Y-m-d')){
											if($fetch['covertype']==1){

												if($fetch['jobdate2'] != "0000-00-00"){
												if($fetch['jobdate2'] >= date('Y-m-d'))
												$showbooking = 1;
												else
												$showbooking = 0;
											}
											else if($fetch['jobdate'] >= date('Y-m-d'))
											$showbooking = 1;
											else
											$showbooking = 0;
										}
										else{
											$showbooking = 1;
										}
										
										if($showbooking == 1){
										?>
										<div class="acceptoffer">

										<a href="<?php echo SITEURL.'staff_login?job='.$id;?>&do=search" class="jobapplybutn">I am interested in this cover</a>
										</div>


										<?php
										}

										}


									?>
									

							</p>
							
							
							<div class="vwthejbsocl">
								<div class="st-custom-button facebook" data-network="facebook"><img alt="facebook sharing button" src="https://platform-cdn.sharethis.com/img/facebook.svg"> Share</div> 
								
								<div class="st-custom-button twitter" data-network="twitter"><img alt="twitter sharing button" src="https://platform-cdn.sharethis.com/img/twitter.svg"> Tweet</div>
								
								<div class="st-custom-button whatsapp" data-network="whatsapp"><img alt="whatsapp sharing button" src="https://platform-cdn.sharethis.com/img/whatsapp.svg"> Share</div>
							</div>

<div class="modal fade" id="availability" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2" style="margin-bottom:0 !important;"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  
	  <form action="" method="post" id="avl_form_apply">
	  <input type="hidden" id="jobid" name="jobid" value="<?php echo $id;?>">
	  <input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['loginUserId'];?>">
	  <div id="applydiv">
        <p>Select available days/times</p>
		<p class="radioparaspc">
		<input type="radio" name="avlblty" value="1" id="complete">&nbsp;Complete availability of the period<br />
		<input type="radio" name="avlblty" value="2" id="part">&nbsp;Part availability of the period
		</p>
		<span id="fullavl" class="datehighlight" style="display:none;"></span>
		<input type="hidden" id="myselctdate" name="myselctdate" value="">
		<input type="hidden" id="alreadyselctdate" name="alreadyselctdate" value="">
		<input type="hidden" id="workmode" value="">
		<input type="hidden" id="start_date" value="">
		<input type="hidden" id="end_date" value="">
		<div class="row" id="mydaternge" style="display:none;">
		<p>
		<input type="radio" name="select_mode" value="1" id="range">&nbsp;Select Range&nbsp;&nbsp;
		<input type="radio" name="select_mode" value="2" id="spcfc_dt">&nbsp;Select Specific Dates
		</p>
			<div class="col-md-6">
				<div class="row" id="jobdates">
					<div class="col-sm-12">
					<input type="text" id="selectrange" placeholder="Select Range" name="range" class="form-control" value="" style="display:none;">
					<input type="text" id="selectspdates" placeholder="Select Specific Dates" name="spdate" class="form-control" value="" style="display:none;">
					</div>
				</div>
				
			</div>
			<div class="col-md-6">
			
				<div id="timesdiv">
					<!--<p style="display:none;" id="avldates">Available Dates</p>-->
					<p style="display:none;" id="avltimes">Available Times</p>
					<div id="mydates"></div>
					<div id="mytimes"></div>
				</div>
				<p>Notes to employer</p>
				<textarea id="notes" name="notes" placeholder="Notes" class="form-control notestextarea"></textarea>
				
			</div>
		</div>
		<div class="row">
		<div class="col-md-4">
		<a href="javascript:void(0);" id="accept_offer" class="jobapplybutn" style="display:none;">I am interested in this cover</a>
		</div>
		</div>
		<br>
		</div>
		</form>
		
		<span id="apply_suc" class="opengrn"></span>
      </div>
    </div>
  </div>
</div>

						</div>

						<div>

						

						<div class="viwthejobdetails">

						

							<div class="alljbdts">

								<h5>Job Details</h5>

								<p class="">

									<span class="pertiwidth"><strong>Employer:</strong></span>

									<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['name']);?></span>

								</p>

								<p class="">

									<span class="pertiwidth"><strong>Work Loation:</strong></span>

									<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['street_address']);?>, <?php echo stripslashes($fetch['suburb']);?>, <?php echo stripslashes($fetch['states']);?>, <?php echo stripslashes($fetch['location']);?>, <?php echo stripslashes($fetch['cname']);?></span>

								</p>

								<!--<p class="">

									<span class="pertiwidth"><strong>Postcode:</strong></span>

									<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['location']);?>, <?php echo stripslashes($fetch['cname']);?></span>

								</p>-->

								

								<?php

								if($fetch['qualification'] != ""){

								?>

								<p class="">

									<span class="pertiwidth"><strong>Qualification Required:</strong></span>

									<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['qualification']);?></span>

								</p>

								<?php

								}

								

								if($fetch['paytype'] == 1)

								$paytype = "Annual Salary";

								else if($fetch['paytype'] == 2)

								$paytype = "Hourly Rate";

								else if($fetch['paytype'] == 3)

								$paytype = "Commission";

								else if($fetch['paytype'] == 4)

								$paytype = "Annual and Commission";

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

								?>

								<?php

									if($fetch['covid19']){

									?>

								<p class="">

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

								<p class="">

									<span class="pertiwidth"><strong>Experience Required:</strong></span>

									<span class="viewthejobrighttxt"><?php echo $fetch['experience'];?> year(s) <?php echo $fetch['experience_month'];?> month(s) (<?php echo $exptype;?>)</span>

								</p>

								

								

							</div>

						

						</div>

						

						<div class="viwthejobdetails">

						

							<div class="alljbdts">

								<h5>Experience</h5>

								<?php

                                        if($fetch['custom_contract']){

                                            ?>

								<p>

									<!--<span class="pertiwidth"><strong>Contract:</strong></span>-->

									<span class="viewthejobrighttxt"><a href="<?php echo SITEURL."contract/".$fetch['custom_contract'];?>" target="_blank">View Details</a></span>

								</p>

								<?php

									}

									else{

										if($fetch['righttowork']){

											if($fetch['otherrightto'])

											$rightto = stripslashes($fetch['otherrightto']);

											else

											$rightto = "Must have the right to work in Australia";

								?>

								<p>

									<span class="pertiwidth"><strong>Right to Work:</strong></span>

									<span class="viewthejobrighttxt"><?php echo $rightto;?></span>

								</p>

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

								<p>

									<span class="pertiwidth"><strong>Working With Children:</strong></span>

									<span class="viewthejobrighttxt"><?php echo $workchild;?></span>

								</p>

								<?php

									}

									if($fetch['work_timeframe']){

										if($fetch['othertmfr'])

											$timefr = stripslashes($fetch['othertmfr']);

											else

											$timefr = "Work is solely casual and for this period only. There is no ongoing work.";

								?>

								<p>

									<span class="pertiwidth"><strong>Work Time Frame:</strong></span>

									<span class="viewthejobrighttxt"><?php echo $timefr;?></span>

								</p>

								<?php

									}
									
									if($fetch['trainingtype']){

										if($fetch['trainingtype'] == '1')
											$training_txt = "Full training provided";
										if($fetch['trainingtype'] == '2')
											$training_txt = "Some training provided";
										if($fetch['trainingtype'] == '3')
											$training_txt = "No training provided, candidate must be highly experienced";
								?>

								<p>

									<span class="pertiwidth"><strong>Training:</strong></span>

									<span class="viewthejobrighttxt"><?php echo $training_txt;?></span>

								</p>
								<?php
								if($fetch['expreq1']!='' || $fetch['expreq2']!='' || $fetch['expreq3']!=''){
								?>
								<p>

									<span class="pertiwidth"><strong>3 Things candidates must have experience in:</strong></span>

									<span class="viewthejobrighttxt"><?php echo stripslashes($fetch['expreq1']);?><?php 
									if($fetch['expreq2'])
									echo ", ".stripslashes($fetch['expreq2']);?><?php 
									if($fetch['expreq3'])
									echo ", ".stripslashes($fetch['expreq3']);?></span>

								</p>

								<?php
									}
									else if($fetch['noexpernce'] == 1){
										echo '<p>
									<span class="pertiwidth"><strong>Absolutely no experience in anything needed</strong></span></p>';
									}

									}
									
									$jobmedical = dbQuery($dbConn, "SELECT is_heavy_lifting,heavy_lifting,is_immunised,no_of_doses from job_medical_info where job_id = '".$id."'");
									if(dbNumRows($jobmedical) > 0){
									$getmedicalinfo = dbFetchArray($jobmedical);
									
								?>
								<p>

									<span class="pertiwidth"><strong>Is there heavy lifting or anything that candidates should know for health?</strong></span>

									<span class="viewthejobrighttxt">
									<?php ($getmedicalinfo['is_heavy_lifting']==1)?stripslashes($getmedicalinfo['heavy_lifting']):"No";?>
									</span>

								</p>
								<p>

									<span class="pertiwidth"><strong>Does the Candidate need to be covid immunised?</strong></span>

									<span class="viewthejobrighttxt">
									<?php ($getmedicalinfo['is_immunised']==1)?stripslashes($getmedicalinfo['no_of_doses']):"No";?>
									</span>

								</p>

								<?php
									}
									if($fetch['uniform']){

										if($fetch['otherunifm'])

										$uniform = stripslashes($fetch['otherunifm']);

										else

										$uniform = $fetch['uniform'];

								?>

								<p>

									<span class="pertiwidth"><strong>Uniform:</strong></span>

									<span class="viewthejobrighttxt"><?php echo $uniform;?></span>

								</p>

								<?php

									}

									if($fetch['lunchbrk']){

										if($fetch['otherlunch'])

											$otherlunch = stripslashes($fetch['otherlunch']);

											else

											$otherlunch = '';

										$mylunch = "There will be a ".$fetch['lunchbrk']." mins lunch break";

								?>

								<p>

									<span class="pertiwidth"><strong>Lunch Break:</strong></span>

									<span class="viewthejobrighttxt"><?php echo $mylunch.", ".$otherlunch;?></span>

								</p>

								<?php

									}

									if($fetch['otherinfo']){

								?>

								<p>

									<span class="pertiwidth"><strong>Other Information:</strong></span>

									<span class="viewthejobrighttxt"><?php echo $fetch['otherinfo'];?></span>

								</p>

								<?php

								}

							}

							?>

								

								

							</div>

						</div>

						<?php
						$checkUploads = dbQuery($dbConn, "SELECT * from job_documents where job_id = '".$id."'");
						if(dbNumRows($checkUploads) > 0){

							?>
						<div class="viwthejobdetails">

							<div class="alljbdts">

								<h5>Additional Details</h5>
								
								<p>
								<?php
								while($resUploads = dbFetchArray($checkUploads)){
								?>

									<span class="" style="margin-right:20px;"><a style="color:#4396BA;text-decoration:underline;" href="<?php echo SITEURL."contract/".$resUploads['jobupload'];?>" target="_blank"><?php echo $resUploads['jobupload'];?></a></span>
								<?php
								}
								?>
								</p>
								
								
							</div>
							</div>

						<?php
						}

						if(isset($_SESSION['loginUserId'])){

						

						$getuser = dbQuery($dbConn, "SELECT name,lname,email from users where id = '".$_SESSION['loginUserId']."'");

						$row = dbFetchArray($getuser);

						$getclock = dbQuery($dbConn, "SELECT clockouttime from staff_job_payment where job_id = '".$id."' and staff_id = '".$_SESSION['loginUserId']."'");

						$clock = dbFetchArray($getclock);

						if(isset($clock['clockouttime']) && $clock['clockouttime']!='0000-00-00 00:00:00'){



						}

						else{
							if($usertype == 2){

						?>

						<div class="viwthejobdetails">

							<div class="alljbdts">

								<div class="row">

									<div class="col-sm-12">

										<div class="onlyviwjobhelp">

											<h5>Message Employer</h5>

											<form action="" method="post" id="msgtoemp">

											<input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['loginUserId'];?>">

											<input type="hidden" id="jobid" name="jobid" value="<?php echo $id;?>">

												<p>For any queries related to the job, fill in the details below.</p>

												<div class="row">

													

													<div class="col-sm-12">

														<textarea placeholder="Message" name="msg" id="msg" class="form-control required"></textarea>

													</div>

													<div class="col-sm-12">
													
													<div class="row">
													<div class="col-sm-2 col-lg-2">
													<input type="button" class="backbtntosearch" value="Back" onclick="location.href='<?php echo SITEURL;?>searchcover?myaddress=<?php echo $myaddress;?>&mycatid=<?php echo $mycatid;?>&workmode=<?php echo $workmode;?>&from=<?php echo $from;?>&to=<?php echo $to;?>'">
													</div>
													<div class="col-sm-2 col-lg-2">
														<input type="submit" name="sub" id="sub_help" value="Submit">
													</div>
													</div>

													</div>

												</div>

												<p>

												<span id="helpsucces" style="display:none;text-transform:none;" class="pertiwidth">Your message has been sent.</span>

												</p>

											</form>

										</div>

									</div>

								</div>

							</div>

						</div>

						<?php
						}

						}

						}

						else{

						?>

						<div class="viwthejobdetails">

							<div class="alljbdts">

								<div class="row">

									<div class="col-sm-12">

										<div class="onlyviwjobhelp">

											<h5>Message Employer</h5>

											<form action="" method="post" id="msgtoemp">

											<input type="hidden" id="jobid" name="jobid" value="<?php echo $id;?>">

												<p>For any queries related to the job, fill in the details below.</p>

												<div class="row">

													<div class="col-sm-6">

														<input type="text" placeholder="Name" name="name" id="name" class="form-control required">

													</div>

													<div class="col-sm-6">

														<input type="email" placeholder="Email" name="email" id="email" class="form-control required">

													</div>

													<div class="col-sm-12">

														<textarea placeholder="Message" name="msg" id="msg" class="form-control required"></textarea>

													</div>

													<div class="col-sm-12">
													<div class="row">
													<div class="col-sm-2 col-lg-2">
													<input type="button" class="backbtntosearch" value="Back" onclick="location.href='<?php echo SITEURL;?>searchcover?myaddress=<?php echo $myaddress;?>&mycatid=<?php echo $mycatid;?>&workmode=<?php echo $workmode;?>&from=<?php echo $from;?>&to=<?php echo $to;?>'">
													</div>
													<div class="col-sm-2 col-lg-2">
														<input type="submit" name="sub" id="sub_help" value="Submit">
													</div>
													</div>
													
													</div>

												</div>

												<p>

													<span id="helpsucces" style="display:none;text-transform:none;" class="pertiwidth">Your message has been sent. Please check your mail for future communications with the employer.</span>

												</p>

											</form>

										</div>

									</div>

								</div>

							</div>

						</div>		

						<?php

						}

						?>
						

					</div>



				</div>

				

			</div>

		</div>

	</div>

	</div>

	<?php include_once('footer.php');?>