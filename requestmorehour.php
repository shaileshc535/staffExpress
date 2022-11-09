<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "requestmorehour"){
    $dbConn = establishcon();
    $currtime = date('Y-m-d H:i:s');
    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
    $morehours = isset($_POST['morehours'])?trim($_POST['morehours']):"";

    $check = dbQuery($dbConn, "SELECT id from staff_job_payment where job_id = '".$jobid."' and staff_id = '".$userid."'");
    if(dbNumRows($check) > 0){
        dbQuery($dbConn, "UPDATE staff_job_payment set extra_hours = '".$morehours."' where job_id = '".$jobid."' and staff_id = '".$userid."'");
    
    $employer = dbQuery($dbConn, "SELECT a.title,b.email,b.name,b.phone from job_details a inner join users b on a.employer_id=b.id where a.id = '".$jobid."'");
    $fetch = dbFetchArray($employer);

    $getuser = dbQuery($dbConn, "SELECT name from users where id = '".$userid."'");
	$rowuser = dbFetchArray($getuser);

    $link = SITEURL."managehire/?id=".base64_encode($jobid)."&userid=".base64_encode($userid)."";

    $to = $fetch['email'];
    $subject = "Staff Has Requested for Extra Hours";

    $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid; '>
			<tr>
				<td style='padding:5px; font-size:18px; color:green;text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
			</tr>
			<tr>
			<td colspan='2'>&nbsp;</td>
			</tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Hello ".$fetch['name'].",</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>".$rowuser['name']." has requested for ".$morehours." extra hours.</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Click the below link to approve/disapprove:</td>
    </tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'><a href='".$link."' target='_blank'>".$link."</a></td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>
    </tr>
    </table>";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

    mail($to,$subject,$message,$headers);

    $username = "ninepebblesteam@gmail.com";
    $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
    $str = $username.":".$password;
    $auth = base64_encode($str);

    $phone = $fetch['phone'];
    if(strpos($phone, "+") !== false)
    $phone = $phone;
    else
    $phone = "+".$phone;

    $smsbody = $rowuser['name']." has requested for ".$morehours." extra hours.<br>Please click the link ".$link." to approve/disapprove.";

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