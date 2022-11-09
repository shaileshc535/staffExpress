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
    $hireagain = isset($_POST['hireagain'])?trim($_POST['hireagain']):"";
	$did_attend = isset($_POST['did_attend'])?trim($_POST['did_attend']):"";
	
	if($did_attend == 2)
	{
		dbQuery($dbConn, "UPDATE staff_details set red_flag = 1 where staff_id = '".$userid."'");
	}
    
    dbQuery($dbConn, "insert into reviews set job_id = '".$jobid."', emp_id = '".$emp_id."', staff_id = '".$userid."', rating = '".$myrate."', comment = '".$mycomment."'");

    dbQuery($dbConn, "insert into staff_hire_again set empid = '".$emp_id."', staff_id = '".$userid."', hireagain = '".$hireagain."'");

    $staff = dbQuery($dbConn, "select name,lname from users where id = '".$userid."'");
    $staffrow = dbFetchArray($staff);
    $staffname = $staffrow['name']." ".$staffrow['lname'];

    $emplr = dbQuery($dbConn, "select name from users where id = '".$emp_id."'");
    $emplrrow = dbFetchArray($emplr);
    $emplrname = $emplrrow['name'];

    if($hireagain == 1)
    $hiredagaintext = "He wants to hire the staff again.";
    else
    $hiredagaintext = "He does not want to hire the staff again.";

    $to = CONTACTEMAIL;
    $subject = "Staff will be Hired Again";

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
    <td colspan='2' style='padding-left:10px;'>Employer ".$emplrname." has given review on the staff ".$staffname."</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td style='padding-left:10px;'>Name:</td>
	<td style='padding-left:10px;'>".$hiredagaintext."</td>
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

    print json_encode(array('success' => 1));

    closeconn($dbConn);
}