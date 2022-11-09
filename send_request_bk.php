<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "send_req"){
    $dbConn = establishcon();

    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";

	dbQuery($dbConn, "UPDATE job_status set confirmation_sent = 1 where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
	
    $employer = dbQuery($dbConn, "SELECT a.title,b.email,b.name,b.business_name from job_details a inner join users b on a.employer_id=b.id where a.id = '".$jobid."'");
    $fetch = dbFetchArray($employer);

    $candidate = dbQuery($dbConn, "SELECT name,email,phone from users where id = '".$userid."'");
    $row = dbFetchArray($candidate);
	
	$link = SITEURL."myjobview/".base64_encode($jobid)."/?do=confirmjob";

    $to = $row['email'];
    $subject = "Hiring Confirmation";

    $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid;'>
    <tr>
        <td style='padding:5px; font-size:18px; color:green;text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
    </tr>
    <tr>
    <td colspan='2'>&nbsp;</td>
    </tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Hello ".$row['name'].",</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>The employer ".stripslashes($fetch['name'])." has sent you hiring confirmation for the job: ".stripslashes($fetch['title']).". Please click the below link to confirm.</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
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

    
	
	// sending sms
    $username = "ninepebblesteam@gmail.com";
    $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
    $str = $username.":".$password;
    $auth = base64_encode($str);

    $phone = $row['phone'];
    if(strpos($phone, "+") !== false)
    $phone = $phone;
    else
    $phone = "+".$phone;

    $smsbody = "The employer ".stripslashes($fetch['name'])." has sent you hiring confirmation for the job: ".stripslashes($fetch['title']).". Please click the link received in the mail to confirm.";

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

    closeconn($dbConn);
}