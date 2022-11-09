<?php
include('config/config.php'); 
include_once "config/common.php";

$dbConn = establishcon();

$msgs = '';
$id = isset($_POST['id'])?trim($_POST['id']):"";
$jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";

$employee = dbQuery($dbConn, "select name,lname from users where id = '".$id."'");
$row = dbFetchArray($employee);
$staffname = $row['name']." ".$row['lname'];

$thejob = dbQuery($dbConn, "select title from job_details where id = '".$jobid."'");
$jobrow = dbFetchArray($thejob);

    $emp = dbQuery($dbConn, "SELECT b.id,b.name FROM users b where b.id='".$_SESSION['loginUserId']."'");
    $fetch = dbFetchArray($emp);
    
    $employer = $fetch['name'];
    $employerid = $_SESSION['loginUserId'];

    $getAllMsg = dbQuery($dbConn, "SELECT senderid,receiverid,msg,msgdate from (SELECT m.senderid,m.receiverid,m.msg,m.msgdate,s.name as sender,r.name as receiver from messages m inner join users s on m.senderid=s.id inner join users r on m.receiverid=r.id where 1 AND jobid = '".$jobid."' and (senderid = '".$id."' OR receiverid = '".$id."') AND  (senderid = '".$employerid."' OR receiverid = '".$employerid."') order by msgdate desc ) t2 order by msgdate");
        if(dbNumRows($getAllMsg) > 0){
        while($recMsg = dbFetchArray($getAllMsg)){
            if($recMsg['senderid'] == $id){
                $sender = $row['name'];
                $msgstyle = "";
            }
            else if($recMsg['senderid'] == $employerid){
                $sender = $employer;
                $msgstyle = "sendermsg";
            }
            $msgs .= '<div class="client_messages" style="margin-bottom:10px;">
                <div class="client_messages_content '.$msgstyle.'">
                    <div class="client_messages_name">
                    <h6>'.$sender.'</h6>
                        <span>'.date("M j, Y h:i a", strtotime($recMsg['msgdate'])).'</span>
                    </div>
                    <p>'.stripslashes($recMsg['msg']).'</p>
                </div>
            </div>
            <div style="clear:both;"></div>';
            $msgid++;
                }
                $msgdt++;
        }

        $getclock = dbQuery($dbConn, "SELECT clockouttime from staff_job_payment where job_id = '".$jobid."' and staff_id = '".$id."'");
		$clock = dbFetchArray($getclock);

        if(isset($clock['clockouttime']) && $clock['clockouttime']!='0000-00-00 00:00:00'){
            $clockedout = 1;
        }
        else{
            $clockedout = 0;
        }
        dbQuery($dbConn, "update messages set isread=1 where receiverid = '".$_SESSION['loginUserId']."' and jobid='".$jobid."' and senderid = '".$id."'");
        
        print json_encode(array('success' => 1, 'msgs' => $msgs, 'staff' => $staffname, 'clockedout' => $clockedout));

closeconn($dbConn);