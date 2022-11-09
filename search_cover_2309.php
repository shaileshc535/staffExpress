<?php

include('config/config.php'); 

include_once "config/common.php";

$dbConn = establishcon();

include_once('header.php');

$quals = array();

$addcomp = '';
+
$benefit = '';



$mybusinesses = '';



$R = 6371;

$cond = '';

$today = date('Y-m-d');

if(count($_POST) > 0){
	
	$inner_join = "";

    $myaddress = isset($_POST['myaddress'])?tep_db_input($_POST['myaddress']):"";

	$mydist = isset($_POST['mydist'])?tep_db_input($_POST['mydist']):"";

    //$catid = isset($_POST['catid'])?tep_db_input($_POST['catid']):"";
	$catid = isset($_POST['catid'])?$_POST['catid']:array();

    $workmode = isset($_POST['workmode'])?tep_db_input($_POST['workmode']):"";



    $from = isset($_POST['from'])?tep_db_input($_POST['from']):"";

    $to = isset($_POST['to'])?tep_db_input($_POST['to']):"";



    if(count($catid)>0){

        //$cond .= "and a.catid = '".$catid."'";
		$catid = implode(",", $catid);
		$cond .= "and jbct.catid IN ('".$catid."')";
		$inner_join .= "inner join job_cat jbct on a.id = jbct.jobid";

    }

    if($workmode){

        $cond .= "and a.workmode = '".$workmode."'";

    }

    if($from && $to){

        $cond .= "and a.jobdate >= '".$from."' and a.jobdate2 <= '".$to."'";

    }



    $mylatlon = get_latlon_from_address($myaddress);

    if($mylatlon != ""){

        $mylatlon_arr = explode("--", $mylatlon);

        $lat = $mylatlon_arr[0];

        $lon = $mylatlon_arr[1];

        $sql = "Select FirstCut.*,

                acos(sin(".deg2rad($lat).")*sin(radians(lat)) + cos(".deg2rad($lat).")*cos(radians(lat))*cos(radians(lon)-".deg2rad($lon).")) * $R As Dist

            From (

               SELECT a.id,a.title,a.lat,a.lon,a.street_address,a.location,a.postdate,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.workmode,a.add_time,a.covertype,b.company_img, (acos(sin(".deg2rad($lat).")*sin(radians(a.lat)) + cos(".deg2rad($lat).")*cos(radians(a.lat))*cos(radians(a.lon)-".deg2rad($lon).")) * $R) as actualdist from job_details a ".$inner_join." inner join users b on a.employer_id=b.id where 1  ".$cond." and a.title != '' and a.location != '' and a.lat != '' and a.workmode != '' and a.isclosed=0 and a.postcomplete=1 group by a.id order by actualdist asc

            ) As FirstCut";

            //echo $sql; die;

        $myjob = dbQuery($dbConn,$sql);

        if(dbNumRows($myjob) > 0){

            $hasrecord = 1;

        }

        else

        $hasrecord = 0;

    }

    else{

        echo "<script>location.href='".SITEURL."searchcover?error=1'</script>";

        exit;

    }

    

}

    
if($hasrecord == 1)

$zoom = 8;

else

$zoom = 5;



    if(isset($_REQUEST['error']) || count($_POST) == 0 || $hasrecord == 0){

        $myjob = dbQuery($dbConn, "SELECT a.id,a.title,a.lat,a.lon FROM job_details a where a.title != '' and a.location != '' and a.lat != '' and a.workmode != '' and a.isclosed=0 and a.postcomplete=1");

        $zoom = 5;

    }

    if(dbNumRows($myjob) > 0){

        $hasinirecored = 1;

        while($fetch = dbFetchArray($myjob)){

            $mylat = $fetch['lat'];

            $mylon = $fetch['lon'];

            $mybusinesses.="['".stripslashes($fetch['title'])."', ".$fetch['lat'].", ".$fetch['lon'].", ".$fetch['id']."],";

        }

    }

    else

    $hasinirecored = 0;



        
$cats = array();
?>

<script src="https://maps.google.com/maps/api/js?sensor=false&key=AIzaSyAcG1xQOlp5AVZOFmsaG9hkOozP1eW2qeM"></script>

	<div class="works works_search_cover">

		<div class="container">

			<div class="inneremploypg jobdtlsall">

                <div class="row" style="margin-bottom:20px;">

					<div class="col-sm-12" style="text-align:center;">

						<div class="advnserch">

							<div id="filter">

								<form action="" method="post" id="jobpost">

									<div class="joccatfrmfild mycatsec">

                                    <!--<select class="form-select" name="catid" id="category">

											<option selected value="">Job Category</option>

											<?php

											$cat = dbQuery($dbConn, "select * from category order by category");

											while($catrow = dbFetchArray($cat)){

											?>

											<option value="<?php echo $catrow['id'];?>" <?php if($_POST['catid']==$catrow['id']) echo "selected";?>><?php echo stripslashes($catrow['category']);?></option>

											<?php

											}

											?>

									</select>-->
									<input type="hidden" id="myselectedcats" value="">
									<input class="form-control" id="showcats" name="showcats" type="text" placeholder="Job Category" readonly />
									<div id="allcats" style="display:none;">
									<ul class="items">

								<?php

									$cat = dbQuery($dbConn, "select * from category where parent_id=0 order by category");

									while($catrow = dbFetchArray($cat)){
										?>
										<li>
										<input id="maincat_<?php echo $catrow['id'];?>" type="checkbox" name="maincatid[]" class="employercats" value="<?php echo $catrow['id'];?>" />&nbsp; <?php echo stripslashes($catrow['category']);?>
										<div id="maincatli_<?php echo $catrow['id'];?>">
										
										</div>
									</li>
									<?php

								}

								?>

								</ul>
								</div>
									
									</div>

									<!--<span></span>-->

									<div class="joccatfrmfild">

									<select class="form-select" name="workmode">

										<option selected value="">Job Type</option>

                                        <option value="2" <?php if($_POST['workmode']=='2') echo "selected";?>>In person</option>

										<option value="1" <?php if($_POST['workmode']=='1') echo "selected";?>>Remote</option>

									</select>

									</div>

									<!--<span></span>-->

									<div class="joccatfrmfild">

									<input class="datepicker form-control" id="from" name="from" type="text" placeholder="Start Date" value="<?php echo $_POST['from'];?>" />

									</div>

									<!--<span></span>-->

									<div class="joccatfrmfild">

									<input class="datepicker form-control" id="to" name="to" type="text" placeholder="End Date" value="<?php echo $_POST['to'];?>" />

									</div>

									<!--<span></span>-->

									<div class="joccatfrmfild">

                                    <input type="text" class="form-control required" id="myaddress" placeholder="Location (suburb)" name="myaddress" value="<?php echo $_POST['myaddress'];?>" autocomplete="off" />

                                    <div id="loading" style="display:none;"><img src="<?php echo SITEURL;?>images/sp-loading.gif" alt=""></div>

                                    <div id="mylocations" style="display:none;z-index:999;padding:15px;">

                                    <ul></ul>

                                    </div>

									</div>

									<!--<span></span>-->

									<!--<input type="text" class="form-control required" id="mydistance" placeholder="Distance in KM" name="mydist" value="<?php echo $_POST['mydist'];?>" />-->

									<br>

									<div class="joccatfrmfild">

									<input type="submit" value="Filter" style="width:auto;" />

									</div>

								</form>

							</div>

						</div>

					

                    </div>

                </div>

				<div class="row">

					<div class="col-lg-7 col-md-12 col-sm-7">

                        <?php

						if(isset($_REQUEST['error']) && $_REQUEST['error'] == 1){

							echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>Please enter proper location.</div>";

						}

                        if(count($_POST) > 0 && $hasrecord == 0){

                            echo "<div class='alert-danger' style='padding:15px;margin-bottom:15px;'>No jobs found.</div>";

                        }

						?>

                        <input type="hidden" id="userid" value="<?php if(isset($_SESSION['loginUserId'])) echo $_SESSION['loginUserId'];?>">

                        <div class="sercoverpage">

							<div class="row">

                        <?php

                        $cond = '';

                        $orderby = '';

                        $currtime = date('Y-m-d H:i:s');


                        if(isset($_REQUEST['error']) || count($_POST) == 0 || $hasrecord == 0){


                            $sql = "SELECT a.id,a.title,a.street_address,a.location,a.postdate,a.job_status,a.jobdate,a.jobdate2,a.starttime,a.endtime,a.workmode,a.add_time,a.covertype,b.company_img from job_details a inner join users b on a.employer_id=b.id where a.title != '' and a.location != '' and a.lat != '' and a.workmode != '' and a.isclosed=0 and a.postcomplete=1 order by a.id desc";

                        }

                        $jobs = dbQuery($dbConn, $sql);

                        if(dbNumRows($jobs) > 0){
						$catstr = '';

                        while($row = dbFetchArray($jobs)){

                            $jobsrtdate = $row['jobdate'];

                            $jobenddate = $row['jobdate2'];

                            $strttime = $row['starttime'];

                            $endtime = $row['endtime'];
							
							$mycats = dbQuery($dbConn, "SELECT category from job_cat a inner join category b on a.catid=b.id where jobid = '".trim($row['id'])."'");

							while($mycatsrow = dbFetchArray($mycats)){

								$cats[] = stripslashes($mycatsrow['category']);

							}
							if(count($cats) > 0){
								$catstr = implode(", ", $cats);
							}

                            ?>

                            <div class="col-sm-6">

									<div class="sercoversingle">

										<div class="covrsinglleft">

											<h4><a href="<?php echo SITEURL;?>viewmyjob/<?php echo base64_encode($row['id']);?>"><?php echo stripslashes($row['title']);?></a></h4>

											<p><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo stripslashes($row['street_address']);?>, <?php echo stripslashes($row['location']);?></p>

											<p><i class="fa fa-calendar" aria-hidden="true"></i><?php 
											if($row['covertype']==1)
											echo date('M j, Y', strtotime($row['jobdate']));
											else
												echo "TBD";
											?>
											<?php
											if($row['add_time'] == 1){
											?>
											at <?php echo date('h:i A', strtotime($row['starttime']));?> 
											<?php
											}
											?> <?php 
											if($row['covertype']==1 && $row['jobdate2'] != "0000-00-00")
											echo " - ".date('M j, Y', strtotime($row['jobdate2']));
											
											?> 
											<?php
											if($row['add_time'] == 1){
												if($row['jobdate2'] != "0000-00-00")
												echo "at";
												else
													echo "-";
											?>
											<?php echo date('h:i A', strtotime($row['endtime']));?>
											<?php
											}
											?></p>

											<p><i class="fa fa-list" aria-hidden="true"></i><?php echo $catstr;?></p>

										</div>

										<div class="covrsinglright">

											<div class="covrtimg">
											<?php
											if($row['company_img']){
												?>
												<img src="<?php echo SITEURL;?>uploads/<?php echo $row['company_img'];?>" alt="">
												<?php
											}
											else{
											?>
											<img src="<?php echo SITEURL;?>images/sercovernoimg.png" class="img-responsive" alt="loginimg" />
											<?php } ?>
											</div>

                                            <ul class="homebtn" id="applybtn_<?php echo $row['id'];?>" style="">

                                                <li><a href="<?php echo SITEURL;?>viewmyjob/<?php echo base64_encode($row['id']);?>" style="padding:0.4rem 0rem;">View</a></li>

                                            </ul>

										</div>

									</div>

								</div>

                            <?php
							$catstr = '';
							$cats = array();

                        }

                        }

                        else{

                            ?>

                            <div class="col-sm-6">

								<div class="sercoversingle">

                                No open jobs.

                                </div>

                            </div>

                            <?php

                        }

                        ?>

						</div>

                    </div>

                    </div>

					<div class="col-lg-5 col-md-12 col-sm-5">

                    <div id="map_canvas" style="height:400px;"></div>



                    <script type="text/javascript">

                    var locations = [<?php echo $mybusinesses;?>];

                    var link;

                    var map = new google.maps.Map(document.getElementById('map_canvas'), {

                    zoom: <?php echo $zoom;?>,

                    <?php

                    if($hasinirecored == 1 && ($hasrecord == 1 || $hasrecord == 0)){

                    ?>

                    center: new google.maps.LatLng(<?php echo $mylat;?>, <?php echo $mylon;?>),

                    <?php }

                    else{

                        ?>

                        center: new google.maps.LatLng(-23.7205325, 133.7977346),

                        <?php

                    }

                    ?>

                    mapTypeId: google.maps.MapTypeId.ROADMAP

                    });

                    

                    <?php

                    if($hasinirecored == 1 && ($hasrecord == 1 || $hasrecord == 0)){

                    ?>

                    var infowindow = new google.maps.InfoWindow();



                    var marker, i;

                    

                    for (i = 0; i < locations.length; i++) {  

                    marker = new google.maps.Marker({

                        position: new google.maps.LatLng(locations[i][1], locations[i][2]),

                        map: map

                    });

                    

                    google.maps.event.addListener(marker, 'click', (function(marker, i) {

                        return function() {

                            link = "<a href='<?php echo SITEURL.'viewmyjob/';?>"+btoa(locations[i][3])+"'>"+locations[i][0]+"</a>";

                        infowindow.setContent(link);

                        infowindow.open(map, marker);

                        }

                    })(marker, i));

                    }

                    <?php } ?>

                </script>

					</div>

				

				</div>

			</div>

		</div>

	</div>

	

	<?php include_once('footer.php');

?>