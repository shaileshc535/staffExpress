<?php

include('config/config.php'); 

include_once "config/common.php";

$dbConn = establishcon();

include_once('header.php');



if(!isset($_SESSION['loginUserId'])){

	echo "<script>location.href='".SITEURL."employer_login'</script>";

	exit;

}


if(isset($_POST['jobid'])){

$user = dbQuery($dbConn, "SELECT name,email from users where id = '".$_SESSION['loginUserId']."'");

$row = dbFetchArray($user);



if(count($_POST) > 0 && isset($_POST["contract"])){

$errstring1 = array();
$errstring2 = array();

    $jobid = isset($_POST['jobid'])?tep_db_input($_POST['jobid']):"";


        if($_FILES["jobupload"]["size"][0]>0)
        {
			
			$check = dbQuery($dbConn, "SELECT * from job_documents where job_id = '".$jobid."'");
            if(dbNumRows($check) > 0){
                dbQuery($dbConn, "DELETE FROM job_documents where job_id = '".$jobid."'");
            }
			
			for($i=0; $i<count($_FILES["jobupload"]['name']); $i++){

            $updimgnm='';


                $mytime=time();


                $srcfile = $_FILES['jobupload']['type'][$i];



                $mflname = strtolower($_FILES['jobupload']['name'][$i]);

                $mflname = str_replace(" ", "_", $mflname);


                if($srcfile == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $srcfile == "application/pdf")
                {


                }
                else
                {

                    $errstring1[] = "error";


                }

                

                        $blacklist = array(".php", ".phtml", ".php3", ".php4", ".php5", ".html");

                

                        if(strpos($mflname,".php")===false && strpos($mflname,".phtml")===false && strpos($mflname,".php3")===false && strpos($mflname,".php4")===false && strpos($mflname,".php5")===false && strpos($mflname,".html")===false && strpos($mflname,".htm")===false && strpos($mflname,".exe")===false) 
                        {

                                    


                        }

                        else
                        {

                            

                            $errstring2[] = "error";

                        }

                        

                if(!in_array('error', $errstring1) && !in_array('error', $errstring2))

                {

                    

                    if(move_uploaded_file($_FILES["jobupload"]["tmp_name"][$i], "contract/" . $mytime.'_'.$mflname))

                    {

                        $myfile = $mytime.'_'.$mflname;

                        dbQuery($dbConn, "INSERT INTO job_documents set jobupload = '".$myfile."', job_id = '".$jobid."'");

                    }

                }

               
				
			}
			

        }
		
		if(!in_array('error', $errstring1) && !in_array('error', $errstring2)){
				echo "<script>location.href='".SITEURL."jobpostsuccess'</script>";
	            exit;
			}
			else{
				echo "<form action='".SITEURL."jobupload' method='post' id='gotostep2'><input type='hidden' name='jobid' value='".$jobid."'><input type='hidden' name='error' value='1'></form><script>document.getElementById('gotostep2').submit();</script>";
				exit;
			}

        //dbQuery($dbConn, "UPDATE job_details set postcomplete = 1 where id = '".$jobid."'");


}




?>



<section class="login_page">

	<div class="container">

		<div class="splnav">

			<ul class="onlyemployernav">

				<li <?php if($page == "success.php" || $page == "viewmyjob.php" || $page == "managehire.php") echo "class='active'";?>><a href="<?php echo SITEURL."myjobs";?>">My Jobs</a></li>

				<li class="active"><a href="<?php echo SITEURL."jobpost1";?>">Post a Job</a></li>

				<li <?php if($page == "employr_messages.php") echo "class='active'";?>><a href="<?php echo SITEURL."emp_messages";?>">Messages</a></li>

				<li <?php if($page == "employer_registration.php") echo "class='active'";?>><a href="<?php echo SITEURL."employer_details";?>">My Profile</a></li>

				<!--<li><a href="<?php echo SITEURL."logout";?>">Logout</a></li>-->

			</ul>

		</div>

		<div class="stepbystp">

			<ul>

				<li>Job Details</li>

				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li>Compensation</li>

				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li>Additional Details</li>

				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li>Post Job</li>
				
				<li><img src="<?php echo SITEURL;?>images/dividr.png" alt="" /></li>

				<li class="actv">Upload</li>

			</ul>

		</div>

	

		<div class="row">

			
			<div class="col-lg-8 m-auto">

				<div class="login onlyscrol jobpostnewbgdesn">

					<h4>Upload Optional Information</h4>

                    <?php

						if(isset($_POST['error']) && $_POST['error'] == 1){

							echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Please upload docx or PDF file.</div>";

						}

						?>


					<form action="" method="post" id="contractform" class="eplyfrm" enctype="multipart/form-data">
					<input type="hidden" name="contract" value="1">
                    <input type="hidden" value="<?php echo trim($_POST['jobid']);?>" name="jobid">

                    <div id="owncontract">

                        <div class="form-group">

                        <label>Upload Optional Information</label><br>
						
						<div class="row">
						<div class="col-sm-8 col-8">

                        <input type="file" name="jobupload[]" id="jobupload" class="form-control">

                        <span>(PDF or Docx file)</span>
						</div>
						<div class="col-sm-2 col-2">
								<a href="javascript:void(0);" class="addoptnlupld"><i class="fa fa-plus"></i></a>
						</div>
						
						</div>
						
						<div id="myoptnlupld"></div>

                        </div>

                    </div>


						<div class="row">

							<div class="col-sm-3">

                            <input type="submit" value="Post Job" class="onlybutfulwdt">

							</div>

							<div class="col-sm-3">

								

							</div>

						</div>


					</form>

					

				</div>

			</div>

		</div>

	</div>

</section>

	

<?php 

}

include_once('footer.php');?>