<?php

session_start();
$_SESSION['ip'] = $_POST['ipaddr'];

$response = http_get("http://".$_SESSION['ip'].":8090/navigate");
print_r($response);