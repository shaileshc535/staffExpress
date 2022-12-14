<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
?>

	<section class="how_to_works_banner staff_banner">
		<div class="container">
			<div class="row">				
				<div class="col-lg-12 col-md-12 col-12">
					<!--<div class="banner_heading_all owl-carousel innerfullwdth">
						<h3>I am Staff</h3>
						<h3>Employee would like to know:</h3>
					</div>-->
				</div>
			</div>
			
			<div class="bantreesec">
				<div class="row">
					
					<div class="col-sm-6">
						<div class="parttxt">
							<h4>Earn more with casual work</h4>
						</div>
						<div class="parttxt">
							<h4>Job Cover for Urgent Work</h4>
						</div>
						<div class="parttxt">
							<h4>Posted by Multiple Employers</h4>
						</div>
                        <div class="parttxt">
							<h4>Casual and no obligation</h4>
						</div>
                        <div class="parttxt">
							<h4>Free to use and be notified</h4>
						</div>
					</div>	
					<div class="col-sm-6">
						<div class="howbanimg"><img src="<?php echo SITEURL;?>images/employerban-removebg-preview2.png" alt="" class="img-responsive" /></div>
					</div>					
				</div>
				
				<div class="banhowbtn"><a href="" data-bs-toggle="modal" data-bs-target="#videoModalOne"><i class="fa fa-play-circle" aria-hidden="true"></i> How it works</a></div>
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
						  <!--<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						  </div>-->
						</div>
					  </div>
					</div>
				
			</div>
			
		</div>
	</section>

    <section class="oparatesec globaccsec_inner">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="oparatecontent">
						<h5>STAFF EXPRESS</h5>
						<h3>How Staff Express Works For You</h3>
						<div class="row allhwwrk">
							<div class="col-sm-6">
								<div class="howwrk fadewhite">
									<div class="hwimg_inner"><img src="images/how1.png" alt="" class="img-responsive" /></div>
									<h3 class="innerhwo">Need staff cover urgently or soon?</h3>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="howwrk fadewhite">
									<div class="hwimg_inner"><img src="images/how2.png" alt="" class="img-responsive" /></div>
									<h3 class="innerhwo">Employers post a job with specific criteria</h3>
								</div>
							</div>
							
							<div class="col-sm-6">
								<div class="howwrk fadewhite">
									<div class="hwimg_inner"><img src="images/how4.png" alt="" class="img-responsive" /></div>
									<h3 class="innerhwo">Matching Employees notified and contact you on platform almost immediately for cover</h3>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="howwrk fadewhite">
									<div class="hwimg_inner"><img src="images/how5.png" alt="" class="img-responsive" /></div>
									<h3 class="innerhwo">Hire them securely on platform with secure payment and contract</h3>
								</div>
							</div>
							<div class="col-sm-6 offset-3">
								<div class="howwrk fadewhite">
									<div class="hwimg_inner"><img src="images/how3.png" alt="" class="img-responsive" /></div>
									<h3 class="innerhwo">Give it a try ??? you would be surprised.</h3>
								</div>
							</div>
							
						</div>
						
					</div>
					<div class="staffsplleftbut" style="text-align:center;"><a href="#" class="banbt">View Existing Cover Required</a> <a href="#" class="banbt" style="min-width:262px;">Register</a></div>
				</div>
				
			</div>
		</div>
	</section>

	
	<?php include_once('footer.php');?>