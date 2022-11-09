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

	$size = 5*1024*1024;
	
	if(isset($_FILES["video"]) && $_FILES["video"]["size"]>0){

	if($_FILES["video"]["size"] <= $size){

		
		$mytime=time();
		
		$updimgnm='';


		$srcfile = $_FILES['video']['type'];

		//$imageinfo = getimagesize($_FILES['video']['tmp_name']); //check image size


		$mflname = strtolower($_FILES['video']['name']);

		$mflname = str_replace(" ", "_", $mflname);

		$path = $mflname;
        $ext = pathinfo($path, PATHINFO_EXTENSION);

		if($ext == "mp4" || $ext == "webm")

		{

			$errstring1="";

		}

		else

		{

			$errstring1="Please Upload only video file.(.jpg,.png,.gif)";

		

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

			if(move_uploaded_file($_FILES["video"]["tmp_name"],"uploads/video/" . $mytime.'_'.$mflname))

			{

				

			$sqlequip = dbQuery($dbConn, "SELECT `video` from staff_details where staff_id = '".$_SESSION['loginUserId']."'");

			$row = dbFetchArray($sqlequip);

			@unlink("uploads/video/".$row['video']);

	

			$updimgnm = $mytime.'_'.$mflname;



				dbQuery($dbConn, "UPDATE staff_details set `video` = '".$updimgnm."' where staff_id = '".$_SESSION['loginUserId']."'");

		

			}
			
			echo "<script>location.href='".SITEURL."staff_payment4'</script>";
			exit;

		}

	}
	else{
		echo "<script>location.href='".SITEURL."staff_video?error=1'</script>";
		exit;
	}
	}
	else{
		echo "<script>location.href='".SITEURL."staff_payment4'</script>";
		exit;
	}
	
}

$staffdetls = dbQuery($dbConn, "SELECT video from staff_details where staff_id = '".$_SESSION['loginUserId']."'");

$staffrow = dbFetchArray($staffdetls);
?>	

	<div class="works works_success i_am_staff_sty staff_payament_4_sty">
		<div class="container">
		<div class="stepbystp">
			<ul>
				<li>My Profile</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Experience</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Health Info</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li class="actv">Video</li>
				<li><img src="images/dividr.png" alt="" /></li>
				<li>Optional Upload</li>
				
			</ul>
		</div>
			<div class="row">
			<?php 
					include_once "staff_left.php";
					?>
				<div class="col-lg-9">
					
					<div class="works_heading">
					
					<h4 style="margin-bottom:20px;">Video</h4>
					<div class="login login_page martopadjst">
					
                    <?php
					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){
							?>
								<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Size must be up to 5MB.</div>

							<?php

						}
					if($errstring1 != "" || $errstring2 != ""){
							?>
								<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Please upload mp4 or webm video file.</div>

							<?php

						}
					if(isset($_REQUEST['success']) && $_REQUEST['success'] == 1){
							?>
								<div class='alert-success' style='padding:15px;margin-bottom:15px;'>Profile updated successfully.</div>

							<?php

						}
					?>

					<form action="" method="post" id="regform" enctype="multipart/form-data" class="newpymt4pg">
                        <input type="hidden" value="1" name="upload_document">
						<div class="row">
							<div class="col-sm-8">
								<div class="uploadvidlt">
									<div class="upinptbrws">
										<label class="paymnt4labltxt">Upload Video</label>
										
										<input type="file" name="video" id="video" class="form-control onlypymt4selcbx" accept="video/*" style="margin-bottom:10px;">
										<span style="margin-bottom:30px;">(Upload mp4/webm file with max 5MB)</span>
									</div>
									<div class="row" style="margin-top:30px;">
										<div class="col-sm-3 col-lg-3">
												<input type="button" value="Back" id="back" onclick="location.href='<?php echo SITEURL;?>staff_payment2'" class="onlybutfulwdt">
										</div>
										<div class="col-sm-3 col-lg-3">
											<input type="submit" value="Next" class="onlybutfulwdt">
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="upldvidrt">
									<span class="onlyfltrt stfpymnt4vwbtn"><a href="https://webcamera.io/" target="_blank">Record Video Externally</a></span>
									<?php
										if($staffrow['video']){
										?>
										<span class="onlyfltrt stfpymnt4vwbtn"><a href="<?php echo SITEURL;?>uploads/video/<?php echo $staffrow['video'];?>" target="_blank">View Uploaded Video</a></span>
										<?php
										}
										?>
								</div>
							</div>
						</div>
						
						<div style="clear:both;"></div>
                    
						
					</form>
					
				</div>
					</div>
					
					
				</div>
				
			</div>
		</div>
	</div>
	
	
	<?php include_once('footer.php');?>