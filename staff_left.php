<div class="col-lg-3">

	<ul class="mainleft">

		<li <?php if($page == "profile.php") echo "class='active'";?>><a href="<?php echo SITEURL."staff_profile";?>">Account</a></li>

		

		<?php

		$checkonboard = dbQuery($dbConn, "SELECT isonboardsuccess from staff_details where staff_id = '".$_SESSION['loginUserId']."'");

		$row = dbFetchArray($checkonboard);

		if($row['isonboardsuccess'] == 0){

			?>

			<!--<li><a href="<?php echo SITEURL."onboard";?>">Onboard</a></li>-->

			<?php

		}

		?>

		<!--<li><a href="<?php echo SITEURL."staff_payment";?>">Payment</a></li>

		<li><a href="<?php echo SITEURL."staff_payment2";?>">Health Info</a></li>

		<li><a href="<?php echo SITEURL."staff_payment3";?>">Superannuation Info</a></li>-->

		<li <?php if($page == "myjobs.php" || $page == "myjobview.php") echo "class='active'";?>><a href="<?php echo SITEURL."myappliedjobs";?>">My Jobs</a></li>

		<li <?php if($page == "staff_msgs.php") echo "class='active'";?>><a href="<?php echo SITEURL."staff_messages";?>">Messages</a></li>
		<li <?php if($page == "i_am_staff.php") echo "class='active'";?>><a href="<?php echo SITEURL."staff_details";?>">My Profile</a></li>

	</ul>

</div>