<?php
session_start();
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="icon" type="image/png" href="favicon.png">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<link rel="stylesheet" href="/bootstrap/bootstrap.min.css" />
<link rel="stylesheet" href="/style.css" />
<script src="/jquery.min.js"></script>
<script src="/jquery-ui.min.js"></script>
<script src="/mstream.js"></script>

<title>MStream</title>
</head>
<body>

<div id="header"><img src="banner1.png"> <div id="search"><?php include('search.php'); ?></div></div>

<?php
require_once('mysql.php');

$ip = $_COOKIE['ip'];
$sourceAccount = $_COOKIE['sourceAccount'];
$source = $_COOKIE['source'];

$curl = curl_init();
curl_setopt_array($curl, array(
			       CURLOPT_URL => 'http://'.$_COOKIE['ip'].':8090/now_playing',
			       CURLOPT_HEADER => 0,
			       CURLOPT_RETURNTRANSFER => 1
			       ));
$resp = curl_exec($curl);
$data = new SimpleXMLElement($resp);?>

<div id="left">
<br />Now Playing:
<h3 id="nowPlayingInfo"><?php echo $data->artist; ?> - <?php echo $data->track; ?></h3>
<a href="/boo.php"><h4>BOO! (<?php echo getBoo($conn); ?>)</h4></a><br />

<b>Next 25 Songs:</b><br />
<?php

$popularSongs = getPopularSongs($conn);
while($row = $popularSongs->fetch_assoc()) {
  echo '[ <a href="/vote.php?val=up&itemName='.urlencode($row['itemName']).'&location='.urlencode($row['location']).'">UP</a> / <a href="/vote.php?val=down&itemName='.urlencode($row['itemName']).'&location='.urlencode($row['location']).'">DOWN</a> ] (' . $row['votes'] . ') '.$row['itemName'] . '<br />';
}

?>

</div>
<div id="right">

<?php
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
  //echo urldecode($item->name);
  if(strcmp($item->type, "track") == 0){
  	  //echo $item->itemName;
    $itemName = (string)$item->ContentItem->itemName;
    $itemNameValue = urlencode($itemName);
    $location = (string)$item->ContentItem->attributes()->location;

    echo getVotes($conn,$itemName,$location).' [ <a href="vote.php?val=up&itemName='.$itemNameValue.'&location='.urlencode($location).'">+</a> / <a href="vote.php?val=down&itemName='.$itemNameValue.'&location='.urlencode($location).'">-</a> ] ';
    echo (string)$item->name.'<br />';
    echo '<form class="form-inline" method="post" action="playSong.php">
		<input type="hidden" name="itemName" value="'.$item->ContentItem->itemName.'">
		<input type="hidden" name="location" value="'.$item->ContentItem->attributes()->location.'">
		<input type="submit" class="form-control" value="Play Song"></form><br />';

  }
  else{

	  echo '<form class="form-inline" method="post" action="goToDir.php">
	<input type="hidden" name="itemName" value="'.urlencode($item->itemName).'">
	<input type="hidden" name="itemType" value="'.$item->type.'">
	<input type="hidden" name="contentLocation" value="'.$item->ContentItem->attributes()->location.'">
	<input type="hidden" name="contentIsPresentable" value="'.$item->ContentItem->attributes()->isPresentable.'">
	<input type="hidden" name="ContentItemName" value="'.$item->ContentItem->itemName.'">
	<input type="submit" class="form-control" value="Browse '.$item->name.'"></form><br />';
	  
	  //echo '<pre>'.print_r($item).'</pre>';
	}
}


//print_r(curl_getinfo($curl));
curl_close($curl);

?>
</div>