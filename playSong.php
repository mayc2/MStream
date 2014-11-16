<?php

session_start();

$ip = $_SESSION['ip'];
$sourceAccount = $_SESSION['sourceAccount'];
$source = $_SESSION['source'];

$itemName = urldecode($_POST['itemName']);
$location = $_POST['location'];

$name = urldecode($_POST['name']);
$offset = urldecode($_POST['offset']);
$mediaItemContainerLocation = urldecode($_POST['mediaItemContainerLocation']);
$mIsPresetable = urldecode($_POST['mIsPresetable']);
$mItemName = urldecode($_POST['mItemName']);
$location = urldecode($_POST['location']);
$isPresetable = urldecode($_POST['isPresetable']);
$itemName = urldecode($_POST['itemName']);


$xml_data = '<ContentItem source="'.$source.'" location="'.$location.'" sourceAccount="'.$sourceAccount.'">
		<itemName>'.$itemName.'</itemName>
	</ContentItem>';

echo $xml_data;//die();

$xml_response = '<item Playable="1"><name>'.$name.'</name><type>dir</type><mediaItemContainer offset="'.$offset.'">
		<ContentItem source="'.$source.'" location="'.$mediaItemContainerLocation.'" sourceAccount="'.$sourceAccount.'" isPresetable="'.$mIsPresetable.'">
			<itemName>'.$mItemName.'</itemName>
		</ContentItem>
	</mediaItemContainer>';

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