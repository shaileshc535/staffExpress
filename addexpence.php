<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "addexp"){
    $dbConn = establishcon();

    $myexp = isset($_POST['myexp'])?trim($_POST['myexp']):"";
    $catid = isset($_POST['catid'])?trim($_POST['catid']):"";
	
    dbQuery($dbConn, "INSERT INTO staff_experience set staff_id = '".$_SESSION['loginUserId']."', catid = '".$catid."', experience = '".$myexp."'");

    print json_encode(array('success' => 1));

    closeconn($dbConn);
}