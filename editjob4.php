<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login'</script>";
	exit;
}
if(count($_POST) > 0 && isset($_POST["contract"])){

    $jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
    $makeopen = isset($_POST['makeopen'])?tep_db_input($_POST['makeopen']):"";

        if(isset($_FILES["upld_contract"]) && $_FILES["upld_contract"]["size"]>0)
        {
            $updimgnm='';
            
                $mytime=time();
                
                $srcfile = $_FILES['upld_contract']['type'];
                //$imageinfo = getimagesize($_FILES['upld_contract']['tmp_name']); //check image size
                
                $mflname = strtolower($_FILES['upld_contract']['name']);
                $mflname = str_replace(" ", "_", $mflname);
                //echo $imageinfo['mime'];

                if($srcfile == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $srcfile == "application/pdf")
                {
                    $errstring1="";
                }
                else
                {
                    $errstring1="Please Upload only image file.(.jpg,.png,.gif)";
                
                }
                
                        $blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");
                
                        if(strpos($mflname,".php")===false && strpos($mflname,".phtml")===false && strpos($mflname,".php3")===false && strpos($mflname,".php4")===false && strpos($mflname,".php5")===false && strpos($mflname,".html")===false && strpos($mflname,".htm")===false && strpos($mflname,".exe")===false) 
                        {
                                    
                            $errstring2="";
                        }
                        else
                        {
                            
                            $errstring2="Please Upload only image file.(.jpg,.png,.gif)";
                        }
                        
                        $dblchk = array();
                        
                        $dblchk=explode(".",$mflname);
                        
                        if(count($dblchk)==2) 
                        {
                                    
                            $errstring3="";
                        }
                        else
                        {
                            
                            $errstring3="Please Upload only image file.(.jpg,.png,.gif)";
                        }
                
                if($errstring1=="" && $errstring2=="")
                {
                    
                    if(move_uploaded_file($_FILES["upld_contract"]["tmp_name"], "contract/" . $mytime.'_'.$mflname))
                    {
                        $myfile = $mytime.'_'.$mflname;
                        dbQuery($dbConn, "UPDATE job_details set custom_contract = '".$myfile."', contract_type=2 where id = '".$jobid."'");
                    }
                }
                else{
                    echo "<form action='".SITEURL."jobpost5' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'><input type='hidden' name='error' value='1'></form><script>document.getElementById('gotostep2').submit();</script>";
	                exit;
                }
                
        }
        if($makeopen == 1){
            //dbQuery($dbConn, "UPDATE job_details set isclosed = 0 where id = '".$jobid."'");
			echo "<form action='".SITEURL."editjob5' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'></form><script>document.getElementById('gotostep2').submit();</script>";
        }
		else{
			echo "<form action='".SITEURL."myjobs' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'><input type='hidden' name='jobupdated' value='1'></form><script>document.getElementById('gotostep2').submit();</script>";
		}

	exit;

}

$check = dbQuery($dbConn, "select custom_contract,contract_type,isclosed,worktype from job_details where id = '".trim($_POST['jobid'])."'");
$getcontct = dbFetchArray($check);
?>

<div class="login_page edit_job_4_sty">
	<div class="works works_success editjoball">
		<div class="container">
			<div class="splnav">
				<ul class="onlyemployernav">
					<li <?php if($page == "editjob1.php" || $page == "editjob2.php" || $page == "editjob3.php" || $page == "editjob4.php" || $page == "editjob5.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">My Jobs</a></li>
					<li <?php if($page == "job_post1.php") echo "class='active'";?>><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>
					<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>
					<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>
				</ul>
			</div>
			<div class="row">
			<?php //include_once "employer_left.php";?>
            <div class="col-lg-12">
				<div class="row">
					<div class="col-lg-5">
						<div class="job_post_img">
							<img src="<?php echo SITEURL;?>/images/jobpostimag.png" class="img-fluid" alt="">
						</div>
					</div>
					<div class="col-lg-7">
			
						<div class="login onlyscrol editscrl">
					<h4>Contract Details</h4>
                    <?php
						if(isset($_POST['error']) && $_POST['error'] == 1){
							echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Please upload docx or PDF file.</div>";
						}
						?>
                        <form action='<?php echo SITEURL;?>contract' method='post' id='viewcontract'>
                        <input type='hidden' name='jobid' value='<?php echo trim($_POST['jobid']);?>'>
                        </form>
                        <form action='<?php echo SITEURL;?>editjob3' method='post' class="formback">
                        <input type='hidden' name='jobid' value='<?php echo trim($_POST['jobid']);?>'>
                        </form>
					<form action="" method="post" id="contractform" class="eplyfrm" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo trim($_POST['jobid']);?>" name="jobid">
					<input type="hidden" id="contracttype" value="<?php echo $getcontct['contract_type'];?>">
                    <?php
						if($getcontct['contract_type']=='1'){
                            $defltcontractstle = '';
                            $owncontractstle = 'style="display:none;"';
							?>
							<div class='alert-success' style='padding:15px;margin-bottom:15px;'>You have selected our pre-filled contract.</div>
							<?php
						}
						else if($getcontct['contract_type']=='2'){
                            $defltcontractstle = 'style="display:none;"';
                            $owncontractstle = '';
							?>
							<div class='alert-success' style='padding:15px;margin-bottom:15px;'>You have uploaded custom contract.</div>
							<?php
						}
                        ?>
						<div class="form-group">
                        <label style="text-transform:none;">
                        <?php
                        if($getcontct['worktype'] == 1){
                        ?>
                        We understand that you maybe looking only for casual cover, however we understand some employers would still like to have some form of contract.<br>
                        <?php
                        }
                        ?>
 To make things easy we have prepared a pre-filled a contract for you to use, alternatively you can upload your own contract</label>

                            <select class="form-select required" name="contract" id="contract">
								<option value="">Select Contract Type</option>
                                <option value="2" <?php if($getcontct['contract_type']==1) echo "selected";?>>Would you like to edit our pre-filled contract</option>
                                <option value="3" <?php if($getcontct['contract_type']==2) echo "selected";?>>Use your own contract</option>
							  </select>
                         </div>
						
                    <div id="owncontract" <?php echo $owncontractstle;?>>
                        <div class="form-group">
                        
                        <?php
                        if($getcontct['custom_contract']){
                            ?>
                            <a href="<?php echo SITEURL;?>contract/<?php echo $getcontct['custom_contract'];?>" style="background-color: transparent;color: #3176B4;text-align: right;padding: 0;border: none;border-radius: 0;text-decoration: underline;" target="_blank">View Your Contract</a><br><br>
                            <?php
                        }
                        ?>
                        <label>Upload Contract</label><br>
                        <input type="file" name="upld_contract" id="upld_contract" class="form-control">
                        <span>(PDF or Docx file of maximum 3 pages)</span>
                        </div>
                    </div>
                    <div id="ourcontract" <?php echo $defltcontractstle;?>>
                        <div class="form-group">
                            <ul class="homebtn" style="margin:20px 0;">
								<li style="width:100%;text-align:right;"><a href="<?php echo SITEURL;?>editcontract?job=<?php echo trim($_POST['jobid']);?>" style="background-color: transparent;color: #3176B4;text-align: right;padding: 0;border: none;border-radius: 0;text-decoration: underline;">View Contract</a></li>
						    </ul>
						
                        </div>
                        </div>

                        <?php
                        if($getcontct['isclosed']==1){
                        ?>
                        <div class="form-group">
                            <select class="form-select required" name="makeopen" id="makeopen">
								<option value="">Open The Job?</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
							  </select>
                         </div>
                        <?php
                        }
                        ?>
						<div class="row">
							<div class="col-sm-2">
								<input type="button" value="Back" id="myback" class="onlybutfulwdt">
							</div>
							<div class="col-sm-2">
								<input type="submit" value="Submit" class="onlybutfulwdt">
							</div>
						</div>
					</form>
					
				</div>
					</div>
				</div>
			</div>
            </div>
		</div>
	</div>
</div>	
<?php include_once('footer.php');?>