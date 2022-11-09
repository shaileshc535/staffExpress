<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

//$id = base64_decode(trim('NQ=='));
//$sql = "SELECT * from `pages` where id = '".$id."'";
//$sql_res = dbQuery($dbConn,$sql);
//$sql_res_fetch = dbFetchAssoc($sql_res);
?>
<section class="login_page privacy_sty">
	<div class="container">
		<div class="login" style="padding:40px 0;text-align:center;">
			<h4 style="text-align:center;">Registration Successful</h4>
			<p>Thank you for registering with Staff Express.</p>
			<p><img src="<?php echo SITEURL;?>images/thanks.png" alt="" width="150"></p>
			<div class="homeviwecoverbutn"><a href="<?php echo SITEURL;?>searchcover">Browse Jobs</a></div>
		</div>
	</div>
</section>
	
	
<?php include_once('footer.php');?>