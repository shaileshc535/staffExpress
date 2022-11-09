<?php
$currpage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Staff Express</title>
	<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=62f4a5f553f05f0019e8a1eb&product=inline-share-buttons' async='async'></script>
	
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" href="<?php echo SITEURL;?>images/favicon.png" type="image/x-icon">
	<!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
	
	<!-- CSS files -->
    <link rel="stylesheet" href="<?php echo SITEURL;?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL;?>css/font-awesome.min.css">
	<!-- Owl carousel -->
	<link href="<?php echo SITEURL;?>css/owl.carousel.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo SITEURL;?>css/owl.theme.default.min.css" rel="stylesheet" type="text/css"/>
	<!-- Custom style -->	
	<link rel="stylesheet" href="<?php echo SITEURL;?>css/mystyle.css">
	<link rel="stylesheet" href="<?php echo SITEURL;?>css/flatpicker.css">
    <link rel="stylesheet" href="<?php echo SITEURL;?>css/style.css">
	<link rel="stylesheet" href="<?php echo SITEURL;?>css/responsive.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
	
	
  </head>
  <body>
	<div class="thetop"></div>
	<header class="headersec">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="<?php echo SITEURL;?>"><img src="<?php echo SITEURL;?>images/logo.png" alt="logo"></a>
				<button class="navbar-toggler" type="button" onclick="openNav()" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
				  <ul class="navbar-nav me-auto mb-2 mb-lg-0 leftsidenav">
					<li class="nav-item">
					  <a class="nav-link <?php if($currpage == "index.php") echo 'active';?>" aria-current="page" href="<?php echo SITEURL;?>">Home</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link <?php if($currpage == "how_it_works.php") echo 'active';?>" href="<?php echo SITEURL;?>how_it_works">How it Works</a>
					</li>
					<?php
					$usertype = 0;
					if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId'] != ""){
						$usertype = getUserType($dbConn, $_SESSION['loginUserId']);
						if($usertype == 2)
						{
					?>
					<!--<li class="nav-item">
						<a class="nav-link <?php if($currpage == "i_am_staff.php") echo 'active';?>" href="<?php echo SITEURL;?>staff_details">I am Staff</a>
					  </li>-->
					  <?php
						}
						if($usertype == 1)
						{
						?>
					  <!--<li class="nav-item">
						<a class="nav-link <?php if($currpage == "employer_registration.php") echo 'active';?>" href="<?php echo SITEURL;?>employer_details">I am Employer</a>
					  </li>-->
					  
					<?php
						$postcoverlink = SITEURL."jobpost1";
						}
						else
						$postcoverlink = "";
					}
					else{
						$postcoverlink = SITEURL."signup";
						?>
						<!--<li class="nav-item">
							<a class="nav-link <?php if($currpage == "employer_info.php") echo 'active';?>" href="<?php echo SITEURL;?>i_am_employer">I am Employer</a>
					  	</li>
						<li class="nav-item">
							<a class="nav-link <?php if($currpage == "staff_info.php") echo 'active';?>" href="<?php echo SITEURL;?>i_am_staff">I am Staff</a>
					  	</li>-->
						<?php
					}
					if(!isset($_SESSION['loginUserId']) || $usertype == 1)
					{
					?>
					<li class="nav-item">
						<a class="nav-link <?php if($currpage == "job_post1.php" || $currpage == "job_post2.php" || $currpage == "job_post3.php" || $currpage == "job_post4.php" || $currpage == "job_post5.php") echo 'active';?>" href="<?php echo $postcoverlink;?>">Post for Cover</a>
					  </li>
					<?php
					}
					?>
					<li class="nav-item">
					  <a class="nav-link <?php if($currpage == "search_cover.php") echo 'active';?>" href="<?php echo SITEURL;?>searchcover">Browse Covers</a>
					</li>
					<?php
					if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId'] != ""){
						if($usertype == 2){
							$msglink = SITEURL."staff_messages";
							$mypage = "staff_msgs.php";
						}
						if($usertype == 1){
							$msglink = SITEURL."emp_messages";
							$mypage = "employr_messages.php";
						}
						?>
						<!--<li class="nav-item">
							<a class="nav-link <?php if($currpage == $mypage) echo 'active';?>" href="<?php echo $msglink;?>">Messages</a>
						</li>-->
						<?php
					}
					?>
				  </ul>
				  <ul class="navbar-nav d-flex rightsidenav">
				  <?php
					if(!isset($_SESSION['loginUserId'])){
					?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						  Log In
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
						  <li><a class="dropdown-item" href="<?php echo SITEURL;?>staff_login">Staff Login</a></li>
						  <li><hr class="dropdown-divider"></li>
						  <li><a class="dropdown-item" href="<?php echo SITEURL;?>employer_login">Employer Login</a></li>
						  
						</ul>
					  </li>
					<li class="nav-item">
					  <a class="nav-link onlygetstrdtbutn" href="<?php echo SITEURL;?>signup">Get Started</a>
					</li>
					
					<?php
					}
					else{
						if($usertype == 1)
						$myurl = "myjobs";
						if($usertype == 2)
						$myurl = "staff_details";
						?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle <?php if($currpage == "welcome.php") echo 'active';?>" href="<?php echo SITEURL;?><?php echo $myurl;?>">My Account</a>
							<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							  <li><a class="dropdown-item" href="<?php echo SITEURL;?>logout">Logout</a></li>
							  
							</ul>
					  	</li>
						<!--<li class="nav-item">
						<a class="nav-link" href="<?php echo SITEURL;?>logout">Logout</a>
						</li>-->
					<?php
					}
					?>
				  </ul>
				  <li class="nav-item">
						<a class="nav-link" href="https://www.facebook.com/staffexpressteam" class="soslicn"><img src="<?php echo SITEURL;?>images/facebook.png" alt="facebook" width="30px"></a>
					</li>
					<li class="nav-item">
						<a class="nav-link splforicon" href="https://www.instagram.com/staffexpressteam" class="soslicn"><img src="<?php echo SITEURL;?>images/instagram.png" alt="insta" width="30px"></a>
					</li>
				</div>
				
				<!-- For Mobile and Tab -->
				<div class="mobsoclicon">
						<a class="nav-link" href="https://www.facebook.com/staffexpressteam" class="soslicn"><img src="<?php echo SITEURL;?>images/facebook.png" alt="facebook" width="20px"></a> &nbsp; <a class="nav-link splforicon" href="https://www.instagram.com/staffexpressteam" class="soslicn"><img src="<?php echo SITEURL;?>images/instagram.png" alt="insta" width="20px"></a>
				  </div>
				<div id="mySidenav" class="sidenav">
				  <a class="navbar-brand" href="<?php echo SITEURL;?>"><img src="<?php echo SITEURL;?>images/logo.png" alt="logo"></a>
				  
				  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
				  <ul class="navbar-nav me-auto mb-2 mb-lg-0 leftsidenav">
					<li class="nav-item">
					  <a class="nav-link <?php if($currpage == "index.php") echo 'active';?>" aria-current="page" href="<?php echo SITEURL;?>">Home</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link <?php if($currpage == "how_it_works.php") echo 'active';?>" href="<?php echo SITEURL;?>how_it_works">How it Works</a>
					</li>
					<?php
					if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId'] != ""){
						if($usertype == 2)
						{
					?>
					  <!--<li class="nav-item">
						<a class="nav-link <?php if($currpage == "i_am_staff.php") echo 'active';?>" href="<?php echo SITEURL;?>staff_details">I am Staff</a>
					  </li>-->
					  <?php
						}
						if($usertype == 1){
						?>
					  <!--<li class="nav-item">
						<a class="nav-link <?php if($currpage == "employer_registration.php") echo 'active';?>" href="<?php echo SITEURL;?>employer_details">I am Employer</a>
					  </li>-->
					  
					  <?php
						}
					}
					else{
						?>
						<!--<li class="nav-item">
							<a class="nav-link <?php if($currpage == "employer_info.php") echo 'active';?>" href="<?php echo SITEURL;?>i_am_employer">I am Employer</a>
					  	</li>
						<li class="nav-item">
							<a class="nav-link <?php if($currpage == "staff_info.php") echo 'active';?>" href="<?php echo SITEURL;?>i_am_staff">I am Staff</a>
					  	</li>-->
						  <li class="nav-item">
						<a class="nav-link <?php if($currpage == "staff_login.php") echo 'active';?>" href="<?php echo SITEURL;?>staff_login">Staff Login</a>
						</li>
						<li class="nav-item">
						<a class="nav-link <?php if($currpage == "employer_login.php") echo 'active';?>" href="<?php echo SITEURL;?>employer_login">Employer Login</a>
						</li>
					<?php
					}
					if(!isset($_SESSION['loginUserId']) || $usertype == 1)
					{
					?>
					<li class="nav-item">
						<a class="nav-link <?php if($currpage == "job_post1.php" || $currpage == "job_post2.php" || $currpage == "job_post3.php" || $currpage == "job_post4.php" || $currpage == "job_post5.php") echo 'active';?>" href="<?php echo $postcoverlink;?>">Post for Cover</a>
					</li>
					<?php
					}
					?>
					<li class="nav-item">
					  <a class="nav-link <?php if($currpage == "search_cover.php") echo 'active';?>" href="<?php echo SITEURL;?>searchcover">Browse Covers</a>
					</li>
					<?php
					if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId'] != ""){
						?>
						<li class="nav-item">
							<a class="nav-link <?php if($currpage == $mypage) echo 'active';?>" href="<?php echo $msglink;?>">Messages</a>
						</li>
						<?php
					}
					?>
				  </ul>
				  
				  <?php
					if(isset($_SESSION['loginUserId']) && $_SESSION['loginUserId'] != ""){
						?>
					<ul class="mobilebelownav">
						<li class="nav-item">
							<a class="nav-link" href="<?php echo SITEURL;?><?php echo $myurl;?>">My Account</a>
					  	</li>
						<li class="nav-item">
						<a class="nav-link" href="<?php echo SITEURL;?>logout">Logout</a>
						</li>
					</ul>
					<?php
					}
					else{
						?>
						<ul class="mobilebelownav">
							<li class="nav-item">
							<a class="nav-link" href="<?php echo SITEURL;?>signup">Get Started</a>
							</li>
						</ul>
						<?php
					}
					?>

					<div class="menu_img">
						<img src="images/menu_img.png" alt="">
					</div>
				</div>
				
			</div>
		</nav>
	</header>