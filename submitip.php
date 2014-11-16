<?php
session_start();

if(!isset($_SESSION['ip']))
  $_SESSION['ip'] = $_POST['ipaddr'];
echo $_SESSION['ip'].'<br />';

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
			       CURLOPT_URL => 'http://'.$_SESSION['ip'].':8090/sources',
			       CURLOPT_HEADER => 0,
			       CURLOPT_RETURNTRANSFER=> 1
			       ));

$resp = curl_exec($curl);
$data = new SimpleXMLElement($resp);


$sourceAccount = (string)$data->sourceItem[2]->attributes()->sourceAccount;
$source = (string)$data->sourceItem[2]->attributes()->source;
$_SESSION['source'] = $source;
$_SESSION['sourceAccount'] = $sourceAccount;

$xml_data = '<navigate source="'.$source.'" sourceAccount="'.$sourceAccount.'">
</navigate>';

$curl = curl_init();
curl_setopt_array($curl,
		  array(CURLOPT_URL => 'http://'.$_SESSION['ip'].':8090/navigate',
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
  echo '<form method="post" action="goToDir.php">
<input type="hidden" name="itemName" value="'.urlencode($item->itemName).'">
<input type="hidden" name="itemType" value="'.$item->type.'">
<input type="hidden" name="contentLocation" value="'.$item->ContentItem->attributes()->location.'">
<input type="hidden" name="contentIsPresentable" value="'.$item->ContentItem->attributes()->isPresentable.'">
<input type="hidden" name="ContentItemName" value="'.$item->ContentItem->itemName.'">
<input type="submit" value="Enter '.$item->name.'"></form><br />';
  echo '<pre>'.print_r($item).'</pre>';
}

	
//print_r(curl_getinfo($curl));
curl_close($curl);

//print_r(file_get_contents("http://128.113.222.71:8080/test.html"));