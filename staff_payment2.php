<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

/*if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."staff_login'</script>";
	exit;
}*/

if(count($_POST) > 0){
	$id = isset($_POST['id'])?tep_db_input($_POST['id']):"";
	$nomedical = isset($_POST['nomedical'])?tep_db_input($_POST['nomedical']):"";
	$heart = isset($_POST['heart'])?tep_db_input($_POST['heart']):"";
	$diabetes = isset($_POST['diabetes'])?tep_db_input($_POST['diabetes']):"";
	$bloodpr = isset($_POST['bloodpr'])?tep_db_input($_POST['bloodpr']):"";
	$hasallgy = isset($_POST['hasallgy'])?tep_db_input($_POST['hasallgy']):"";
    $allergy = isset($_POST['allergy'])?tep_db_input($_POST['allergy']):"";
    $hasid = isset($_POST['hasid'])?tep_db_input($_POST['hasid']):"";
    $infectious = isset($_POST['infectious'])?tep_db_input($_POST['infectious']):"";
    $otherdis = isset($_POST['otherdis'])?tep_db_input($_POST['otherdis']):"";
    $hasother = isset($_POST['hasother'])?tep_db_input($_POST['hasother']):"";
	$covid_vaccn = isset($_POST['covid_vaccn'])?$_POST['covid_vaccn']:"";
	
	$no_of_doses = isset($_POST['no_of_doses'])?tep_db_input($_POST['no_of_doses']):"";
	$had_covid_before = isset($_POST['had_covid_before'])?$_POST['had_covid_before']:"";
	$had_covid_before_past = isset($_POST['had_covid_before_past'])?$_POST['had_covid_before_past']:"";

	dbQuery($dbConn, "UPDATE staff_details set nomedical = '".$nomedical."', heart = '".$heart."', diabetes = '".$diabetes."', bloodpr = '".$bloodpr."', hasallgy = '".$hasallgy."', allergy = '".$allergy."', hasid = '".$hasid."', infectious = '".$infectious."', hasother = '".$hasother."', otherdis = '".$otherdis."', no_of_doses = '".$no_of_doses."', had_covid_before = '".$had_covid_before."', had_covid_before_past = '".$had_covid_before_past."', profile_step = 3 where staff_id = '".$_SESSION['loginUserId']."'");
	
	

	echo "<script>location.href='".SITEURL."staff_payment4'</script>";
    exit;
	
}



$checkdetls = dbQuery($dbConn, "SELECT * from staff_details where staff_id = '".$_SESSION['loginUserId']."'");
$getdetls = dbFetchArray($checkdetls);
?>	

	<div class="works works_success i_am_staff_sty">
		<div class="container">

		<div class="stepbystp">

        <ul>

            <li>My Profile</li>

            <li><img src="images/dividr.png" alt="" /></li>

            <li>Experience</li>
            <li><img src="images/dividr.png" alt="" /></li>
            <li class="actv">Health Info</li>
            <li><img src="images/dividr.png" alt="" /></li>
            <li>Optional Upload</li>
			
        </ul>

        </div>
		
			<div class="row">
			<?php 
					include_once "staff_left.php";
					?>
				<div class="col-lg-9">
					
					<div class="works_heading" style="display:inline-block;">
					<?php
					if(isset($_REQUEST['success']) && $_REQUEST['success'] == 1){
					?>
						<div class='alert-success' style='padding:15px;margin-bottom:15px;'>Health info updated successfully.</div>
					<?php
					}
					?>
					<h4 style="margin-bottom:20px;">Health Info</h4>
					<div class="login login_page martopadjst2">					
					<form action="" method="post" id="healthinfo" class="" enctype="multipart/form-data">
						
						<div class="form-group" id="formedical">
							<div class="row">
								<div class="col-sm-12" style="margin-bottom:20px;">
								<label class="hlthspl">Any Medical Conditions?</label><br><br>
								<input type="checkbox" name="nomedical" id="nomedical" class="" value="1" <?php if($getdetls['nomedical'] == 1) echo "checked";?>> &nbsp; <label>Select "No" to all</label></div>
								<div class="col-sm-4" style="margin-bottom:20px;">

								<input type="checkbox" name="heart" id="heart" class="issue" value="1" <?php if($getdetls['heart'] == 1) echo "checked";?>> &nbsp; <label>Heart Conditions</label>
								
								</div>
								<div class="col-sm-4" style="margin-bottom:20px;">

								<input type="checkbox" name="diabetes" id="diabetes" class="issue" value="1" <?php if($getdetls['diabetes'] == 1) echo "checked";?>> &nbsp; <label>Diabetes</label></div>
								<div class="col-sm-4" style="margin-bottom:20px;">

								<input type="checkbox" name="bloodpr" id="bloodpr" class="issue" value="1" <?php if($getdetls['bloodpr'] == 1) echo "checked";?>> &nbsp; <label>High Blood Pressure</label></div>
								<div class="col-sm-4" style="margin-bottom:20px;">

								<input type="checkbox" name="hasallgy" id="hasallgy" class="issue" value="1" <?php if($getdetls['hasallgy'] == 1) echo "checked";?>> &nbsp; <label>Allergies:</label>
								<?php
								if($getdetls['allergy'])
								$otrallrystyle = '';
								else
								$otrallrystyle = 'style="display:none;"';
								?>
								<div <?php echo $otrallrystyle;?> id="allergy">
								<input type="text" placeholder="Please state" name="allergy" class="form-control" value="<?php echo $getdetls['allergy'];?>" style="margin-top:10px;"><br><br>
								</div>
								</div>
								<div class="col-sm-4" style="margin-bottom:20px;">
								<input type="checkbox" name="hasid" id="hasid" class="issue" value="1" <?php if($getdetls['hasid'] == 1) echo "checked";?>> &nbsp; <label>Infectious Disease:</label>
								<?php
								if($getdetls['infectious'])
								$infectsstyle = '';
								else
								$infectsstyle = 'style="display:none;"';
								?>
								<div <?php echo $infectsstyle;?> id="infectious" >
								<input type="text" placeholder="Please state" name="infectious" class="form-control" value="<?php echo $getdetls['infectious'];?>"  style="margin-top:10px;"><br><br>
								</div>
								</div>
								
								<div class="col-sm-4" style="margin-bottom:20px;">
								<input type="checkbox" name="hasother" id="hasother" class="issue" value="1" <?php if($getdetls['hasother'] == 1) echo "checked";?>> &nbsp; <label>Other:</label>
								<?php
								if($getdetls['otherdis'])
								$othdesisestyle = '';
								else
								$othdesisestyle = 'style="display:none;"';
								?>
								<div <?php echo $othdesisestyle;?> id="otherdis">
								<input type="text" placeholder="Please state" name="otherdis" class="form-control" value="<?php echo $getdetls['otherdis'];?>"  style="margin-top:10px;">
								</div>
								</div>
								
								<div class="col-sm-4" style="margin-bottom:20px;">
								<label style="float: left;line-height: 27px;padding-right: 10px;">Covid Number of Doses</label><input type="text" placeholder="Number of Doses" name="no_of_doses" class="form-control required digits" value="<?php echo $getdetls['no_of_doses'];?>"  style="margin-top: 0px;width: 130px;font-size: 12px;">
								</div>
								<div class="col-sm-4" style="margin-bottom:20px;">
								<label>I have had Covid before</label><br />
								<input type="radio" name="had_covid_before" value="1" required <?php if($getdetls['had_covid_before']==1) echo "checked";?>> <label>Yes</label>&nbsp;&nbsp;
								<input type="radio" name="had_covid_before" value="2" <?php if($getdetls['had_covid_before']==2) echo "checked";?>> <label>No</label>
								</div>
								<div class="col-sm-4" style="margin-bottom:20px;">
								<label>I have had Covid in the past 3 months</label><br />
								<input type="radio" name="had_covid_before_past" value="1" required <?php if($getdetls['had_covid_before_past']==1) echo "checked";?>> <label>Yes</label>&nbsp;&nbsp;
								<input type="radio" name="had_covid_before_past" value="2" <?php if($getdetls['had_covid_before_past']==2) echo "checked";?>> <label>No</label>
								</div>
								
								</div>
								
							</div>
						
						<div class="row">
							<div class="col-sm-2 col-lg-2">
								<input type="button" value="Back" id="back" onclick="location.href='<?php echo SITEURL;?>staff_experience'" class="onlybutfulwdt">
							</div>
							<div class="col-sm-2 col-lg-2">
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
	
	
	<?php include_once('footer.php');?>