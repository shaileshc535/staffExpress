<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

$category = isset($_GET['category'])?trim($_GET['category']):"";
$postcode = isset($_GET['postcode'])?trim($_GET['postcode']):"";
$working_days = isset($_GET['working_days'])?trim($_GET['working_days']):"";

$cat = dbQuery($dbConn, "SELECT category from category where id = '".$category."'");
$row = dbFetchArray($cat);
?>

	<div class="works works_my_job_search">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="works_heading">
						<h6>Category: <?php echo stripslashes($row['category']);?></h6>
						<div class="sucespagtab">
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							  <li class="nav-item" role="presentation">
								<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Jobs</button>
							  </li>
							  
							  
							</ul>
							<div class="tab-content" id="pills-tabContent">
							  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
									
								<div class="table-responsive successtable">
								
									<table class="table" cellpadding="5" cellspacing="5">
										<tr>
											<th>Job</th>
											<th>Rate</th>
											<th style="text-align:center;">Status</th>
										</tr>
										<?php
										$cond = '';
										if($category){
											$cond .= "and catid = '".$category."'";
										}
                                        if($postcode){
											$cond .= "and location = '".$postcode."'";
										}
                                        if($working_days){
                                            if($working_days == 1)
                                            $cond .= '';
                                            else if($working_days == 2){
                                                
                                                $cond .= "and DAYOFWEEK(jobdate) != '1' and DAYOFWEEK(jobdate) != '7' and DAYOFWEEK(jobdate2) != '1' and DAYOFWEEK(jobdate2) != '7' and DAYOFWEEK(jobdate) <= DAYOFWEEK(jobdate2)";
                                            }
                                            else if($working_days == 3){
                                                
                                                $cond .= "and (DAYOFWEEK(jobdate) = 1 or DAYOFWEEK(jobdate) = 7) and (DAYOFWEEK(jobdate2) = 1 or DAYOFWEEK(jobdate2) = 7)";
                                            }
                                            else if($working_days == 4){
                                                $cond .= "and DAYOFWEEK(jobdate) = '2' and DAYOFWEEK(jobdate2) = '2'";
                                            }
                                            else if($working_days == 5){
                                                $cond .= "and DAYOFWEEK(jobdate) = '3' and DAYOFWEEK(jobdate2) = '3'";
                                            }
                                            else if($working_days == 6){
                                                $cond .= "and DAYOFWEEK(jobdate) = '4' and DAYOFWEEK(jobdate2) = '4'";
                                            }
                                            else if($working_days == 7){
                                                $cond .= "and DAYOFWEEK(jobdate) = '5' and DAYOFWEEK(jobdate2) = '5'";
                                            }
                                            else if($working_days == 8){
                                                $cond .= "and DAYOFWEEK(jobdate) = '6' and DAYOFWEEK(jobdate2) = '6'";
                                            }
                                        }
                                        $today = date('Y-m-d H:i:s');
										$jobs = dbQuery($dbConn, "SELECT id,title,street_address,postdate,payperhr,job_status,jobdate,jobdate2 from job_details where 1 ".$cond." and jobdate2 > '".$today."' order by id desc");
										if(dbNumRows($jobs) > 0){
										while($row = dbFetchArray($jobs)){
											
											$no_hired = dbNumRows($josbstat2);
											?>
											<tr>
											<td><span class="frmfstname"><a href="<?php echo SITEURL;?>viewmyjob/<?php echo base64_encode($row['id']);?>"><?php echo stripslashes($row['title']);?></a></span><br><span class="comnmadres"><?php echo stripslashes($row['street_address']);?><br>Created: <?php echo date('F j, Y', strtotime($row['postdate']));?><br>End Date: <?php echo date('F j, Y', strtotime($row['jobdate2']));?></span></td>
											
											<td><?php echo stripslashes($row['payperhr']);?>/Hour AUD</td>
											<td style="text-align:center;"><span class="openstatus"><?php echo ($row['jobdate2'] > date('Y-m-d'))?'Open':'Closed';?></span></td>
											</tr>
											<?php
										}
										}
										else{
											?>
											<tr>
											<td colspan="4" style="text-align:center;">No jobs found as per your criteria.</td>
											</tr>
											<?php
										}
										?>
									</table>
								</div>
							  
							  </div>
							  
							  
							  <!-- </div> -->
							</div>

							
						</div>
						
					</div>
				</div>
				<div class="col-lg-6">
					<img src="./images/wk.png" class="img-fluid" alt="">
				</div>
			</div>
		</div>
	</div>
	
	<?php include_once('footer.php');?>