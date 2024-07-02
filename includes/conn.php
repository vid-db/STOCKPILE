<?php

$host =  "localhost";
$username = "root";
$pass = "";
$db = "smpc";


$conn = mysqli_connect($host, $username, $pass, $db);

if (!$conn) {
  die('Connection Failed' . mysqli_connect_error());
}
