<?php

session_start();

$ip = $_SESSION['ip'];
$sourceAccount = $_SESSION['sourceAccount'];
$source = $_SESSION['source'];

$itemName = urldecode($_POST['itemName']);
$location = $_POST['location'];

$xml_data = '<ContentItem source="'.$source.'" sourceAccount="'.$sourceAccount.'" location="'.$location.'">
<itemName>'.$itemName.'</itemName>
</ContentItem>';

echo $xml_data;die();

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

header("Location: submitip.php");