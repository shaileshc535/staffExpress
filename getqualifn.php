<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
$data = array();
$allquals = array();
$i=0;
if(count($_POST) > 0 && $_POST['action'] == "getqualifn"){
    $cat = trim($_POST['cat']);

    $qual = dbQuery($dbConn, "SELECT * from qualifications where catid = '".$cat."'");
    while($row = dbFetchArray($qual)){
        $allquals[$i++] = array('id' => $row['id'], 'qual' => $row['qualifictn']);
    }

    $data = array('allquals' => $allquals);
    print json_encode($data);
}

closeconn($dbConn);