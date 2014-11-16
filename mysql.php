<?php
$servername = "localhost";
$username = "mstream";
$password = "mstream";
$db = "mstream";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

function getVotes($conn, $itemName,$location) {
  $rs = $conn->query("SELECT votes, last_vote FROM songvotes WHERE location='".$conn->real_escape_string($location)."' AND itemName='".$conn->real_escape_string($itemName)."'");

  if($rs->num_rows > 0) {
    while($row = $rs->fetch_assoc()) {
      return $row['votes'];
    }
  } else {
    $conn->query("INSERT INTO songvotes(location,itemName,votes,last_vote) VALUES('".$conn->real_escape_string($location)."', '".$conn->real_escape_string($itemName)."', '0', '0')");
    return 0;
  }

}