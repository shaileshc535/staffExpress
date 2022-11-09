<?php
include('config/config.php'); 
include_once "config/common.php";
include "phpmailer/class.phpmailer.php";
$dbConn = establishcon();
$parentmsgid = 0;
if(count($_POST) > 0 && $_POST['action'] == "sendmsg"){
    $now = date("Y-m-d H:i:s");
    $userid = isset($_REQUEST['userid'])?trim($_REQUEST['userid']):"";
    $jobid = isset($_REQUEST['jobid'])?trim($_REQUEST['jobid']):"";
    $employerid = isset($_REQUEST['employerid'])?trim($_REQUEST['employerid']):"";
    $msgid = ($_REQUEST['msgid']!=0)?trim($_REQUEST['msgid']):0;

    $usertype = isset($_REQUEST['usertype'])?trim($_REQUEST['usertype']):"";
    $mymsg = isset($_REQUEST['mymsg'])?strip_tags(trim($_REQUEST['mymsg'])):"";

    $candidt = dbQuery($dbConn, "SELECT a.id,a.name,a.phone,a.email,b.notified_msg from users a inner join staff_details b on a.id=b.staff_id where a.id = '".$userid."'");
    $rowcand = dbFetchArray($candidt);
    $candidt_name = $rowcand['name'];

    $employer = dbQuery($dbConn, "SELECT name,email,phone,notified_msg from users where id = '".$employerid."'");
    $rowemployer = dbFetchArray($employer);
    $employer_name = $rowemployer['name'];

    $job = dbQuery($dbConn, "SELECT title from job_details where id = '".$jobid."'");
    $jobrow = dbFetchArray($job);

    /*if($rowreceiver['image'])
    $renterimg = SITEURL."uploads/user/".$rowreceiver['image'];
    else
    $renterimg = SITEURL."images/noimg.jpg";

    $sender_arr = explode(" ", $_SESSION['loginUserName']);
	$sender = $sender_arr[0];
    $getsender = dbQuery($dbConn, "SELECT image from users where id = '".$_SESSION['loginUserId']."'");
    $rowsender = dbFetchArray($getsender);
    if($rowsender['image'])
    $senderimg = SITEURL."uploads/user/".$rowsender['image'];
    else
    $senderimg = SITEURL."images/noimg.jpg";*/

    if($usertype == 1){
		if($rowcand['notified_msg'] == "Email" || $rowcand['notified_msg'] == "Both"){
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
			$mail->Subject = "New Message from Employer";
			$mail->isHTML(true);
			$mail->AddAddress($rowcand['email']);
			//$to = $rowcand['email'];
			//$subject = "New Message from Employer";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
        <tr>
        <td colspan='2'>&nbsp;</td>
        </tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Hello ".$candidt_name.",</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Your employer ".$employer_name." has sent a message about the job: ".stripslashes($jobrow['title']).".</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>The message is:</td>
        </tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>".$mymsg."</td>
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
		if($rowcand['notified_msg'] == "SMS" || $rowcand['notified_msg'] == "Both"){
			// sending sms
			$username = "ninepebblesteam@gmail.com";
			$password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
			$str = $username.":".$password;
			$auth = base64_encode($str);

			$phone = $rowcand['phone'];
			if(strpos($phone, "+") !== false)
			$phone = $phone;
			else
			$phone = "+".$phone;

			$smsbody = "Your employer ".$employer_name." has sent a message about the job: ".stripslashes($jobrow['title']).". The message is: ".$mymsg."";

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
    }
    if($usertype == 2){
		if($rowemployer['notified_msg'] == "Email" || $rowemployer['notified_msg'] == "Both"){
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
			$mail2->Subject = "New Message from Staff";
			$mail2->isHTML(true);
			$mail2->AddAddress($rowemployer['email']);
			
        //$to = $rowemployer['email'];
        //$subject = "New Message from Staff";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: 'Karla', border-top:7px #15506C solid;'>
        <tr>
        <td colspan='2'>&nbsp;</td>
        </tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Hello ".$employer_name.",</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Your staff ".$candidt_name." has sent a message about the job: ".stripslashes($jobrow['title']).".</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>The message is:</td>
        </tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>".$mymsg."</td>
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
		if($rowemployer['notified_msg'] == "SMS" || $rowemployer['notified_msg'] == "Both"){
			// sending sms
			$username = "ninepebblesteam@gmail.com";
			$password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
			$str = $username.":".$password;
			$auth = base64_encode($str);

			$phone = $rowemployer['phone'];
			if(strpos($phone, "+") !== false)
			$phone = $phone;
			else
			$phone = "+".$phone;

			$smsbody = "Your staff ".$candidt_name." has sent a message about the job: ".stripslashes($jobrow['title']).". The message is: ".$mymsg."";

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
            
    }

    $msgdate = date("d M", strtotime($now));

    $getsender = dbQuery($dbConn, "SELECT name from users where id = '".$_SESSION['loginUserId']."'");
    $rowsender = dbFetchArray($getsender);

    if($usertype == 1){
        dbQuery($dbConn, "INSERT INTO messages set parent_id = '".$msgid."', jobid = '".$jobid."', senderid = '".$employerid."', receiverid = '".$userid."', msg = '".$mymsg."', msgdate = '".$now."'");
    }
    else if($usertype == 2){
        dbQuery($dbConn, "INSERT INTO messages set parent_id = '".$msgid."', jobid = '".$jobid."', senderid = '".$userid."', receiverid = '".$employerid."', msg = '".$mymsg."', msgdate = '".$now."'");
        
    }
    if(dbInsertId($dbConn) > 0){
        if($usertype == 2){
            $getMsg = dbQuery($dbConn, "SELECT id from messages where senderid = '".$_SESSION['loginUserId']."' and jobid = '".$jobid."'");
            if(dbNumRows($getMsg) == 1){
                $rowMsg = dbFetchArray($getMsg);
                $parentmsgid = $rowMsg['id'];
            }
        }
        print json_encode(array('success' => 1, 'usertype' => $usertype, 'sender' => $rowsender['name'], 'receiver' => $employer_name, 'parentmsgid' => $parentmsgid, 'msg' => stripslashes($mymsg), 'msgdt' => date("M j, Y", strtotime($now)), 'msgtime' => date("h:i a", strtotime($now))));
    }
    else{
        print json_encode(array('success' => 0));
    }
}

closeconn($dbConn);
?>