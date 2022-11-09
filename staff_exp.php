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
	
	$experienced = isset($_POST['experienced'])?$_POST['experienced']:"";
	$company = isset($_POST['company'])?$_POST['company']:array();
	$designation = isset($_POST['designation'])?$_POST['designation']:array();
	
	//$catidforexp = isset($_POST['catidforexp'])?$_POST['catidforexp']:array();
    $experience = isset($_POST['experience'])?$_POST['experience']:array();
    $experience_month = isset($_POST['experience_month'])?$_POST['experience_month']:array();
	
	dbQuery($dbConn, "UPDATE staff_details set experienced = '".$experienced."', profile_step = 2 where staff_id = '".$_SESSION['loginUserId']."'");
	
	if($experienced == '1'){
		if(count($experience) > 0 && count($company) > 0 && count($designation) > 0 && count($experience_month) > 0){
		$i=0;
			dbQuery($dbConn, "DELETE from staff_experience where staff_id = '".$_SESSION['loginUserId']."'");
			foreach($company as $val){
				if($val != "" && $designation[$i] != "" && $experience[$i] && $experience_month[$i] != ""){
				dbQuery($dbConn, "INSERT INTO staff_experience SET staff_id = '".$_SESSION['loginUserId']."', company = '".strip_tags(tep_db_input($val))."', designation = '".strip_tags(tep_db_input($designation[$i]))."', experience = '".strip_tags(tep_db_input($experience[$i]))."', experience_month = '".strip_tags(tep_db_input($experience_month[$i]))."'");
				}

				$i++;
			}
			echo "<script>location.href='".SITEURL."staff_payment2'</script>";
			exit;
		}
		else{
			echo "<script>location.href='".SITEURL."staff_experience?error=1'</script>";
			exit;
		}
	}
	else{
		dbQuery($dbConn, "DELETE from staff_experience where staff_id = '".$_SESSION['loginUserId']."'");
		echo "<script>location.href='".SITEURL."staff_payment2'</script>";
		exit;
	}
	
}

/*$catids = array();
$maincatids = array();
$checkdetls = dbQuery($dbConn, "SELECT * from staff_details where staff_id = '".$_SESSION['loginUserId']."'");
$getdetls = dbFetchArray($checkdetls);

$mycats = dbQuery($dbConn, "SELECT catid from staff_job_cat where staff_id = '".$_SESSION['loginUserId']."'");

while($mycatsrow = dbFetchArray($mycats)){

	$catids[] = $mycatsrow['catid'];

}

$mymaincats = dbQuery($dbConn, "SELECT catid from staff_job_parent_cat where staff_id = '".$_SESSION['loginUserId']."'");

while($mycatsrow = dbFetchArray($mymaincats)){

	$maincatids[] = $mycatsrow['catid'];

}

if(count($catids) > 0)

$catids = $catids;

if(isset($_POST['catid']))

$catids = $_POST['catid'];

if(count($maincatids) > 0)

$maincatids = $maincatids;

if(isset($_POST['maincatid']))

$maincatids = $_POST['maincatid'];*/
?>	

	<div class="works works_success i_am_staff_sty">
		<div class="container">
        <div class="stepbystp">

        <ul>

            <li>My Profile</li>

            <li><img src="images/dividr.png" alt="" /></li>

            <li class="actv">Experience</li>
            <li><img src="images/dividr.png" alt="" /></li>
            <li>Health Info</li>
			<li><img src="images/dividr.png" alt="" /></li>
            <li>Optional Upload</li>
			
        </ul>

        </div>
		
			<div class="row">
			<?php 
					include_once "staff_left.php";
					
					$staffdetls = dbQuery($dbConn, "SELECT experienced from staff_details where staff_id = '".$_SESSION['loginUserId']."'");
					$staffrow = dbFetchArray($staffdetls);
					$experienced = $staffrow['experienced'];
					?>
				<div class="col-lg-9">
					
					<div class="works_heading" style="display:inline-block;width:100%;">
					
					<h4 style="margin-bottom:20px;">Experience</h4>
					<div class="login login_page martopadjst2">
					<?php
					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){
							?>
								<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Please enter experiences.</div>

							<?php

						}
						?>						
					<form action="" method="post" id="staffexpinfo" class="eplyfrm" enctype="multipart/form-data">
						
						<div class="form-group" id="forstaff">
						<div class="row">
							
								<div class="col-sm-12">
									<label>Have You Worked Before?</label>
									<div class="radio_box" style="margin-bottom:15px;">

									<div class="radio_box_single">

									<input type="radio" name="experienced" value="1" required <?php if($experienced=='1') echo "checked";?>> <span>Yes</span>

									</div>

									<div class="radio_box_single">

									<input type="radio" name="experienced" value="2" <?php if($experienced=='2') echo "checked";?>> <span>No</span>

									</div>

									</div>
									
									<?php
									$staffexp = dbQuery($dbConn, "SELECT company,designation,experience,experience_month from staff_experience where staff_id = '".$_SESSION['loginUserId']."' order by id");
									if(dbNumRows($staffexp) == 0){
									?>
									<div style="display:none;" id="expbox">
									<div class="mobjobad">
									<div class="row" style="margin-bottom:10px;">
										<div class="col-sm-3">
											<label>Latest Company</label>
											<input type="text" name="company[]" class="form-control required" value="">
										</div>
										<div class="col-sm-3">
											<label>Role/Title</label>
											<input type="text" name="designation[]" class="form-control required" value="">
										</div>
										<div class="col-sm-4 col-8">
											<div class="row">
											<div class="col-sm-6 col-6">
												<label style="text-transform:none;">Year(s)</label>
												<input type="text" class="form-control digits required myexpyr" name="experience[]" value=""> 
											</div>
											<div class="col-sm-6 col-6">
												<label style="text-transform:none;">Month(s)</label>
												<input type="text" class="form-control digits required myexpmth" name="experience_month[]" value="">  
											</div>
											</div>
										</div>
										<div class="col-sm-1 col-4">
											<label>&nbsp;</label>
											<a href="javascript:void(0);" class="addmyexps"><i class="fa fa-plus"></i></a>
										</div>
									
									</div>
									</div>
									<div id="myexps"></div>
									</div>
									<?php
									}
									else{
										?>
										<div id="expbox">
										<?php
										$i=0;
										while($staffexprow = dbFetchArray($staffexp)){
											if($i==0)
												$company = "Latest Company";
											else
												$company = "Previous Company";
										?>
											<div class="mobjobad">
												<div class="row" style="margin-bottom:10px;">
													<div class="col-sm-3">
														<label><?php echo $company;?></label>
														<input type="text" name="company[]" class="form-control required" value="<?php echo stripslashes($staffexprow['company']);?>">
													</div>
													<div class="col-sm-3">
														<label>Role/Title</label>
														<input type="text" name="designation[]" class="form-control required" value="<?php echo stripslashes($staffexprow['designation']);?>">
													</div>
													<div class="col-sm-4 col-8">
														<div class="row">
														<div class="col-sm-6 col-6">
															<label style="text-transform:none;">Year(s)</label>
															<input type="text" class="form-control digits required myexpyr" name="experience[]" value="<?php echo stripslashes($staffexprow['experience']);?>"> 
														</div>
														<div class="col-sm-6 col-6">
															<label style="text-transform:none;">Month(s)</label>
															<input type="text" class="form-control digits required myexpmth" name="experience_month[]" value="<?php echo stripslashes($staffexprow['experience_month']);?>">
														</div>
														</div>
													</div>
													<div class="col-sm-2 col-4">
														<label>&nbsp;</label>
														<a href="javascript:void(0);" class="addmyexps"><i class="fa fa-plus"></i></a>
														<?php
														if($i > 0){
														?>
														&nbsp;<a href="javascript:void(0);" class="removemyexps"><i class="fa fa-minus"></i></a>
														<?php
														}
														?>
													</div>
													<!--<div class="col-sm-1">
														<label>&nbsp;</label>
														
													</div>-->
												</div>
											</div>
											
										<?php
										$i++;
										}
										?>
										<div id="myexps"></div>
										</div>
										<?php
									}
									?>
								</div>
								</div>
								</div>
								
						
						<div class="row" style="margin-top:15px;">
							<div class="col-sm-2 col-lg-2">
								<input type="button" value="Back" id="back" onclick="location.href='<?php echo SITEURL;?>staff_details'" class="onlybutfulwdt">
							</div>
							<div class="col-sm-2 col-lg-2">
								<input type="submit" value="Next" class="onlybutfulwdt">
							</div>
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