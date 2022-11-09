<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();

    $now = date("Y-m-d H:i:s");
if(count($_POST) > 0){
    $userid = isset($_REQUEST['userid'])?trim($_REQUEST['userid']):"";
    $jobid = isset($_REQUEST['jobid'])?trim($_REQUEST['jobid']):"";

    $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
    $email = isset($_REQUEST['email'])?trim($_REQUEST['email']):"";

    $msgid = ($_REQUEST['msgid']!=0)?trim($_REQUEST['msgid']):0;

    $mymsg = isset($_REQUEST['msg'])?strip_tags(trim($_REQUEST['msg'])):"";

   
    $job = dbQuery($dbConn, "SELECT title,employer_id from job_details where id = '".$jobid."'");
    $jobrow = dbFetchArray($job);
    $employerid = $jobrow['employer_id'];

    $employer = dbQuery($dbConn, "SELECT name,email from users where id = '".$employerid."'");
    $rowemployer = dbFetchArray($employer);
    $employer_name = $rowemployer['name'];
    
    if($userid){
        $candidt = dbQuery($dbConn, "SELECT name,email from users where id = '".$userid."'");
        $rowcand = dbFetchArray($candidt);
        $candidt_name = $rowcand['name'];

        $to = $rowemployer['email'];
        $subject = "New Message from Staff";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
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

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: Staff Express <'.ADMINEMAIL.'>' . "\r\n";

        mail($to,$subject,$message,$headers);


        dbQuery($dbConn, "INSERT INTO messages set parent_id = '".$msgid."', jobid = '".$jobid."', senderid = '".$userid."', receiverid = '".$employerid."', msg = '".$mymsg."', msgdate = '".$now."'");

    }
    else{
        $to = $rowemployer['email'];
        $subject = "New Message from Staff";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
        <tr>
        <td colspan='2'>&nbsp;</td>
        </tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Hello ".$employer_name.",</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>A staff ".$name." (".$email.") has sent a query about the job: ".stripslashes($jobrow['title']).".</td>
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

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: Staff Express <'.ADMINEMAIL.'>' . "\r\n";

        mail($to,$subject,$message,$headers);


        dbQuery($dbConn, "INSERT INTO initial_messages set jobid = '".$jobid."', name = '".$name."', email = '".$email."', receiverid = '".$employerid."', msg = '".$mymsg."', msgdate = '".$now."'");

    }

    if(dbInsertId($dbConn) > 0){
        print json_encode(array('success' => 1));
    }
    else{
        print json_encode(array('success' => 0));
    }
}

closeconn($dbConn);
?>