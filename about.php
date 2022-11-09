<?php
include('config/config.php');
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

$id = base64_decode(trim('MTI='));
$sql = "SELECT * from `pages` where id = '" . $id . "'";
$sql_res = dbQuery($dbConn, $sql);
$sql_res_fetch = dbFetchAssoc($sql_res);
?>
<section class="login_page termspg">
	<div class="container2">
		<div class="row lessmar">
			<!--<div class="col-sm-6 lesspadd">
				<img src="<?php echo SITEURL; ?>images/stafflog_img.jpg" class="img-responsive" alt="loginimg" />
			</div>-->
			<div class="col-lg-12">
				<div class="login">
					<h4><?php echo stripslashes($sql_res_fetch['name']); ?></h4>
					<?php echo stripslashes($sql_res_fetch['description']); ?>

				</div>
			</div>
		</div>
	</div>
</section>


<?php include_once('footer.php'); ?>