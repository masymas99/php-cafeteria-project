<?php

$servername = "localhost";
$UserName = "root"; 
$Password = "";
$dbname = "storedb";
$port = "8005";



$conn = new mysqli($servername,$UserName,$Password,$dbname,$port);

if ($conn->connect_error) {
    die("No Connection " . $conn->connect_error);
}
?>

