<?php
session_start();
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="/jquery.min.js"></script>
<script src="/jquery-ui.min.js"></script>
<script src="/mstream.js"></script>

<title>MStream</title>
</head>
<body>

<?php
require_once('mysql.php');

include('search.php');

$ip = $_COOKIE['ip'];
$sourceAccount = $_COOKIE['sourceAccount'];
$source = $_COOKIE['source'];

$itemName = urldecode($_POST['itemName']);
$itemType = $_POST['itemType'];
$contentLocation = $_POST['contentLocation'];
$contentIsPresentable = $_POST['contentIsPresentable'];
$ContentItemName = $_POST['ContentItemName'];

$xml_data = '<navigate source="'.$source.'" sourceAccount="'.$sourceAccount.'">
<item> <name>'.$itemName.'</name> <type>'.$itemType.'</type>
<ContentItem source="'.$source.'" sourceAccount="'.$sourceAccount.'" location="'.$contentLocation.'">
<itemName>'.$itemName.'</itemName>
</ContentItem>
</item>
</navigate>';

$curl = curl_init();
curl_setopt_array($curl,
	array(CURLOPT_URL => 'http://'.$ip.':8090/navigate',
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => $xml_data,
		CURLOPT_HTTPHEADER => array('Content-type: text/xml')
	));

$resp = curl_exec($curl);
$data = new SimpleXMLElement($resp);

foreach($data->items->item as $item) {
  echo urldecode($item->name);
  if(strcmp($item->type, "track") == 0){
  	  //echo $item->itemName;
    $itemName = (string)$item->ContentItem->itemName;
    $itemNameValue = urlencode($itemName);
    $location = (string)$item->ContentItem->attributes()->location;
    echo (string)$item->name;
    echo '<form style="display:inline" method="post" action="playSong.php">
		<input type="hidden" name="itemName" value="'.$item->ContentItem->itemName.'">
		<input type="hidden" name="location" value="'.$item->ContentItem->attributes()->location.'">
		<input type="submit" value="Play Song"></form><br />';

 		echo getVotes($conn,$itemName,$location).' [ <a href="vote.php?val=up&itemName='.$itemNameValue.'&location='.urlencode($location).'">+</a> / <a href="vote.php?val=down&itemName='.$itemNameValue.'&location='.urlencode($location).'">-</a> ]<br /><br />';
  }
  else{

	  echo '<form method="post" action="goToDir.php">
	<input type="hidden" name="itemName" value="'.urlencode($item->itemName).'">
	<input type="hidden" name="itemType" value="'.$item->type.'">
	<input type="hidden" name="contentLocation" value="'.$item->ContentItem->attributes()->location.'">
	<input type="hidden" name="contentIsPresentable" value="'.$item->ContentItem->attributes()->isPresentable.'">
	<input type="hidden" name="ContentItemName" value="'.$item->ContentItem->itemName.'">
	<input type="submit" value="Browse '.$item->name.'"></form><br />';
	  
	  //echo '<pre>'.print_r($item).'</pre>';
	}
}


//print_r(curl_getinfo($curl));
curl_close($curl);