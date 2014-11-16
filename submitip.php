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

/*
$postFields = 'source='.urlencode($source).'&sourceAccount='.urlencode($sourceAccount);
$fields = array('source'=>$source,
		'sourceAccount'=>$sourceAccount);


$fields = http_build_query($fields);


$xml_data = '<navigate source="'.$source.'" sourceAccount="'.$sourceAccount.'"></navigate>';

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

$xml_data = '<navigate source="'.$source.'" sourceAccount="'.$sourceAccount.'">
'.$data->items->item->asXML().'
</navigate>';
*/

$xml_data = '<navigate source="'.$source.'" sourceAccount="'.$sourceAccount.'">
<numItems>20</numItems>
<item Playable="1">
<name>Album Artists</name>
<type>dir</type>
<mediaItemContainer offset="0">
<ContentItem source="'.$source.'" location="1" sourceAccount="'.$sourceAccount.'" isPresetable="true">
<itemName>Music</itemName>
</ContentItem>
</mediaItemContainer>
<ContentItem source="'.$source.'" location="107" sourceAccount="'.$sourceAccount.'" isPresetable="true">
<itemName>Album Artists</itemName>
</ContentItem>
</item></navigate>';


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
  echo $item->name;
  echo '<form method="post" action="artists.php">
<input type="hidden" name="artistName" value="'.urldecode($item->name).'">
<input type="hidden" name="offset" value="'.urlencode($item->mediaItemContainer->attributes()->offset).'">
<input type="hidden" name="mediaItemContainerLocation" value="'.urlencode($item->mediaItemContainer->attributes()->location).'">
<input type="hidden" name="mIsPresetable" value="'.urlencode($item->mediaItemContainer->attributes()->isPresetable).'">
<input type="hidden" name="location" value="'.urlencode($item->ContentItem->attributes()->location).'">
<input type="hidden" name="isPresetable" value="'.urlencode($item->ContentItem->attributes()->isPresetable).'">
<input type="hidden" name="itemName" value="'.urlencode($item->ContentItem->itemName).'">
<input type="submit" value="Select Artist"></form><br />';
  echo '<pre>'.print_r($item).'</pre>';
}

	
//print_r(curl_getinfo($curl));
curl_close($curl);

//print_r(file_get_contents("http://128.113.222.71:8080/test.html"));