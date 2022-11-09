<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "allowmore"){
    $dbConn = establishcon();
    $currtime = date('Y-m-d H:i:s');
    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";


    $check = dbQuery($dbConn, "SELECT id,clockincode from staff_job_payment where job_id = '".$jobid."' and staff_id = '".$userid."'");
    if(dbNumRows($check) > 0){


        $employer = dbQuery($dbConn, "SELECT a.title from job_details a where a.id = '".$jobid."'");
        $fetch = dbFetchArray($employer);

        $getuser = dbQuery($dbConn, "SELECT name,email,phone from users where id = '".$userid."'");
        $rowuser = dbFetchArray($getuser);

        dbQuery($dbConn, "UPDATE staff_job_payment set emp_req_extra_hours=1 where job_id = '".$jobid."' and staff_id = '".$userid."'");

        $to = $rowuser['email'];
        $subject = "Employer Has Requested to Work For Some More Hours";

        $message = "<table width='100%'>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Hello ".$rowuser['name'].",</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Your employer has requested to work for some more hours for the job: ".stripslashes($fetch['title']).". Please let him know how many hours you want to work more.</td>
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

        $phone = $rowuser['phone'];
        if(strpos($phone, "+") !== false)
        $phone = $phone;
        else
        $phone = "+".$phone;

        $smsbody = "Your employer has requested to work for some more hours for the job: ".stripslashes($fetch['title']).". Please let him know how many hours you want to work more.";

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