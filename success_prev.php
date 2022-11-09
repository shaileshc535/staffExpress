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

	<div class="works works_success">
		<div class="container">
			<div class="row">
			<?php include_once "employer_left.php";?>
				<div class="col-lg-9">
					<div class="works_heading">
						<div class="works_success_column" style="display: flex;justify-content: right;margin-bottom:30px;padding:0px;background-color:transparent;">
							<!--<h6>StaffExpress is working on your job and expect notifications very shortly on your email/SMS. Please keep an eye out for positions soon.</h6>-->
							<ul class="homebtn" style="margin:20px 0;text-align:right;">
								<li style="padding-right:0;"><a href="<?php echo SITEURL;?>jobpost1" style="padding:0.4rem 1.5rem;">Post a Job</a></li>
							</ul>
						</div>
						<div class="sucespagtab">
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
							  <li class="nav-item" role="presentation">
								<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Jobs</button>
							  </li>
							  <!--<li class="nav-item" role="presentation">
								<button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Candidates</button>
							  </li>-->
							  
							</ul>
							<div class="tab-content" id="pills-tabContent">
							  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
									
								<div class="table-responsive successtable">
								
									<div class="row height d-flex">

									  <div class="col-md-6 m-auto">

										<div class="search">
										  <i class="fa fa-search"></i>
										  <form action="" method="get">
										  <input type="text" name="title" class="form-control" placeholder="Search job title..." value="<?php echo $_GET['title'];?>">
										  <button class="btn btn-primary" type="submit">Search</button>
										  </form>
										</div>
										
									  </div>
									  
									</div>
								
									<table class="table" cellpadding="5" cellspacing="5">
										<tr>
											<th>Job</th>
											<th>Stats</th>
											<th>Rate</th>
											<th style="text-align:center;">Status</th>
										</tr>
										<?php
										$cond = '';
										$currtime = date('Y-m-d H:i:s');
										if(isset($_GET['title']) && $_GET['title'] != ""){
											$cond .= "and title like '%".tep_db_input($_GET['title'])."%'";
										}

										$jobs = dbQuery($dbConn, "SELECT id,title,street_address,postdate,payperhr,job_status,jobdate,jobdate2,starttime,endtime,isclosed,paytype,payperhr_max from job_details where employer_id = '".$_SESSION['loginUserId']."' ".$cond." order by id desc");
										if(dbNumRows($jobs) > 0){
										while($row = dbFetchArray($jobs)){
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
											$paytype = "Annual and Commission";

											$josbstat = dbQuery($dbConn, "SELECT id	from job_status where jobid = '".$row['id']."' and contacting=1");
											$no_contacting = dbNumRows($josbstat);
											$josbstat2 = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$row['id']."' and hired=1");
											$no_hired = dbNumRows($josbstat2);
											?>
											<tr>
											<td><span class="frmfstname"><a href="<?php echo SITEURL;?>viewjob/<?php echo stripslashes($row['id']);?>"><?php echo stripslashes($row['title']);?></a></span><br><span class="comnmadres"><?php echo stripslashes($row['street_address']);?><br>Created: <?php echo date('F j, Y', strtotime($row['postdate']));?></span></td>
											<td><div style="float:left;margin-right:15px;text-align:center;"><?php echo $no_contacting;?><br>Contacting</div>
											<div style="text-align:center;"><?php echo $no_hired;?><br>Hired</div>
											</td>
											<td style="text-align:center;"><?php echo $paytype;?> <?php echo $row['payperhr'];?> AUD - <?php echo $row['payperhr_max'];?> AUD</td>
											<td style="text-align:center;"><span class="openstatus"><?php 
											if($row['isclosed'] == 0){
												if($jobendttime > strtotime($currtime))
												echo "Open";
												else
												echo "Closed";
											}
											else
											echo "Closed";
											?></span></td>
											</tr>
											<?php
										}
										}
										else{
											?>
											<tr>
											<td colspan="4" style="text-align:center;">No jobs posted.</td>
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
										<tr>
											<th style="text-align:left;">Candidate</th>
											<th style="text-align:left;">Job</th>
											<th>Qualification</th>
											<th style="text-align:center;">Status</th>
										</tr>
										<?php
										$staff_quals = array();
										$jobs = dbQuery($dbConn, "SELECT a.id as userid,a.name,b.hired,b.application_sent_to,c.title,c.id from users a left join job_status b on a.id=b.application_sent_to inner join job_details c on b.jobid=c.id where c.employer_id = '".$_SESSION['loginUserId']."' and b.contacting=1 order by b.id desc");
										if(dbNumRows($jobs) > 0){
										while($row = dbFetchArray($jobs)){
											if($row['hired'] == 1)
											$hired = "Hired";
											else
											$hired = "Not Hired";

											$myqual = dbQuery($dbConn, "SELECT qualifictn from qualifications a inner join staff_qualification b on a.id=b.qualification where b.staff_id = '".$row['application_sent_to']."'");
											while($qualfetch = dbFetchArray($myqual)){
												$staff_quals[] = $qualfetch['qualifictn'];
											}
											if(count($staff_quals) > 0){
												$staff_quals = implode(", ", $staff_quals);
											}
											?>
											<tr>
											<td><span class="frmfstname"><a href="<?php echo SITEURL;?>managehire/?id=<?php echo base64_encode(stripslashes($row['id']));?>&userid=<?php echo base64_encode(stripslashes($row['userid']));?>"><?php echo stripslashes($row['name']);?></a></span></td>
											<td>
											<?php echo stripslashes($row['title']);?>
											</td>
											<td style="text-align:center;"><?php echo $staff_quals;?></td>
											<td style="text-align:center;"><span class="openstatus"><?php echo $hired;?></span></td>
											</tr>
											<?php
											$staff_quals = array();
										}
										}
										else{
											?>
											<tr>
											<td colspan="4" style="text-align:center;">No candidates so far.</td>
											</tr>
											<?php
										}
										?>
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