<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
include_once('header.php');
if(!isset($_SESSION['loginUserId'])){
	echo "<script>location.href='".SITEURL."employer_login'</script>";
	exit;
}
    $user = dbQuery($dbConn, "SELECT name,email,phone from users where id = '".$_SESSION['loginUserId']."'");
    $row = dbFetchArray($user);
    
    if(count($_POST) > 0 && isset($_POST['description'])){
        $jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";
		$qualification = isset($_POST['qualification'])?strip_tags(tep_db_input($_POST['qualification'])):"";
        $description = isset($_POST['description'])?tep_db_input($_POST['description']):"";
		$description = str_replace("<p>", "", $description);
		$description = str_replace("</p>", "", $description);

        $receive_app = isset($_POST['receive_app'])?tep_db_input($_POST['receive_app']):"";
        //$phone = isset($_POST['phone'])?tep_db_input($_POST['phone']):"";
        $applicn_deadln = isset($_POST['applicn_deadln'])?tep_db_input($_POST['applicn_deadln']):"";
        $applicn_deadln_date = isset($_POST['applicn_deadln_date'])?tep_db_input($_POST['applicn_deadln_date']):"";
		
		$trainingtype = isset($_POST['trainingtype'])?tep_db_input($_POST['trainingtype']):"";
		$expreq1 = isset($_POST['expreq1'])?strip_tags(tep_db_input($_POST['expreq1'])):"";
		$expreq2 = isset($_POST['expreq2'])?strip_tags(tep_db_input($_POST['expreq2'])):"";
		$expreq3 = isset($_POST['expreq3'])?strip_tags(tep_db_input($_POST['expreq3'])):"";
		$noexpernce = isset($_POST['noexpernce'])?tep_db_input($_POST['noexpernce']):"";
		
		$is_heavy_lifting = isset($_POST['is_heavy_lifting'])?tep_db_input($_POST['is_heavy_lifting']):"";
		$heavy_lifting = isset($_POST['heavy_lifting'])?tep_db_input($_POST['heavy_lifting']):"";
		$is_immunised = isset($_POST['is_immunised'])?tep_db_input($_POST['is_immunised']):"";
		$no_of_doses = isset($_POST['no_of_doses'])?tep_db_input($_POST['no_of_doses']):"";

		/*$phone = str_replace(" ", "", $phone);
		$firstdigit = substr($phone, 0, 1);
		if($firstdigit == 0)
		$phone = "61".substr($phone, 1, 10);
		else
		$phone = "61".$phone;*/
        
            $today = date("Y-m-d");
            dbQuery($dbConn, "UPDATE job_details set qualification = '".$qualification."', description = '".$description."', receive_app = '".$receive_app."', applicn_deadln = '".$applicn_deadln."', applicn_deadln_date = '".$applicn_deadln_date."', trainingtype = '".$trainingtype."', expreq1 = '".$expreq1."', expreq2 = '".$expreq2."', expreq3 = '".$expreq3."', noexpernce = '".$noexpernce."' where id = '".$jobid."'");
			
			dbQuery($dbConn, "DELETE FROM job_medical_info WHERE job_id = '".$jobid."'");
		dbQuery($dbConn, "INSERT INTO job_medical_info SET job_id = '".$jobid."', is_heavy_lifting = '".$is_heavy_lifting."', heavy_lifting = '".$heavy_lifting."', is_immunised = '".$is_immunised."', no_of_doses = '".$no_of_doses."'");
    
            //dbQuery($dbConn, "UPDATE users set phone = '".$phone."' where id = '".$_SESSION['loginUserId']."'");
            
            echo "<form action='".SITEURL."job_uploadedit' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'></form><script>document.getElementById('gotostep2').submit();</script>";
        
        exit;
    
    }
    
    if(isset($_POST['jobid'])){
        $myjobid = $_POST['jobid'];
    }
    
    $checkifjob = dbQuery($dbConn, "SELECT qualification,description,receive_app,applicn_deadln,applicn_deadln_date,trainingtype,expreq1,expreq2,expreq3,noexpernce from job_details where id = '".trim($myjobid)."'");
    $getdetls = dbFetchArray($checkifjob);
    
	$qualification = stripslashes($getdetls['qualification']);
    $description = $getdetls['description'];
    $receive_app = $getdetls['receive_app'];
    $applicn_deadln = $getdetls['applicn_deadln'];
    $applicn_deadln_date = $getdetls['applicn_deadln_date'];
	
	$jobmedical = dbQuery($dbConn, "SELECT is_heavy_lifting,heavy_lifting,is_immunised,no_of_doses from job_medical_info where job_id = '".trim($myjobid)."'");
	$getmedicalinfo = dbFetchArray($jobmedical);

?>
<script src="https://cdn.tiny.cloud/1/8i1ezchxs70z1hwfopuphjin8xj1nd501w0n6cl76d6gzz8h/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
      tinymce.init({
        selector: '#mytextarea',
        plugins: [
          'autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks','fullscreen','insertdatetime','media','table','help','wordcount'
        ],
        toolbar: 'undo redo | bold italic backcolor | ' +
          'alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist checklist outdent indent | removeformat'
      });
</script>
<div class="login_page">
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
				<form action='<?php echo SITEURL;?>editjob2' method='post' class="formback">
                        <input type='hidden' name='jobid' value='<?php echo trim($_POST['jobid']);?>'>
                        </form>
					<h4>Additional Details</h4>
					<form action="" method="post" id="jobpost" class="eplyfrm">
                    <input type="hidden" value="<?php echo trim($_POST['jobid']);?>" name="jobid">
						
						<div class="form-group">
							<label>Describe Your Cover Required Briefly</label>
							<textarea placeholder="Include what the role is, what you are looking for from the candidate and some brief information." name="description" id="mytextarea" class="form-control required"><?php echo stripslashes($description);?></textarea>
						</div>
						
						<!--<div class="form-group">
							<label>How Would You Like to Receive Applications? (Email/SMS or both)</label><br>							
							<input type="radio" name="receive_app" value="1" required <?php if($receive_app == 1) echo "checked";?>> <span>Email</span>
							<input type="radio" name="receive_app" value="2" <?php if($receive_app == 2) echo "checked";?>> <span>SMS</span>
							<input type="radio" name="receive_app" value="3" <?php if($receive_app == 3) echo "checked";?>> <span>Both</span>
							<br><br>	
							<div class="row">
								<div class="col-sm-6">
									<label>The registered email is:</label><br />
									<input type="text" placeholder="Email" class="form-control required" readonly value="<?php echo $row['email'];?>"> 
								</div>
								<div class="col-sm-6">
									<label>for notifications and Mobile is:</label><br />
									<input type="text" name="phone" placeholder="Phone number with country code" class="form-control required" value="<?php echo substr($row['phone'],2,10);?>">
								</div>
							</div>                                    
						</div>-->

						<div class="form-group">
							<label>Is There an Application Deadline?</label><br>
								<input type="radio" name="applicn_deadln" value="1" required <?php if($applicn_deadln == 1) echo "checked";?>> <span>Yes</span><br>
								<input type="radio" name="applicn_deadln" value="2" <?php if($applicn_deadln == 2) echo "checked";?> > <span>No</span><br>
						</div>
						<?php
						if($applicn_deadln_date != "" && $applicn_deadln_date != "0000-00-00")
						$dedlinestyle = '';
						else
						$dedlinestyle = 'style="display:none;"';
						?>
						<div class="form-group" id="deadlndt" <?php echo $dedlinestyle;?>>
							<label>Deadline Date</label><br>
								<input type="text" name="applicn_deadln_date" id="applicn_deadln_date" class="form-control" value="<?php if($applicn_deadln_date != "0000-00-00") echo $applicn_deadln_date;?>">
						</div>
						
						<div class="form-group">
						<label>Training</label><br>
						<input type="radio" value="1" name="trainingtype" required <?php if($getdetls['trainingtype']==1) echo "checked";?>> <span>Full training provided</span>
						<br>
						<input type="radio" name="trainingtype" value="2" <?php if($getdetls['trainingtype']==2) echo "checked";?>> <span>Some training provided</span>
						<br>
						<input type="radio" name="trainingtype" value="3" <?php if($getdetls['trainingtype']==3) echo "checked";?>> <span>No training provided, candidate must be highly experienced</span>
						</div>
						
						<div class="form-group">
						<label>3 Things candidates must have experience in:</label><br>
						<label>(E.g. Cash Registers, Particular Software, Custom Service)</label>
						<input type="text" name="expreq1" class="form-control mustexp " value="<?php echo stripslashes($getdetls['expreq1']);?>">
						<input type="text" name="expreq2" class="form-control mustexp " value="<?php echo stripslashes($getdetls['expreq2']);?>">
						<input type="text" name="expreq3" class="form-control mustexp " value="<?php echo stripslashes($getdetls['expreq3']);?>">
						<!--<label>OR</label>
						<label><input type="checkbox" name="noexpernce" id="noexpernce" value="1" <?php //if($getdetls['noexpernce']==1) echo "checked";?>> Absolutely no experience in anything needed</label>-->
						</div>
						
						<div class="form-group">
						<label>Is there heavy lifting or anything that candidates should know for health?</label><br>
							<input type="radio" name="is_heavy_lifting" value="1" required <?php if($getmedicalinfo['is_heavy_lifting'] == 1) echo "checked";?>> <span>Yes</span><br>
							<input type="radio" name="is_heavy_lifting" value="2" <?php if(!$getmedicalinfo['is_heavy_lifting'] || $getmedicalinfo['is_heavy_lifting'] == 2) echo "checked";?> > <span>No</span><br>
							
							<?php
							if($getmedicalinfo['is_heavy_lifting'] == "1")
							$lifting_style = '';
							else
							$lifting_style = 'style="display:none;"';
							?>
							<input type="text" name="heavy_lifting" id="heavy_lifting" class="form-control" value="<?php echo stripslashes($getmedicalinfo['heavy_lifting']);?>" placeholder="Please specify" <?php echo $lifting_style;?>>
						</div>
						
						<div class="form-group">
						<label>Does the Candidate need to be covid immunised?</label><br>
							<input type="radio" name="is_immunised" value="1" required <?php if($getmedicalinfo['is_immunised'] == 1) echo "checked";?>> <span>Yes</span><br>
							<input type="radio" name="is_immunised" value="2" <?php if(!$getmedicalinfo['is_immunised'] || $getmedicalinfo['is_immunised'] == 2) echo "checked";?> > <span>No</span><br>
							
							<?php
							if($getmedicalinfo['is_immunised'] == "1")
							$doses_style = '';
							else
							$doses_style = 'style="display:none;"';
							?>
							<input type="text" name="no_of_doses" id="no_of_doses" class="form-control digits" value="<?php echo stripslashes($getmedicalinfo['no_of_doses']);?>" placeholder="Specify number of doses required" <?php echo $doses_style;?>>
						</div>
						
						<div class="form-group">
							<label>Qualification or Certificates Required</label><br>

							<textarea placeholder="E.g. White Card, Police Clearance, Diploma etc." name="qualification" class="form-control"><?php echo $qualification;?></textarea>

						</div>

						<div class="row">
							<div class="col-sm-2">
								<input type="button" value="Back" id="myback" class="onlybutfulwdt">
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