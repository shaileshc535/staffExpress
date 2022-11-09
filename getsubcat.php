<?php
include('config/config.php'); 
include_once "config/common.php";
if(count($_POST)>0){
    $dbConn = establishcon();
	$data = array();
	$subcat = array();
	$i=0;
	
    $catid = isset($_POST['catid'])?trim($_POST['catid']):"";
	
	$getsubcats = dbQuery($dbConn, "SELECT id,category from category where parent_id = '".$catid."' order by category");
	while($subcatrow = dbFetchArray($getsubcats)){
		$subcat[$i++] = array('id' => $subcatrow['id'], 'category' => stripslashes($subcatrow['category']));
	}
    
	$data = array('success' => 1, 'subcat' => $subcat);
	print json_encode($data);

    closeconn($dbConn);
}