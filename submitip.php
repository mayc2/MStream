<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$xml_data = '<navigate source="'.$source.'" sourceAccount="'.$sourceAccount.'"><item><name>All Music</name><type>dir</type>
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

foreach($data->items->item as $item) {
  echo $item->name;
}


//print_r(curl_getinfo($curl));
curl_close($curl);

//print_r(file_get_contents("http://128.113.222.71:8080/test.html"));