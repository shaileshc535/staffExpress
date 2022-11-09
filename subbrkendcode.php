<?php
include('config/config.php'); 
include_once "config/common.php";
include "phpmailer/class.phpmailer.php";
if($_REQUEST['action'] == "brkendcodesub"){
    $dbConn = establishcon();
    $currtime = date('Y-m-d H:i:s');
    $username = "ninepebblesteam@gmail.com";
    $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
    $str = $username.":".$password;
    $auth = base64_encode($str);

    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
    $breakendcode = isset($_POST['breakendcode'])?trim($_POST['breakendcode']):"";
    $today = date('Y-m-d');

    $check = dbQuery($dbConn, "SELECT id,brkendcode from staff_job_breaks where job_id = '".$jobid."' and staff_id = '".$userid."' and breakday = '".$today."' order by id desc limit 0,1");
    if(dbNumRows($check) > 0){
        $getcode = dbFetchArray($check);
        if($getcode['brkendcode'] == $breakendcode){
            dbQuery($dbConn, "UPDATE staff_job_breaks SET brkendtime = '".$currtime."' where job_id = '".$jobid."' and staff_id = '".$userid."' and brkendcode = '".$breakendcode."' and breakday = '".$today."'");

            $employer = dbQuery($dbConn, "SELECT a.title,b.email,b.name,b.phone from job_details a inner join users b on a.employer_id=b.id where a.id = '".$jobid."'");
            $fetch = dbFetchArray($employer);

            // sms to employer
            $phone = $fetch['phone'];
            if(strpos($phone, "+") !== false)
            $phone = $phone;
            else
            $phone = "+".$phone;

            $smsbody = "Staff's break has been ended for the job: ".stripslashes($fetch['title']).".";

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

            $getuser = dbQuery($dbConn, "SELECT a.id,a.name,a.phone,a.email,b.notified from users a inner join staff_details b on a.id=b.staff_id where a.id = '".$userid."'");
            $rowuser = dbFetchArray($getuser);

            if($rowuser['notified'] == "Email" || $rowuser['notified'] == "Both"){
				$mail = new PHPMailer();
				$mail->IsSMTP();

				$mail->Host       = "cp-wc12.syd02.ds.network"; // cp-wc12.syd02.ds.network
				$mail->SMTPAuth   = true;  
				$mail->SMTPSecure = "ssl";                
				$mail->Port       = 465;                    
				$mail->Username   = "contact@staffexpress.com.au";            
				$mail->Password   = "QYhi[=Aoor{t";

				$mail->From = ADMINEMAIL;
				$mail->FromName = "Staff Express";
				$mail->Subject = "Break has been ended";
				$mail->isHTML(true);
				$mail->AddAddress($rowuser['email']);
				
            //$to = $rowuser['email'];
            //$subject = "Break has been ended";

            $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
			<tr>
            <td colspan='2'>&nbsp;</td>
        	</tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Hello ".$rowuser['name'].",</td>
            </tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Break has been ended for the job: ".stripslashes($fetch['title']).".</td>
            </tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            <tr>
            <td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>
            </tr>
            </table>";
			
			$mail->Body = $message;
			  if(!$mail->Send()) {
				echo $mail->ErrorInfo;
			  } else { 
			  }

            //$headers = "MIME-Version: 1.0" . "\r\n";
            //$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            //$headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

            //mail($to,$subject,$message,$headers);
            }
            if($rowuser['notified'] == "SMS" || $rowuser['notified'] == "Both"){
            
            $phone = $rowuser['phone'];
            if(strpos($phone, "+") !== false)
            $phone = $phone;
            else
            $phone = "+".$phone;

            $smsbody = "Break has been ended for the job: ".stripslashes($fetch['title']).".";

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

            print json_encode(array('success' => 1));
        }
        else{
            print json_encode(array('success' => 2));
        }
    }
    else{
        print json_encode(array('success' => 3));
    }

    closeconn($dbConn);
}