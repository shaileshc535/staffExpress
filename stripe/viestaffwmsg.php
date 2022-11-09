<?php
include('config/config.php'); 
include_once "config/common.php";

$dbConn = establishcon();
if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."staff_login'</script>";
	exit;
}
    
include_once('header.php');
$addcomp = '';
$benefit = '';


$id = isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
$jobid = isset($_REQUEST['jobid'])?base64_decode(trim($_REQUEST['jobid'])):"";

$employer = dbQuery($dbConn, "select name from users where id = '".$id."'");
$row = dbFetchArray($employer);

$thejob = dbQuery($dbConn, "select title from job_details where id = '".$jobid."'");
$jobrow = dbFetchArray($thejob);

    $candidate = dbQuery($dbConn, "SELECT b.id,b.name FROM users b where b.id='".$_SESSION['loginUserId']."'");
    $fetch = dbFetchArray($candidate);
    
    $candid = $_SESSION['loginUserId'];
    $candname = $fetch['name'];

    $usertype = getUserType($dbConn, $_SESSION['loginUserId']);
?>

	<div class="works messaging_sty">
		<div class="container">
			<div class="inneremploypg jobdtlsall">
            <input type="hidden" id="userid" name="userid" value="<?php echo $candid;?>">
            <input type="hidden" id="employerid" name="employerid" value="<?php echo $id;?>">
            <input type="hidden" id="usertype" name="usertype" value="<?php echo $usertype;?>">
				<div class="row">
                    <div class="col-lg-6 m-auto">
                        <div class="messaging_sty_heading">
                        <h4>Messaging <?php echo stripslashes($row['name']);?></h4>
                        <h4>Job: <?php echo stripslashes($jobrow['title']);?></h4>
                        </div>
                    </div>
					<div class="col-sm-4">
						<div class="jobimageleft"><img src="<?php echo SITEURL;?>images/nursejob.png" alt="" class="img-responsive" /></div>
					</div>
					<div class="col-sm-8">
                    <div id="existingmsg" style="height: 400px;overflow: auto;border:1px solid #ccc;margin-bottom:20px;padding:10px;">
                    <?php
                    // show messages
                    $msgid = 1;
                    $msgdt = 1;
                        /*$getLatstMsg = dbQuery($dbConn, "SELECT DATE(msgdate) as mydate from (SELECT msgdate from messages where (senderid = '".$id."' OR receiverid = '".$id."') AND  (senderid = '".$candid."' OR receiverid = '".$candid."') group by DATE(msgdate) order by msgdate desc ) t order by msgdate");
                        if(dbNumRows($getLatstMsg) > 0){*/
                            $chatdisply = "display:none;";
                        //while($myMsg = dbFetchArray($getLatstMsg)){
                            ?>
                            <!--<p class="text-center client_messages_time" id="msgdt_<?php echo $msgdt;?>" style="margin-bottom:10px;"><?php echo date("d M Y", strtotime($myMsg['mydate']));?></p>-->
                            <?php
                            $getAllMsg = dbQuery($dbConn, "SELECT senderid,receiverid,msg,msgdate from (SELECT m.senderid,m.receiverid,m.msg,m.msgdate,s.name as sender,r.name as receiver from messages m inner join users s on m.senderid=s.id inner join users r on m.receiverid=r.id where 1 AND (senderid = '".$id."' OR receiverid = '".$id."') AND  (senderid = '".$candid."' OR receiverid = '".$candid."') order by msgdate desc ) t2 order by msgdate");
                            if(dbNumRows($getAllMsg) > 0){
                            while($recMsg = dbFetchArray($getAllMsg)){
                    ?>
                        
                        <?php
                        if($recMsg['senderid'] == $id){
                            $sender = $row['name'];
                            $msgstyle = "";
                            /*$provider = dbQuery($dbConn, "SELECT id from users where id = '".$_SESSION['loginUserId']."'");
                            $providerrow = dbFetchArray($provider);
                            $senderimg = getUserImg($dbConn, $providerrow['id']);*/
                        }
                        else if($recMsg['senderid'] == $candid){
                            $sender = $candname;
                            $msgstyle = "style='text-align:right;'";
                            //$senderimg = $renterimg;
                        }
                        ?>
                        <div class="client_messages" style="margin-bottom:15px;">
                            <!--<img src="<?php echo $senderimg;?>" alt="">-->
                            <div class="client_messages_content" <?php echo $msgstyle;?>>
                                <div class="client_messages_name">
                                    <h6><?php echo $sender;?></h6>
                                    <span><?php echo date('M j, Y h:i a', strtotime($recMsg['msgdate']));?></span>
                                </div>
                                <p><?php echo stripslashes($recMsg['msg']);?></p>
                                
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                        <?php
                        $msgid++;
                            }
                            $msgdt++;
                            //}
                        }
                        else{
                            $chatdisply = "";
                        }
                            
                        ?>
                        <!--<div id="loadnewmsg" style="overflow: auto;"></div>-->
                        </div>
                        <h6 style='text-align:center;margin-top:0px;margin-bottom:0;<?php echo $chatdisply;?>' class='nomsg'>No messages so far.</h6>
                        
                        <br>
                        <input type="text" class="form-control" id="mymsg" placeholder="Type a message">
                        <a href="javascript:void(0);" class="chkotcls" id="sendmsg" style="float:right;margin-top:10px;">Send Message</a>
                        <br>
                        <span id="success" style="color:#69A268;font-weight:bold;"></span>

                        <ul class="homebtn" style="margin:20px 0;">
							<li><a href="<?php echo SITEURL;?>staff_messages" style="padding:0.4rem 1.5rem;">Back</a></li>
							</ul>
					</div>
				
									
				</div>
			</div>
		</div>
	</div>
	
	<?php include_once('footer.php');
?>