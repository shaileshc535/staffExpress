<?php
include('config/config.php'); 
include_once "config/common.php";

$dbConn = establishcon();

$msgs = '';

$id = isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
$jobid = isset($_REQUEST['jobid'])?trim($_REQUEST['jobid']):"";

$employer = dbQuery($dbConn, "select name from users where id = '".$id."'");
$row = dbFetchArray($employer);

$thejob = dbQuery($dbConn, "select title from job_details where id = '".$jobid."'");
$jobrow = dbFetchArray($thejob);

    $candidate = dbQuery($dbConn, "SELECT b.id,b.name FROM users b where b.id='".$_SESSION['loginUserId']."'");
    $fetch = dbFetchArray($candidate);
    
    $candid = $_SESSION['loginUserId'];
    $candname = $fetch['name'];

    $usertype = getUserType($dbConn, $_SESSION['loginUserId']);

    $getAllMsg = dbQuery($dbConn, "SELECT senderid,receiverid,msg,msgdate from (SELECT m.senderid,m.receiverid,m.msg,m.msgdate,s.name as sender,r.name as receiver from messages m inner join users s on m.senderid=s.id inner join users r on m.receiverid=r.id where 1 AND (senderid = '".$id."' OR receiverid = '".$id."') AND  (senderid = '".$candid."' OR receiverid = '".$candid."') order by msgdate desc ) t2 order by msgdate");
    if(dbNumRows($getAllMsg) > 0){
    while($recMsg = dbFetchArray($getAllMsg)){
        if($recMsg['senderid'] == $id){
            $sender = $row['name'];
            $msgstyle = "";
        }
        else if($recMsg['senderid'] == $candid){
            $sender = $candname;
            $msgstyle = "sendermsg";
        }
            $msgs .= '<div class="client_messages" style="margin-bottom:10px;">
                <div class="client_messages_content '.$msgstyle.'">
                    <div class="client_messages_name">
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
            else{
                $chatdisply = "";
            }

        dbQuery($dbConn, "update messages set isread=1 where receiverid = '".$_SESSION['loginUserId']."' and jobid='".$jobid."'");
        
        print json_encode(array('success' => 1, 'msgs' => $msgs, 'emp' => $row['name']));

closeconn($dbConn);