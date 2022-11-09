<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');

if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."staff_login'</script>";
	exit;
}

if(count($_POST) > 0){
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

	dbQuery($dbConn, "UPDATE staff_details set nomedical = '".$nomedical."', heart = '".$heart."', diabetes = '".$diabetes."', bloodpr = '".$bloodpr."', hasallgy = '".$hasallgy."', allergy = '".$allergy."', hasid = '".$hasid."', infectious = '".$infectious."', hasother = '".$hasother."', otherdis = '".$otherdis."', covid_vaccn = '".$covid_vaccn."' where staff_id = '".$_SESSION['loginUserId']."'");
	
	if(isset($_FILES["vaccn_cert"]) && $_FILES["vaccn_cert"]["size"]>0){

		$updimgnm='';
		
		$mytime=time();

		$srcfile = $_FILES['vaccn_cert']['type'];

		$imageinfo = getimagesize($_FILES['vaccn_cert']['tmp_name']); //check vaccn_cert size


		$mflname = strtolower($_FILES['vaccn_cert']['name']);

		$mflname = str_replace(" ", "_", $mflname);

		if(($srcfile=='image/gif' || $srcfile=='image/jpeg' || $srcfile=='image/jpg' || $srcfile=='image/pjpeg' || $srcfile=='image/png' || $srcfile=='image/x-png') && ($imageinfo['mime']=='image/jpg' || $imageinfo['mime']=='image/gif' || $imageinfo['mime']=='image/jpeg' || $imageinfo['mime']=='image/pjpeg' || $imageinfo['mime']=='image/png' || $imageinfo['mime']=='image/x-png'))

		{

			$errstring1="";

		}

		else

		{

			$errstring1="Please Upload only vaccn_cert file.(.jpg,.png,.gif)";

		

		}

		

				$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");

		

				if(strpos($mflname,".php")===false && strpos($mflname,".phtml")===false && strpos($mflname,".php3")===false && strpos($mflname,".php4")===false && strpos($mflname,".php5")===false && strpos($mflname,".html")===false && strpos($mflname,".htm")===false && strpos($mflname,".exe")===false) 

				{

							

					$errstring2="";

				}

				else

				{

					

					$errstring2="Please Upload only vaccn_cert file.(.jpg,.png,.gif)";

				}

				

				$dblchk = array();

				

				$dblchk=explode(".",$mflname);

				

				if(count($dblchk)==2) 

				{

							

					$errstring3="";

				}

				else

				{

					

					$errstring3="Please Upload only vaccn_cert file.(.jpg,.png,.gif)";

				}

		

		if($errstring1=="" && $errstring2=="")

		{

			if(move_uploaded_file($_FILES["vaccn_cert"]["tmp_name"],"uploads/covid/" . $mytime.'_'.$mflname))

			{


			$sqlequip = dbQuery($dbConn, "SELECT `vaccn_cert` from staff_details where staff_id = '".$_SESSION['loginUserId']."'");

			$row = dbFetchArray($sqlequip);

			@unlink("uploads/covid/".$row['vaccn_cert']);

	

			$updimgnm = $mytime.'_'.$mflname;



				dbQuery($dbConn, "UPDATE staff_details set `vaccn_cert` = '".$updimgnm."' where staff_id='".$_SESSION['loginUserId']."'");

		

			}

		}

	}

	echo "<script>location.href='".SITEURL."staff_payment'</script>";
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
				<li><img src="vaccn_certs/dividr.png" alt="" /></li>
				<li class="actv">Health Info</li>
				<li><img src="vaccn_certs/dividr.png" alt="" /></li>
				<li>Payments</li>
				<li><img src="vaccn_certs/dividr.png" alt="" /></li>
				<li>Superannuation</li>
				<li><img src="vaccn_certs/dividr.png" alt="" /></li>
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
					
						<div class="form-group" id="forstaff">
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
								<label>Covid Vaccine?</label>&nbsp;&nbsp;
								<input type="radio" name="covid_vaccn" value="1" required <?php if($getdetls['covid_vaccn']==1) echo "checked";?>> <label>Yes</label>&nbsp;&nbsp;
								<input type="radio" name="covid_vaccn" value="2" <?php if($getdetls['covid_vaccn']==2) echo "checked";?>> <label>No</label>
								<?php
								if($getdetls['covid_vaccn']==1)
								$covidUpldstyle = '';
								else
								$covidUpldstyle = 'style="display:none;"';
								?>
								<div <?php echo $covidUpldstyle;?> id="cert_upload">
								<label>Upload Vaccine Certificate</label>
								<?php

								if($getdetls['vaccn_cert']){

									?>

									<a href="<?php echo SITEURL;?>uploads/covid/<?php echo $getdetls['vaccn_cert'];?>" target="_blank"><img src="<?php echo SITEURL;?>uploads/covid/<?php echo $getdetls['vaccn_cert'];?>" alt="" width="136"></a><br>

									<?php

								}

								?>
								<input type="file" name="vaccn_cert" id="vaccn_cert" class="form-control" style="margin-bottom:10px;" accept="image/*">
								<span style="margin-bottom:30px;">(.jpg, .gif or .png file)</span>
								</div>
								</div>
								
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2 col-lg-2">
								<input type="button" value="Back" id="back" onclick="location.href='<?php echo SITEURL;?>staff_details'">
							</div>
							<div class="col-sm-2 col-lg-2">
								<input type="submit" value="Next">
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