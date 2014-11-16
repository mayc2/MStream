<?php

require_once('mysql.php');

$term = $conn->real_escape_string($_GET['term']);
$query = $conn->query("SELECT itemName, location FROM songvotes WHERE itemName LIKE '%".$term."%'");

$results = array();
while($row = $query->fetch_assoc()) {
  $result['itemName'] = $row['itemName'];
  $result['location'] = $row['location'];
  $result['label'] = $row['itemName'];
  $result['value'] = $row['location'];
  $results[] = $result;
}

header("Content-type: application/json");
echo json_encode($results);
