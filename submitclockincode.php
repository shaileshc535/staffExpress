<?php
include('config/config.php'); 
include_once "config/common.php";
include "phpmailer/class.phpmailer.php";
if($_REQUEST['action'] == "submitcode"){
    $dbConn = establishcon();
    $currtime = date('Y-m-d H:i:s');
    $username = "ninepebblesteam@gmail.com";
    $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
    $str = $username.":".$password;
    $auth = base64_encode($str);

    $today = date('Y-m-d');

    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
    $clockincode = isset($_POST['clockincode'])?trim($_POST['clockincode']):"";

    $code = mt_rand('10000', '999999');

    $check = dbQuery($dbConn, "SELECT id,clockincode from staff_job_clock where job_id = '".$jobid."' and staff_id = '".$userid."' and jobstartdate = '".$today."'");
    if(dbNumRows($check) > 0){
        $getcode = dbFetchArray($check);
        if($getcode['clockincode'] == $clockincode){
            dbQuery($dbConn, "UPDATE staff_job_clock SET clockintime = '".$currtime."' where job_id = '".$jobid."' and staff_id = '".$userid."' and clockincode = '".$clockincode."' and jobstartdate = '".$today."'");

            $employer = dbQuery($dbConn, "SELECT a.title,b.email,b.name,b.phone,b.notified from job_details a inner join users b on a.employer_id=b.id where a.id = '".$jobid."'");
            $fetch = dbFetchArray($employer);
	
			if($fetch['notified'] == "Email" || $fetch['notified'] == "Both"){
				
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
				$mail->Subject = "The clock has started";
				$mail->isHTML(true);
				$mail->AddAddress($fetch['email']);
				
				//$to = $fetch['email'];
				
				//$subject = "The clock is started";

				$message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
				<tr>
				<td colspan='2'>&nbsp;</td>
				</tr>
				<tr>
				<td colspan='2' style='padding-left:10px;'>Hello ".$fetch['name'].",</td>
				</tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<tr>
				<td colspan='2' style='padding-left:10px;'>Clock has been started for the job: ".stripslashes($fetch['title']).".</td>
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
			if($fetch['notified'] == "SMS" || $fetch['notified'] == "Both"){
            // sms to employer
            $phone = $fetch['phone'];
            if(strpos($phone, "+") !== false)
            $phone = $phone;
            else
            $phone = "+".$phone;

            $smsbody = "Clock has been started for the job: ".stripslashes($fetch['title']).".";

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

            $getuser = dbQuery($dbConn, "SELECT a.id,a.name,a.phone,a.email,b.notified from users a inner join staff_details b on a.id=b.staff_id where a.id = '".$userid."'");
            $rowuser = dbFetchArray($getuser);

            if($rowuser['notified'] == "Email" || $rowuser['notified'] == "Both"){
				
				$mail2 = new PHPMailer();
				$mail2->IsSMTP();

				$mail2->Host       = "cp-wc12.syd02.ds.network"; // cp-wc12.syd02.ds.network
				$mail2->SMTPAuth   = true;  
				$mail2->SMTPSecure = "ssl";                
				$mail2->Port       = 465;                    
				$mail2->Username   = "contact@staffexpress.com.au";            
				$mail2->Password   = "QYhi[=Aoor{t";

				$mail2->From = ADMINEMAIL;
				$mail2->FromName = "Staff Express";
				$mail2->Subject = "The clock has started";
				$mail2->isHTML(true);
				$mail2->AddAddress($rowuser['email']);
			
            //$to = $rowuser['email'];
            //$subject = "The clock is started";

            $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
			<tr>
            <td colspan='2'>&nbsp;</td>
        	</tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Hello ".$rowuser['name'].",</td>
            </tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Clock has been started for the job: ".stripslashes($fetch['title']).".</td>
            </tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            <tr>
            <td colspan='2' style='padding-left:10px;'>Please start work.</td>
            </tr>
            <tr><td colspan='2'>&nbsp;</td></tr>
            <tr>
            <td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>
            </tr>
            </table>";
			
			$mail2->Body = $message;
			  if(!$mail2->Send()) {
				echo $mail2->ErrorInfo;
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

            $smsbody = "Clock has been started for the job: ".stripslashes($fetch['title']).". Please start work.";

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