<?php

session_start();
$_SESSION['ip'] = $_POST['ipaddr'];
echo $_SESSION['ip'].'<br />';
/*
$response = http_get("http://".$_SESSION['ip']."/navigate",array('port'=>8090),$info);
print_r($info);
*/

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
			       CURLOPT_URL => 'http://'.$_SESSION['ip'].':8090/sources',
			       CURLOPT_HEADER => 0,
			       CURLOPT_RETURNTRANSFER=> 1
			       ));
// Send the request & save response to $resp
echo '<br /><pre>';
$resp = curl_exec($curl);
$data = new SimpleXMLElement($resp);

print_r($data);

echo '</pre>';

curl_close($curl);

//print_r(file_get_contents("http://128.113.222.71:8080/test.html"));