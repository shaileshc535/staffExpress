<?php
include('config/config.php'); 
include_once "config/common.php";
if($_POST['action'] == "fetchstaffcl"){
    $dbConn = establishcon();

    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
    $today = date('Y-m-d');

    $check = dbQuery($dbConn, "SELECT cover_letter from staff_details where staff_id = '".$userid."'");
    if(dbNumRows($check) > 0){
        $row = dbFetchArray($check);
        $path = "uploads/resumes/".$row['cover_letter'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
            print json_encode(array('success' => 1, 'cover_letter' => $row['cover_letter'], 'ext' => $ext));
    }
    else{
        print json_encode(array('success' => 3));
    }

    closeconn($dbConn);
}