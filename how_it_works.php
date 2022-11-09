<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

$sql = "SELECT * from `pages` where id = '1'";
$sql_res = dbQuery($dbConn,$sql);
$sql_res_fetch = dbFetchArray($sql_res);
?>

	<section class="how_to_works_banner">
		<div class="container">
			<div class="row">				
				<div class="col-lg-12 col-md-12 col-12">
					<div class="banner_heading_all owl-carousel innerfullwdth">
						<h3><?php echo stripslashes($sql_res_fetch['heading1']);?></h3>
						<h3><?php echo stripslashes($sql_res_fetch['heading2']);?></h3>
						<h3><?php echo stripslashes($sql_res_fetch['heading3']);?></h3>
					</div>
					 <h3 style="padding-top:20px;"><?php echo stripslashes($sql_res_fetch['subheading']);?></h3>
					

					<div class="banhowbtn"><a href="" data-bs-toggle="modal" data-bs-target="#videoModalStaffPage"><i class="fa fa-video-camera" aria-hidden="true"></i> &nbsp; See How It Works</a></div>
				<!-- Modal -->
						<div class="modal fade" id="videoModalStaffPage" tabindex="-1" aria-labelledby="videoModalOneLabel" aria-hidden="true">
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
					</div>
				</div>
			</div>
			
			<div class="bantreesec bantreesec_sty">
				<div class="row">
					<div class="col-sm-6">
						<div class="howbanimg"><img src="<?php echo SITEURL;?>uploads/<?php echo stripslashes($sql_res_fetch['leftimg']);?>" alt="" class="img-responsive" /></div>
					</div>
					<div class="col-sm-6">
						<div class="parttxt">
							<h4><?php echo stripslashes($sql_res_fetch['rightheading1']);?></h4>
							<p><?php echo stripslashes($sql_res_fetch['rightsubheading1']);?></p>
						</div>
						<div class="parttxt">
							<h4><?php echo stripslashes($sql_res_fetch['rightheading2']);?></h4>
							<p><?php echo stripslashes($sql_res_fetch['rightsubheading2']);?></p>
						</div>
						<div class="parttxt">
							<h4><?php echo stripslashes($sql_res_fetch['rightheading3']);?></h4>
							<p><?php echo stripslashes($sql_res_fetch['rightsubheading3']);?></p>
						</div>
					</div>						
				</div>
			</div>
			
		</div>
	</section>

	<div class="works howpagewhy" style="background-color:#EEF0FB;">
		<div class="container">
			<h3><?php echo stripslashes($sql_res_fetch['sectntwohead']);?></h3>
			<div class="row">
				<div class="col-sm-4 col-6">
					<div class="works_single_content">
						<a href="#">
							<div class="works_img whystffimg">
								<img src="<?php echo SITEURL;?>uploads/<?php echo stripslashes($sql_res_fetch['whystaffimg1']);?>" class="img-fluid" alt="">
							</div>
							<p><?php echo stripslashes($sql_res_fetch['whystafftext1']);?></p>
						</a>
					</div>
				</div>
				<!--<div class="col-sm-3 col-6">
					<div class="works_single_content">
						<a href="#">
							<div class="works_img whystffimg">
								<img src="<?php echo SITEURL;?>uploads/<?php echo stripslashes($sql_res_fetch['whystaffimg2']);?>" class="img-fluid" alt="">
							</div>
							<h5><?php echo stripslashes($sql_res_fetch['whystafftext2']);?></h5>
						</a>
					</div>
				</div>-->
				<div class="col-sm-4 col-6">
					<div class="works_single_content">
						<a href="#">
							<div class="works_img whystffimg">
								<img src="<?php echo SITEURL;?>uploads/<?php echo stripslashes($sql_res_fetch['whystaffimg3']);?>" class="img-fluid" alt="">
							</div>
							<p><?php echo stripslashes($sql_res_fetch['whystafftext3']);?></p>
						</a>
					</div>
				</div>
				<div class="col-sm-4 col-6">
					<div class="works_single_content">
						<a href="#">
							<div class="works_img whystffimg">
								<img src="<?php echo SITEURL;?>uploads/<?php echo stripslashes($sql_res_fetch['whystaffimg4']);?>" class="img-fluid" alt="">
							</div>
							<p><?php echo stripslashes($sql_res_fetch['whystafftext4']);?></p>
						</a>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
	
	<!--<div class="works fivebox">
		<div class="container1">
			<h3>How It Works</h3>
			
			<ul>
				<li>
					<div class="works_single_content gapmar onlyhiw fadeblue">
						<a href="#">
							<div class="works_img">
								<img src="<?php echo SITEURL;?>images/how1.png" class="img-fluid" alt="">
							</div>
							<h5>Need staff cover urgently or soon?</h5>
						</a>
					</div>
				</li>
				<li>
					<div class="works_single_content gapmar onlyhiw fadesky">
						<a href="#">
							<div class="works_img">
								<img src="<?php echo SITEURL;?>images/how2.png" class="img-fluid" alt="">
							</div>
							<h5>Employers post a job with specific criteria</h5>
						</a>
					</div>
				</li>
							
				<li>
					<div class="works_single_content onlyhiw fadered">
						<a href="#">
							<div class="works_img">
								<img src="<?php echo SITEURL;?>images/how4.png" class="img-fluid" alt="">
							</div>
							<h5>Hire them securely on platform with secure payment and contract</h5>
						</a>
					</div>
				</li>
				<li>
					<div class="works_single_content onlyhiw fadeyellow">
						<a href="#">
							<div class="works_img">
								<img src="<?php echo SITEURL;?>images/how5.png" class="img-fluid" alt="">
							</div>
							<h5>Matching Employees notified and contact you on platform almost immediately for cover</h5>
						</a>
					</div>
				</li>
				<li>
					<div class="works_single_content gapmar onlyhiw fadegreen">
						<a href="#">
							<div class="works_img">
								<img src="<?php echo SITEURL;?>images/how3.png" class="img-fluid" alt="">
							</div>
							<h5>Give it a try – you would be surprised.</h5>
						</a>
					</div>
				</li>
			</ul>
			
			
		</div>
	</div>-->


	<div class="staff_express_special" style="background-color:#fff;">
		<div class="container">
			<h3><?php echo stripslashes($sql_res_fetch['secthreehead']);?></h3>
			<div class="row">
				<div class="col-sm-6">
					<div class="staffsplleft">
						<ul>
							<li><img src="<?php echo SITEURL;?>images/earn1.png" alt="" width="50" /> <?php echo stripslashes($sql_res_fetch['secthreetext1']);?></li>
							<li><img src="<?php echo SITEURL;?>images/earn2.png" alt="" width="50" /> <?php echo stripslashes($sql_res_fetch['secthreetext2']);?></li>
							<!--<li><img src="<?php echo SITEURL;?>images/earn3.png" alt="" width="50" /> <?php echo stripslashes($sql_res_fetch['secthreetext3']);?> </li>-->
							<li><img src="<?php echo SITEURL;?>images/earn4.png" alt="" width="50" /> <?php echo stripslashes($sql_res_fetch['secthreetext4']);?></li>
						</ul>
					</div>
					<div class="staffsplleftbut">
					<?php
					if(!isset($_SESSION['loginUserId'])){
					?>
					<a href="<?php echo SITEURL;?>signup" class="banbt">Join StaffExpress</a> &nbsp; &nbsp; 
					<?php
					}
					?>
					<!--<a href="#" class="banbt">Earn Money</a>--></div>
				</div>
				<div class="col-sm-6">
					<img src="<?php echo SITEURL;?>uploads/<?php echo stripslashes($sql_res_fetch['secthreerightimg']);?>" alt="" class="img-responsive" />					
				</div>
			</div>
		</div>
	</div>
	
	<?php include_once('footer.php');?>