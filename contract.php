<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login'</script>";
	exit;
}

//if(isset($_POST['jobid'])){

if(count($_POST) > 0 && isset($_POST['contract'])){
	$jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
	$uniform = isset($_POST['uniform'])?tep_db_input($_POST['uniform']):"";
	$selctuniform = isset($_POST['selctuniform'])?tep_db_input($_POST['selctuniform']):"";

	$righttowork = isset($_POST['righttowork'])?tep_db_input($_POST['righttowork']):"";
	$otherrightto = isset($_POST['otherrightto'])?strip_tags(tep_db_input($_POST['otherrightto'])):"";
	$otherlunch = isset($_POST['otherlunch'])?strip_tags(tep_db_input($_POST['otherlunch'])):"";
	$lunchbrk = isset($_POST['lunchbrk'])?tep_db_input($_POST['lunchbrk']):"";
	
	$otherinfo = isset($_POST['otherinfo'])?strip_tags(tep_db_input($_POST['otherinfo'])):"";
    $work_with_child = isset($_POST['work_with_child'])?tep_db_input($_POST['work_with_child']):"";
	$otherchild = isset($_POST['otherchild'])?strip_tags(tep_db_input($_POST['otherchild'])):"";
    $work_timeframe = isset($_POST['work_timeframe'])?tep_db_input($_POST['work_timeframe']):"";
	$othertmfr = isset($_POST['othertmfr'])?strip_tags(tep_db_input($_POST['othertmfr'])):"";
	
	$trainingtype = isset($_POST['trainingtype'])?tep_db_input($_POST['trainingtype']):"";
	$expreq1 = isset($_POST['expreq1'])?strip_tags(tep_db_input($_POST['expreq1'])):"";
	$expreq2 = isset($_POST['expreq2'])?strip_tags(tep_db_input($_POST['expreq2'])):"";
	$expreq3 = isset($_POST['expreq3'])?strip_tags(tep_db_input($_POST['expreq3'])):"";
	$noexpernce = isset($_POST['noexpernce'])?tep_db_input($_POST['noexpernce']):"";

		dbQuery($dbConn, "UPDATE job_details set righttowork = '".$righttowork."', otherlunch = '".$otherlunch."', uniform = '".$uniform."', otherunifm = '".$selctuniform."', lunchbrk = '".$lunchbrk."', otherrightto = '".$otherrightto."', otherinfo = '".$otherinfo."', work_with_child = '".$work_with_child."', otherchild = '".$otherchild."', work_timeframe = '".$work_timeframe."', othertmfr = '".$othertmfr."', trainingtype = '".$trainingtype."', expreq1 = '".$expreq1."', expreq2 = '".$expreq2."', expreq3 = '".$expreq3."', noexpernce = '".$noexpernce."', contract_type=1 where id = '".$jobid."'");
	echo "<form action='".SITEURL."jobpost5' method='post' id='gotostep2'><input type='hidden' value='1' name='contract_done'><input type='hidden' name='jobid' value='".$jobid."'></form><script>document.getElementById('gotostep2').submit();</script>";
	
	exit;

}
?>
<section class="login_page">
	<div class="container">
		<div class="stepbystp">
			<ul>
				<li>Job Details</li>
				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>
				<li>Compensation</li>
				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>
				<li>Additional Details</li>
				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>
				<li class="actv">Contract</li>
				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>
				<li>Post Job</li>
			</ul>
		</div>
	
		<div class="row">
			
			<div class="col-sm-8 offset-sm-2">
				<div class="login onlyscrol jobpostnewbgdesn">
					<h4>Contract Details</h4>
					<form action='<?php echo SITEURL;?>jobpost5' method='post' class="formback">
                        <input type='hidden' name='jobid' value='<?php echo trim($_REQUEST['job']);?>'>
                    </form>
					<form action="" method="post" id="jobpost" class="eplyfrm" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo trim($_REQUEST['job']);?>" name="jobid">
                    <input type="hidden" value="1" name="contract">
                        <div class="form-group">
						<label>Right to Work</label><br>
						<input type="radio" value="1" name="righttowork" checked > <span>Must have the right to work in Australia</span>
						<br>
						<input type="radio" id="showotherrightto" name="righttowork" value="2"> <span>Other</span>
						<input type="text" name="otherrightto" id="otherrightto" placeholder="Type Other" class="form-control" style="display:none;">
						</div>
						
                        <!--<div class="form-group">
						<label>Working With Children</label><br>
						<input type="radio" value="1" name="work_with_child" checked > <span>Must have completed working with children check</span>
						<br>
						<input type="radio" value="3" name="work_with_child" > <span>Does not need to have working with children check</span>
						<br>
						<input type="radio" id="showotherchild" name="work_with_child" value="2"> <span>Other</span>
						<input type="text" name="otherchild" id="otherchild" placeholder="Type Other" class="form-control" style="display:none;">
						</div>-->
						
                        <div class="form-group">
						<label>Work Time Frame</label><br>
						<input type="radio" value="1" name="work_timeframe" checked > <span>Work is solely casual and for this period only. There is no ongoing work. </span>
						<br>
						<input type="radio" id="showothertmfr" name="work_timeframe" value="2"> <span>Other</span>
						<input type="text" name="othertmfr" id="othertmfr" placeholder="Type Other" class="form-control" style="display:none;">
						</div>
						
						<div class="form-group">
						<label>Training</label><br>
						<input type="radio" value="1" name="trainingtype" checked> <span>Full training provided</span>
						<br>
						<input type="radio" name="trainingtype" value="2"> <span>Some training provided</span>
						<br>
						<input type="radio" name="trainingtype" value="3"> <span>No training provided, candidate must be highly experienced</span>
						</div>
						
						<div class="form-group">
						<label>3 Things candidates must have experience in:</label><br>
						<label>(E.g. Cash Registers, Particular Software, Custom Service)</label>
						<input type="text" name="expreq1" class="form-control mustexp required">
						<input type="text" name="expreq2" class="form-control mustexp required">
						<input type="text" name="expreq3" class="form-control mustexp required">
						<label>OR</label>
						<label><input type="checkbox" name="noexpernce" id="noexpernce" value="1"> Absolutely no experience in anything needed</label>
						</div>
						
                        
                        <div class="form-group">
						<label><input type="checkbox" id="showuni"> Uniform</label><br>
                        <div id="uniformdiv" style="display:none;padding-left:15px;">
						<?php
							$i=0;
								$uniform = dbQuery($dbConn, "select * from uniform_contract");
								while($row = dbFetchArray($uniform)){
								?>
						<input type="radio" name="uniform" id="uni_<?php echo $i;?>" value="<?php echo $row['uniform'];?>"> <span><?php echo $row['uniform'];?></span><br>
						<?php
						$i++;
						}
						?>
						<input type="text" name="selctuniform" id="selctuniform" placeholder="Other Uniform" class="form-control" style="display:none;">
						</div>
                        </div>
						<div class="form-group">
						<label><input type="checkbox" id="showlunch"> Lunch</label><br>
                        <div id="lunchdiv" style="display:none;padding-left:15px;">
						<span style="float:left;">There will be a</span> <input style="width:150px;float:left;margin:0 10px;height:auto;padding:5px;border-radius:5px;" type="text" name="lunchbrk" id="lunchbrk" class="form-control digits"> <span style="float:left;">mins lunch break</span>
						<br>
						<div class="othr" style="clear:both;">
							<span><input type="checkbox" id="showother"> Other</span>
							<input type="text" name="otherlunch" id="otherlunch" placeholder="Other breaks (if any)" class="form-control" style="display:none;">
						</div>
                        </div>
						</div>
												
						<div class="form-group">
						<label>Other Information</label><br>
						<textarea name="otherinfo" class="form-control" placeholder="Please type"></textarea>
						</div>
						
						<div class="form-group">
						<label>Company Policies</label><br>
						<input type="checkbox" value="1" name="policy" style="vertical-align: top;margin-top: 4px;" checked disabled>&nbsp;<span>Please check with us on any company policies on arrival including conduct,<br>privacy and safety policies</span>
						</div>
						<div class="row">
							<div class="col-sm-6"><input type="button" value="Back" id="back"></div>
							<div class="col-sm-6"><input type="submit" value="Submit"></div>
						</div>
						
						
					</form>
					
				</div>
			</div>
		</div>
	</div>
</section>
	
<?php 
//}
include_once('footer.php');?>