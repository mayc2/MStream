<?php

session_start();

require_once('mysql.php');

$ip = $_COOKIE['ip'];
$sourceAccount = $_COOKIE['sourceAccount'];
$source = $_COOKIE['source'];

$data = $_POST['data'];
$xmlResponse = new SimpleXMLElement($data);


if($xmlResponse->ContentItem->attributes()->source == "INVALID_SOURCE") {
  $query = $conn->query("SELECT itemName,location FROM songvotes ORDER BY votes DESC LIMIT 1");
  while($row = $query->fetch_assoc()) {
    $itemName = $row['itemName'];
    $location = $row['location'];
    $conn->query("UPDATE songvotes SET votes=0 WHERE itemName='".$conn->real_escape_string($itemName)."' AND location='".$conn->real_escape_string($location)."'");
  }
  $conn->query("UPDATE boo SET boos='0'");


$xml_data = '<ContentItem source="'.$source.'" sourceAccount="'.$sourceAccount.'" location="'.$location.'">
<itemName>'.$itemName.'</itemName>
</ContentItem>';


$curl = curl_init();
curl_setopt_array($curl,
		  array(CURLOPT_URL => 'http://'.$ip.':8090/select',
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $xml_data,
			CURLOPT_HTTPHEADER => array('Content-type: text/xml')
			));
$resp = curl_exec($curl);
curl_close($curl);

$curl = curl_init();
curl_setopt_array($curl, array(
			       CURLOPT_URL => 'http://'.$_COOKIE['ip'].':8090/now_playing',
			       CURLOPT_HEADER => 0,
			       CURLOPT_RETURNTRANSFER => 1
			       ));
$resp = curl_exec($curl);
$data = new SimpleXMLElement($resp);
//$results['artist'] = $data->artist;
//$results['track'] = $data->track;

header("Content-type: application/json");
echo json_encode($results);

}
