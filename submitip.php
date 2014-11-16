<?php
session_start();
?>

<html>
<head>
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
$_SESSION['deviceID'] = $data->attributes()->deviceID;

$curl = curl_init();
curl_setopt_array($curl, array(
			       CURLOPT_URL => 'http://'.$_SESSION['ip'].':8090/now_playing',
			       CURLOPT_HEADER => 0,
			       CURLOPT_RETURNTRANSFER => 1
			       ));
$resp = curl_exec($curl);
$data = new SimpleXMLElement($resp);?>

<br />Now Playing:
<h3><?php echo $data->artist; ?> - <?php echo $data->track; ?></h3>

<?php
$xml_data = '<navigate source="'.$source.'" sourceAccount="'.$sourceAccount.'">
<numItems>20</numItems>
<item><name>All Music</name><type>dir</type>
<ContentItem source="'.$source.'" sourceAccount="'.$sourceAccount.'" location="4">
<itemName>Music</itemName>
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

/*
foreach($data->items->item as $item) {
  $itemName = (string)$item->ContentItem->itemName;
  $itemNameValue = urlencode($itemName);
  $location = (string)$item->ContentItem->attributes()->location;
  echo (string)$item->name;

  echo '<form style="display:inline" method="post" action="playSong.php">
<input type="hidden" name="itemName" value="'.$itemNameValue.'">
<input type="hidden" name="location" value="'.$location.'">
<input type="submit" value="Play Song"></form><br />';

  echo getVotes($conn,$itemName,$location).' [ <a href="vote.php?val=up&itemName='.$itemNameValue.'&location='.urlencode($location).'">+</a> / <a href="vote.php?val=down&itemName='.$itemNameValue.'&location='.urlencode($location).'">-</a> ]<br /><br />';
}
*/
curl_close($curl);

?>
<div id="results"></div>
</body>
</html>