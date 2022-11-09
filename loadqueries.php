<?php
include('config/config.php'); 
include_once "config/common.php";

$dbConn = establishcon();

$msgs = '';
$jobid = isset($_POST['jobid'])?trim($_POST['jobid']):"";

    $getAllMsg = dbQuery($dbConn, "SELECT * from initial_messages where receiverid = '".$_SESSION['loginUserId']."' and jobid='".$jobid."' order by msgdate desc");
        if(dbNumRows($getAllMsg) > 0){
        while($recMsg = dbFetchArray($getAllMsg)){
           
            $msgs .= '<div class="client_messages" style="margin-bottom:10px;">
                <div class="client_messages_content>
                    <div class="client_messages_name">
					<p class="messdatestyl"><span class="lintst"><span class="dtabso">'.date("M j, Y h:i a", strtotime($recMsg['msgdate'])).'</span></span></p>
                    <h6>'.$recMsg['name'].' ('.$recMsg['email'].')</h6>
                    </div>
                    <p>'.stripslashes($recMsg['msg']).'</p>
                </div>
            </div>
            <div style="clear:both;"></div>';
        }
    }
    else{
        $msgs .= '<div class="client_messages" style="margin-bottom:10px;text-align:center;">
                <div class="client_messages_content">
                    No queries.
                </div>
            </div>';
    }
        
        dbQuery($dbConn, "update initial_messages set isread=1 where receiverid = '".$_SESSION['loginUserId']."' and jobid='".$jobid."'");
        
        print json_encode(array('success' => 1, 'msgs' => $msgs));

closeconn($dbConn);