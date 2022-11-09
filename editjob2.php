<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login'</script>";
	exit;
}

    $user = dbQuery($dbConn, "SELECT name,email from users where id = '".$_SESSION['loginUserId']."'");
    $row = dbFetchArray($user);
    
    if(count($_POST) > 0 && isset($_POST['paytype']) && isset($_POST['workmode'])){
        $myaddtl_compnstion = '';
        
        $jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
        $addtl_compnstion = isset($_POST['addtl_compnstion'])?$_POST['addtl_compnstion']:array();
        $paytype = isset($_POST['paytype'])?tep_db_input($_POST['paytype']):"";
        $payperhr = isset($_POST['payperhr'])?tep_db_input($_POST['payperhr']):"";
        $payperhr_max = isset($_POST['payperhr_max'])?tep_db_input($_POST['payperhr_max']):"";
        $commission_perctg = isset($_POST['commission_perctg'])?tep_db_input($_POST['commission_perctg']):"";
        $annual = isset($_POST['annual'])?tep_db_input($_POST['annual']):"";
        $annualamt = isset($_POST['annualamt'])?tep_db_input($_POST['annualamt']):"";
        $commission_perctg_amt = isset($_POST['commission_perctg_amt'])?tep_db_input($_POST['commission_perctg_amt']):"";
        $workmode = isset($_POST['workmode'])?tep_db_input($_POST['workmode']):"";
    
        
            if(count($addtl_compnstion) > 0){
                $myaddtl_compnstion = implode(",", $addtl_compnstion);
            }
            $today = date("Y-m-d");
            dbQuery($dbConn, "UPDATE job_details set addtl_compnstion = '".$myaddtl_compnstion."', paytype = '".$paytype."', payperhr = '".$payperhr."', payperhr_max = '".$payperhr_max."', commission_perctg = '".$commission_perctg."', annual = '".$annual."', annualamt = '".$annualamt."', commission_perctg_amt = '".$commission_perctg_amt."', workmode = '".$workmode."' where id = '".$jobid."'");
    
            echo "<form action='".SITEURL."editjob3' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'></form><script>document.getElementById('gotostep2').submit();</script>";
        
        exit;
    
    }
    
    if(isset($_POST['jobid'])){
        $myjobid = $_POST['jobid'];
    }
    
    $checkifjob = dbQuery($dbConn, "SELECT paytype,payperhr,payperhr_max,addtl_compnstion,commission_perctg,annual,annualamt,commission_perctg_amt,workmode from job_details where id = '".trim($myjobid)."'");
    $getdetls = dbFetchArray($checkifjob);
    
    $paytype = $getdetls['paytype'];
    $payperhr = $getdetls['payperhr'];
    $payperhr_max = $getdetls['payperhr_max'];
    $commission_perctg = $getdetls['commission_perctg'];
    $annual = $getdetls['annual'];
    $annualamt = $getdetls['annualamt'];
    $commission_perctg_amt = $getdetls['commission_perctg_amt'];
    $addtl_compnstion = $getdetls['addtl_compnstion'];
    $workmode = $getdetls['workmode'];

?>
<div class="login_page edit_job_2_sty">
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
					
					<div class="col-sm-8 offset-sm-2">
			
            <div class="login onlyscrol editscrl jobpostnewbgdesn">
				<form action='<?php echo SITEURL;?>editjob1' method='post' class="formback">
                        <input type='hidden' name='jobid' value='<?php echo trim($_POST['jobid']);?>'>
                        </form>
					<h4>Compensation</h4>
					<form action="" method="post" id="jobpost" class="eplyfrm">
                    <input type="hidden" value="<?php echo trim($_POST['jobid']);?>" name="jobid">
                        <label>Pay Type</label>
						<div class="form-group">
							<input type="radio" name="paytype" class="" value="2" <?php if($paytype==2) echo "checked";?>> <span>Hourly</span> &nbsp; &nbsp;
							<input type="radio" name="paytype" class="" value="3" <?php if($paytype==3) echo "checked";?>> <span>Commission</span><br />
							<input type="radio" name="paytype" class="required" value="1" <?php if($paytype==1) echo "checked";?>> <span>Annual</span> &nbsp; &nbsp;
							<input type="radio" name="paytype" value="4" <?php if($paytype==4) echo "checked";?>> <span>Annual and Commission</span>
						</div>
						<div class="col-sm-12">
						
						<?php
						if($paytype == 2){
							$hourlystyle = '';
						}
						else
						$hourlystyle = 'style="display:none;"';
						if($paytype == 3){
							$cmmsonstyle = '';
						}
						else
						$cmmsonstyle = 'style="display:none;"';
						if($paytype == 1){
							$annualstyle = '';
						}
						else
						$annualstyle = 'style="display:none;"';
						if($paytype == 4){
							$annualcommstyle = '';
						}
						else
						$annualcommstyle = 'style="display:none;"';
						?>
							<div class="" id="hourly" <?php echo $hourlystyle;?>>
							<label style="text-transform:none;">Rate ($AUD)<span style="font-size:12px;"> &nbsp; Enter a rate to offer staff</span></label>
								<div class="row">
									<div class="col-sm-6"><input type="text" placeholder="Rate" name="payperhr" class="form-control required digits" value="<?php if($payperhr) echo $payperhr;?>"></div>
									<!--<div class="col-sm-6"><input type="text" placeholder="Maximum" name="payperhr_max" class="form-control required digits" value="<?php //if($payperhr_max) echo $payperhr_max;?>"></div>-->
								</div>
							 </div>
							 <div class="" id="commission" <?php echo $cmmsonstyle;?>>
							 <label style="text-transform:none;">Enter commission %<span style="font-size:12px;"> &nbsp; Enter commission % to offer staff</span></label>
								<div class="row">
									<div class="col-sm-12"><input type="text" placeholder="Commission %" name="commission_perctg" class="form-control required digits" value="<?php if($commission_perctg) echo $commission_perctg;?>"></div>
								</div>
							 </div>
							 <div class="" id="annual" <?php echo $annualstyle;?>>
							 <label style="text-transform:none;">Annual amount ($AUD)<span style="font-size:12px;"> &nbsp; Enter annual amount to offer staff</span></label>
								<div class="row">
									<div class="col-9 col-sm-10"><input type="text" placeholder="Amount" name="annual" class="form-control required digits" value="<?php if($annual) echo $annual;?>"></div>
									<div class="col-3 col-sm-2"><p style="line-height:55px;">/ Year</p></div>
								</div>
							 </div>
							 <div class="" id="annualcomm" <?php echo $annualcommstyle;?>>
							 <label style="text-transform:none;">Annual amount ($AUD) & commission %<span style="font-size:12px;"> &nbsp; Enter annual amount & commission % to offer staff</span></label>
								<div class="row">
									<div class="col-4 col-sm-5"><input type="text" placeholder="Amount" name="annualamt" class="form-control required digits" value="<?php if($annualamt) echo $annualamt;?>"></div>
									<div class="col-3 col-sm-2"><p style="line-height:55px;">/ Year</p></div>
									<div class="col-5 col-sm-5"><input type="text" placeholder="Commission %" name="commission_perctg_amt" class="form-control required digits" value="<?php if($commission_perctg_amt) echo $commission_perctg_amt;?>"></div>
								</div>
							 </div>
						</div>
						<div class="form-group">
						<label>Do You Offer Any of the Following Supplementary Pay?</label><br>
						<?php
						$wtype = dbQuery($dbConn, "select * from addtl_compnstion");
								while($row = dbFetchArray($wtype)){
								?>
								<input type="checkbox" name="addtl_compnstion[]" <?php if(strpos($addtl_compnstion, $row['id'])!==false) echo "checked";?> value="<?php echo $row['id'];?>"> <span><?php echo $row['compensation'];?></span><br>
								<?php
								}
								?>
						</div>

                        <div class="form-group">
							<label>Work Mode</label>
							<select class="form-select required" name="workmode">
								<option value="2" <?php if($workmode==2) echo "selected";?>>In person</option>
                                <option value="1" <?php if($workmode==1) echo "selected";?>>Remote</option>
							  </select>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<input type="button" value="Back" onclick="location.href='<?php echo SITEURL;?>editjob1/<?php echo $myjobid;?>'" class="onlybutfulwdt">
							</div>
							<div class="col-sm-2">
								<input type="submit" value="Next" class="onlybutfulwdt">
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