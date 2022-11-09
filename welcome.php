<?php
include('config/config.php');
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

if (!isset($_SESSION['loginUserId'])) {
	echo "<script>location.href='" . SITEURL . "signup'</script>";
	exit;
}

$user = dbQuery($dbConn, "SELECT name from users where id = '" . $_SESSION['loginUserId'] . "'");
$row = dbFetchArray($user);
$username = $row['name'];
$usertype = getUserType($dbConn, $_SESSION['loginUserId']);
?>

<div class="works">
	<div class="container">
		<div class="inneremploypg">
			<div class="row">
				<?php
				if ($usertype == 1)
					include_once "employer_left.php";
				if ($usertype == 2)
					include_once "staff_left.php";
				?>
				<div class="col-lg-9">
					<div class="works_heading innerhding">
						<h4>Welcome <?php echo $username; ?></h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php include_once('footer.php'); ?>