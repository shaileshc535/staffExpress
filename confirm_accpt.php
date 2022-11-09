<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "yes_confirm"){
    $dbConn = establishcon();
	$today = date('Y-m-d');

    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
	$avl_update = isset($_POST['avl_update'])?tep_db_input($_POST['avl_update']):"";

    dbQuery($dbConn, "UPDATE job_status set hired=1, hiredon = '".$today."' where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
	
    $employer = dbQuery($dbConn, "SELECT a.title,a.howmnypeople,a.jobdate2,b.email,b.name,b.business_name,b.phone from job_details a inner join users b on a.employer_id=b.id where a.id = '".$jobid."'");
    $fetch = dbFetchArray($employer);
	
	$contact = dbQuery($dbConn, "SELECT avlblty from job_status where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
	$getstat = dbFetchArray($contact);
	if($getstat['avlblty'] == 1){
		if($fetch['howmnypeople'] == 1){
				//dbQuery($dbConn, "UPDATE job_details set isclosed=1 where id = '".$jobid."'");
		}
		else{
			$checkhired = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$jobid."' and hired=1");
			if(dbNumRows($checkhired) == $fetch['howmnypeople']){
				//dbQuery($dbConn, "UPDATE job_details set isclosed=1 where id = '".$jobid."'");
			}
		}
	}
	else{
		//if($avl_update == 1){
			$avldays = dbQuery($dbConn, "SELECT availble_date FROM staff_availability where jobid = '".$jobid."' and staff_id = '".$userid."' order by id desc limit 0,1");
			$myavldays = dbFetchArray($avldays);
			$last_available = $myavldays['availble_date'];
			 
				 if($fetch['howmnypeople'] == 1){
					 if($last_available == $fetch['jobdate2']){
						//dbQuery($dbConn, "UPDATE job_details set isclosed=1 where id = '".$jobid."'");
					 }
				}
				else{
					$checkhired = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$jobid."' and hired=1");
					if(dbNumRows($checkhired) == $fetch['howmnypeople']){
						if($last_available == $fetch['jobdate2']){
							//dbQuery($dbConn, "UPDATE job_details set isclosed=1 where id = '".$jobid."'");
						}
					}
				}
				 
			$startdate = date('Y-m-d', strtotime('+1 day', strtotime($last_available)));
			//dbQuery($dbConn, "UPDATE job_details set jobdate = '".$startdate."' where id = '".$jobid."'");
		//}
	}

    $candidate = dbQuery($dbConn, "SELECT a.id,a.name,a.email,a.phone,b.notified from users a inner join staff_details b on a.id=b.staff_id where a.id = '".$userid."'");
    $row = dbFetchArray($candidate);

	if($row['notified'] == "Email" || $row['notified'] == "Both"){
    $to = $row['email'];
    $subject = "You have accepted the confirmation";

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
    <td colspan='2' style='padding-left:10px;'>You have accepted the hiring confirmation for the job: ".stripslashes($fetch['title']).". So you are hired.</td>
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
	}
	if($row['notified'] == "SMS" || $row['notified'] == "Both"){
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

        $smsbody = "You have accepted the hiring confirmation for the job: ".stripslashes($fetch['title']).". So you are hired.";

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
    }
	
	// sending sms
    $username = "ninepebblesteam@gmail.com";
    $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
    $str = $username.":".$password;
    $auth = base64_encode($str);

    $phone = $fetch['phone'];
    if(strpos($phone, "+") !== false)
    $phone = $phone;
    else
    $phone = "+".$phone;

    $smsbody = "The staff accepted the hiring confirmation for ".stripslashes($fetch['title'])." job role. So the staff is hired.";

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