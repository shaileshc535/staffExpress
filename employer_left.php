<div class="col-lg-3">
<ul class="mainleft">
<li <?php if($page == "success.php" || $page == "viewmyjob.php" || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">My Jobs</a></li>
<li <?php if($page == "job_post1.php") echo "class='active'";?>><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>

<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>
<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>
</ul>
</div>