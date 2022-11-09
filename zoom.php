<?php
$ch = curl_init();

define('CLIENT_ID', 'g4dprA89Rlq25EPZcp4DJA');
define('CLIENT_SECRET', 'xYjAvODVgkId46A9n4XOR3MnLooSWOjn');
define('REDIRECT_URI', 'REDIRECT_URL_FOR_OAUTH');

$auth = base64_encode(CLIENT_ID.':'.CLIENT_SECRET);

$json_data = '[
                "action" => "create",
                "email" => "symapptest10@gmail.com",
                "type" => 1,
            ]';
			
curl_setopt($ch, CURLOPT_URL, 'https://api.zoom.us/v2/users/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

$headers = array();
$headers[] = 'Authorization: Basic '.$auth.'';
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
//access token
$userid = "symapptest3@gmail.com";
			

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
$result = json_decode($result);
echo "<pre>";
print_r($result);
?>