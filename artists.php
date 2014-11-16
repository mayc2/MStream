<?php

session_start();

$ip = $_SESSION['ip'];
$sourceAccount = $_SESSION['sourceAccount'];
$source = $_SESSION['source'];

$artistName = urldecode($_POST['artistName']);
$offset = urldecode($_POST['offset']);
$mediaItemContainerLocation = urldecode($_POST['mediaItemContainerLocation']);
$mIsPresetable = urldecode($_POST['mIsPresetable']);
$location = urldecode($_POST['location']);
$isPresetable = urldecode($_POST['isPresetable']);
$itemName = urldecode($_POST['itemName']);

$xml_data = '<navigate source="'.$source.'" sourceAccount="'.$sourceAccount.'">
<numItems>20</numItems>
<item Playable="1">
	<name>'.$artistName.'</name>
	<type>dir</type>
	<ContentItem source="'.$source.'" location="'.$location.'" sourceAccount="'.$sourceAccount.'" isPresetable="'.$isPresetable.'">
		<itemName>"'.$itemName.'"</itemName>
	</ContentItem>
</item></navigate>';

$xml_response = '<mediaItemContainer offset="'.$offset.'">
		<ContentItem source="'.$source.'" location="'.$mediaItemContainerLocation.'" sourceAccount="'.$sourceAccount.'" isPresetable="'.$mIsPresetable.'">
			<itemName>Album Artists</itemName>
		</ContentItem>
	</mediaItemContainer>';

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

echo '<pre>'.$data.'</pre>';


foreach($data->items->item as $item) {
  echo $item->name;
  echo '<form method="post" action="playSong.php">
<input type="hidden" name="name" value="'.urldecode($item->name).'">
<input type="hidden" name="offset" value="'.urlencode($item->mediaItemContainer->attributes()->offset).'">
<input type="hidden" name="mediaItemContainerLocation" value="'.urlencode($item->mediaItemContainer->ContentItem->attributes()->location).'">
<input type="hidden" name="mIsPresetable" value="'.urlencode($item->mediaItemContainer->ContentItem->attributes()->isPresetable).'">
<input type="hidden" name="mItemName" value="'.urlencode($item->mediaItemContainer->ContentItem->itemName).'"
<input type="hidden" name="location" value="'.urlencode($item->ContentItem->attributes()->location).'">
<input type="hidden" name="isPresetable" value="'.urlencode($item->ContentItem->attributes()->isPresetable).'">
<input type="hidden" name="itemName" value="'.urlencode($item->ContentItem->itemName).'">
<input type="submit" value="Play Song"></form><br />';
  echo '<pre>'.print_r($item).'</pre>';
}

curl_close($curl);