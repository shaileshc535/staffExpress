<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):"";
	if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'confirmjob'){
	echo "<script>location.href='".SITEURL."staff_login?job=".$id."&do=confirmjob'</script>";
	}
	else{
		echo "<script>location.href='".SITEURL."staff_login'</script>";
	}
	exit;
}

?>

	<div class="works my_job_sty works_success vwjbmainbk mainpadadjst">
		<div class="container">
			<div class="row">
            <?php 
					include_once "staff_left.php";
					?>
					<div class="col-lg-9">
					
					<div class="works_heading">
						<!--<h4 style="padding-bottom:20px;">My Jobs</h4>-->
						<?php
						$latest_confirmed_job_id = get_latest_confirmed_job($_SESSION['loginUserId'], $dbConn);
						$id = isset($_REQUEST['id'])?base64_decode(trim($_REQUEST['id'])):$latest_confirmed_job_id;
						if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'confirmjob'){
							$tab3_active = "active";
							$tab1_active = "";
							$tab3_content_active = "show active";
							$tab1_content_active = "";
						}
						else{
							$tab3_active = "";
							$tab1_active = "active";
							$tab3_content_active = "";
							$tab1_content_active = "show active";
						}
						$myjob = dbQuery($dbConn, "SELECT workmode FROM job_details where id='".$id."'");

						$fetch = dbFetchArray($myjob);
						?>
						<div class="sucespagtab">
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="margin-bottom:20px !important;">
							  <li class="nav-item" role="presentation">
								<button class="nav-link <?php echo $tab1_active;?>" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">My Jobs</button>
							  </li>
							  <li class="nav-item" role="presentation">
									<button class="nav-link <?php echo $tab3_active;?>" id="pills-confirm-tab" data-bs-toggle="pill" data-bs-target="#pills-confirm" type="button" role="tab" aria-controls="pills-confirm" aria-selected="false">Confirmation</button>
							  </li>
							  <li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-calendr-tab" data-bs-toggle="pill" data-bs-target="#pills-calendr" type="button" role="tab" aria-controls="pills-calendr" aria-selected="true">Calendar</button>
							  </li>
							  
							</ul>
							<div class="tab-content" id="pills-tabContent">
							  <div class="tab-pane fade <?php echo $tab1_content_active;?>" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
									
								<div class="table-responsive successtable">
								
									<table class="table" cellpadding="5" cellspacing="5">
										<tr>
											<th style="text-align:left;">Jobs</th>
											<th>Rate</th>
                                            <th>Hired</th>
											<th style="text-align:center;">Status</th>
										</tr>
										<?php
										$cond = '';
										
                                        $currtime = date('Y-m-d H:i:s');
										$jobs = dbQuery($dbConn, "SELECT a.id,a.title,a.street_address,a.postdate,a.payperhr,a.job_status,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.isclosed,a.paytype,a.payperhr_max,a.annual,a.commission_perctg,a.annualamt,a.commission_perctg_amt,a.add_time,a.covertype,b.hired from job_details a inner join job_status b on a.id=b.jobid where b.contacting=1 and b.application_sent_to = '".$_SESSION['loginUserId']."' order by b.id desc");
										if(dbNumRows($jobs) > 0){
										while($row = dbFetchArray($jobs)){
                                                if($row['hired'] == 1)
                                                $hired = "Yes";
                                                else if($row['hired'] == 2)
                                                $hired = "Rejected";
                                                else
                                                $hired = "No";

												$jobsrtdate = $row['jobdate'];
												$jobenddate = $row['jobdate2'];
												$strttime = $row['starttime'];
												$endtime = $row['endtime'];

												$jobstrttime = $jobsrtdate." ".$strttime;
												$jobendttime = $jobenddate." ".$endtime;

												$jobstrttime = strtotime($jobstrttime);
												$jobendttime = strtotime($jobendttime);

												if($row['paytype'] == 1)
												$paytype = "Annual Salary";
												else if($row['paytype'] == 2)
												$paytype = "Hourly Rate";
												else if($row['paytype'] == 3)
												$paytype = "Commission";
												else if($row['paytype'] == 4)
												$paytype = "Annual and Commission";
											
											if($row['isclosed'] == 0){
													$closed = 0;
											}
											else
												$closed = 1;
											?>
											<tr>
											<td <?php if($row['hired'] == 1) echo 'class="hiredjob"';?>><span class="frmfstname"><a href="<?php echo SITEURL;?>myjobview/<?php echo base64_encode($row['id']);?>"><?php echo stripslashes($row['title']);?></a></span><br><span class="comnmadres"><?php echo stripslashes($row['street_address']);?>
											<?php
											if($row['covertype']==1){
											?>
											<br>Start Date: <?php echo date('F j, Y', strtotime($row['jobdate']));?><br>End Date: <?php echo date('F j, Y', strtotime($row['jobdate2']));?>
											<?php } ?>
											</span></td>
											
											<td style="text-align:center;"><?php echo $paytype;?>:<br>
											<?php 
											if($row['paytype'] == 2){
											echo $row['payperhr'];?> AUD
											<?php
											}
											if($row['paytype'] == 1){
												?>
												<?php echo $row['annual'];?> AUD/Year
												<?php
											}
											if($row['paytype'] == 3){
												?>
												Commission: <?php echo $row['commission_perctg'];?>%
												<?php
											}
											if($row['paytype'] == 4){
												?>
												<?php echo $row['annualamt'];?> AUD/Year & Commission <?php echo $row['commission_perctg_amt'];?>%
												<?php
											}
											?>
											</td>
                                            <td style="text-align:center;"><?php echo $hired;?>
											<br>
											<?php
											if($row['hired'] == 0 && $closed == 0){
											?>
											<a href="javascript:void(0);" class="editavl" id="avl_<?php echo $row['id'];?>_<?php echo $_SESSION['loginUserId'];?>"><i class="fa fa-pencil" aria-hidden="true"></i> &nbsp;Edit Availability</a>
											<?php
											}
											?>
											</td>
											<td style="text-align:center;"><span class="">
											<?php 
											if($row['isclosed'] == 0){
												
												echo "<span class='txtopn'><i class='fa fa-circle' aria-hidden='true'></i> &nbsp;Open</span>";
												
											}
											else
											echo "<span class='txtclos'><i class='fa fa-circle' aria-hidden='true'></i> &nbsp;Closed</span>";
											?>
											</span></td>
											</tr>
											<?php
										}
										}
										else{
											?>
											<tr>
											<td colspan="4" style="text-align:center;">You have not applied for any job.</td>
											</tr>
											<?php
										}
										?>
									</table>
								</div>
							  
							  </div>
							  
							  <div class="modal fade" id="availability" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2" style="margin-bottom:0 !important;"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  
	  <form action="" method="post" id="avl_form">
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
			<div class="col-md-7">
				<div class="row">
					<div class="col-sm-5">
						<div id="dates" class="myjobspopavlbldts"><p>Available Dates</p><ul id="myavldates"></ul></div>
					</div>
					<div class="col-sm-7">
					<div class="row" id="jobdates">
					<div class="col-sm-12">
					<p>Change Dates</p>
					<input type="text" id="selectrange" placeholder="Select Range" name="range" class="form-control" value="" style="display:none;">
					<input type="text" id="selectspdates" placeholder="Select Specific Dates" name="spdate" class="form-control" value="" style="display:none;">
					</div>
				</div>
					</div>
				</div>
				
			</div>
			<div class="col-md-5">
			
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
		<a href="javascript:void(0);" id="edit_avlblty" class="jobapplybutn">Edit Availability for this Job</a>
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


							  <div class="tab-pane fade" id="pills-calendr" role="tabpanel" aria-labelledby="pills-calendr-tab">
								<div class="table-responsive successtable" style="position:relative;">
								<?php
								$currmonth = date('m');
								$curryear = date('Y');
								$month = ($_GET['month']!="")?$_GET['month']:$currmonth;
                        		$year = ($_GET['year']!="")?$_GET['year']:$curryear;

								$d= 2; // To Finds today's date
								$no_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);//calculate number of days in a month
								$j= date('w',mktime(0,0,0,$month,1,$year)); // This will calculate the week day of the first day of the month
								
								$adj = str_repeat("<td>&nbsp;</td>",$j);  // Blank starting cells of the calendar 
								$blank_at_end = 42-$j-$no_of_days; // Days left after the last day of the month
								if($blank_at_end >= 7){$blank_at_end = $blank_at_end - 7 ;}

								$adj2 = str_repeat("<td>&nbsp;</td>",$blank_at_end); // Blank ending cells of the calendar
								?>
                                <div style="text-align:center;font-weight:bold;padding: 10px 0 20px;font-size: 20px;" id="current2"><?php echo date("F", mktime(0, 0, 0, $month, 10));?> <?php echo $curryear;?> </div>
                                
                                <div class="loadwrap" style="position:absolute;width:96%;top: 23px;left: 48%;"><span id="load" style="display:none;"><img src="<?php echo SITEURL;?>images/spinner_load.gif" alt="" /></span></div>
								
                                <div style="" class="prevbtn">
									<a href="javascript:void(0);" id="prev_<?php echo $month;?>_<?php echo $year;?>" class="prev"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
								</div>
								<div style="position:absolute;right:20px;top:25px;" class="nxtbtn">
									<a href="javascript:void(0);" id="next_<?php echo $month;?>_<?php echo $year;?>" class="next"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
								</div>
								 
								 
                        	<table class="table table-responsive calndrstaff">
                            <thead>
                                <tr>
                                <th class="text-center" scope="col">Sun</th>
                                <th class="text-center" scope="col">Mon</th>
                                <th class="text-center" scope="col">Tue</th>
                                <th class="text-center" scope="col">Wed</th>
                                <th class="text-center" scope="col">Thu</th>
                                <th class="text-center" scope="col">Fri</th>
                                <th class="text-center" scope="col">Sat</th>
                                </tr>
                            </thead>
                            <?php
                            $today = date('Y-m-d');
                            $tdbackcls = "";
                            $myevents = '';
							
                            ?>
                            <tbody id="caldates2">
                            <?php
                            $rpt_event = array();
                            $mytitles = '';
							$mytitles2 = '';
                            $mydates = '';
                            $myrptevnts = '';
                            for($i=1; $i<=$no_of_days; $i++){
                            
                                if($i < 10)
                                $i = '0'.$i;
                                else
                                $i = $i;
                                $pv = $year."-".$month."-".$i;
                                
                                if($i == date('d') && $month == date('m') && $year==date('Y')){
                                    $tdbackcls = "style='background:#e4f8ee;'";
                                    $h6color = "style='font-weight:bold;'";
                                    $todaytxt = "";
                                }
                                else{
                                    $h6color = "";
                                    $todaytxt = "";
                                    $tdbackcls = "";
                                }
    
                                $mydate = $year."-".$month."-".$i;
                                
                                    /*foreach ($rpt_event as $event) {
                                        for ($d = 0; $d <= ($event[2]-1); $d++) {
                                            if (date('y-m-d', strtotime($year . '-' . $month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
                                                $mytitles .= "<a href='javascript:void(0);' class='myevent' id='id_".$event[3]."'>
                                                    <h6>".$event[0]."</h6>
                                                </a>
                                                <br/><br/>";
                                            }
                                        }
                                    }*/
									
										
										$avldays = dbQuery($dbConn, "SELECT a.*,b.apply_type,b.hired,b.avlblty,c.title,c.id as jobid,c.jobdate,c.jobdate2 FROM staff_availability a inner join job_status b on a.jobid=b.jobid inner join job_details c on a.jobid=c.id where a.staff_id = '".$_SESSION['loginUserId']."' and availble_date = '".$mydate."'");
										$fetchdays = dbFetchArray($avldays);
										//if($fetchdays['avlblty'] == 2){
											//if($fetchdays['apply_type'] == 2){
												$avldays2 = dbQuery($dbConn, "SELECT b.hired,c.title,c.id FROM staff_availability a inner join job_status b on a.jobid=b.jobid inner join job_details c on a.jobid=c.id where a.staff_id = '".$_SESSION['loginUserId']."' and availble_date = '".$mydate."' group by a.jobid");
												while($fetchdays2 = dbFetchArray($avldays2)){
													if($fetchdays2['hired'] ==1){
														if(strlen($fetchdays2['title']) > 10)
															$myjobtitle = substr(stripslashes($fetchdays2['title']), 0, 10)."...";
														else
															$myjobtitle = stripslashes($fetchdays2['title']);
													$mytitles .= "<span class='grnback'><a href=".SITEURL."myjobview/".base64_encode($fetchdays2['id']).">".$myjobtitle."</a></span><br>";
													}
													/*else{
														$mytitles .= "<span class='orgback'>".$fetchdays2['title']."</span><br>";
													}*/
												}
											//}
											
										/*}
										else{
											if($fetchdays['jobdate2']!=""){
                                            
                                            $mystartdate = $fetchdays['jobdate'];
                                            
                                            $myenddate = $fetchdays['jobdate2'];
                                            $date1 = new DateTime($mystartdate);
                                            $date2 = new DateTime($myenddate);
                                            $days = $date1->diff($date2)->format("%a");
											}
											else{
												$days = 0;
											}
										$days = $days + 1;
                                        $rpt_event[] = [stripslashes($fetchdays['title']), $mystartdate, $days, $fetchdays['hired'], $fetchdays['jobid']];
										
										
											foreach ($rpt_event as $event) {
												for ($d = 0; $d <= ($event[2]-1); $d++) {
													if (date('y-m-d', strtotime($year . '-' . $month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
														if($event[3] == 1){
															if(strlen($event[0]) > 10)
																$myjobtitle = substr($event[0], 0, 10)."...";
															else
																$myjobtitle = $event[0];
															$mytitles2 = "<span class='grnback'><a href=".SITEURL."myjobview/".base64_encode($event[4]).">".$myjobtitle."</a></span><br>";
														}
														//else{
															//$mytitles2 = "<span class='orgback'>".$event[0]."</span><br>";
															
														//}
													}
													}
											}
											
											
										}*/
                                    echo $adj."<td ".$tdbackcls." id='td_".$pv."'>".$i."<br/>".$mytitles.$mytitles2."</td>";
    
                                    $adj='';
                                    $j++;
                                    if($j==7){  
                                        echo "</tr><tr>"; // start a new row
                                        $j=0;
                                    }
                                    
                                    $mytitles = '';
									$mytitles2 = '';
                                    $myrptevnts = '';
                                    $rpt_event = array();
                                }
                            ?>
                            </tbody>
                        	</table>
								</div>
							  </div>
							  
							  <div class="tab-pane fade <?php echo $tab3_content_active;?>" id="pills-confirm" role="tabpanel" aria-labelledby="pills-confirm-tab">
								<div class="table-responsive successtable">									
									<div class="newcnfrmjob">
										<!--<p>Please use this page to confirm all details to be sent to candidates once finalized</p>
										<ul>
											<li>Send confirmation of date, time, location & details.</li>
											<li>Get access to staff superannuation, tax file and bank details.</li>
											<li>Staff clock In/Out feature to track their time on day of work.</li>
										</ul>-->
										<div class="myalljobs">									
											<?php
											$alljobs = dbQuery($dbConn, "SELECT c.title,c.id from job_status b inner join job_details c on b.jobid=c.id where b.application_sent_to = '".$_SESSION['loginUserId']."' and b.contacting=1 and b.confirmation_sent=1 and b.emp_option_sent=1 order by b.id desc");
											?>
											<label>My Jobs</label>
											<select name="myjobs" id="myjobs_staff" class="form-select">
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
										<div class="jobopnnew staffconfjob">
											<div class="table-responsive">
												<table class="table">
													<tr>
														<td><strong>Work Address:</strong></td>
														<td><?php echo stripslashes($fetch['street_address']);?></td>
													</tr>
													<tr>
														<td><strong>Dates:</strong></td>
														<td><?php echo date('M j, Y', strtotime($fetch['jobdate']));?>
														<?php
														if($fetch['add_time'] == 1){
														?>
														at <?php echo date('h:i A', strtotime($fetch['starttime']));
														}
														?>

														- <?php echo date('M j, Y', strtotime($fetch['jobdate2']));?>
														<?php
														if($fetch['add_time'] == 1){
														?>
														at <?php echo date('h:i A', strtotime($fetch['endtime']));
														}
														?></td>
													</tr>
													
													<tr>
														<td><strong>Contract:</strong></td>
														<td>
														<?php
														if($fetch['custom_contract']){
														?>
														<a href="<?php echo SITEURL."contract/".$fetch['custom_contract'];?>" target="_blank">View contract</a></td>
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
									<span class="pertiwidth">Right to Work:</span>

									<span class="viewthejobrighttxt" style="width:75%;"><?php echo $rightto;?></span>
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
									<span class="pertiwidth">Working With Children:</span>

									<span class="viewthejobrighttxt" style="width:75%;"><?php echo $workchild;?></span>
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
									<span class="pertiwidth">Work Time Frame:</span>

									<span class="viewthejobrighttxt" style="width:75%;"><?php echo $timefr;?></span>
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
									<span class="pertiwidth">Uniform:</span>

									<span class="viewthejobrighttxt" style="width:75%;"><?php echo $uniform;?></span>
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
									<span class="pertiwidth">Lunch Break:</span>

									<span class="viewthejobrighttxt" style="width:75%;"><?php echo $mylunch.", ".$otherlunch;?></span>

</p>
								<?php

									}

									if($fetch['otherinfo']){

								?>

<p>
									<span class="pertiwidth">Other Information:</span>

									<span class="viewthejobrighttxt" style="width:75%;"><?php echo $fetch['otherinfo'];?></span>
</p>

								<?php

								}
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
									$jobs = dbQuery($dbConn, "SELECT a.id as userid,a.name,c.title,c.jobdate,c.jobdate2,c.starttime,c.endtime,d.hired,d.emp_option_sent,d.employer_note from users a inner join job_details c on a.id=c.employer_id inner join job_status d on d.jobid=c.id where d.jobid = '".$id."' and d.application_sent_to = '".$_SESSION['loginUserId']."' and d.contacting=1 and d.confirmation_sent=1 and d.emp_option_sent=1");

									if(dbNumRows($jobs) > 0 && $fetch['workmode'] == 2){
										?>
									<div class="newcnfrmjob">
										<div class="canddtsvw">
											<div class="table-responsive">
												<table class="table">
													<tr>
														<td width="25%">Date</td>
														<td width="30%">Timing</td>
														<td width="25%">Employer</td>
														<td width="20%" style="text-align:center;">Confirmed</td>
													</tr>
													<?php
													while($myselctjobs = dbFetchArray($jobs)){
														/*$confrmtn = dbQuery($dbConn, "SELECT confirm_date FROM staff_confirmation WHERE jobid = '".$id."' and staff_id = '".$_SESSION['loginUserId']."'");
														while($row_confrmtn = dbFetchArray($confrmtn)){
															$confrm_dates[] = $row_confrmtn['confirm_date'];
														}*/
														
													?>
													<tr>
														<td>
														
														<input type="text" name="" class="timinp" readonly placeholder="Select Date" style="width:100%;" value="<?php echo date('d/m/Y', strtotime($fetch['jobdate']));?> - <?php echo date('d/m/Y', strtotime($fetch['jobdate2']));?>" />
														
														
														</td>
														<td>
														<?php
														if($fetch['add_time'] == 1){
														?>
														<?php echo date('h:i A', strtotime($fetch['starttime']));?>
														to 
														<?php echo date('h:i A', strtotime($fetch['endtime']));?>
														<?php
														}
														else
														echo "-";
														?>
														</td>
														<td>
															<?php echo $myselctjobs['name'];?>
															<?php
															if($myselctjobs['employer_note']){
																?>
																<br>
																Note: <?php echo stripslashes($myselctjobs['employer_note']);?>
																<?php
															}
															?>
														</td>
														<td>
														
															<!--<div class="intrsttab">-->
															<?php
															if($myselctjobs['hired'] == 1){
															?>
																<a href="javascript:void(0);" class="sndcnfmsn onlyicnmyjb accepted" style="margin:0;"><i class="fa fa-check" aria-hidden="true"></i></a>
																<a href="<?php echo SITEURL;?>staff_messages?id=<?php echo $id;?>&action=sendjobmsg" id="msg_<?php echo $id;?>_<?php echo $_SESSION['loginUserId'];?>" class="sndcnfmsn cofrmreject onlyicnmyjb" title="Send Message"><i class="fa fa-envelope" aria-hidden="true"></i></a>
																<?php
															}
															//if($myselctjobs['hired'] == 2){
															?>
																<!--<a href="javascript:void(0);" class="sndcnfmsn" style="margin:0;">Declined</a>-->
																<?php
															//}
															if($myselctjobs['hired'] == 0 && $myselctjobs['emp_option_sent'] == 1){
															?>
																<a href="javascript:void(0);" id="accept_<?php echo $id;?>_<?php echo $_SESSION['loginUserId'];?>" class="sndcnfmsn cofrmaccept onlyicnmyjb" title="Accept"><i class="fa fa-check" aria-hidden="true"></i></a><!--<a href="javascript:void(0);" id="amend_<?php echo $id;?>_<?php echo $_SESSION['loginUserId'];?>" class="sndcnfmsn cofrmaccept onlyicnmyjb" title="Amend"><i class="fa fa-hourglass-half" aria-hidden="true"></i></a>--><a href="<?php echo SITEURL;?>staff_messages?id=<?php echo $id;?>&action=sendjobmsg" id="msg_<?php echo $id;?>_<?php echo $_SESSION['loginUserId'];?>" class="sndcnfmsn cofrmreject onlyicnmyjb" title="Send Message"><i class="fa fa-envelope" aria-hidden="true"></i></a>
																<?php
															}
															?>
															<!--</div>
															-->
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
							  
							  
							  </div>
							</div>

							
						</div>
						
					</div>

				</div>
				
			</div>
		</div>
	</div>
	
	<?php include_once('footer.php');?>