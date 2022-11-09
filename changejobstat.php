<?php
include('config/config.php'); 
include_once "config/common.php";
if(count($_POST) > 0){
	$dbConn = establishcon();
	$myjobval = isset($_POST['myjobval'])?tep_db_input($_POST['myjobval']):"";
	$myjobid = isset($_POST['myjobid'])?tep_db_input($_POST['myjobid']):"";
	
	dbQuery($dbConn, "UPDATE job_details SET isclosed = '".$myjobval."' WHERE id = '".$myjobid."'");
	
	print json_encode(array('success' => 1));
	closeconn($dbConn);
}