<?php
include('config/config.php');
include_once "config/common.php";
include "phpmailer/class.phpmailer.php";
if ($_REQUEST['action'] == "acceptoffr") {
    $dbConn = establishcon();

    $jobid = isset($_POST['jobid']) ? trim($_POST['jobid']) : "";
    $userid = isset($_POST['userid']) ? trim($_POST['userid']) : "";

    dbQuery($dbConn, "UPDATE job_status set hired = 1 where jobid = '" . $jobid . "' and application_sent_to = '" . $userid . "'");

    $myjobdate = dbQuery($dbConn, "SELECT availble_date from staff_availability where jobid = '" . $jobid . "' and staff_id = '" . $userid . "'");
    $fetchdate = dbFetchArray($myjobdate);
    $fetchdate = date("M j, Y", strtotime($fetchdate['availble_date']));

    $employer = dbQuery($dbConn, "SELECT a.title,b.email,b.name,b.notified,b.phone from job_details a inner join users b on a.employer_id=b.id where a.id = '" . $jobid . "'");
    $fetch = dbFetchArray($employer);

    $candidate = dbQuery($dbConn, "SELECT a.id,a.name,a.phone,a.email,b.notified from users a inner join staff_details b on a.id=b.staff_id where a.id = '" . $userid . "'");
    $row = dbFetchArray($candidate);

    $link = SITEURL . "myjobview/" . base64_encode($jobid) . "";

    if ($row['notified'] == "Email" || $row['notified'] == "Both") {

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
        $mail->Subject = "Job Confirmation Accepted";
        $mail->isHTML(true);
        $mail->AddAddress($row['email']);

        //$to = $row['email'];
        //$subject = "Job Confirmation Accepted";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
    <tr>
    <td colspan='2'>&nbsp;</td>
    </tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Hello " . $row['name'] . ",</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
	<tr>
    <td colspan='2' style='padding-left:10px;'>You have accepted the confirmation for the job: " . stripslashes($fetch['title']) . ".</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
	<tr>
    <td colspan='2' style='padding-left:10px;'>Reminder your role is on " . $fetchdate . " with " . stripslashes($fetch['name']) . ". Remember to use the staff clock in and out to keep a record of your hours worked after login. It’s a free and easy to use feature. Click <a href='" . $link . "' target='_blank'>here</a>.</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>
    </tr>
    </table>";

        $mail->Body = $message;
        if (!$mail->Send()) {
            echo $mail->ErrorInfo;
        } else {
        }

        //$headers = "MIME-Version: 1.0" . "\r\n";
        //$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        //$headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

        //mail($to,$subject,$message,$headers);
    }

    if ($row['notified'] == "SMS" || $row['notified'] == "Both") {
        // sending sms
        $username = "ninepebblesteam@gmail.com";
        $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
        $str = $username . ":" . $password;
        $auth = base64_encode($str);

        $phone = $row['phone'];
        if (strpos($phone, "+") !== false)
            $phone = $phone;
        else
            $phone = "+" . $phone;

        $smsbody = "You have accepted the confirmation for the job: " . stripslashes($fetch['title']) . ". Reminder your role is on " . $fetchdate . " with " . stripslashes($fetch['name']) . ". Remember to use the staff clock in and out to keep a record of your hours worked after login. It’s a free and easy to use feature. Click here: " . $link . "";

        $data_string = '{
        "messages": [
        {
            "to": "' . $phone . '",
            "source": "sdk",
            "body": "' . $smsbody . '"
        }
        ]
    }';

        $ch = curl_init("https://rest.clicksend.com/v3/sms/send");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $auth . ''
            )
        );
        $result = curl_exec($ch);
        $result = json_decode($result);
    }

    // to employer

    if ($fetch['notified'] == "Email" || $fetch['notified'] == "Both") {

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
        $mail2->Subject = "Staff Accepted Job Confirmation";
        $mail2->isHTML(true);
        $mail2->AddAddress($fetch['email']);

        //$to = $fetch['email'];
        //$subject = "Staff Accepted Job Confirmation";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
    <tr>
    <td colspan='2'>&nbsp;</td>
    </tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Hello " . $fetch['name'] . ",</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>The staff accepted the confirmation for the job: " . stripslashes($fetch['title']) . ". So the staff has been hired.</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>
    </tr>
    </table>";

        $mail2->Body = $message;
        if (!$mail2->Send()) {
            echo $mail2->ErrorInfo;
        } else {
        }

        //$headers = "MIME-Version: 1.0" . "\r\n";
        //$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        //$headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

        //mail($to,$subject,$message,$headers);
    }

    if ($fetch['notified'] == "SMS" || $fetch['notified'] == "Both") {

        // sending sms
        $username = "ninepebblesteam@gmail.com";
        $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
        $str = $username . ":" . $password;
        $auth = base64_encode($str);

        $phone = $fetch['phone'];
        if (strpos($phone, "+") !== false)
            $phone = $phone;
        else
            $phone = "+" . $phone;

        $smsbody = "The staff accepted the confirmation for the job: " . stripslashes($fetch['title']) . ". So the staff has been hired.";

        $data_string = '{
        "messages": [
        {
            "to": "' . $phone . '",
            "source": "sdk",
            "body": "' . $smsbody . '"
        }
        ]
    }';

        $ch = curl_init("https://rest.clicksend.com/v3/sms/send");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $auth . ''
            )
        );
        $result = curl_exec($ch);
        $result = json_decode($result);
    }

    print json_encode(array('success' => 1));

    closeconn($dbConn);
}
