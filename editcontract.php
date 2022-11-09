<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login'</script>";
	exit;
}
if(count($_POST) > 0 && isset($_POST['contract'])){
	$jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
	$showuni = isset($_POST['showuni'])?tep_db_input($_POST['showuni']):"";

	if($showuni == 1){
		$uniform = isset($_POST['uniform'])?tep_db_input($_POST['uniform']):"";
		$selctuniform = isset($_POST['selctuniform'])?tep_db_input($_POST['selctuniform']):"";
	}
	else{
		$uniform = '';
		$selctuniform = '';
	}

	$righttowork = isset($_POST['righttowork'])?tep_db_input($_POST['righttowork']):"";
	$otherrightto = isset($_POST['otherrightto'])?strip_tags(tep_db_input($_POST['otherrightto'])):"";

	$showlunch = isset($_POST['showlunch'])?tep_db_input($_POST['showlunch']):"";

	if($showlunch == 1){
		$otherlunch = isset($_POST['otherlunch'])?strip_tags(tep_db_input($_POST['otherlunch'])):"";
		$lunchbrk = isset($_POST['lunchbrk'])?tep_db_input($_POST['lunchbrk']):"";
	}
	else{
		$otherlunch = '';
		$lunchbrk = '';
	}
	
	$otherinfo = isset($_POST['otherinfo'])?strip_tags(tep_db_input($_POST['otherinfo'])):"";
    $work_with_child = isset($_POST['work_with_child'])?tep_db_input($_POST['work_with_child']):"";
	$otherchild = isset($_POST['otherchild'])?tep_db_input($_POST['otherchild']):"";
    $work_timeframe = isset($_POST['work_timeframe'])?tep_db_input($_POST['work_timeframe']):"";
	$othertmfr = isset($_POST['othertmfr'])?strip_tags(tep_db_input($_POST['othertmfr'])):"";
	
	$trainingtype = isset($_POST['trainingtype'])?tep_db_input($_POST['trainingtype']):"";
	$expreq1 = isset($_POST['expreq1'])?strip_tags(tep_db_input($_POST['expreq1'])):"";
	$expreq2 = isset($_POST['expreq2'])?strip_tags(tep_db_input($_POST['expreq2'])):"";
	$expreq3 = isset($_POST['expreq3'])?strip_tags(tep_db_input($_POST['expreq3'])):"";
	$noexpernce = isset($_POST['noexpernce'])?tep_db_input($_POST['noexpernce']):"";

		dbQuery($dbConn, "UPDATE job_details set righttowork = '".$righttowork."', otherlunch = '".$otherlunch."', uniform = '".$uniform."', otherunifm = '".$selctuniform."', lunchbrk = '".$lunchbrk."', otherrightto = '".$otherrightto."', otherinfo = '".$otherinfo."', work_with_child = '".$work_with_child."', otherchild = '".$otherchild."', work_timeframe = '".$work_timeframe."', othertmfr = '".$othertmfr."', trainingtype = '".$trainingtype."', expreq1 = '".$expreq1."', expreq2 = '".$expreq2."', expreq3 = '".$expreq3."', noexpernce = '".$noexpernce."', contract_type=1, custom_contract='' where id = '".$jobid."'");
	echo "<form action='".SITEURL."editjob4' method='post' id='gotostep2'><input type='hidden' value='1' name='contract_done'><input type='hidden' name='jobid' value='".$jobid."'></form><script>document.getElementById('gotostep2').submit();</script>";
	
	exit;

}

$check = dbQuery($dbConn, "select righttowork,otherrightto,otherlunch,uniform,otherunifm,lunchbrk,otherinfo,work_with_child,otherchild,work_timeframe,othertmfr,trainingtype,expreq1,expreq2,expreq3,noexpernce from job_details where id = '".trim($_REQUEST['job'])."'");
$getcontct = dbFetchArray($check);
?>

<div class="login_page edit_contract_sty">
	<div class="works works_success editjoball">
		<div class="container">
        
			<div class="row">
			<?php include_once "employer_left.php";?>
            <div class="col-lg-9">
				<div class="row">
					
					<div class="col-sm-8 offset-sm-2">
					<div class="login onlyscrol editscrl jobpostnewbgdesn">
					<h4>Contract Details</h4>
					<form action='<?php echo SITEURL;?>editjob4' method='post' class="formback">
                        <input type='hidden' name='jobid' value='<?php echo trim($_REQUEST['job']);?>'>
                    </form>
					<form action="" method="post" id="jobpost" class="eplyfrm" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo trim($_REQUEST['job']);?>" name="jobid">
                    <input type="hidden" value="1" name="contract">
                        <div class="form-group">
						<label>Right to Work</label><br>
						<input type="radio" value="1" name="righttowork" <?php if($getcontct['righttowork']==1) echo "checked";?> > <span>Must have the right to work in Australia</span>
						<br>
						<input type="radio" id="showotherrightto" name="righttowork" value="2" <?php if($getcontct['righttowork']==2) echo "checked";?>> <span>Other</span>
						<?php
						if($getcontct['otherrightto'])
						$otrrightstyle = '';
						else
						$otrrightstyle = 'style="display:none;"';
						?>
						<input type="text" name="otherrightto" id="otherrightto" placeholder="Type Other" class="form-control" <?php echo $otrrightstyle;?> value="<?php echo stripslashes($getcontct['otherrightto']);?>">
						</div>
						
                        <!--<div class="form-group">
						<label>Working With Children</label><br>
						<input type="radio" value="1" name="work_with_child" <?php //if($getcontct['work_with_child']==1) echo "checked";?> > <span>Must have completed working with children check</span>
						<br>
						<input type="radio" value="3" name="work_with_child" <?php //if($getcontct['work_with_child']==3) echo "checked";?>> <span>Does not need to have working with children check</span>
						<br>
						<input type="radio" id="showotherchild" name="work_with_child" value="2" <?php //if($getcontct['work_with_child']==2) echo "checked";?>> <span>Other</span>
						<?php
				 		if($getcontct['otherchild'])
						$otrchildstyle = '';
						else
						$otrchildstyle = 'style="display:none;"';
						?>
						<input type="text" name="otherchild" id="otherchild" placeholder="Type Other" class="form-control" <?php //echo $otrchildstyle;?> value="<?php //echo stripslashes($getcontct['otherchild']);?>">
						</div>-->
						
                        <div class="form-group">
						<label>Work Time Frame</label><br>
						<input type="radio" value="1" name="work_timeframe" <?php if($getcontct['work_timeframe']==1) echo "checked";?> > <span>Work is solely casual and for this period only. There is no ongoing work. </span>
						<br>
						<input type="radio" id="showothertmfr" name="work_timeframe" value="2" <?php if($getcontct['work_timeframe']==2) echo "checked";?>> <span>Other</span>
						<?php
				 		if($getcontct['othertmfr'])
						$otrtimefrstyle = '';
						else
						$otrtimefrstyle = 'style="display:none;"';
						?>
						<input type="text" name="othertmfr" id="othertmfr" placeholder="Type Other" class="form-control" <?php echo $otrtimefrstyle;?> value="<?php echo stripslashes($getcontct['othertmfr']);?>">
						</div>
						
						<div class="form-group">
						<label>Training</label><br>
						<input type="radio" value="1" name="trainingtype" <?php if($getcontct['trainingtype']==1) echo "checked";?>> <span>Full training provided</span>
						<br>
						<input type="radio" name="trainingtype" value="2" <?php if($getcontct['trainingtype']==2) echo "checked";?>> <span>Some training provided</span>
						<br>
						<input type="radio" name="trainingtype" value="3" <?php if($getcontct['trainingtype']==3) echo "checked";?>> <span>No training provided, candidate must be highly experienced</span>
						</div>
						
						<div class="form-group">
						<label>3 Things candidates must have experience in:</label><br>
						<label>(E.g. Cash Registers, Particular Software, Custom Service)</label>
						<input type="text" name="expreq1" class="form-control mustexp required" value="<?php echo stripslashes($getcontct['expreq1']);?>">
						<input type="text" name="expreq2" class="form-control mustexp required" value="<?php echo stripslashes($getcontct['expreq2']);?>">
						<input type="text" name="expreq3" class="form-control mustexp required" value="<?php echo stripslashes($getcontct['expreq3']);?>">
						<label>OR</label>
						<label><input type="checkbox" name="noexpernce" id="noexpernce" value="1" <?php if($getcontct['noexpernce']==1) echo "checked";?>> Absolutely no experience in anything needed</label>
						</div>
						
						<?php
				 		if($getcontct['uniform'])
						$uniformstyle = '';
						else
						$uniformstyle = 'display:none;';
						?>
                        <div class="form-group">
						<label><input type="checkbox" id="showuni" name="showuni" value="1" <?php if($getcontct['uniform']) echo "checked";?>> Uniform</label><br>
                        <div id="uniformdiv" style="<?php echo $uniformstyle;?>padding-left:15px;">
						<?php
							$i=0;
								$uniform = dbQuery($dbConn, "select * from uniform_contract");
								while($row = dbFetchArray($uniform)){
								?>
						<input type="radio" name="uniform" id="uni_<?php echo $i;?>" value="<?php echo $row['uniform'];?>" <?php if($getcontct['uniform']==$row['uniform']) echo "checked";?>> <span><?php echo $row['uniform'];?></span><br>
						<?php
						$i++;
						}
				 		if($getcontct['otherunifm'])
						$otrunistyle = '';
						else
						$otrunistyle = 'style="display:none;"';
						?>
						<input type="text" name="selctuniform" id="selctuniform" placeholder="Other Uniform" class="form-control" <?php echo $otrunistyle;?> value="<?php echo stripslashes($getcontct['otherunifm']);?>">
						</div>
                        </div>
						<?php
				 		if($getcontct['lunchbrk'])
						$lunchbrstyle = '';
						else
						$lunchbrstyle = 'display:none;';
						?>
						<div class="form-group">
						<label><input type="checkbox" id="showlunch" name="showlunch" value="1" <?php if($getcontct['lunchbrk']) echo "checked";?>> Lunch</label><br>
                        <div id="lunchdiv" style="<?php echo $lunchbrstyle;?>padding-left:15px;">
						<span style="float:left;">There will be a</span> <input style="width:150px;float:left;margin:0 10px;height:auto;padding:5px;border-radius:5px;" type="text" name="lunchbrk" id="lunchbrk" value="<?php echo stripslashes($getcontct['lunchbrk']);?>" class="form-control digits"> <span style="float:left;">mins lunch break</span>
						<br>
						<?php
				 		if($getcontct['otherlunch'])
						$otrlunchstyle = '';
						else
						$otrlunchstyle = 'style="display:none;"';
						?>
						<div class="othr" style="clear:both;">
							<span><input type="checkbox" id="showother" <?php if($getcontct['otherlunch']) echo "checked";?>> Other</span>
							<input type="text" name="otherlunch" id="otherlunch" placeholder="Other break (if any)" class="form-control" <?php echo $otrlunchstyle;?> value="<?php echo stripslashes($getcontct['otherlunch']);?>">
						</div>
                        </div>
						</div>
												
						<div class="form-group">
						<label>Other Information</label><br>
						<textarea name="otherinfo" class="form-control" placeholder="Please type"><?php echo stripslashes($getcontct['otherinfo']);?></textarea>
						</div>
						
						<div class="form-group">
						<label>Company Policies</label><br>
						<input type="checkbox" value="1" name="policy" style="vertical-align: top;margin-top: 4px;" checked disabled>&nbsp;<span>Please check with us on any company policies on arrival including conduct,<br>privacy and safety policies</span>
						</div>
						<div class="row">
							<div class="col-sm-6"><input type="button" value="Back" id="myback"></div>
							<div class="col-sm-6"><input type="submit" value="Submit"></div>
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