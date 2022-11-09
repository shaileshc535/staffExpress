<?php
include('config/config.php'); 
include_once "config/common.php";
if($_REQUEST['action'] == "disputeemp"){
    $dbConn = establishcon();
    $currtime = date('Y-m-d H:i:s');
    $jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?tep_db_input($_POST['userid']):"";
    $reason = isset($_POST['reason'])?tep_db_input($_POST['reason']):"";
    $other_reason = isset($_POST['other_reason'])?tep_db_input($_POST['other_reason']):"";

    $usertype = getUserType($dbConn, $_SESSION['loginUserId']);

    $myjob = dbQuery($dbConn, "SELECT a.title,a.employer_id,b.name FROM job_details a inner join users b on a.employer_id=b.id where a.id='".$jobid."'");
    $jobrow = dbFetchArray($myjob);

    if($usertype == 1){
        $check = dbQuery($dbConn, "INSERT INTO disputes set job_id = '".$jobid."', raisedagainst = '".$userid."', raisedby = '".$_SESSION['loginUserId']."', raisedusertype = '".$usertype."', reason = '".$reason."', other_reason = '".$other_reason."', senton = '".$currtime."'");

        if($reason == 1)
        $reason = "Staff not turning up to job";
        else if($reason == 2)
        $reason = "Staff not performing to standards";
        else if($reason == 3)
        $reason = "Staff not qualified as stated";
        else if($reason == 4)
        $reason = $other_reason;

        $candidt = dbQuery($dbConn, "SELECT name from users where id = '".$userid."'");
        $candrow = dbFetchArray($candidt);

        $to = CONTACTEMAIL;
        $subject = "Employer Has Raised Dispute";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid; '>
        <tr>
            <td style='padding:5px; font-size:18px; color:green;text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
        </tr>
        <tr>
        <td colspan='2'>&nbsp;</td>
        </tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Hello Admin,</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>The employer ".$jobrow['name']." has raised dispute for the job: ".$jobrow['title'].".</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Dispute raised against the staff: ".$candrow['name']."</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>The reason is: ".$reason."</td>
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
    else if($usertype == 2){
        $check = dbQuery($dbConn, "INSERT INTO disputes set job_id = '".$jobid."', raisedagainst = '".$jobrow['employer_id']."', raisedby = '".$_SESSION['loginUserId']."', raisedusertype = '".$usertype."', reason = '".$reason."', other_reason = '".$other_reason."', senton = '".$currtime."'");

        if($reason == 5)
        $reason = "Work location not as described";
        else if($reason == 6)
        $reason = "Insufficient training/support on site";
        else if($reason == 7)
        $reason = "Tasks not as described";

        $candidt = dbQuery($dbConn, "SELECT name from users where id = '".$_SESSION['loginUserId']."'");
        $candrow = dbFetchArray($candidt);

        $to = CONTACTEMAIL;
        $subject = "Staff Has Raised Dispute";

        $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid; '>
        <tr>
            <td style='padding:5px; font-size:18px; color:green;text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
        </tr>
        <tr>
        <td colspan='2'>&nbsp;</td>
        </tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Hello Admin,</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>The staff ".$candrow['name']." has raised dispute for the job: ".$jobrow['title'].".</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>Dispute raised against the employer: ".$jobrow['name']."</td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>
        <td colspan='2' style='padding-left:10px;'>The reason is: ".$reason."</td>
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

            print json_encode(array('success' => 1));
  

    closeconn($dbConn);
}