<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();

$eligible_cands = array();
$qualification = array();
$staff_quals = array();
$match1 = 0;
$match2 = 0;
$match3 = 0;
$match4 = 0;
$match5 = 0;
$job = dbQuery($dbConn, "SELECT a.*,b.phone FROM job_details a inner join users b on a.employer_id=b.id where a.id=5");
$row = dbFetchArray($job);
$title = $row['title'];
$jobcat = $row['catid'];
$jobdate = $row['jobdate'];
$strttime = $row['starttime'];
$endtime = $row['endtime'];
$exp = $row['experience'];
$exp_type = $row['exp_type'];

$jobday = date('D', strtotime($jobdate));
$job_day_number = date('N', strtotime($jobday));

$jobstrttime = $jobdate." ".$strttime;
$jobendttime = $jobdate." ".$endtime;

$jobstrttime = strtotime($jobstrttime);
$jobendttime = strtotime($jobendttime);
$duration = ceil(($jobendttime - $jobstrttime)/3600);

$jobquals = dbQuery($dbConn, "SELECT qualifications from qualifictn_required where jobid=5");
while($rowqual = dbFetchArray($jobquals)){
    $qualification[] = $rowqual['qualifications'];
}

$candidates = dbQuery($dbConn, "SELECT a.email,b.* FROM users a inner join staff_details b on a.id=b.staff_id where a.type=2");
while($res = dbFetchArray($candidates)){
    if($res['catid'] == $jobcat){
        $match1 = 1;
    }
    else
    $match1 = 0;

    if($res['working_days'] == 1)
    $match2 = 1;
    else if($res['working_days'] == 2){
        if($job_day_number == 1 || $job_day_number == 2 || $job_day_number == 3 || $job_day_number == 4 || $job_day_number == 5)
        $match2 = 1;
    }
    else if($res['working_days'] == 3){
        if($job_day_number == 0 || $job_day_number == 6)
        $match2 = 1;
    }
    else if($res['working_days'] == 4){
        if($job_day_number == 1)
        $match2 = 1;
    }
    else if($res['working_days'] == 5){
        if($job_day_number == 2)
        $match2 = 1;
    }
    else if($res['working_days'] == 6){
        if($job_day_number == 3)
        $match2 = 1;
    }
    else if($res['working_days'] == 7){
        if($job_day_number == 4)
        $match2 = 1;
    }
    else if($res['working_days'] == 8){
        if($job_day_number == 5)
        $match2 = 1;
    }

    $staff_qual = dbQuery($dbConn, "SELECT qualification from staff_qualification where staff_id = '".$res['staff_id']."'");
    while($getstffqual = dbFetchArray($staff_qual)){
        $staff_quals[] = $getstffqual['qualification'];
    }
    for($i=0; $i<count($staff_quals); $i++){
        if(in_array($staff_quals[$i], $qualification)){
            $match3 = 1;
            break;
        }
        else
        $match3 = 0;
    }
    if($exp_type == 1){
        if($res['experience'] == $exp)
        $match4 = 1;
    }
    else if($exp_type == 2){
        //if($res['experience'] >= $exp)
        $match4 = 1;
    }

    if($duration <= $res['timesavl'])
    $match5 = 1;

    if($match1 == 1 && $match2 == 1 && $match3 == 1 && $match4 == 1 && $match5 == 1){
        $eligible_cands[] = $res['email'];
    }
    
    $match1 = 0;
    $match2 = 0;
    $match3 = 0;
    $match4 = 0;
    $match5 = 0;
    $staff_quals = array();
     
}

$id = 5;

$today = date('Y-m-d');
foreach($eligible_cands as $val){
    $users = dbQuery($dbConn, "SELECT id,name,phone from users where email = '".$val."'");
    $fetch = dbFetchArray($users);

    dbQuery($dbConn, "INSERT INTO job_status set jobid = '".$id."', application_sent_to = '".$fetch['id']."', senton = '".$today."'");

    $link = SITEURL."job_details/?job=".base64_encode($id)."&user=".base64_encode($fetch['id'])."&do=viewjob";
    // send mail to candidates
    $to = $val;
    $subject = "Job offered";

    $message = "<table width='100%'>
    <tr>
    <td colspan='2'>Hello ".$fetch['name'].",</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2'>You are offered to perform this job: ".$title.".</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2'>Please click the link below to accept the offer if interested:</td>
    </tr>
    <tr>
    <td colspan='2'><a href='".$link."' target='_blank'>".$link."</a></td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2'>Thanks,<br>Staff Express</td>
    </tr>
    </table>";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $headers .= 'From: <'.ADMINEMAIL.'>' . "\r\n";

    mail($to,$subject,$message,$headers);
}


