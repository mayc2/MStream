<?php

session_start();

require_once('mysql.php');

$ip = $_COOKIE['ip'];
$sourceAccount = $_COOKIE['sourceAccount'];
$source = $_COOKIE['source'];

$itemName = urldecode($_GET['itemName']);
$location = urldecode($_GET['location']);

$currVotes = getVotes($conn,$itemName,$location);

if($_GET['val']=='up')
  $newVotes = $currVotes+1;
else
  $newVotes = $currVotes-1;

$time = time();

$conn->query("UPDATE songvotes SET votes='".$newVotes."', last_vote='".$time."' WHERE itemName='".$conn->real_escape_string($itemName)."' AND location='".$conn->real_escape_string($location)."'");

header("Location: submitip.php");



