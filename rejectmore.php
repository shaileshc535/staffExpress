<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "rejectmore"){
    $dbConn = establishcon();
    $currtime = date('Y-m-d H:i:s');
    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";

    $check = dbQuery($dbConn, "SELECT id,extra_hours from staff_job_payment where job_id = '".$jobid."' and staff_id = '".$userid."'");
    if(dbNumRows($check) > 0){
    $row = dbFetchArray($check);
        if($row['extra_hours'] != 0){
            $extra = $row['extra_hours'];
            dbQuery($dbConn, "UPDATE staff_job_payment set extra_hours_approved=2 where job_id = '".$jobid."' and staff_id = '".$userid."'");

            /*$employer = dbQuery($dbConn, "SELECT a.title,a.jobdate2,a.endtime from job_details a where a.id = '".$jobid."'");
            $fetch = dbFetchArray($employer);
            $jobenddate = $fetch['jobdate2'];
            $endtime = $fetch['endtime'];
            $jobendttime = $jobenddate." ".$endtime;
            $jobendttime = strtotime($jobendttime);

            $newdate = strtotime("+".$extra." hours", $jobendttime);
            $newenddate = date("Y-m-d", $newdate);
            $newendtime = date("H:i:s", $newdate);
            dbQuery($dbConn, "UPDATE job_details set jobdate2 = '".$newenddate."', endtime = '".$newendtime."' where id = '".$jobid."'");*/

            $getuser = dbQuery($dbConn, "SELECT name,email,phone from users where id = '".$userid."'");
            $rowuser = dbFetchArray($getuser);

            $to = $rowuser['email'];
            $subject = "Employer Has Rejected Your Request for Extra Hours";

            $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid; '>
			<tr>
				<td style='padding:5px; font-size:18px; color:green;text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
			</tr>
			<tr>
			<td colspan='2'>&nbsp;</td>
			</tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Hello ".$rowuser['name'].",</td>
            </tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Your employer has rejected request for ".$row['extra_hours']." extra hours for the job: ".stripslashes($fetch['title']).".</td>
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

            $smsbody = "Your employer has rejected request for ".$row['extra_hours']." extra hours for the job: ".stripslashes($fetch['title']).".";

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
        
            print json_encode(array('success' => 1, 'extrahour' => $row['extra_hours']));
    }
    else{
        print json_encode(array('success' => 2));
    }
        
    }
    else{
        print json_encode(array('success' => 2));
    }

    closeconn($dbConn);
}