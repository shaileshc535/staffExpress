<?php
include('config/config.php'); 
include_once "config/common.php";
include "phpmailer/class.phpmailer.php";
if(count($_POST) > 0){
    $dbConn = establishcon();
    $today = date('Y-m-d');
	$now = date("Y-m-d H:i:s");

    $jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";
    $userid = isset($_POST['userid'])?trim($_POST['userid']):"";
    //$from = isset($_POST['availble_date1'])?trim($_POST['availble_date1']):"";
    //$availble_time1 = isset($_POST['availble_time1'])?trim($_POST['availble_time1']):"";
    //$to = isset($_POST['availble_date2'])?trim($_POST['availble_date2']):"";
    //$availble_time2 = isset($_POST['availble_time2'])?trim($_POST['availble_time2']):"";
    //$multi_day = isset($_POST['multi_day'])?trim($_POST['multi_day']):"";
    $avlblty = isset($_POST['avlblty'])?trim($_POST['avlblty']):"";
    $notes = isset($_POST['notes'])?tep_db_input($_POST['notes']):"";
	$select_mode = isset($_POST['select_mode'])?trim($_POST['select_mode']):"";
    $myselctdate = isset($_POST['myselctdate'])?trim($_POST['myselctdate']):"";

    //$starttime = isset($_POST['starttime'])?trim($_POST['starttime']):"";
    //$endtime = isset($_POST['endtime'])?trim($_POST['endtime']):"";
	$mydates = isset($_POST['mydates'])?$_POST['mydates']:array();
	$starttime = isset($_POST['starttime'])?$_POST['starttime']:array();
	$endtime = isset($_POST['endtime'])?$_POST['endtime']:array();

    $staffdetls = dbQuery($dbConn, "SELECT superannuation from staff_details where staff_id = '".$userid."'");
    $row = dbFetchArray($staffdetls);
    //if($row['superannuation']){

    if($avlblty == 1)
    $allowapply = 1;
    else{
        if($myselctdate)
        $allowapply = 1;
        else
        $allowapply = 0;
    }

    if($allowapply == 1){
    $check = dbQuery($dbConn, "SELECT id from job_status where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
    if(dbNumRows($check) > 0){
        dbQuery($dbConn, "UPDATE job_status set contacting=1, avlblty = '".$avlblty."', apply_type = '".$select_mode."', notes = '".$notes."' where jobid = '".$jobid."' and application_sent_to = '".$userid."'");
    }
    else{
        dbQuery($dbConn, "INSERT INTO job_status set contacting=1, jobid = '".$jobid."', application_sent_to = '".$userid."', avlblty = '".$avlblty."', apply_type = '".$select_mode."', notes = '".$notes."', senton = '".$today."'");
    }

    if($avlblty == 1){
		$myjob = dbQuery($dbConn, "SELECT jobdate,jobdate2 FROM job_details where id='".$jobid."'");
		$jobdetails = dbFetchArray($myjob);
		$from = $jobdetails['jobdate'];
		$to = $jobdetails['jobdate2'];
		
		if($jobdetails['jobdate'] != "0000-00-00"){
			dbQuery($dbConn, "DELETE FROM staff_availability WHERE jobid = '".$jobid."' AND staff_id = '".$userid."'");
			
			if($jobdetails['jobdate2'] != "0000-00-00"){
			$period = new DatePeriod(
				new DateTime($from),
				new DateInterval('P1D'),
				new DateTime($to)
		   );
			
		   foreach ($period as $key => $value) {
				$days = $value->format('Y-m-d');
				dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$days."'");
				$i++;
			}

			$last_day_to_add = strtotime('+1 day',strtotime($days));
			$last_day_to_add = date('Y-m-d', $last_day_to_add);
			dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$last_day_to_add."'");
			}
			else{
				$myselctdate = $from;
				dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$myselctdate."'");
			}
		}
        /*dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$from."'");
        dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$to."'");*/
    }
    else if($avlblty == 2){
		dbQuery($dbConn, "DELETE FROM staff_availability WHERE jobid = '".$jobid."' AND staff_id = '".$userid."'");
        /*if($multi_day){
            $multi_day = explode(", ", $multi_day);
            foreach($multi_day as $val){
				$datearr = explode("/", $val);
				$mydate = $datearr[2]."-".$datearr[0]."-".$datearr[1];
                dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$mydate."', starttime = '".$starttime."', endtime = '".$endtime."'");
            }
        }*/

		if(count($mydates) > 0){
			$i=0;
			foreach($mydates as $val){
				//$datearr = explode("-", $val);
				//$mydate = $datearr[2]."-".$datearr[0]."-".$datearr[1];
                if($starttime[$i] != "" && $endtime[$i] != ""){
                    dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$val."', starttime = '".$starttime[$i]."', endtime = '".$endtime[$i]."'");
                }
				$i++;
            }
		}
        else{
            $myselctdate_arr = explode(" to ", $myselctdate);
			if(count($myselctdate_arr) > 1){
            $jobstartdate = $myselctdate_arr[0];
            $jobenddate = $myselctdate_arr[1];

            $period = new DatePeriod(
                new DateTime($jobstartdate),
                new DateInterval('P1D'),
                new DateTime($jobenddate)
           );

           $i=0;
           foreach ($period as $key => $value) {
                $days = $value->format('Y-m-d');
                dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$days."', starttime = '".$starttime[$i]."', endtime = '".$endtime[$i]."'");
                $i++;
            }

            $last_day_to_add = strtotime('+1 day',strtotime($days));
            $last_day_to_add = date('Y-m-d', $last_day_to_add);
            dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$last_day_to_add."'");
			}
			else{
				$myselctdate = $myselctdate;
				dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$myselctdate."', starttime = '".$starttime[0]."', endtime = '".$endtime[0]."'");
			}
           
            //$i=0;
            /*foreach($myselctdate_arr as $val){
                //if($starttime[$i] != "" && $endtime[$i] != ""){
                    dbQuery($dbConn, "INSERT INTO staff_availability set jobid = '".$jobid."', staff_id = '".$userid."', availble_date = '".$val."', starttime = '".$starttime[$i]."', endtime = '".$endtime[$i]."'");
                //}
				$i++;
            }*/
        }
    }
	
    
    $employer = dbQuery($dbConn, "SELECT a.employer_id,a.title,b.email,b.name,b.phone,b.notified from job_details a inner join users b on a.employer_id=b.id where a.id = '".$jobid."'");
    $fetch = dbFetchArray($employer);
	
	// send message to employer
	
	if($notes){
		if($notes)
			$staff_note = "Notes: ".$notes.".";
		else
			$staff_note = "";
		
	}
	$mymsg = "I am available for ".stripslashes($fetch['title'])." job role. ".$staff_note."";
	dbQuery($dbConn, "INSERT INTO messages set jobid = '".$jobid."', senderid = '".$userid."', receiverid = '".$fetch['employer_id']."', msg = '".$mymsg."', msgdate = '".$now."'");

    $link = SITEURL."managehire/?id=".base64_encode($jobid)."&userid=".base64_encode($userid)."";
	
	if($fetch['notified'] == "Email" || $fetch['notified'] == "Both"){
		
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
		$mail->Subject = "Staff available for job role";
		$mail->isHTML(true);
		$mail->AddAddress($fetch['email']);

    //$to = $fetch['email'];
    //$subject = "Staff available for job role";

    $message = "<table cellpadding='0' cellspacing='0' width='600px' style='margin:0 auto; font-family: sans-serif; border-top:7px #15506C solid;'>
    <tr>
    <td colspan='2'>&nbsp;</td>
    </tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Hello ".$fetch['name'].",</td>
    </tr>
    <tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>A staff is available for ".stripslashes($fetch['title'])." job role.</td>
    </tr>";
	$message .= "<tr><td colspan='2'>&nbsp;</td></tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'>Please click the link below to view details:</td>
    </tr>
    <tr>
    <td colspan='2' style='padding-left:10px;'><a href='".$link."' target='_blank'>".$link."</a></td>
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
	
	if($fetch['notified'] == "SMS" || $fetch['notified'] == "Both"){

    // sending sms
    $username = "ninepebblesteam@gmail.com";
    $password = "3F65DFA5-33A9-08D5-54CD-0838804B9B49";
    $str = $username.":".$password;
    $auth = base64_encode($str);

    $phone = $fetch['phone'];
    if(strpos($phone, "+") !== false)
    $phone = $phone;
    else
    $phone = "+".$phone;

    $smsbody = "A staff is available for ".stripslashes($fetch['title'])." job role. Please click the link to view details: ".$link."";

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

    print json_encode(array('success' => 1));
    }
    else{
        print json_encode(array('success' => 3));
    }
    /*}
    else{
        print json_encode(array('success' => 2));
    }*/

    closeconn($dbConn);
}