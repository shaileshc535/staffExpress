<?php
include('config/config.php'); 
include_once "config/common.php";
include "phpmailer/class.phpmailer.php";
$dbConn = establishcon();

if(count($_POST) > 0){
    $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
    $email = isset($_REQUEST['email'])?trim($_REQUEST['email']):"";
    $msg = isset($_REQUEST['msg'])?trim($_REQUEST['msg']):"";
	
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
	$mail->Subject = "Contact Message Received";
	$mail->isHTML(true);
	$mail->AddAddress(CONTACTEMAIL);

//$to = CONTACTEMAIL;
//$to = "symapptest3@gmail.com";
//$subject = "Contact Message Received";

$message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
<tr>
<td colspan='2'>&nbsp;</td>
</tr>
<tr>
<td colspan='2' style='padding-left:10px;'>Hello Admin,</td>
</tr>
<tr><td colspan='2'>&nbsp;</td></tr>
<tr>
<td colspan='2' style='padding-left:10px;'>A user has sent message for help. The details are below:</td>
</tr>
<tr><td colspan='2'>&nbsp;</td></tr>
<tr>
<td style='padding-left:10px;'>Name:</td><td>".$name."</td>
</tr>
<tr>
<td style='padding-left:10px;'>Email:</td><td>".$email."</td>
</tr>
<tr>
<td style='padding-left:10px;'>Message:</td><td>".$msg."</td>
</tr>
<tr><td colspan='2'>&nbsp;</td></tr>
<tr>
<td colspan='2' style='padding-left:10px;padding-bottom;10px;'>Thanks,<br>Staff Express</td>
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
print json_encode(array('success' => 1));
exit;
}