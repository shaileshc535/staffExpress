<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

$id = base64_decode(trim('NQ=='));
$sql = "SELECT * from `pages` where id = '".$id."'";
$sql_res = dbQuery($dbConn,$sql);
$sql_res_fetch = dbFetchAssoc($sql_res);
?>
<section class="login_page privacy_sty">
	<div class="container">
		<div class="login" style="padding:40px 0;">
			<h4><?php echo stripslashes($sql_res_fetch['name']);?></h4>
			<?php echo stripslashes($sql_res_fetch['description']);?>					
		</div>
	</div>
</section>
	
	
<?php include_once('footer.php');?>