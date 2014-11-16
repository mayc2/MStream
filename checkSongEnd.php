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
  $secondsTotal = $data->time->attributes()->total % 60;
  $seconds = $data->time % 60;
  if($secondsTotal < 10)
    $secondsTotal = '0'.$secondsTotal;
  if($seconds < 10)
    $seconds = '0'.$seconds;
  $result['timeTotal'] = floor($data->time->attributes()->total / 60).':'.$secondsTotal;
  $result['time'] = floor($data->time / 60).':'.$seconds;
} else {
  $result['artist'] = ' ';
  $result['track'] = ' ';
  $result['timeTotal'] = '0:00';
  $result['time'] = '0:00';
}

$result['xml'] = $data->asXML();

header("Content-type: application/json");
echo json_encode($result);
exit;