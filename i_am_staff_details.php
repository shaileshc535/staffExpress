<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
?>


	<section class="how_to_works_banner">
		<div class="container">
			<div class="row">				
				<div class="col-lg-12 col-md-12 col-12">
					<div class="banner_heading_all owl-carousel innerfullwdth">
						<h3>Need Urgent Staff cover on the same day?</h3>
						<h3>Staff down with Covid or sick?</h3>
						<h3>Staff required for short term?</h3>
					</div>
					 <h3 style="padding-top:20px;">Think StaffExpress</h3>
					<div class="videoban"><a href="#" class="banbt"><i class="fa fa-video-camera" aria-hidden="true"></i> &nbsp; See How It Works</a></div>
				</div>
			</div>
			
			
		</div>
	</section>
	
	<section class="staffall">
		<div class="container">
			<h3>I am Staff</h3>
			<div class="row">
				<div class="col-sm-8 offset-2">
					<ul class="stfdetls">
						<li>
							<div class="stfimgcls"><img src="images/icon_look.png" alt="icon" class="img-responsive"></div>
							<div class="stftxtcls"><strong class="titlebox">Be notified of employers looking for same day or urgent cover over next few days.</strong></div>
						</li>
						<li>
							<div class="stfimgcls"><img src="images/icon_flxitime.png" alt="icon" class="img-responsive"></div>
							<div class="stftxtcls"><strong class="titlebox">Flexibility as to when to work.</strong></div>
						</li>
						<li>
							<div class="stfimgcls"><img src="images/icon_notify.png" alt="icon" class="img-responsive"></div>
							<div class="stftxtcls"><strong class="titlebox">We notify you on SMS and Email (you don’t even need to search).</strong></div>
						</li>
						<li>
							<div class="stfimgcls"><img src="images/icon_earn.png" alt="icon" class="img-responsive"></div>
							<div class="stftxtcls"><strong class="titlebox">$0 Fees and Potential to earn good Income.</strong></div>
						</li>
					</ul>
					
					<div class="regisnow"><a href="#" class="banbt">Register Now</a></div>
				</div>
			</div>
		</div>
	</section>
	
	
	
	<div class="works fivebox">
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
	</div>


	<?php include_once('footer.php');?>