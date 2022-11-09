<?php
include('config/config.php');
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');


$today = date("Y-m-d");
if (count($_POST) > 0) {

	//$category = isset($_POST['category'])?tep_db_input($_POST['category']):"";
	//$busdate = isset($_POST['busdate'])?tep_db_input($_POST['busdate']):"";
	//$busdate2 = isset($_POST['busdate2'])?tep_db_input($_POST['busdate2']):"";
	$suburb = isset($_POST['suburb']) ? strip_tags(tep_db_input($_POST['suburb'])) : "";
	$catid = isset($_POST['catid']) ? $_POST['catid'] : array();
	$maincatid = isset($_POST['maincatid']) ? $_POST['maincatid'] : array();

	if (isset($_SESSION['loginUserId']) && $_SESSION['loginUserId'] != "") {
		dbQuery($dbConn, "INSERT into job_details set employer_id = '" . $_SESSION['loginUserId'] . "', suburb = '" . $suburb . "', postdate = '" . $today . "'");
		$jobid = dbInsertId($dbConn);

		if (count($maincatid) > 0) {
			foreach ($maincatid as $val) {

				dbQuery($dbConn, "INSERT into job_parent_cat set jobid = '" . $jobid . "', catid = '" . $val . "'");
			}
		}

		if (count($catid) > 0) {
			foreach ($catid as $val) {
				dbQuery($dbConn, "INSERT into job_cat set jobid = '" . $jobid . "', catid = '" . $val . "'");
			}
		}

		echo "<form action='" . SITEURL . "jobpost1' method='post' id='gotostep2'><input type='hidden' name='jobid' value='" . $jobid . "'></form><script>document.getElementById('gotostep2').submit();</script>";
		exit;
	} else {
		if (!isset($_SESSION['SESSID'])) {
			$_SESSION['SESSID'] = session_id();
		}
		dbQuery($dbConn, "INSERT into job_details set sessid = '" . $_SESSION['SESSID'] . "', suburb = '" . $suburb . "', postdate = '" . $today . "'");
		$jobid = dbInsertId($dbConn);

		if (count($maincatid) > 0) {
			foreach ($maincatid as $val) {

				dbQuery($dbConn, "INSERT into job_parent_cat set jobid = '" . $jobid . "', catid = '" . $val . "'");
			}
		}

		if (count($catid) > 0) {
			foreach ($catid as $val) {
				dbQuery($dbConn, "INSERT into job_cat set jobid = '" . $jobid . "', catid = '" . $val . "'");
			}
		}

		echo "<script>location.href='" . SITEURL . "signup'</script>";
		exit;
	}
}
$sql = "SELECT * from `pages` where id = '6'";
$sql_res = dbQuery($dbConn, $sql);
$sql_res_fetch = dbFetchArray($sql_res);
?>
<section class="homebannersec" style="background-image:url('<?php echo SITEURL; ?>uploads/<?php echo stripslashes($sql_res_fetch['toprightimg']); ?>');">
	<div class="container">
		<div class="bannercaption">
			<!-- <h1>Connect with experts to get<br />the job done on Staff Express</h1>
				<h2>Staff Express is where 100 million workers team up to take their<br />next job to the next level.</h2> -->
			<div class="banner_heading_all owl-carousel">
				<h1><?php echo stripslashes($sql_res_fetch['heading1']); ?></h1>
				<h1><?php echo stripslashes($sql_res_fetch['heading2']); ?></h1>
				<h1><?php echo stripslashes($sql_res_fetch['heading3']); ?></h1>
			</div>
			<p><?php echo stripslashes($sql_res_fetch['subheading']); ?></p>

			<?php
			$usertype = 0;
			if (isset($_SESSION['loginUserId'])) {
				$usertype = getUserType($dbConn, $_SESSION['loginUserId']);
			}

			if (!isset($_SESSION['loginUserId']) || $usertype == 1) {
			?>
				<section class="serchsecsn">
					<div class="container2">
						<div class="">
							<!--<h3><?php echo stripslashes($sql_res_fetch['sectntwohead']); ?></h3>-->
							<div class="serbox">
								<form action="" class="serchfrm" method="post" id="jobpost">
									<div class="row">

										<div class="col-sm-12 col-md-4">
											<div class="sertxtbxall mycatsec">
												<label for=""><i class="fa fa-pencil" aria-hidden="true"></i> &nbsp; Enter Skills</label>
												<!--<select class="form-select required" name="category">
													<?php
													$cat = dbQuery($dbConn, "SELECT * from category order by category");
													while ($row = dbFetchArray($cat)) {
													?>
													<option value="<?php echo stripslashes($row['id']); ?>"><?php echo stripslashes($row['category']); ?></option>
													<?php
													}
													?>
												  </select>-->
												<input type="hidden" id="myselectedcats" value="">
												<input class="form-control" id="showcats" name="showcats" type="text" placeholder="Job Category" readonly />
												<div id="allcats" style="display:none;">
													<ul class="items">

														<?php

														$cat = dbQuery($dbConn, "select * from category where parent_id=0 order by category");

														while ($catrow = dbFetchArray($cat)) {
														?>
															<li>
																<input id="maincat_<?php echo $catrow['id']; ?>" type="checkbox" class="employercats" name="maincatid[]" value="<?php echo $catrow['id']; ?>" data-value="<?php echo stripslashes($catrow['category']); ?>" />&nbsp; <?php echo stripslashes($catrow['category']); ?>
																<div id="maincatli_<?php echo $catrow['id']; ?>">

																</div>
															</li>
														<?php

														}

														?>

													</ul>
												</div>
											</div>


										</div>

										<div class="col-sm-8 col-md-6">
											<div class="sertxtbxall" style="position:relative;">
												<label for=""><i class="fa fa-calendar"></i> &nbsp; Location</label>
												<input type="text" placeholder="Location (Suburb)" id="myaddress" name="suburb" class="form-control required inpbxser">

												<div id="loading" style="display:none;"><img src="<?php echo SITEURL; ?>images/sp-loading.gif" alt=""></div>

												<div id="mylocations" style="display:none;z-index:999;padding:15px;width:428px;">

													<ul></ul>

												</div>
											</div>
										</div>

										<div class="col-sm-4 col-md-2"><input type="submit" class="sersub" name="" value="Submit" /></div>
									</div>
								</form>
							</div>

						</div>
					</div>
				</section>
			<?php
			}
			?>


		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="videoModalOne" tabindex="-1" aria-labelledby="videoModalOneLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<!-- <h5 class="modal-title" id="videoModalOneLabel">Video title</h5> -->
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<video controls loop mute>
						<source src="images/staff_video.mp4">
					</video>
				</div>

			</div>
		</div>
	</div>

</section>


<section class="vidsecsn">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-8 offset-lg-2 ">
				<div class="mainvid">
					<h3><?php echo stripslashes($sql_res_fetch['sectntwohead']); ?></h3>
					<video width="100%" controls>
						<source src="images/staff_video.mp4" type="video/mp4">
					</video>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="availbjob">
	<div class="container">
		<div class="populrserch">
			<h3><?php echo stripslashes($sql_res_fetch['topjobcathead']); ?></h3>
			<p><?php echo stripslashes($sql_res_fetch['topjobcattext']); ?></p>
			<div class="stepsec">
				<div class="row">
					<div class="col-sm-4">
						<div class="indivisualstep"><span>1</span>
							<p class=""><?php echo stripslashes($sql_res_fetch['leftboxtext']); ?></p>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="indivisualstep"><span>2</span>
							<p class=""><?php echo stripslashes($sql_res_fetch['middleboxtext']); ?></p>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="indivisualstep"><span>3</span>
							<p class=""><?php echo stripslashes($sql_res_fetch['rightboxtext']); ?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<?php
				$dispcats = dbQuery($dbConn, "SELECT * from `category` where isdisplayhomepage=1 order by RAND() limit 0,8");
				while ($rowCats = dbFetchArray($dispcats)) {
					if ($rowCats['catimg'])
						$catimg = SITEURL . "uploads/" . $rowCats['catimg'];
					else
						$catimg = "images/noimg.jpg";
				?>
					<div class="col-sm-3 col-6">
						<div class="populrbx">
							<div class="popuimg"><img src="<?php echo $catimg; ?>" alt="" class="img-responsive" /></div>
							<div class="poputxt">
								<h5><?php echo stripslashes($rowCats['category']); ?></h5>
							</div>

						</div>
					</div>
				<?php
				}
				?>

			</div>

		</div>
	</div>
</section>

<input type="hidden" id="userid" value="<?php if (isset($_SESSION['loginUserId'])) echo $_SESSION['loginUserId']; ?>">

<section class="recentcover">
	<div class="container2">
		<div class="mainvid">
			<h3>Recent Posts</h3>
			<div class="homeallrecntcovr">

				<div class="owl-slider">
					<div id="carousel" class="owl-carousel">

						<?php
						$cats = array();
						$catstr = '';
						$cond = '';
						$orderby = '';
						$currtime = date('Y-m-d H:i:s');

						$total_cover = total_open_jobs($dbConn);

						$sql = "SELECT a.id,a.title,a.street_address,a.location,a.postdate,a.job_status,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.workmode,a.add_time,a.covertype,a.longstartdt,b.company_img from job_details a inner join users b on a.employer_id=b.id where a.title != '' and a.location != '' and a.lat != '' and a.workmode != '' and a.isclosed=0 and a.postcomplete=1 order by a.id desc limit 0,5";
						$jobs = dbQuery($dbConn, $sql);
						if (dbNumRows($jobs) > 0) {
							while ($row = dbFetchArray($jobs)) {
								$jobsrtdate = $row['jobdate'];
								$jobenddate = $row['jobdate2'];
								$strttime = $row['starttime'];
								$endtime = $row['endtime'];

								$mycats = dbQuery($dbConn, "SELECT category from job_cat a inner join category b on a.catid=b.id where jobid = '" . trim($row['id']) . "'");

								while ($mycatsrow = dbFetchArray($mycats)) {

									$cats[] = stripslashes($mycatsrow['category']);
								}
								if (count($cats) > 0) {
									$catstr = implode(", ", $cats);
								}

						?>
								<div class="item">

									<div class="sercoversingle">
										<div class="covrsinglleft">
											<h4><a href="<?php echo SITEURL; ?>viewmyjob/<?php echo base64_encode($row['id']); ?>"><?php echo stripslashes($row['title']); ?></a></h4>
											<p><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo stripslashes($row['street_address']); ?>, <?php echo stripslashes($row['location']); ?></p>
											<p><i class="fa fa-calendar" aria-hidden="true"></i>
												<?php
												if ($row['covertype'] == 1)
													echo date('M j, Y', strtotime($row['jobdate']));
												else if ($row['covertype'] == 2) {
													if ($row['longstartdt'] != "0000-00-00")
														echo "From " . date('M j, Y', strtotime($row['longstartdt']));
													else
														echo "TBD";
												}

												?>
												<?php
												if ($row['add_time'] == 1) {
												?>
													at <?php echo date('h:i A', strtotime($row['starttime'])); ?>
												<?php
												}
												?>
												<?php
												if ($row['covertype'] == 1 && $row['jobdate2'] != "0000-00-00")
													echo " - " . date('M j, Y', strtotime($row['jobdate2']));

												?>
												<?php
												if ($row['add_time'] == 1) {
													if ($row['jobdate2'] != "0000-00-00")
														echo "at";
													else
														echo "-";
												?>
													<?php echo date('h:i A', strtotime($row['endtime'])); ?>
												<?php
												}
												?>
											</p>
											<p><i class="fa fa-list" aria-hidden="true"></i><?php echo $catstr; ?></p>
										</div>
										<div class="covrsinglright">
											<div class="covrtimg"><?php
																	if ($row['company_img']) {
																	?>
													<img src="<?php echo SITEURL; ?>uploads/<?php echo $row['company_img']; ?>" alt="">
												<?php
																	} else {
												?>
													<img src="<?php echo SITEURL; ?>images/sercovernoimg.png" class="img-responsive" alt="loginimg" />
												<?php } ?>
											</div>
											<ul class="homebtn" id="applybtn_<?php echo $row['id']; ?>" style="">
												<li><a href="<?php echo SITEURL; ?>viewmyjob/<?php echo base64_encode($row['id']); ?>" style="padding:0.4rem 0rem;">View</a></li>
											</ul>
										</div>
									</div>

								</div>
							<?php
								$catstr = '';
								$cats = array();
							}
						} else {
							?>
							<div class="col-sm-12">
								<div class="sercoversingle" style="text-align:center;">
									No open jobs.
								</div>
							</div>
						<?php
						}
						?>

					</div>
				</div>

				<?php
				if ($total_cover > 5) {
				?>
					<div class="owl-slider">
						<div id="carousel1" class="owl-carousel carousal2">

							<?php
							$cats = array();
							$catstr = '';
							$cond = '';
							$orderby = '';
							$currtime = date('Y-m-d H:i:s');


							$sql2 = "SELECT a.id,a.title,a.street_address,a.location,a.postdate,a.job_status,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.workmode,a.add_time,a.covertype,b.company_img from job_details a inner join users b on a.employer_id=b.id where a.title != '' and a.location != '' and a.lat != '' and a.workmode != '' and a.isclosed=0 and a.postcomplete=1 order by a.id desc limit 5,5";
							$jobs2 = dbQuery($dbConn, $sql2);
							if (dbNumRows($jobs2) > 0) {
								while ($row = dbFetchArray($jobs2)) {
									$jobsrtdate = $row['jobdate'];
									$jobenddate = $row['jobdate2'];
									$strttime = $row['starttime'];
									$endtime = $row['endtime'];

									$mycats = dbQuery($dbConn, "SELECT category from job_cat a inner join category b on a.catid=b.id where jobid = '" . trim($row['id']) . "'");

									while ($mycatsrow = dbFetchArray($mycats)) {

										$cats[] = stripslashes($mycatsrow['category']);
									}
									if (count($cats) > 0) {
										$catstr = implode(", ", $cats);
									}

							?>
									<div class="item">

										<div class="sercoversingle">
											<div class="covrsinglleft">
												<h4><a href="<?php echo SITEURL; ?>viewmyjob/<?php echo base64_encode($row['id']); ?>"><?php echo stripslashes($row['title']); ?></a></h4>
												<p><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo stripslashes($row['street_address']); ?>, <?php echo stripslashes($row['location']); ?></p>
												<p><i class="fa fa-calendar" aria-hidden="true"></i>
													<?php
													if ($row['covertype'] == 1)
														echo date('M j, Y', strtotime($row['jobdate']));
													else
														echo "TBD";
													?>
													<?php
													if ($row['add_time'] == 1) {
													?>
														at <?php echo date('h:i A', strtotime($row['starttime'])); ?>
													<?php
													}
													?>
													<?php
													if ($row['covertype'] == 1)
														echo " - " . date('M j, Y', strtotime($row['jobdate2']));

													?>
													<?php
													if ($row['add_time'] == 1) {
													?>
														at <?php echo date('h:i A', strtotime($row['endtime'])); ?>
													<?php
													}
													?>
												</p>
												<p><i class="fa fa-list" aria-hidden="true"></i><?php echo $catstr; ?></p>
											</div>
											<div class="covrsinglright">
												<div class="covrtimg">
													<?php
													if ($row['company_img']) {
													?>
														<img src="<?php echo SITEURL; ?>uploads/<?php echo $row['company_img']; ?>" alt="">
													<?php
													} else {
													?>
														<img src="<?php echo SITEURL; ?>images/sercovernoimg.png" class="img-responsive" alt="loginimg" />
													<?php } ?>
												</div>
												<ul class="homebtn" id="applybtn_<?php echo $row['id']; ?>" style="">
													<li><a href="<?php echo SITEURL; ?>viewmyjob/<?php echo base64_encode($row['id']); ?>" style="padding:0.4rem 0rem;">View</a></li>
												</ul>
											</div>
										</div>

									</div>
							<?php
									$catstr = '';
									$cats = array();
								}
							}
							?>

						</div>
					</div>
				<?php
				}
				?>

				<div class="sercoverpage">

					<?php
					if ($total_cover > 10) {
					?>
						<div class="homeviwecoverbutn"><a href="<?php echo SITEURL; ?>searchcover">View all Covers</a></div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="oparatesec">
	<!--style="background-image:url('<?php echo SITEURL; ?>uploads/<?php echo stripslashes($sql_res_fetch['secfourlftimg']); ?>');"-->

	<div class="globalimgtextmani">

		<h2><?php echo stripslashes($sql_res_fetch['secfourhead']); ?></h2>
		<div class="row lessmar">
			<div class="col-lg-1 col-md-1 lesspadd_lt">&nbsp;</div>
			<div class="col-sm-2 col-4 col-md-2 col-lg-2">
				<div class="flagmain">
					<a href="http://www.staffexpress.com.sg/" target="_blank">
						<div class="flgleft"><img src="<?php echo SITEURL; ?>images/flag_singapore.png" alt="" /></div>
						<div class="flgrttxt">
							<h5>Staff Express</br>Singapore</h5>
						</div>
					</a>
				</div>
			</div>
			<div class="col-sm-2 col-4 col-md-2 col-lg-2">
				<div class="flagmain">
					<a href="http://www.staffexpressglobal.com/" target="_blank">
						<div class="flgleft"><img src="<?php echo SITEURL; ?>images/flag_usa.png" alt="" /></div>
						<div class="flgrttxt">
							<h5>Staff Express</br>USA</h5>
						</div>
					</a>
				</div>
			</div>
			<div class="col-sm-2 col-4 col-md-2 col-lg-2">
				<div class="flagmain">
					<a href="https://www.staffexpress.com.au/" target="_blank">
						<div class="flgleft"><img src="<?php echo SITEURL; ?>images/flag_australia.png" alt="" /></div>
						<div class="flgrttxt">
							<h5>Staff Express</br>Australia</h5>
						</div>
					</a>
				</div>
			</div>
			<div class="col-sm-2 col-4 col-md-2 col-lg-2">
				<div class="flagmain">
					<a href="http://www.staffexpress.co.nz/" target="_blank">
						<div class="flgleft"><img src="<?php echo SITEURL; ?>images/flag_nz.png" alt="" /></div>
						<div class="flgrttxt">
							<h5>Staff Express</br>New Zealand</h5>
						</div>
					</a>
				</div>
			</div>
			<div class="col-sm-2 col-4 col-md-2 col-lg-2">
				<div class="flagmain">
					<a href="http://www.staffexpress.co.uk/" target="_blank">
						<div class="flgleft"><img src="<?php echo SITEURL; ?>images/flag_uk.png" alt="" /></div>
						<div class="flgrttxt">
							<h5>Staff Express</br>UK</h5>
						</div>
					</a>
				</div>
			</div>
			<div class="col-md-1 d-none lesspadd_rt">&nbsp;</div>
		</div>


		<div class="container">
			<div class="row lessmar">
				<div class="col-xl-1 col-lg-1 col-md-1">&nbsp;</div>
				<div class="col-xl-5 col-lg-5 col-md-5">
					<div class="glortall">

						<div class="oparatecontent">
							<h5><?php echo stripslashes($sql_res_fetch['secfourtext1']); ?></h5>
							<!--<h3><?php echo stripslashes($sql_res_fetch['secfourtext2']); ?></h3>-->
							<ul class="oparatelist">
								<li>
									<img src="images/icon_area.png" alt="icon">
									<?php echo stripslashes($sql_res_fetch['secfourtext3']); ?>
								</li>
								<li>
									<img src="images/icon01.png" alt="icon">
									<?php echo stripslashes($sql_res_fetch['secfourtext4']); ?>
								</li>
								<li>
									<img src="images/nocost.png" alt="icon">
									<?php echo stripslashes($sql_res_fetch['secfourtext5']); ?>
								</li>
								<li>
									<img src="images/compltfree.png" alt="icon">
									<?php echo stripslashes($sql_res_fetch['secfourtext6']); ?>
								</li>
							</ul>
							<a href="<?php echo SITEURL; ?>signup" class="globalresbutn">Register</a>
						</div>
					</div>
				</div>
				<div class="col-xl-5 col-lg-5 col-md-5">
					<div class="glortall" style="border-right:none;">

						<div class="oparatecontent">
							<h5><?php echo stripslashes($sql_res_fetch['secfourtext7']); ?></h5>

							<ul class="oparatelist">
								<li>
									<img src="images/urgntcvr.png" alt="icon">
									<?php echo stripslashes($sql_res_fetch['secfourtext8']); ?>
								</li>
								<li>
									<img src="images/machingcandidts.png" alt="icon">
									<?php echo stripslashes($sql_res_fetch['secfourtext9']); ?>
								</li>
								<li>
									<img src="images/regisfree.png" alt="icon">
									<?php echo stripslashes($sql_res_fetch['secfourtext10']); ?>
								</li>
								<li>
									<img src="images/livejov.png" alt="icon">
									<?php echo stripslashes($sql_res_fetch['secfourtext11']); ?>
								</li>
							</ul>
							<a href="<?php echo SITEURL; ?>signup" class="globalresbutn">Register</a>
						</div>
					</div>
				</div>
				<div class="col-xl-1 col-lg-1 col-md-1">&nbsp;</div>
			</div>
		</div>
	</div>
</section>



<?php
$check = dbQuery($dbConn, "select id from users where type=2 and isdeactivated=0");
$noofstuff = dbNumRows($check);

$hired = dbQuery($dbConn, "select id from job_status where hiredon = '" . date('Y-m-d') . "'");
$nohired = dbNumRows($hired);
?>
<!--<section class="numbrcunt">
		<div class="container">
			<div class="row">
				<div class="row" id="counter">
					<div class="col-sm-4 counter-Txt"><?php echo stripslashes($sql_res_fetch['secsixtext1']); ?><span class="counter-value" data-count="<?php echo $noofstuff; ?>">0</span></div>
					<div class="col-sm-4 counter-Txt"><?php echo stripslashes($sql_res_fetch['secsixtext2']); ?><span class="counter-value" data-count="25">0</span></div>
					<div class="col-sm-4 counter-Txt margin-bot-35"><?php echo stripslashes($sql_res_fetch['secsixtext3']); ?><span class="counter-value" data-count="15">0</span></div>
				</div>
			</div>
		</div>
	</section>-->

<section class="oparatesec globaccsec" style="background-color:#F6F8FD;background-image:none;min-height:auto;">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="oparatecontent hwstafftabspl">
					<h5>STAFF EXPRESS</h5>
					<h3><?php echo stripslashes($sql_res_fetch['secsvnhead']); ?></h3>
					<div class="row allhwwrk">
						<div class="col-sm-3">
							<div class="howwrk ">
								<div class="hwimg"><img src="<?php echo SITEURL; ?>uploads/<?php echo stripslashes($sql_res_fetch['secsvnimg1']); ?>" alt="" class="img-responsive" /></div>
								<p><?php echo stripslashes($sql_res_fetch['secsvntext1']); ?></p>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="howwrk ">
								<div class="hwimg"><img src="<?php echo SITEURL; ?>uploads/<?php echo stripslashes($sql_res_fetch['secsvnimg2']); ?>" alt="" class="img-responsive" /></div>
								<p><?php echo stripslashes($sql_res_fetch['secsvntext2']); ?></p>
							</div>
						</div>

						<div class="col-sm-3">
							<div class="howwrk ">
								<div class="hwimg"><img src="<?php echo SITEURL; ?>uploads/<?php echo stripslashes($sql_res_fetch['secsvnimg3']); ?>" alt="" class="img-responsive" /></div>
								<p><?php echo stripslashes($sql_res_fetch['secsvntext3']); ?></p>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="howwrk ">
								<div class="hwimg"><img src="<?php echo SITEURL; ?>uploads/<?php echo stripslashes($sql_res_fetch['secsvnimg4']); ?>" alt="" class="img-responsive" /></div>
								<p><?php echo stripslashes($sql_res_fetch['secsvntext4']); ?></p>
							</div>
						</div>


					</div>

				</div>
			</div>
			<!--<div class="col-sm-5"></div>-->
		</div>
	</div>
</section>
<?php include_once('footer.php'); ?>