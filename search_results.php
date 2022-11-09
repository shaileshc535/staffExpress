<?php
include('config/config.php');
include_once "config/common.php";
$dbConn = establishcon();

if (count($_POST) > 0) {

	//$mlat = isset($_POST['mlat'])?tep_db_input($_POST['mlat']):"";
	//$mlon = isset($_POST['mlon'])?tep_db_input($_POST['mlon']):"";

	$main_cat1 = isset($_POST['main_cat1']) ? tep_db_input($_POST['main_cat1']) : "";
	$location = isset($_POST['location']) ? tep_db_input($_POST['location']) : "";
	$how_to_price = isset($_POST['how_to_price']) ? tep_db_input($_POST['how_to_price']) : "";

	$payment_consideraion = isset($_POST['payment_consideraion']) ? tep_db_input($_POST['payment_consideraion']) : "";
	$sub_cat1 = isset($_POST['sub_cat1']) ? tep_db_input($_POST['sub_cat1']) : "";
	$availability = isset($_POST['availability']) ? tep_db_input($_POST['availability']) : "";

	$skill_level = isset($_POST['skill_level']) ? tep_db_input($_POST['skill_level']) : "";

	$have_transportation = isset($_POST['have_transportation']) ? tep_db_input($_POST['have_transportation']) : "";

	$keywords = isset($_POST['keywords']) ? tep_db_input($_POST['keywords']) : "";

	$pricing = isset($_POST['pricing']) ? tep_db_input($_POST['pricing']) : "";

	$name = isset($_POST['name']) ? tep_db_input($_POST['name']) : "";

	$phone = isset($_POST['phone']) ? tep_db_input($_POST['phone']) : "";

	$name = isset($_POST['name']) ? tep_db_input($_POST['name']) : "";

	$phone = isset($_POST['phone']) ? tep_db_input($_POST['phone']) : "";

	$trade_licensed = isset($_POST['trade_licensed']) ? $_POST['trade_licensed'] : 0;

	$business_licensing = isset($_POST['business_licensing']) ? $_POST['business_licensing'] : 0;

	$workers_comp = isset($_POST['workers_comp']) ? $_POST['workers_comp'] : 0;

	$heights_ok = isset($_POST['heights_ok']) ? $_POST['heights_ok'] : 0;


	$have_tools = isset($_POST['have_tools']) ? $_POST['have_tools'] : 0;

	$have_equipment = isset($_POST['have_equipment']) ? $_POST['have_equipment'] : 0;

	$havecrew = isset($_POST['havecrew']) ? $_POST['havecrew'] : 0;

	$myzip = isset($_POST['myzip']) ? $_POST['myzip'] : "";

	if ($myzip != "") {

		$findzipcode = dbQuery($dbConn, "Select * from condi_saved_zip where zipcode='" . $myzip . "'");

		if (dbNumRows($findzipcode) == 0) {

			$spincode = $myzip;
			$getlocation = $spincode;

			$getlocation2 = urlencode($getlocation);

			$url = 'https://maps.google.com/maps/api/geocode/json?address=' . $getlocation2 . '&key=AIzaSyAlY4PP7Z22qwIyvoTXQIQSaEjU7pewk-M';

			// Initialize CURL:
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);

			$myjson = json_decode($json);
			$northeastlat = $myjson->results[0]->geometry->bounds->northeast->lat;

			$northeastlon = $myjson->results[0]->geometry->bounds->northeast->lng;



			$southwestlat = $myjson->results[0]->geometry->bounds->southwest->lat;

			$southwestlon = $myjson->results[0]->geometry->bounds->southwest->lng;

			if ($northeastlat != "" && $northeastlon != "" && $southwestlat != "" && $southwestlon != "") {

				if ($northeastlat > $southwestlat) {
					$latdiff = ($northeastlat - $southwestlat) / 2;
					$latcenter = $southwestlat + $latdiff;
				}

				if ($southwestlat > $northeastlat) {
					$latdiff = ($southwestlat - $northeastlat) / 2;
					$latcenter = $northeastlat + $latdiff;
				}

				if ($northeastlon > $southwestlon) {
					$londiff = ($northeastlon - $southwestlon) / 2;
					$loncenter = $southwestlon + $londiff;
				}

				if ($northeastlon < $southwestlon) {
					$londiff = ($southwestlon - $northeastlon) / 2;
					$loncenter = $northeastlon + $londiff;
				}

				$lat = $latcenter;
				$lon = $loncenter;

				dbQuery($dbConn, "Insert into condi_saved_zip set zipcode='" . $spincode . "',lat='" . $lat . "',longl='" . $lon . "'");
			} else {

				echo "<script>location.href='" . SITEURL . "?error=2'</script>";

				exit;
			}
		} else {
			$findzipcode_res = dbFetchArray($findzipcode);

			$lat = $findzipcode_res['lat'];
			$lon = $findzipcode_res['longl'];
		}
	} else {
		echo "<script>location.href='" . SITEURL . "?error=1'</script>";

		exit;
	}

	/*$lat = $mlat; 
	$lon = $mlon;*/

	$findactualradius = dbQuery($dbConn, "Select howval from master_how_far where id='" . $location . "'");
	$findactualradius_res = dbFetchArray($findactualradius);
	$rad = $findactualradius_res['howval'];


	$R = 6371;

	$extrastr = "";
	$extrastr_citer = "";

	if ($main_cat1 !== "") {

		$extrastr .= " and a.cat_id='" . $main_cat1 . "'";
	}

	if ($sub_cat1 !== "") {

		$extrastr .= " and (a.subcat_id='" . $sub_cat1 . "' || a.subcat_id='0')";
	}



	if ($how_to_price !== "") {

		$extrastr .= " and a.how_to_price='" . $how_to_price . "'";
	}

	if ($payment_consideraion !== "") {

		$extrastr .= " and a.payment_consideraion='" . $payment_consideraion . "'";
	}

	/*if($sub_cat1!=="")
	{
		
		$extrastr.=" and (a.subcat_id='".$sub_cat1."' || a.subcat_id='0')";
	}*/

	if ($availability !== "") {

		$extrastr .= " and a.availability='" . $availability . "'";
	}

	if ($skill_level !== "") {

		$extrastr .= " and a.skill_level='" . $skill_level . "'";
	}

	if ($have_transportation !== "") {

		$extrastr .= " and a.have_transportation='" . $have_transportation . "'";
	}

	if ($pricing !== "") {

		$extrastr .= " and a.pricing='" . $pricing . "'";
	}

	//$trade_licensed=(int)$trade_licensed;

	if ($trade_licensed == 1) {

		$extrastr .= " and a.trade_licensed='1'";

		$extrastr_citer .= ", Trade Licensed";
	}

	//$business_licensing=(int)$business_licensing;

	if ($business_licensing == 1) {

		$extrastr .= " and a.business_licensing='1'";

		$extrastr_citer .= ", Bus. License";
	}

	if ($workers_comp == 1) {

		$extrastr .= " and a.workers_compensation='1'";

		$extrastr_citer .= ", Workers Comp";
	}
	//echo $heights_ok;
	//EXIT;
	if ($heights_ok == 1) {


		$extrastr .= " and a.good_with_heighs='1'";

		$extrastr_citer .= ", Heights Ok";
	}

	if ($have_tools == 1) {

		$extrastr .= " and a.have_tools='1'";

		$extrastr_citer .= ", Have Tools";
	}

	if ($have_equipment == 1) {

		$extrastr .= " and a.relevent_equipment='1'";

		$extrastr_citer .= ", Have Equipment";
	}

	if ($havecrew == 1) {

		$extrastr .= " and a.crew_available='1'";
		$extrastr_citer .= ", Have Crew";
	}
	//echo "Select * from condi_skill where 1 $extrastr";
	$mykeywords = array();
	$extrastring2 = '';
	$extrastring3 = '';
	if ($keywords != "") {
		$mykeywords = @explode(",", $keywords);
		//$extrastring2.=" and b.mykeywords in('$keywords')";

	}
	if (count($mykeywords) > 0) {

		for ($i = 0; $i < count($mykeywords); $i++) {
			$extrastring3 .= "'" . $mykeywords[$i] . "',";
		}
		$extrastring3 .= substr($extrastring3, 0, -1);
	}

	$foundresultid = array();
	//echo $extrastring3;
	//echo "Select a.* from condi_skill a inner join condi_keywords b on a.id=b.skillid where 1 $extrastr $extrastring2";
	dbQuery($dbConn, "Insert into saved_search set main_cat1='" . $main_cat1 . "',location='" . $location . "',how_to_price='" . $how_to_price . "',sub_cat1='" . $sub_cat1 . "',availability='" . $availability . "',skill_level='" . $skill_level . "',have_transportation='" . $have_transportation . "',keywords='" . $keywords . "',pricing='" . $pricing . "',search_name='" . $name . "',phone='" . $phone . "',trade_licensed='" . $trade_licensed . "',business_licensing='" . $business_licensing . "',workers_comp='" . $workers_comp . "',heights_ok='" . $heights_ok . "',have_tools='" . $have_tools . "',have_equipment='" . $have_equipment . "',havecrew='" . $havecrew . "' ,payment_consideraion='" . $payment_consideraion . "',zip='" . $myzip . "'");
	$insertidsrch = dbInsertId($dbConn);

	if ($location != 6) {
		/*echo "Select FirstCut.*,
                acos(sin(".deg2rad($lat).")*sin(radians(lat)) + cos(".deg2rad($lat).")*cos(radians(lat))*cos(radians(lon)-".deg2rad($lon).")) * $R As Dist
            From (
               Select a.*,c.member_name,c.member_email,c.member_primaryphone,c.member_text,d.howval from condi_skill a inner join condi_keywords b on a.id=b.skillid inner join condi_members c on a.user_id=c.id inner join master_how_far d on a.radius_working=d.id   where 1 $extrastr $extrastring2 and ((acos(sin(".deg2rad($lat).")*sin(radians(a.lat)) + cos(".deg2rad($lat).")*cos(radians(a.lat))*cos(radians(a.lon)-".deg2rad($lon).")) * $R)<=d.howval) group by a.id
            ) As FirstCut
            Where ((acos(sin(".deg2rad($lat).")*sin(radians(lat)) + cos(".deg2rad($lat).")*cos(radians(lat))*cos(radians(lon)-".deg2rad($lon).")) * $R) <= $rad) 
            ";*/

		$findsearch_result = dbQuery($dbConn, "Select FirstCut.*,
                acos(sin(" . deg2rad($lat) . ")*sin(radians(lat)) + cos(" . deg2rad($lat) . ")*cos(radians(lat))*cos(radians(lon)-" . deg2rad($lon) . ")) * $R As Dist
            From (
               Select a.*,c.member_name,c.member_email,c.member_primaryphone,c.member_text,c.is_paid,d.howval from condi_skill a inner join condi_keywords b on a.id=b.skillid inner join condi_members c on a.user_id=c.id inner join master_how_far d on a.radius_working=d.id   where 1 and c.is_paid='1' $extrastr $extrastring2 and ((acos(sin(" . deg2rad($lat) . ")*sin(radians(a.lat)) + cos(" . deg2rad($lat) . ")*cos(radians(a.lat))*cos(radians(a.lon)-" . deg2rad($lon) . ")) * $R)<=d.howval) group by a.id
            ) As FirstCut
            Where ((acos(sin(" . deg2rad($lat) . ")*sin(radians(lat)) + cos(" . deg2rad($lat) . ")*cos(radians(lat))*cos(radians(lon)-" . deg2rad($lon) . ")) * $R) <= $rad) 
            ");
	} else {

		$findsearch_result = dbQuery($dbConn, "
               Select a.*,c.member_name,c.member_email,c.member_primaryphone,c.member_text,d.howval,c.is_paid from condi_skill a inner join condi_keywords b on a.id=b.skillid inner join condi_members c on a.user_id=c.id inner join master_how_far d on a.radius_working=d.id   where 1 and c.is_paid='1' $extrastr $extrastring2 and c.billing_zipcode='" . $myzip . "' group by a.id");
	}



	/*"Select FirstCut.*,
                acos(sin(".deg2rad($lat).")*sin(radians(lat)) + cos(".deg2rad($lat).")*cos(radians(lat))*cos(radians(lon)-".deg2rad($lon).")) * $R As Dist
            From (
               Select a.* from condi_skill a inner join condi_keywords b on a.id=b.skillid where 1 $extrastr $extrastring2
            ) As FirstCut
            Where (acos(sin(".deg2rad($lat).")*sin(radians(lat)) + cos(".deg2rad($lat).")*cos(radians(lat))*cos(radians(lon)-".deg2rad($lon).")) * $R) < $rad
            "*/

	//user_id!='".$_SESSION['SUBTRADEUSRID']."'


	include_once('includes/header_general.php');

?>
	<section class="innerbox">
		<div class="rotatebtn"><a href="#"><img src="images/googleplay.png" alt="" /></a> <a href="#"><img src="images/appstore.png" alt="" /></a></div>
		<div class="searchid">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h3><b>Search ID#:</b> <span><?php echo $insertidsrch; ?></span></h3>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="sea_detail">
						<?php
						$srchcatname = "All";
						if ($main_cat1 != "") {
							$searchcatname = dbQuery($dbConn, "Select cat_name from condi_categories where id='" . $main_cat1 . "'");
							$searchcatname_res = dbFetchArray($searchcatname);
							$srchcatname = stripslashes($searchcatname_res['cat_name']);
						}

						$srchsubcatname = "All";
						if ($sub_cat1 != "") {
							$searchsubcatname = dbQuery($dbConn, "Select cat_name from condi_categories where id='" . $sub_cat1 . "'");
							$searchsubcatname_res = dbFetchArray($searchsubcatname);
							$srchsubcatname = stripslashes($searchsubcatname_res['cat_name']);
						}
						if ($how_to_price != "") {
							$howtoprice = dbQuery($dbConn, "Select * from master_how_to_price where id='" . $how_to_price . "'");
							$howtoprice_res = dbFetchArray($howtoprice);
						}
						if ($payment_consideraion != "") {
							$paymentconsideration = dbQuery($dbConn, "Select * from master_payment_consideration where id='" . $payment_consideraion . "'");
							$paymentconsideration_res = dbFetchArray($paymentconsideration);
						}
						if ($availability != "") {
							$availabilitysrch = dbQuery($dbConn, "Select * from master_availibility where id='" . $availability . "'");
							$availabilitysrch_res = dbFetchArray($availabilitysrch);
						}

						if ($skill_level != "") {
							$skill_levelsrch = dbQuery($dbConn, "Select * from master_skill_level where id='" . $skill_level . "'");
							$skill_levelsrch_res = dbFetchArray($skill_levelsrch);
						}
						if ($have_transportation != "") {
							$findtransport = dbQuery($dbConn, "Select * from master_have_transport where id='" . $have_transportation . "'");
							$findtransport_res = dbFetchArray($findtransport);
						}
						$srchpricing = "All";
						if ($pricing != "") {
							$findpricing = dbQuery($dbConn, "Select * from master_pricing where id='" . $pricing . "'");
							$findpricing_res = dbFetchArray($findpricing);
							$srchpricing = stripslashes($findpricing_res['howto']);
						}

						?>
						<h3>Your Search Results Based on:</h3>
						<p><b>Category:</b> <?php echo $srchcatname; ?>, <b>Subcategory:</b> <?php echo $srchsubcatname; ?>, <?php if ($keywords != "") { ?><b>Keywords:</b> (<?php echo stripslashes($keywords); ?>), <?php } ?><?php if ($how_to_price != "") { ?><?php echo stripslashes($howtoprice_res['howto']); ?>, <?php } ?><?php if ($paymentconsideration != "") { ?><?php echo stripslashes($paymentconsideration_res['pay_consider']); ?>, <?php } ?><?php if ($how_to_price != "") { ?><?php echo stripslashes($howtoprice_res['howto']); ?>, <?php } ?><?php if ($availability != "") { ?><?php echo stripslashes($availabilitysrch_res['howto']); ?>, <?php } ?><?php if ($skill_level != "") { ?><?php echo stripslashes($skill_levelsrch_res['howto']); ?>, <?php } ?><?php if ($have_transportation != "") { ?><?php echo stripslashes($findtransport_res['howto']); ?>, <?php } ?><?php echo $srchpricing; ?><?php echo $extrastr_citer; ?> </p>
					</div>
				</div>
			</div>
		</div>
		<?php

		if (dbNumRows($findsearch_result) > 0) {
		?>
			<div class="sea_table">
				<div class="container">
					<div class="row">
						<div class="table-responsive">
							<div class="col-md-12">
								<table class="table">
									<thead>
										<tr>

											<th>Name</th>
											<th>Link</th>
											<th>Phone</th>
											<th>Text</th>
											<th>Email</th>
											<th>Rating</th>
											<th>Keywords</th>
											<th>Experience</th>
											<th>$'s</th>
											<!--<th>Ins</th>-->
											<th>Trans</th>
											<!--<th>Lic</th>-->
											<th>Working</th>
											<th>On</th>
										</tr>
									</thead>
									<tbody>

										<?php
										while ($findskills_res = dbFetchArray($findsearch_result)) {
											$foundresultid[] = $findskills_res['id'];
											$keywordstr = "";
											$findcategory = dbQuery($dbConn, "Select cat_name from condi_categories where id='" . $findskills_res['cat_id'] . "'");
											$findcategory_res = dbFetchArray($findcategory);

											$findcategory1 = dbQuery($dbConn, "Select cat_name from condi_categories where id='" . $findskills_res['subcat_id'] . "'");
											$findcategory_res1 = dbFetchArray($findcategory1);

											$findkeyword = dbQuery($dbConn, "Select * from condi_keywords where skillid='" . $findskills_res['id'] . "'");
											while ($findkeyword_res = dbFetchArray($findkeyword)) {
												$keywordstr .= stripslashes($findkeyword_res['mykeywords']) . ',';
											}
											$findpricing = dbQuery($dbConn, "Select * from master_pricing where id='" . $findskills_res['pricing'] . "'");
											$findpricing_res = dbFetchArray($findpricing);

											$findtool = dbQuery($dbConn, "Select * from master_tools where id='" . $findskills_res['have_tools'] . "'");
											$findtool_res = dbFetchArray($findtool);

											$findequipment = dbQuery($dbConn, "Select * from master_relevent_equipment where id='" . $findskills_res['relevent_equipment'] . "'");
											$findequipment_res = dbFetchArray($findequipment);

											$findtradelicense = dbQuery($dbConn, "Select * from master_trade_licensed where id='" . $findskills_res['trade_licensed'] . "'");
											$findtradelicense_res = dbFetchArray($findtradelicense);

											$findtradelicense = dbQuery($dbConn, "Select * from master_trade_licensed where id='" . $findskills_res['trade_licensed'] . "'");
											$findtradelicense_res = dbFetchArray($findtradelicense);

											$findtransport = dbQuery($dbConn, "Select * from master_have_transport where id='" . $findskills_res['have_transportation'] . "'");
											$findtransport_res = dbFetchArray($findtransport);

											$howtoprice = dbQuery($dbConn, "Select * from master_how_to_price where id='" . $findskills_res['how_to_price'] . "'");
											$howtoprice_res = dbFetchArray($howtoprice);

											$paymentconsideration = dbQuery($dbConn, "Select * from master_payment_consideration where id='" . $findskills_res['payment_consideraion'] . "'");
											$paymentconsideration_res = dbFetchArray($paymentconsideration);

											$thisrate = generaterating($dbConn, $findskills_res['user_id']);
											$findtotalrating = dbQuery($dbConn, "Select id from condi_rating where usrid='" . $userid . "'");
										?>
											<tr>
												<td><?php echo stripslashes($findskills_res['member_name']); ?></td>
												<td><a href="<?php echo SITEURL; ?>skill_details.php?skillid=<?php echo $findskills_res['id']; ?>" target="_blank">See More</a></td>
												<td><?php echo stripslashes($findskills_res['member_primaryphone']); ?></td>
												<td><?php echo stripslashes($findskills_res['member_text']); ?></td>
												<td><?php echo stripslashes($findskills_res['member_email']); ?></td>
												<td><img src="images/<?php echo $thisrate; ?>.png" class="img-responsive" width="70%" alt="" />(<?php echo dbNumRows($findtotalrating); ?> rating(s))</td>
												<!--<td><?php echo substr($keywordstr, 0, -1); ?></td>-->
												<!--<td>-----</td>-->
												<td><?php echo substr($keywordstr, 0, -1); ?></td>
												<td><?php echo stripslashes($findskills_res['experience']) . ' year(s)'; ?></td>
												<td><?php echo stripslashes($findpricing_res['howto']); ?></td>
												<!--<td><?php echo stripslashes($findtool_res['howto']); ?></td>-->
												<!--<td><?php echo stripslashes($findequipment_res['howto']); ?></td>-->
												<!--<td><?php echo stripslashes($findtradelicense_res['howto']); ?></td>-->
												<td><?php echo stripslashes($findtransport_res['howto']); ?></td>
												<td><?php echo stripslashes($howtoprice_res['howto']); ?></td>
												<td><?php echo stripslashes($paymentconsideration_res['pay_consider']); ?></td>
											</tr>
										<?php
										}
										?>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		} else {
		?>
			<p style="text-align:center;">Your search produced no result(s).</p>
			<p style="text-align:center;">Please change your search criteria.</p>
			<?php
		}
		if (dbNumRows($findsearch_result) <= 2) {
			$foundstr1 = '';
			$foundstr2 = '';
			if (count($foundresultid) > 0) {
				$foundstr1 = implode(",", $foundresultid);

				$foundstr2 = " and a.id not in($foundstr1)";
			}

			$findsearch_result2 = dbQuery($dbConn, "Select FirstCut.*,
                acos(sin(" . deg2rad($lat) . ")*sin(radians(lat)) + cos(" . deg2rad($lat) . ")*cos(radians(lat))*cos(radians(lon)-" . deg2rad($lon) . ")) * $R As Dist
            From (
               Select a.*,c.member_name,c.member_email,c.member_primaryphone,c.member_text,d.howval,c.is_paid   from condi_skill a inner join condi_keywords b on a.id=b.skillid inner join condi_members c on a.user_id=c.id inner join master_how_far d on a.radius_working=d.id   where 1 and c.is_paid='1' $extrastr $extrastring2 $foundstr2 group by a.id
            ) As FirstCut
            Where ((acos(sin(" . deg2rad($lat) . ")*sin(radians(lat)) + cos(" . deg2rad($lat) . ")*cos(radians(lat))*cos(radians(lon)-" . deg2rad($lon) . ")) * $R) <= $rad) limit 0,10
            ");


			if (dbNumRows($findsearch_result2) > 0) {

			?>
				<div class="sea_table table" style="margin:50px 0;">
					<div class="table-responsive">
						<div class="container">
							<div class="row">
								<div class="col-md-12">
									<p>Outside Their Zone</p>
									<table class="table">
										<thead>
											<tr>

												<th>Name</th>
												<th>Link</th>
												<th>Phone</th>
												<th>Text</th>
												<th>Email</th>
												<th>Keywords</th>
												<th>Experience</th>
												<th>$'s</th>
												<!--<th>Ins</th>-->
												<th>Trans</th>
												<!--<th>Lic</th>-->
												<th>Working</th>
												<th>On</th>
											</tr>
										</thead>
										<tbody>

											<?php
											while ($findskills_res = dbFetchArray($findsearch_result2)) {

												$keywordstr = "";
												$findcategory = dbQuery($dbConn, "Select cat_name from condi_categories where id='" . $findskills_res['cat_id'] . "'");
												$findcategory_res = dbFetchArray($findcategory);

												$findcategory1 = dbQuery($dbConn, "Select cat_name from condi_categories where id='" . $findskills_res['subcat_id'] . "'");
												$findcategory_res1 = dbFetchArray($findcategory1);

												$findkeyword = dbQuery($dbConn, "Select * from condi_keywords where skillid='" . $findskills_res['id'] . "'");
												while ($findkeyword_res = dbFetchArray($findkeyword)) {
													$keywordstr .= stripslashes($findkeyword_res['mykeywords']) . ',';
												}
												$findpricing = dbQuery($dbConn, "Select * from master_pricing where id='" . $findskills_res['pricing'] . "'");
												$findpricing_res = dbFetchArray($findpricing);

												$findtool = dbQuery($dbConn, "Select * from master_tools where id='" . $findskills_res['have_tools'] . "'");
												$findtool_res = dbFetchArray($findtool);

												$findequipment = dbQuery($dbConn, "Select * from master_relevent_equipment where id='" . $findskills_res['relevent_equipment'] . "'");
												$findequipment_res = dbFetchArray($findequipment);

												$findtradelicense = dbQuery($dbConn, "Select * from master_trade_licensed where id='" . $findskills_res['trade_licensed'] . "'");
												$findtradelicense_res = dbFetchArray($findtradelicense);

												$findtradelicense = dbQuery($dbConn, "Select * from master_trade_licensed where id='" . $findskills_res['trade_licensed'] . "'");
												$findtradelicense_res = dbFetchArray($findtradelicense);

												$findtransport = dbQuery($dbConn, "Select * from master_have_transport where id='" . $findskills_res['have_transportation'] . "'");
												$findtransport_res = dbFetchArray($findtransport);

												$howtoprice = dbQuery($dbConn, "Select * from master_how_to_price where id='" . $findskills_res['how_to_price'] . "'");
												$howtoprice_res = dbFetchArray($howtoprice);

												$paymentconsideration = dbQuery($dbConn, "Select * from master_payment_consideration where id='" . $findskills_res['payment_consideraion'] . "'");
												$paymentconsideration_res = dbFetchArray($paymentconsideration);


											?>
												<tr>
													<td><?php echo stripslashes($findskills_res['member_name']); ?></td>
													<td><a href="<?php echo SITEURL; ?>skill_details.php?skillid=<?php echo $findskills_res['id']; ?>" target="_blank">See More</a></td>
													<td><?php echo stripslashes($findskills_res['member_primaryphone']); ?></td>
													<td><?php echo stripslashes($findskills_res['member_text']); ?></td>
													<td><?php echo stripslashes($findskills_res['member_email']); ?></td>
													<!--<td><?php echo substr($keywordstr, 0, -1); ?></td>-->
													<!--<td>-----</td>-->
													<td><?php echo substr($keywordstr, 0, -1); ?></td>
													<td><?php echo stripslashes($findskills_res['experience']) . ' year(s)'; ?></td>
													<td><?php echo stripslashes($findpricing_res['howto']); ?></td>
													<!--<td><?php echo stripslashes($findtool_res['howto']); ?></td>-->
													<!--<td><?php echo stripslashes($findequipment_res['howto']); ?></td>-->
													<!--<td><?php echo stripslashes($findtradelicense_res['howto']); ?></td>-->
													<td><?php echo stripslashes($findtransport_res['howto']); ?></td>
													<td><?php echo stripslashes($howtoprice_res['howto']); ?></td>
													<td><?php echo stripslashes($paymentconsideration_res['pay_consider']); ?></td>
												</tr>
											<?php
											}
											?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
		<?php
			}
		}
		?>
	</section>
<?php
	include('includes/footer.php');
} else {

	echo "<script>location.href='" . SITEURL . "'</script>";

	exit;
}
?>