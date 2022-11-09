<?php
date_default_timezone_set('Canada/Mountain');
$t = time();
$t = date('YmdHis',$t);

$mysecret='secret';
$merchant='zaksmart';
$ordid = 12;

$tempToHash = $t.".".$merchant.".".$ordid."...";
$myhash1 = sha1($tempToHash);
$myhash = sha1($myhash1.'.'.$mysecret);

$xml = '<?xml version="1.0" encoding="UTF-8"?>
<request type="settle" timestamp="'.$t.'">
  <merchantid>zaksmart</merchantid>
  <account>internet</account>
  <orderid>12</orderid>
  <pasref>16190727954395420</pasref>
  <sha1hash>'.$myhash.'</sha1hash>
</request>';


//The URL that you want to send your XML to.
$url = 'https://api.sandbox.realexpayments.com/epage-remote.cgi';

//Initiate cURL
$curl = curl_init($url);

//Set the Content-Type to text/xml.
curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

//Set CURLOPT_POST to true to send a POST request.
curl_setopt($curl, CURLOPT_POST, true);

//Attach the XML string to the body of our request.
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

//Tell cURL that we want the response to be returned as
//a string instead of being dumped to the output.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//Execute the POST request and send our XML.
$result = curl_exec($curl);

//Do some basic error checking.
if(curl_errno($curl)){
    throw new Exception(curl_error($curl));
}

//Close the cURL handle.
curl_close($curl);

//Print out the response output.
echo $result;
?>