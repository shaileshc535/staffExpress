<?php
include('config/config.php'); 
include_once "config/common.php";
include "phpmailer/class.phpmailer.php";
if($_REQUEST['action'] == "clockout"){
    $dbConn = establishcon();
    $currtime = date('Y-m-d H:i:s');
    $today = date('Y-m-d');
    $total_diff = 0;
    $total_diff_brk = 0;
    $total_hours = 0;
    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";

    $code = mt_rand('10000', '999999');

    $check = dbQuery($dbConn, "SELECT id,clockincode from staff_job_clock where job_id = '".$jobid."' and staff_id = '".$userid."' and jobstartdate = '".$today."'");
    if(dbNumRows($check) > 0){

        $getbreak = dbQuery($dbConn, "SELECT brkstarttime,brkendtime from staff_job_breaks where staff_id = '".$userid."' and job_id = '".$jobid."' and breakday = '".$today."' order by id desc limit 0,1");
        $break = dbFetchArray($getbreak);
        if($break['brkstarttime'] != '0000-00-00 00:00:00'){
            if($break['brkendtime'] != '0000-00-00 00:00:00')
            $clockout = 1;
            else
            $clockout = 0;
        }
        else
        $clockout = 1;

        if($clockout == 1){
        dbQuery($dbConn, "UPDATE staff_job_clock SET clockouttime = '".$currtime."' where job_id = '".$jobid."' and staff_id = '".$userid."' and jobstartdate = '".$today."'");

        $contact = dbQuery($dbConn, "SELECT * from job_status where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
		$getstat = dbFetchArray($contact);
        if($getstat['avlblty'] == 2){
            $availble_date2 = $getstat['availble_date2'];
            $availble_time2 = $getstat['availble_time2'];

            $newtime = strtotime($availble_time2) + 60*60;
            $newtime = date('H:i:s', $newtime);
            dbQuery($dbConn, "UPDATE job_details SET jobdate = '".$availble_date2."', starttime = '".$newtime."' where id = '".$jobid."' ");
        }

        $employer = dbQuery($dbConn, "SELECT a.title,b.email,b.name,b.phone,b.notified from job_details a inner join users b on a.employer_id=b.id where a.id = '".$jobid."'");
        $fetch = dbFetchArray($employer);

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
			$mail->Subject = "You are Clocked Out";
			$mail->isHTML(true);
			$mail->AddAddress($rowuser['email']);

        //$to = $rowuser['email'];
        //$subject = "You are Clocked Out";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
		<tr>
        <td colspan='2'>&nbsp;</td>
        </tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Hello ".$rowuser['name'].",</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>You are clocked out for the job: ".stripslashes($fetch['title']).".</td>
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
        $username = "ninepebblesteam@gmail.com";
        $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
        $str = $username.":".$password;
        $auth = base64_encode($str);

        $phone = $rowuser['phone'];
        if(strpos($phone, "+") !== false)
        $phone = $phone;
        else
        $phone = "+".$phone;

        $smsbody = "You are clocked out for the job: ".stripslashes($fetch['title']).".";

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
		
		// mail to employer
		if($fetch['notified'] == "Email" || $fetch['notified'] == "Both"){
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
			$mail2->Subject = "Staff is Clocked Out";
			$mail2->isHTML(true);
			$mail2->AddAddress($fetch['email']);
			
		//$to = $fetch['email'];
        //$subject = "Staff is Clocked Out";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
		<tr>
        <td colspan='2'>&nbsp;</td>
        </tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Hello ".$fetch['name'].",</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Staff is clocked out for the job: ".stripslashes($fetch['title']).".</td>
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

            // sms to employer
			if($fetch['notified'] == "SMS" || $fetch['notified'] == "Both"){
            $phone = $fetch['phone'];
            if(strpos($phone, "+") !== false)
            $phone = $phone;
            else
            $phone = "+".$phone;

            $smsbody = "Staff is clocked out for the job: ".stripslashes($fetch['title']).".";

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

                $gethours2 = dbQuery($dbConn, "SELECT clockintime,clockouttime from staff_job_clock where staff_id = '".$userid."' and job_id = '".$jobid."'");
                while($hours2 = dbFetchArray($gethours2)){
                    if($hours2['clockouttime'] != '0000-00-00 00:00:00'){
                        $starttime = strtotime($hours2['clockintime']);
                        $jobendtime = strtotime($hours2['clockouttime']);
                        $diff = $jobendtime - $starttime;
                        $total_diff += $diff;
                    }
                }
                $getstffbreak = dbQuery($dbConn, "SELECT id,brkstarttime from staff_job_breaks where staff_id = '".$userid."' and job_id = '".$jobid."'");
                if(dbNumRows($getstffbreak) > 0){

                    $getstffbreak2 = dbQuery($dbConn, "SELECT brkstarttime,brkendtime from staff_job_breaks where staff_id = '".$userid."' and job_id = '".$jobid."'");
                    while($staffbrk2 = dbFetchArray($getstffbreak2)){
                        if($staffbrk2['brkstarttime'] != '0000-00-00 00:00:00')
                        $brkstart = strtotime($staffbrk2['brkstarttime']);
                        else
                        $brkstart = 0;

                        if($staffbrk2['brkendtime'] != '0000-00-00 00:00:00'){
                            $brkend = strtotime($staffbrk2['brkendtime']);
                            //else
                            //$brkend = 0;
                            $diff_brk = $brkend - $brkstart;
                            $total_diff_brk += $diff_brk;
                        }
                    }
                    
                }
                if($total_diff > 0){
                    $diffwork = $total_diff - $total_diff_brk;
                    if($diffwork >= 60){
                        $minute = floor($diffwork/60);
                        $second = $diffwork % 60;
                        if($minute >= 60){
                            $hour = floor($minute/60);
                            $minute = $minute % 60;
                        }
                        else{
                            $hour = "00";
                            $minute = $minute;
                        }
                    }
                    else{
                        $hour = "00";
                        $min = "00";
                        $second = $diffwork;
                    }
                    if($second < 10)
                    $second = "0".$second;
                    else
                    $second = $second;
                    if($minute < 10)
                    $minute = "0".$minute;
                    else
                    $minute = $minute;
                    
                    $total_hours = $hour.":".$minute.":".$second;
                    }
       
        print json_encode(array('success' => 1, 'total_hours' => $total_hours));
        }
        else{
            print json_encode(array('success' => 3));
        }
        
    }
    else{
        print json_encode(array('success' => 2));
    }

    closeconn($dbConn);
}