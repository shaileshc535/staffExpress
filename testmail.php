<?php
include('config/config.php');
include_once "phpmailer/class.phpmailer.php";
/*$mail = new PHPMailer();
$mail->IsSMTP();

$mail->Host       = "cp-wc12.syd02.ds.network"; // cp-wc12.syd02.ds.network
$mail->SMTPAuth   = true;  
$mail->SMTPSecure = "ssl";                
$mail->Port       = 465;                    
$mail->Username   = "contact@staffexpress.com.au";            
$mail->Password   = "QYhi[=Aoor{t";

            $mail->From = "contact@staffexpress.com.au";
            $mail->FromName = "Staff";
            $mail->Subject = "testsg";
            $mail->isHTML(true);
            $mail->AddAddress("ayankkr@hotmail.com");*/

            $to = "ayankkr@hotmail.com";

            $subject = "testing";
            
            $message = '<table cellpadding="0" cellspacing="0" width="600px" style="margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;">
                        <tr>
                        <td colspan="2">Hi john,</td>
                        </tr>
                        <tr>
                        <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan="2">Thank You for Registering with Staff Express. We will be in contact with you when we officially launch very soon.</td>
                        </tr>
                        <tr>
                        <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan="2">Staff Express was created to match employers needing urgent cover with available staff on the day.</td>
                        </tr>
						            <tr>
                        <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                        <td colspan="2">We hope this helps to ease staff shortages and also give staff job opportunities for casual work cover.</td>
                        </tr>
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <tr>
                      <td colspan="2">Thanks,<br>Staff Express</td>
                      </tr>
                      </table>';
                      
                      $headers = "MIME-Version: 1.0" . "\r\n";

                      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


                      $headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

                      mail($to,$subject,$message,$headers);

                /*$mail->Body = $message;
                if(!$mail->Send()) {
  echo $mail->ErrorInfo;;
} else {
  echo "Email sent successfully";
}*/
?>