<?php
include('config/config.php'); 
include_once "config/common.php";
if(count($_POST)>0){
    $dbConn = establishcon();

    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
    $emp_id = isset($_POST['emp_id'])?trim($_POST['emp_id']):"";
    $myrate = isset($_POST['myrate'])?trim($_POST['myrate']):"";
    $mycomment = isset($_POST['mycomment'])?strip_tags($_POST['mycomment']):"";
    
    dbQuery($dbConn, "insert into reviews set job_id = '".$jobid."', emp_id = '".$emp_id."', staff_id = '".$userid."', rating = '".$myrate."', comment = '".$mycomment."', givenbystaff=1");

    $staff = dbQuery($dbConn, "select name,lname from users where id = '".$userid."'");
    $staffrow = dbFetchArray($staff);
    $staffname = $staffrow['name']." ".$staffrow['lname'];

    $emplr = dbQuery($dbConn, "select name from users where id = '".$emp_id."'");
    $emplrrow = dbFetchArray($emplr);
    $emplrname = $emplrrow['name'];
	
	if($myrate == 1)
		$rating = "Unsatisfactory";
	if($myrate == 4)
		$rating = "Ok";
	if($myrate == 5)
		$rating = "Excellent";


    $to = CONTACTEMAIL;
    $subject = "Review Given by Staff";

    $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border:1px #3176B4 solid; border-top:3px #3176B4 solid;'>
    <tr>
        <td style='padding:5px; font-size:18px; color:green; text-align:center;' colspan='2'><img src='".SITEURL."images/logo.png' alt='' width='150'></td>
    </tr>
    <tr>
    <td colspan='2'>&nbsp;</td>
    </tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Hello Admin,</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Staff ".$staffname." has given review on the employer ".$emplrname.".</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
	<tr><td style='padding-left:10px;'>Rating given:</td><td style='padding-left:10px;'>".$rating."</td></tr>
	<tr><td colspan='2'>&nbsp;</td></tr>
	<tr><td style='padding-left:10px;'>Comment:</td><td style='padding-left:10px;'>".$mycomment."</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;padding-bottom:10px;'>Thanks,<br>Staff Express</td>
    </tr>
    </table>";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

    mail($to,$subject,$message,$headers);

    print json_encode(array('success' => 1));

    closeconn($dbConn);
}