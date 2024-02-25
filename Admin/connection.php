<?php
$dbhost="localhost";
  $dbUsername="root";
  $dbpassword="";
  $dbName="Bus";

  $conn= new mysqli($dbhost,$dbUsername,$dbpassword,$dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

