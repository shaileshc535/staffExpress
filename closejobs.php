<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();

$today = date('Y-m-d');
$sqlTotal = "SELECT a.id,a.postdate from job_details a where a.isclosed=0 and a.postcomplete=1";
$jobsTotal = dbQuery($dbConn, $sqlTotal);
$total_cover = dbNumRows($jobsTotal);
if($total_cover > 0){
	while($row = dbFetchArray($jobsTotal)){
		$date1 = new DateTime($today);
		$date2 = new DateTime($row['postdate']);
		$diff = $date1->diff($date2)->format("%a");
		if($diff > 30){
			dbQuery($dbConn, "UPDATE job_details set isclosed=1 where id = '".$row['id']."'");
		}
	}
}

closeconn($dbConn);
?>