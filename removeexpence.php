<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "remexp"){
    $dbConn = establishcon();

    $catid = isset($_POST['catid'])?trim($_POST['catid']):"";
	
    dbQuery($dbConn, "DELETE FROM staff_experience WHERE staff_id = '".$_SESSION['loginUserId']."' AND catid = '".$catid."'");
	
	$exp = dbQuery($dbConn, "select id from staff_experience where staff_id='".$_SESSION['loginUserId']."'");
	$total = dbNumRows($exp);

    print json_encode(array('success' => 1, 'total' => $total));

    closeconn($dbConn);
}