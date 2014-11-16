<?php
session_start();

$curl = curl_init();
curl_setopt_array($curl, array(
			       CURLOPT_URL => 'http://'.$_COOKIE['ip'].':8090/now_playing',
			       CURLOPT_HEADER => 0,
			       CURLOPT_RETURNTRANSFER => 1
			       ));
$resp = curl_exec($curl);
$data = new SimpleXMLElement($resp);

if(isset($data->artist)) {
  $result['artist'] = $data->artist;
  $result['track'] = $data->track;
} else {
  $result['artist'] = ' ';
  $result['track'] = ' ';
}

$result['xml'] = $data->asXML();

header("Content-type: application/json");
echo json_encode($result);
exit;