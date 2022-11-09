<?php
include('config/config.php'); 
include_once "config/common.php";
if($_POST['action'] == "fetchstaffemail"){
    $dbConn = establishcon();

    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
    $today = date('Y-m-d');

    $check = dbQuery($dbConn, "SELECT email from users where id = '".$userid."'");
    if(dbNumRows($check) > 0){
        $row = dbFetchArray($check);
        
            print json_encode(array('success' => 1, 'email' => $row['email']));
    }
    else{
        print json_encode(array('success' => 3));
    }

    closeconn($dbConn);
}