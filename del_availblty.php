<?php
include('config/config.php'); 
include_once "config/common.php";
if(count($_POST)>0 && $_POST['action'] == 'del_avlty'){
    $dbConn = establishcon();
	$data = array();
	$i=0;
	
    $availid = isset($_POST['availid'])?trim($_POST['availid']):"";
	
	dbQuery($dbConn, "DELETE from staff_availability where id = '".$availid."'");
	
	$data = array('success' => 1);
	print json_encode($data);

    closeconn($dbConn);
}