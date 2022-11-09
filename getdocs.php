<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
$userid = isset($_POST['userid'])?trim($_POST['userid']):"";
$data = array();
$mydocs = array();
$i=0;

if($_POST['action'] == "loadcl"){
    
	$check = dbQuery($dbConn, "SELECT cover_letter from staff_documents where staff_id = '".$userid."' order by id desc limit 0,1");
	$getdetls = dbFetchArray($check);
	/*while($getdetls = dbFetchArray($check)){
		$mydocs[$i] = array('docname' => $getdetls['cover_letter']);
		
		$i++;
		$docname = $getdetls['cover_letter'];
	}*/
	$docname = $getdetls['cover_letter'];
	
		$path = "uploads/resumes/".$docname;
        $ext = pathinfo($path, PATHINFO_EXTENSION);
    print json_encode(array('success' => 1, 'docname' => $docname, 'ext' => $ext));
    
}
if($_POST['action'] == "loadqual"){
    
	$check = dbQuery($dbConn, "SELECT qualifications from staff_qualifications where staff_id = '".$userid."' order by id desc limit 0,5");
												
	while($getdetls = dbFetchArray($check)){
		$mydocs[$i] = array('docname' => $getdetls['qualifications']);
		
		$i++;
	}
    print json_encode(array('success' => 1, 'mycls' => $mydocs));
    
}
if($_POST['action'] == "loadcert"){
    
	$check = dbQuery($dbConn, "SELECT `certificate` from staff_certificate where staff_id = '".$userid."' order by id desc limit 0,5");
												
	while($getdetls = dbFetchArray($check)){
		$mydocs[$i] = array('docname' => $getdetls['certificate']);
		
		$i++;
	}
    print json_encode(array('success' => 1, 'mycls' => $mydocs));
    
}


closeconn($dbConn);