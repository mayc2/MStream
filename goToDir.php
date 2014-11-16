<?php

session_start();

$ip = $_SESSION['ip'];
$sourceAccount = $_SESSION['sourceAccount'];
$source = $_SESSION['source'];


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
  	  echo $item->itemName;
  }
  else{
  	
	  echo '<form method="post" action="goToDir.php">
	<input type="hidden" name="itemName" value="'.urlencode($item->itemName).'">
	<input type="hidden" name="itemType" value="'.$item->type.'">
	<input type="hidden" name="contentLocation" value="'.$item->ContentItem->attributes()->location.'">
	<input type="hidden" name="contentIsPresentable" value="'.$item->ContentItem->attributes()->isPresentable.'">
	<input type="hidden" name="ContentItemName" value="'.$item->ContentItem->itemName.'">
	<input type="submit" value="Enter '.$item->name.'"></form><br />';
	  
	  echo '<pre>'.print_r($item).'</pre>';
	}
}


//print_r(curl_getinfo($curl));
curl_close($curl);