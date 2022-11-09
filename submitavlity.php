<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "submitavalibly"){
    $dbConn = establishcon();

    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
    $from = isset($_POST['from'])?trim($_POST['from']):"";
    $starttime = isset($_POST['starttime'])?trim($_POST['starttime']):"";
    $to = isset($_POST['to'])?trim($_POST['to']):"";
    $endtime = isset($_POST['endtime'])?trim($_POST['endtime']):"";
    $avlblty = isset($_POST['avlblty'])?trim($_POST['avlblty']):"";

    $staffdetls = dbQuery($dbConn, "SELECT superannuation from staff_details where staff_id = '".$userid."'");
    $row = dbFetchArray($staffdetls);

    $check = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
    if(dbNumRows($check) > 0){
        dbQuery($dbConn, "UPDATE job_status set `availble_date1` = '".$from."', availble_time1 = '".$starttime."', `availble_date2` = '".$to."', availble_time2 = '".$endtime."', avlblty = '".$avlblty."' where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
    }
    else{
        dbQuery($dbConn, "INSERT INTO job_status set contacting=1, jobid = '".$jobid."', application_sent_to = '".$userid."', `availble_date1` = '".$from."', availble_time1 = '".$starttime."', `availble_date2` = '".$to."', availble_time2 = '".$endtime."', avlblty = '".$avlblty."'");
    }
    
    
    print json_encode(array('success' => 1));
    

    closeconn($dbConn);
}