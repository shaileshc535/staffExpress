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
							<li><a href="<?php echo SITEURL."myjobs";?>">Jobs</a></li>
							<li><a href="<?php echo SITEURL;?>viewjob/<?php echo $latest_job_id;?>?action=contacting">Candidates</a></li>
							<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>
							<li><a href="<?php echo SITEURL;?>emp_confirmation/<?php echo $latest_job_id;?>">Confirmation</a></li>
							<li <?php if($page == "calendar.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_calendar";?>">Calender View</a></li>
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

						<!--<h4 style="padding-bottom:20px;">My Jobs</h4>-->

						<?php

						if(isset($_POST['jobupdated']) && $_POST['jobupdated']==1){

							?>

							<div class='alert-success' style='padding:15px;margin-bottom:15px;'>Job has been updated.</div>

							<?php

						}

						?>

						<div class="sucespagtab">

							<!--<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

							  <li class="nav-item" role="presentation">

								<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">My Jobs</button>

							  </li>

							</ul>-->

							<div class="tab-content" id="pills-tabContent">

									

							  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

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
                                <div style="text-align:center;font-weight:bold;padding: 10px 0 20px;font-size: 20px;"  id="current"><?php echo date("F", mktime(0, 0, 0, $month, 10));?> <?php echo $curryear;?> </div>
                                
                                <div class="loadwrap" style="position:absolute;width:96%;top: 23px;left: 48%;"><span id="load" style="display:none;"><img src="<?php echo SITEURL;?>images/spinner_load.gif" alt="" /></span></div>
								
                                <!--div style="position:absolute;right:20px;top:25px;" class="nxtprev"><a href="javascript:void(0);" id="prev_<?php echo $month;?>_<?php echo $year;?>" class="prev2"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
                                <a href="javascript:void(0);" id="next_<?php echo $month;?>_<?php echo $year;?>" class="next2">
								 <i class="fa fa-chevron-right" aria-hidden="true"></i></a></div>-->
								 
								 <div style="" class="prevbtn">
									<a href="javascript:void(0);" id="prev_<?php echo $month;?>_<?php echo $year;?>" class="prev2"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
								</div>
								<div class="nxtbtn">
									<a href="javascript:void(0);" id="next_<?php echo $month;?>_<?php echo $year;?>" class="next2"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
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
                            <tbody id="caldates">
                            <?php
                            $rpt_event = array();
							$rpt_event2 = array();
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
                                
                                   
										
										$avldays = dbQuery($dbConn, "SELECT a.*,b.apply_type,b.hired,b.avlblty,c.title,c.id as jobid,c.jobdate,c.jobdate2 FROM staff_availability a inner join job_status b on a.jobid=b.jobid inner join job_details c on a.jobid=c.id where c.employer_id = '".$_SESSION['loginUserId']."' and availble_date = '".$mydate."'");
										$fetchdays = dbFetchArray($avldays);
										//if($fetchdays['avlblty'] == 2){
											//if($fetchdays['apply_type'] == 2){
												$avldays2 = dbQuery($dbConn, "SELECT b.hired,c.title,c.id FROM staff_availability a inner join job_status b on a.jobid=b.jobid inner join job_details c on a.jobid=c.id where c.employer_id = '".$_SESSION['loginUserId']."' and availble_date = '".$mydate."' group by a.jobid");
												while($fetchdays2 = dbFetchArray($avldays2)){
													if($fetchdays2['hired'] ==1){
														if(strlen($fetchdays2['title']) > 15)
															$myjobtitle = substr(stripslashes($fetchdays2['title']), 0, 15)."...";
														else
															$myjobtitle = stripslashes($fetchdays2['title']);
														$mytitles .= "<span class='grnback'><a href=".SITEURL."viewjob/".$fetchdays2['id']."?action=contacting>".$myjobtitle."</a></span><br>";
													}
													/*else{
														$mytitles .= "<span class='orgback'>".$fetchdays2['title']."</span><br>";
													}*/
												}
											//}
											/*else{
												$rangestart = dbQuery($dbConn, "SELECT a.availble_date,a.jobid FROM staff_availability a inner join job_details c on a.jobid=c.id where c.employer_id = '".$_SESSION['loginUserId']."' and a.availble_date = '".$mydate."' order by a.id limit 0,1");
												$recstart = dbFetchArray($rangestart);
												$startdate = $recstart['availble_date'];
												
												$rangeend = dbQuery($dbConn, "SELECT a.availble_date FROM staff_availability a inner join job_details c on a.jobid=c.id where c.employer_id = '".$_SESSION['loginUserId']."' and a.jobid = '".$recstart['jobid']."' order by a.id desc limit 0,1");
												$recend = dbFetchArray($rangeend);
												$enddate = $recend['availble_date'];
												
												$date1 = new DateTime($startdate);
												$date2 = new DateTime($enddate);
												$days = $date1->diff($date2)->format("%a");
												
												$days = $days + 1;
												$rpt_event[] = [stripslashes($fetchdays['title']), $startdate, $days, $fetchdays['hired'],  $fetchdays['jobid']];
										
										
											foreach ($rpt_event as $event) {
												for ($d = 0; $d <= ($event[2]-1); $d++) {
													if (date('y-m-d', strtotime($year . '-' . $month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
														if($event[3] == 1){
															if(strlen($event[0]) > 15)
																$myjobtitle = substr($event[0], 0, 15)."...";
															else
																$myjobtitle = $event[0];
															$mytitles2 .= "<span class='grnback'><a href=".SITEURL."viewjob/".$event[4]."?action=contacting>".$myjobtitle."</a></span><br>";
														}
														
													}
													}
											}
												
											}*/
										//}
										/*else{
											if($fetchdays['jobdate2']!="" && $fetchdays['jobdate2'] > $fetchdays['jobdate']){
                                            
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
                                        $rpt_event[] = [stripslashes($fetchdays['title']), $mystartdate, $days, $fetchdays['hired'],  $fetchdays['jobid']];
										
										
											foreach ($rpt_event as $event) {
												for ($d = 0; $d <= ($event[2]-1); $d++) {
													if (date('y-m-d', strtotime($year . '-' . $month . '-' . $i . ' -' . $d . ' day')) == date('y-m-d', strtotime($event[1]))) {
														if($event[3] == 1){
															if(strlen($event[0]) > 15)
																$myjobtitle = substr($event[0], 0, 15)."...";
															else
																$myjobtitle = $event[0];
															$mytitles2 .= "<span class='grnback'><a href=".SITEURL."viewjob/".$event[4]."?action=contacting>".$myjobtitle."</a></span><br>";
														}
														//else{
															//$mytitles2 = "<span class='orgback'>".$event[0]."</span><br>";
															
														//}
													}
													}
											}
											
											
										}*/
									//}
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
                                    //$rpt_event = array();
                                }
                            ?>
                            </tbody>
                        	</table>
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