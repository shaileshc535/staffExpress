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
    $errstring1 = array();
    $errstring2 = array();
    $errstring3 = array();
    $errstring4 = array();
    $errstring5 = array();
    $errstring6 = array();
	$errstring1_video = '';
	$errstring2_video = '';

	if($_FILES["cover_letter"]["size"][0]>0)
        {
            $check = dbQuery($dbConn, "SELECT * from staff_documents where staff_id = '".$_SESSION['loginUserId']."'");
            if(dbNumRows($check) > 0){
                dbQuery($dbConn, "DELETE FROM staff_documents where staff_id = '".$_SESSION['loginUserId']."'");
            }
            for($i=0; $i<count($_FILES["cover_letter"]['name']); $i++){

            $updimgnm='';

                $mytime=time();

                $srcfile = $_FILES['cover_letter']['type'][$i];

                $mflname = strtolower($_FILES['cover_letter']['name'][$i]);

                $mflname = str_replace(" ", "_", $mflname);

                if($srcfile == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $srcfile == "application/pdf")

                {

                    //$errstring1="";

                }

                else

                {

                    $errstring1[] = "error";

                }

                

                        $blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");

                

                        if(strpos($mflname,".php")===false && strpos($mflname,".phtml")===false && strpos($mflname,".php3")===false && strpos($mflname,".php4")===false && strpos($mflname,".php5")===false && strpos($mflname,".html")===false && strpos($mflname,".htm")===false && strpos($mflname,".exe")===false) 

                        {

                                    

                            //$errstring2="";

                        }

                        else

                        {

                            

                            $errstring2[] = "error";

                        }

                        

                        $dblchk = array();

                        

                        $dblchk=explode(".",$mflname);

                        

                        /*if(count($dblchk)==2) 

                        {

                                    

                            $errstring3="";

                        }

                        else

                        {

                            

                            $errstring3="Please Upload only image file.(.jpg,.png,.gif)";

                        }*/

                

                if(!in_array('error', $errstring1) && !in_array('error', $errstring2))
                {

                    if(move_uploaded_file($_FILES["cover_letter"]["tmp_name"][$i], "uploads/resumes/" . $mytime.'_'.$mflname))
                    {

                        $myfile = $mytime.'_'.$mflname;

                        dbQuery($dbConn, "INSERT INTO staff_documents set cover_letter = '".$myfile."', staff_id = '".$_SESSION['loginUserId']."'");

                    }

                }

            }
        }

        if($_FILES["qualifications"]["size"][0]>0)
        {
            $check = dbQuery($dbConn, "SELECT * from staff_qualifications where staff_id = '".$_SESSION['loginUserId']."'");
            if(dbNumRows($check) > 0){
                dbQuery($dbConn, "DELETE FROM staff_qualifications where staff_id = '".$_SESSION['loginUserId']."'");
            }
            for($i=0; $i<count($_FILES["qualifications"]['name']); $i++){

            $updimgnm='';

                $mytime=time();

                $srcfile2 = $_FILES['qualifications']['type'][$i];

                $mflname2 = strtolower($_FILES['qualifications']['name'][$i]);

                $mflname2 = str_replace(" ", "_", $mflname2);

                if($srcfile2 == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $srcfile2 == "application/pdf")

                {

                    //$errstring1="";

                }

                else

                {

                    $errstring3[] = "error";

                }

                

                        $blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");

                

                        if(strpos($mflname2,".php")===false && strpos($mflname2,".phtml")===false && strpos($mflname2,".php3")===false && strpos($mflname2,".php4")===false && strpos($mflname2,".php5")===false && strpos($mflname2,".html")===false && strpos($mflname2,".htm")===false && strpos($mflname2,".exe")===false) 

                        {

                                    

                            //$errstring2="";

                        }

                        else

                        {

                            

                            $errstring4[] = "error";

                        }

                        

                        $dblchk = array();

                        

                        $dblchk=explode(".",$mflname2);

                        

                        /*if(count($dblchk)==2) 

                        {

                                    

                            $errstring3="";

                        }

                        else

                        {

                            

                            $errstring3="Please Upload only image file.(.jpg,.png,.gif)";

                        }*/

                

                if(!in_array('error', $errstring3) && !in_array('error', $errstring4))
                {

                    if(move_uploaded_file($_FILES["qualifications"]["tmp_name"][$i], "uploads/resumes/" . $mytime.'_'.$mflname2))
                    {

                        $myfile2 = $mytime.'_'.$mflname2;

                        dbQuery($dbConn, "INSERT INTO staff_qualifications set qualifications = '".$myfile2."', staff_id = '".$_SESSION['loginUserId']."'");

                    }

                }

            }
        }

        if($_FILES["certificate"]["size"][0]>0)
        {
            $check = dbQuery($dbConn, "SELECT * from staff_certificate where staff_id = '".$_SESSION['loginUserId']."'");
            if(dbNumRows($check) > 0){
                dbQuery($dbConn, "DELETE FROM staff_certificate where staff_id = '".$_SESSION['loginUserId']."'");
            }
            
            for($i=0; $i<count($_FILES["certificate"]['name']); $i++){

            $updimgnm='';

                $mytime=time();

                $srcfile3 = $_FILES['certificate']['type'][$i];

                $mflname3 = strtolower($_FILES['certificate']['name'][$i]);

                $mflname3 = str_replace(" ", "_", $mflname3);

                if($srcfile3 == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $srcfile3 == "application/pdf")

                {

                    //$errstring1="";

                }

                else

                {

                    $errstring5[] = "error";

                }

                

                        $blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");

                

                        if(strpos($mflname3,".php")===false && strpos($mflname3,".phtml")===false && strpos($mflname3,".php3")===false && strpos($mflname3,".php4")===false && strpos($mflname3,".php5")===false && strpos($mflname3,".html")===false && strpos($mflname3,".htm")===false && strpos($mflname3,".exe")===false) 

                        {

                                    

                            //$errstring2="";

                        }

                        else

                        {

                            

                            $errstring6[] = "error";

                        }

                        

                        $dblchk = array();

                        

                        $dblchk=explode(".",$mflname3);

                        

                        /*if(count($dblchk)==2) 

                        {

                                    

                            $errstring3="";

                        }

                        else

                        {

                            

                            $errstring3="Please Upload only image file.(.jpg,.png,.gif)";

                        }*/

                

                if(!in_array('error', $errstring5) && !in_array('error', $errstring6))
                {

                    if(move_uploaded_file($_FILES["certificate"]["tmp_name"][$i], "uploads/resumes/" . $mytime.'_'.$mflname3))
                    {

                        $myfile3 = $mytime.'_'.$mflname3;

                        dbQuery($dbConn, "INSERT INTO staff_certificate set `certificate` = '".$myfile3."' , staff_id = '".$_SESSION['loginUserId']."'");

                    }

                }

            }
        }
		
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

				$errstring1_video="";

			}

			else

			{

				$errstring1_video="Please Upload only video file.(.jpg,.png,.gif)";

			

			}

			

					$blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");

			

					if(strpos($mflname,".php")===false && strpos($mflname,".phtml")===false && strpos($mflname,".php3")===false && strpos($mflname,".php4")===false && strpos($mflname,".php5")===false && strpos($mflname,".html")===false && strpos($mflname,".htm")===false && strpos($mflname,".exe")===false) 

					{

								

						$errstring2_video="";

					}

					else

					{

						

						$errstring2_video="Please Upload only image file.(.jpg,.png,.gif)";

					}

					

					$dblchk = array();

					

					$dblchk=explode(".",$mflname);

					


			if($errstring1_video=="" && $errstring2_video=="")

			{

				if(move_uploaded_file($_FILES["video"]["tmp_name"],"uploads/video/" . $mytime.'_'.$mflname))

				{

					

				$sqlequip = dbQuery($dbConn, "SELECT `video` from staff_details where staff_id = '".$_SESSION['loginUserId']."'");

				$row = dbFetchArray($sqlequip);

				@unlink("uploads/video/".$row['video']);

		

				$updimgnm = $mytime.'_'.$mflname;



					dbQuery($dbConn, "UPDATE staff_details set `video` = '".$updimgnm."' where staff_id = '".$_SESSION['loginUserId']."'");

			

				}
				
				

			}

		}
		else{
			echo "<script>location.href='".SITEURL."staff_payment4?error=1'</script>";
			exit;
		}
	}
		
	dbQuery($dbConn, "UPDATE staff_details set profile_step = 4	where staff_id = '".$_SESSION['loginUserId']."'");
    if((!in_array('error', $errstring1) && !in_array('error', $errstring2) && !in_array('error', $errstring3) && !in_array('error', $errstring4) && !in_array('error', $errstring5) && !in_array('error', $errstring6)) && ($errstring1_video == "" && $errstring2_video == "")){
        echo "<script>location.href='".SITEURL."searchcover'</script>";
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
				<li class="actv">Optional Upload</li>
			</ul>
		</div>
			<div class="row">
			<?php 
					include_once "staff_left.php";
					?>
				<div class="col-lg-9">
					
					<div class="works_heading" style="display:inline-block;">
					
					<h4 style="margin-bottom:20px;">Optional Uploads</h4>
					<div class="login login_page martopadjst">
					
                    <?php
					if(in_array('error', $errstring1) || in_array('error', $errstring2) || in_array('error', $errstring3) || in_array('error', $errstring4) || in_array('error', $errstring5) || in_array('error', $errstring6)){
					?>
						<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>All documents are not uploaded. Must be PDF or Docx file.</div>
					<?php
					}
					if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){
						?>
							<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Size must be up to 5MB.</div>

						<?php

					}
					if($errstring1_video != "" || $errstring2_video != ""){
						?>
							<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Please upload mp4 or webm video file.</div>

						<?php

					}
					?>

					<form action="" method="post" id="regform" enctype="multipart/form-data" class="newpymt4pg">
                        <input type="hidden" value="1" name="upload_document">
                    <div class="row">
                    <div class="col-sm-12">

                    <label class="paymnt4labltxt">Optional Cover Letter</label>
                    <?php
                    $check = dbQuery($dbConn, "SELECT cover_letter from staff_documents where staff_id = '".$_SESSION['loginUserId']."' order by id desc limit 0,1");
                    if(dbNumRows($check) > 0){
                        while($getdetls = dbFetchArray($check)){

                            ?>
                            <span class="onlyfltrt stfpymnt4vwbtn"><a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['cover_letter'];?>" target="_blank">View</a></span>

                            <?php

                        }
                    }
                    ?>

                    <input type="file" multiple name="cover_letter[]" id="cover_letter" class="form-control onlypymt4selcbx" style="margin-bottom:10px;">

                    <span style="margin-bottom:30px;">(PDF or Docx file)</span>

                    </div>

                    <div class="col-sm-12" style="margin-top:30px;">
                    <label class="paymnt4labltxt">Optional Qualifications Document</label>

                    <?php
                    $check = dbQuery($dbConn, "SELECT qualifications from staff_qualifications where staff_id = '".$_SESSION['loginUserId']."' order by id desc limit 0,1");
                    if(dbNumRows($check) > 0){
                        while($getdetls = dbFetchArray($check)){

                            ?>
                            <span class="onlyfltrt stfpymnt4vwbtn"><a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['qualifications'];?>" target="_blank">View</a></span>

                            <?php

                        }
                    }
                    ?>

                    <input type="file" multiple name="qualifications[]" id="qualifications" class="form-control onlypymt4selcbx" style="margin-bottom:10px;">

                    <span style="margin-bottom:30px;">(PDF or Docx file)</span>

                    </div>

                    <div class="col-sm-12" style="margin-top:30px;">
                    <label class="paymnt4labltxt">Optional Certificate</label>

                    <?php

                    $check = dbQuery($dbConn, "SELECT `certificate` from staff_certificate where staff_id = '".$_SESSION['loginUserId']."' order by id desc limit 0,1");
                    if(dbNumRows($check) > 0){
                        while($getdetls = dbFetchArray($check)){

                            ?>
                            <span class="onlyfltrt stfpymnt4vwbtn"><a href="<?php echo SITEURL;?>uploads/resumes/<?php echo $getdetls['certificate'];?>" target="_blank">View</a></span>

                            <?php

                        }
                    }

                    ?>

                    <input type="file" multiple name="certificate[]" id="certificate" class="form-control onlypymt4selcbx" style="margin-bottom:10px;">

                    <span style="margin-bottom:30px;">(PDF or Docx file)</span>

                    </div>
					<div class="col-sm-12" style="margin-top:30px;">
						<div class="form-group">
						
						<input style="min-height:0;margin-right:4px;" type="checkbox" name="check2" value="1" required> <span style="font-size:14px;">I consent for my uploaded information to be shared with prospective employers</span>
						<br>
							<input style="min-height:0;margin-right:4px;" type="checkbox" name="check" value="1" required> <span style="font-size:14px;">By ticking this box, I agree to <a href="<?php echo SITEURL;?>terms" target="_blank">Terms of Use</a></span>

						</div>
					</div>
                    </div>
					
					
					
					
						<div class="row" style="margin-top:30px;">
							<div class="col-sm-2 col-lg-2">
									<input type="button" value="Back" id="back" onclick="location.href='<?php echo SITEURL;?>staff_payment2'" class="onlybutfulwdt">
							</div>
							<div class="col-sm-2 col-lg-2">
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
	
	
	<?php include_once('footer.php');?>