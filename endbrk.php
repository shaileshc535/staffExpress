<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "endbrk"){
    $username = "ninepebblesteam@gmail.com";
    $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
    $str = $username.":".$password;
    $auth = base64_encode($str);

    $dbConn = establishcon();
    $currtime = date('Y-m-d H:i:s');
    $today = date('Y-m-d');
    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";

    $code = mt_rand('10000', '999999');

    $check = dbQuery($dbConn, "SELECT id,brkstarttime,brkendtime from staff_job_breaks where job_id = '".$jobid."' and staff_id = '".$userid."' and breakday = '".$today."' order by id desc limit 0,1");
    $getbrk = dbFetchArray($check);
    if(dbNumRows($check) == 0){
        $canendbrk = 0;
    }
    else{
        if($getbrk['brkstarttime'] != "0000-00-00 00:00:00" && $getbrk['brkendtime'] == "0000-00-00 00:00:00"){
            $canendbrk = 1;
        }
        else
        $canendbrk = 0;
    }

    if($canendbrk == 1){
        dbQuery($dbConn, "UPDATE staff_job_breaks SET brkendcode = '".$code."' where job_id = '".$jobid."' and staff_id = '".$userid."' and breakday = '".$today."'");

    $employer = dbQuery($dbConn, "SELECT a.title,b.email,b.name,b.phone from job_details a inner join users b on a.employer_id=b.id where a.id = '".$jobid."'");
    $fetch = dbFetchArray($employer);
    
    $getuser = dbQuery($dbConn, "SELECT name,phone from users where id = '".$userid."'");
	$rowuser = dbFetchArray($getuser);

    // code to staff
    $phone = $rowuser['phone'];
    if(strpos($phone, "+") !== false)
    $phone = $phone;
    else
    $phone = "+".$phone;

    $smsbody = "Please enter the code ".$code." to end break.";

    $data_string = '{
        "messages": [
        {
            "to": "'.$phone.'",
            "source": "sdk",
            "body": "'.$smsbody.'"
        }
        ]
    }';

    $ch = curl_init("https://rest.clicksend.com/v3/sms/send");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Authorization: Basic '.$auth.''
                        )
        );
        $result = curl_exec($ch);
        $result = json_decode($result);


        print json_encode(array('success' => 1));
    }
    else{
        print json_encode(array('success' => 2));
    }

    closeconn($dbConn);
}