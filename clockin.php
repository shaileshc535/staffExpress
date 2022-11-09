<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "clockin"){
    $dbConn = establishcon();
    $currtime = date('Y-m-d H:i:s');
    $today = date('Y-m-d');
    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";

    $code = mt_rand('10000', '999999');

    $check = dbQuery($dbConn, "SELECT id from staff_job_clock where job_id = '".$jobid."' and staff_id = '".$userid."' and jobstartdate = '".$today."'");
    if(dbNumRows($check) == 0){
        dbQuery($dbConn, "INSERT INTO staff_job_clock set job_id = '".$jobid."', staff_id = '".$userid."', jobstartdate = '".$today."', clockincode = '".$code."'");
    
    $employer = dbQuery($dbConn, "SELECT a.title,b.email,b.name,b.phone from job_details a inner join users b on a.employer_id=b.id where a.id = '".$jobid."'");
    $fetch = dbFetchArray($employer);

    $getuser = dbQuery($dbConn, "SELECT name,phone from users where id = '".$userid."'");
	$rowuser = dbFetchArray($getuser);

    $to = $fetch['email'];
    $subject = "Staff Have Sent Clock In Code";

    $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid;'>
    <tr>
        <td style='padding:5px; font-size:18px; color:green; text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
    </tr>
    <tr>
    <td colspan='2'>&nbsp;</td>
    </tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Hello ".$fetch['name'].",</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>".$rowuser['name']." has sent the clock in code for the job: ".stripslashes($fetch['title'])." or staff can also enter the code.</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Please enter the code ".$code." to start the clock.</td>
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

    $smsbody = $rowuser['name']." has sent the clock in code for the job: ".stripslashes($fetch['title']).".<br>Please enter the code ".$code." to start the clock or staff can also enter the code.";

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

        // code to staff
        $phone = $rowuser['phone'];
    if(strpos($phone, "+") !== false)
    $phone = $phone;
    else
    $phone = "+".$phone;

    $smsbody = "Please enter the code ".$code." to start the clock. If you have problem, employer also can enter the code.";

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