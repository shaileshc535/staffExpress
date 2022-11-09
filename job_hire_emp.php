<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
$job = isset($_REQUEST['job'])?base64_decode($_REQUEST['job']):"";
$user = isset($_REQUEST['user'])?base64_decode($_REQUEST['user']):"";
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login?job=".$job."&user=".$user."'</script>";
	exit;
}

$myjob = dbQuery($dbConn, "SELECT a.*,b.phone,b.business_name,c.category FROM job_details a inner join users b on a.employer_id=b.id inner join category c on a.catid=c.id where a.id='".$job."'");
$fetch = dbFetchArray($myjob);

   
?>

	<div class="works">
		<div class="container">
			<div class="row">
			<?php include_once "employer_left.php";?>
				<div class="col-lg-9">
					
					<div class="works_heading">
						<h6>Escrow amount <?php echo $fetch['escrow_amt'];?> AUD deposited for the job: <?php echo stripslashes($fetch['title']);?></h6>
						
						<div class="row">
							<div class="col-sm-8">
								
							</div>
						</div>
						
					</div>

				</div>
				
			</div>
		</div>
	</div>
	
	<?php 
    include_once('footer.php');
?>