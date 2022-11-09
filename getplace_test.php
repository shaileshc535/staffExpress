<?php
include('config/config.php'); 
include_once "config/common.php";
$dbConn = establishcon();
$alldata = array();
$locations = array();

$k = 0;
    $keyword = isset($_REQUEST['keyword'])?trim($_REQUEST['keyword']):"";

   // if($_POST['action'] == "getplaces"){
        $ch = curl_init("https://maps.googleapis.com/maps/api/place/autocomplete/json?input=sydney&libraries=places&components=country:au&key=AIzaSyAcG1xQOlp5AVZOFmsaG9hkOozP1eW2qeM");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result2 = curl_exec($ch);

       $result = json_decode($result2);
         

        if(count($result->predictions) > 0){
            for($i=0; $i<count($result->predictions); $i++){
                $description = explode(", ", $result->predictions[$i]->description);
				
				$getlocation=$result->predictions[$i]->description;
		  $getlocation2=urlencode($getlocation);

$url = 'https://maps.google.com/maps/api/geocode/json?address='.$getlocation2.'&key=AIzaSyAcG1xQOlp5AVZOFmsaG9hkOozP1eW2qeM';
 
// Initialize CURL:
$ch1 = curl_init($url);  
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
 
// Store the data:
$json = curl_exec($ch1);
curl_close($ch1);

$myjson = json_decode($json);
$address_type = $myjson->results[0]->address_components[3]->types[0];
if($address_type == 'postal_code'){
    $postal_code = $myjson->results[0]->address_components[3]->long_name;
}
else{
    $address_type = $myjson->results[0]->address_components[4]->types[0];
    if($address_type == 'postal_code'){
        $postal_code = $myjson->results[0]->address_components[4]->long_name;
    }
    else{
        $address_type = $myjson->results[0]->address_components[5]->types[0];
        if($address_type == 'postal_code'){
            $postal_code = $myjson->results[0]->address_components[5]->long_name;
        }
    }
}
echo $postal_code."<br>";
//echo '<pre>';
//print_r($myjson);
              
       }
		}
      
       // print json_encode($alldata);
    //}


    closeconn($dbConn);
?>