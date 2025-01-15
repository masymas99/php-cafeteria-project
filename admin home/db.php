<?php 
$dbtype = "mysql";
$host = "localhost";
$dbname = "storedb";
$userName = "root";
$password = "";
$port = "5500";

$connection = new PDO("$dbtype:host=$host;port=$port;dbname=$dbname", $userName, $password);
// <!-- var_dump($connetion);

?>