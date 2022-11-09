<?php

include('config/config.php'); 

include_once "config/common.php";

$dbConn = establishcon();

if(!isset($_SESSION['loginUserId'])){

	echo "<script>location.href='".SITEURL."employer_login'</script>";

	exit;

}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == "jobdelete"){
	$id = isset($_REQUEST['id'])?tep_db_input($_REQUEST['id']):"";
	
	dbQuery($dbConn,"delete from qualifictn_required where jobid = '".$id."'");
	dbQuery($dbConn,"delete from job_cat where jobid = '".$id."'");
	dbQuery($dbConn,"delete from job_parent_cat where jobid = '".$id."'");
	dbQuery($dbConn, "DELETE FROM job_details WHERE id = '".$id."'");
	
	echo "<script>location.href='".SITEURL."myjobs'</script>";
	exit;
}

include_once('header.php');
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

											<th style="text-align:left;">Posted Jobs</th>

											<th style="text-align:left;">Applicants</th>

											<th style="text-align:left;">Hired</th>

											<th style="text-align:center;">Queries</th>

											<th style="text-align:center;">Messages</th>

											<th style="text-align:center;">Status</th>

											<th style="text-align:center;">Action</th>

										</tr>

										<?php

										$cond = '';

										$currtime = date('Y-m-d H:i:s');
										
										$records_per_page = 10;
										$page = isset($_GET['page'])?trim($_GET['page']):1;
										$start = ($page - 1) * $records_per_page;
										

										if(isset($_GET['title']) && $_GET['title'] != ""){
											$cond .= "and title like '%".tep_db_input($_GET['title'])."%'";
										}
										
										$mytotal_cover = $jobs = dbQuery($dbConn, "SELECT id from job_details where employer_id = '".$_SESSION['loginUserId']."' ".$cond." and postcomplete=1 order by id desc");
										$total_cover = dbNumRows($mytotal_cover);


										$jobs = dbQuery($dbConn, "SELECT id,title,street_address,postdate,payperhr,job_status,jobdate,jobdate2,starttime,endtime,isclosed,paytype,payperhr_max,annual,commission_perctg,annualamt,commission_perctg_amt from job_details where employer_id = '".$_SESSION['loginUserId']."' ".$cond." and postcomplete=1 order by id desc limit ".$start.",".$records_per_page."");

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

											$paytype = "Commission";

											else if($row['paytype'] == 4)

											$paytype = "Annual and Commission";



											$josbstat = dbQuery($dbConn, "SELECT id	from job_status where jobid = '".$row['id']."' and contacting=1");

											$no_contacting = dbNumRows($josbstat);

											$josbstat2 = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$row['id']."' and hired=1");

											$no_hired = dbNumRows($josbstat2);

											$initl_msg = dbQuery($dbConn, "SELECT id from initial_messages where receiverid = '".$_SESSION['loginUserId']."' and jobid='".$row['id']."'");

											$num_initl_msg = dbNumRows($initl_msg);

											$unread = dbQuery($dbConn, "SELECT id from messages where receiverid = '".$_SESSION['loginUserId']."' and jobid='".$row['id']."' and isread=0");

											$unread_count = dbNumRows($unread);

											?>

											<tr>

											<td <?php if($no_hired > 0) echo 'class="hiredjob"';?>><span class="frmfstname"><a href="<?php echo SITEURL;?>viewjob/<?php echo stripslashes($row['id']);?>"><?php echo stripslashes($row['title']);?></a></span><br><span class="comnmadres"><?php echo stripslashes($row['street_address']);?><br>Created: <?php echo date('F j, Y', strtotime($row['postdate']));?></span></td>

											<td style="text-align:left;"><div style="text-align:left;">
											<?php
											if($no_contacting > 0){
											?>
											<a href="<?php echo SITEURL;?>viewjob/<?php echo stripslashes($row['id']);?>?action=contacting"><?php echo $no_contacting;?></a>
											<?php
											}
											else{
												?>
												<?php echo $no_contacting;?>
												<?php
											}
											?>
											</div>

											</td>

											<td style="text-align:left;">

											<div style="text-align:left;"><?php echo $no_hired;?></div>

											</td>

											<!--<td style="text-align:left;"><?php echo $paytype;?>:<br>

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

											</td>-->

											<td><a href="javascript:void(0);" class="loadquery" id="view_<?php echo $row['id'];?>"><?php echo $num_initl_msg;?></a></td>

											<td><a href="<?php echo SITEURL;?>emp_messages/"><?php echo $unread_count;?></a></td>

											<td style="text-align:center;">
											<select id="change_stat_<?php echo $row['id'];?>" class="form-select change_stat">
											<option value="0" <?php if($row['isclosed']==0) echo "selected";?>>Open</option>
											<option value="1" <?php if($row['isclosed']==1) echo "selected";?>>Pause</option>
											<option value="2">Delete</option>
											</select>
											</td>


											<td><a href="<?php echo SITEURL;?>editjob1/<?php echo stripslashes($row['id']);?>" class="edtxt">Edit</a></td>

											</tr>

											<?php

										}
										
										if($total_cover > $records_per_page){
										?>
										<tr>
										<td colspan="7">
										<div class="mypaginate">
										<ul>
										<?php
										for($i = 1; $i<=ceil($total_cover/$records_per_page); $i++){
											if($i == $page){
											?>
												<li class="active"><?php echo $i;?></li>
											<?php
											}
											else{
												?>
													<li><a href="<?php echo SITEURL;?>myjobs/?page=<?php echo $i;?>"><?php echo $i;?></a></li>
												<?php
											}
										}
										?>
										</ul>
										</div>
										</td>
										</tr>
										<?php
										}

										}

										else{

											?>

											<tr>

											<td colspan="7" style="text-align:center;">No jobs posted.</td>

											</tr>

											<?php

										}

										?>

									</table>


<div class="modal fade" id="showjobstatus" tabindex="-1" role="dialog" aria-labelledby="coverlettrLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="text-align:center;">
		<div id="jobstatus"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel" style="color:#3176B4;">Queries</h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>

      <div class="modal-body">

	  		<div class="messgbk">

				<div id="existingmsg" class="onlyscrol"></div>

			</div>

      </div>

      <div class="modal-footer">

		<span style="font-size:12px;">To respond to the queries please check mail sent to registered email ID.</span>

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