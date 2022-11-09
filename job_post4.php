<?php

include('config/config.php'); 

include_once "config/common.php";

$dbConn = establishcon();

include_once('header.php');



if(!isset($_SESSION['loginUserId'])){

	echo "<script>location.href='".SITEURL."employer_login'</script>";

	exit;

}

//if(isset($_POST['jobid'])){

?>



<section class="login_page job_post_4_sty">

	<div class="container">

		<div class="splnav">

			<ul class="onlyemployernav">

				<li <?php if($page == "success.php" || $page == "viewmyjob.php" || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">My Jobs</a></li>

				<li class="active"><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>

				<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>

				<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>

				<!--<li><a href="<?php echo SITEURL."logout";?>">Logout</a></li>-->

			</ul>

		</div>

		<div class="row">

			<div class="col-lg-8 m-auto">

				<div class="login jobpostnewbgdesn">

					<h4>Thank You</h4>

					<div class="row">

						

						<div class="">

							<form action="<?php echo SITEURL;?>myjobs" method="post" id="jobpost" class="eplyfrm">

								<!--<input type="hidden" value="<?php echo trim($_POST['jobid']);?>" name="jobid">-->

								<div class="form-group">

								<label style="text-transform:none;">Your job has been posted. Go to the listing page to review staffs.</label>

								</div>

								

								<input type="submit" value="Go to listing page" style="width:auto;">

							</form>

						</div>

					</div>

					

					

				</div>

			</div>

		</div>

	</div>

</section>

	

<?php 

//}

include_once('footer.php');?>