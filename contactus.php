<?php

include('config/config.php'); 

include_once "config/common.php";

$dbConn = establishcon();

include_once('header.php');

?>

<section class="works contactspl my_job_sty">

	<div class="container">

		<h3>Contact Us</h3>

		<p>Couldn't find the help you are looking for? Staff Express is here to help you!</p>

		<div class="row">

			<div class="col-sm-3 col-6">

				<div class="contactuspartimage"><img src="<?php echo SITEURL;?>images/icon_livechat.png" alt="" /></div>

				<label>Live Chat</label>

				<p>24 / 7</p>

				<p><a href="javascript:void(0);" onclick="tidioChatApi.display(true);tidioChatApi.open()"">Let's Chat &nbsp; <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></p>

			</div>

			<div class="col-sm-3 col-6">

				<div class="contactuspartimage"><img src="<?php echo SITEURL;?>images/icon_email.png" alt="" /></div>

				<label>Email</label>

				<p>Replies within 2 business days</p>

				<p><a href="mailto:<?php echo CONTACTEMAIL;?>">Drop us a line &nbsp; <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></p>

			</div>

			<div class="col-sm-3 col-6">

				<div class="contactuspartimage"><img src="<?php echo SITEURL;?>images/icon_call.png" alt="" /></div>

				<label>Call Us</label>

				<p>Responds in under an hour</p>

				<p><a href="tel:0893374756">Call &nbsp; <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></p>

			</div>

			<div class="col-sm-3 col-6">

				<div class="contactuspartimage"><img src="<?php echo SITEURL;?>images/icon_office.png" alt="" /></div>

				<label>Head Office</label>

				<p>Lot 16/78 Coolbellup Avenue Coolbellup WA 6163 Australia</p>

				<p><a href="<?php echo SITEURL;?>findus">Find Us &nbsp; <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></p>

			</div>

			

		</div>

	</div>

</section>

	

	

<?php include_once('footer.php');?>