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
	$superannuation = isset($_POST['superannuation'])?tep_db_input($_POST['superannuation']):"";
	$supname = isset($_POST['supname'])?tep_db_input($_POST['supname']):"";
	$supnumber = isset($_POST['supnumber'])?tep_db_input($_POST['supnumber']):"";
	$supabn = isset($_POST['supabn'])?tep_db_input($_POST['supabn']):"";

	dbQuery($dbConn, "UPDATE staff_details set superannuation = '".$superannuation."', supname = '".$supname."', supnumber = '".$supnumber."', supabn = '".$supabn."' where staff_id = '".$_SESSION['loginUserId']."'");

	echo "<script>location.href='".SITEURL."staff_payment4'</script>";
    exit;
	
}

$checkdetls = dbQuery($dbConn, "SELECT superannuation,supname,supnumber,supabn from staff_details where staff_id = '".$_SESSION['loginUserId']."'");
$getdetls = dbFetchArray($checkdetls);
?>	

	<div class="works works_success i_am_staff_sty">
		<div class="container">
		<div class="stepbystp">
			<ul>
				<li>My Profile</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Health Info</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Payments</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li class="actv">Superannuation</li>
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
						<div class='alert-success' style='padding:15px;margin-bottom:15px;'>Superannuation info updated successfully.</div>
					<?php
					}
					?>
					<h4 style="margin-bottom:20px;">Superannuation</h4>
					<div class="login login_page martopadjst">
					
					
					<form action="" method="post" id="regform">
					
						<div class="form-group" id="forstaff">
                        <label>Would You Like Superannuation to be Paid to Your Choice of Fund or Employer Nominated Fund.</label><br>
                        <input type="radio" name="superannuation" value="1" required <?php if($getdetls['superannuation']=='1') echo "checked";?>> <span>My Superannuation Fund</span> &nbsp; &nbsp; &nbsp; 
						<input type="radio" name="superannuation" value="2" <?php if($getdetls['superannuation']=='2') echo "checked";?>> <span>Employer Choice of Fund</span><br><br>
						</div>

						<?php
						if($getdetls['superannuation']=='1' && $getdetls['supname'] && $getdetls['supnumber'] && $getdetls['supabn'])
						$divstyle = '';
						else
						$divstyle = 'style="display:none;"';
						?>
                        <div <?php echo $divstyle;?> id="mysuper">
                        <div class="form-group">
                            <input type="text" placeholder="Superannuation Name" id="supname" name="supname" class="form-control checkno " value="<?php echo $getdetls['supname'];?>"> 
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Superannuation Number" id="supnumber" name="supnumber" class="form-control checkno " value="<?php echo $getdetls['supnumber'];?>"> 
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Superannuation ABN" id="supabn" name="supabn" class="form-control checkno" value="<?php echo $getdetls['supabn'];?>"> 
                        </div>
                        </div>
						<div class="row">
							<div class="col-sm-2 col-lg-2">
									<input type="button" value="Back" id="back" onclick="location.href='<?php echo SITEURL;?>staff_payment2'">
							</div>
							<div class="col-sm-2 col-lg-2">
								<input type="submit" value="Next">
							</div>
							<div class="col-sm-2 col-lg-2">
								<input type="button" value="Skip" onclick="location.href='<?php echo SITEURL;?>staff_payment4'">
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