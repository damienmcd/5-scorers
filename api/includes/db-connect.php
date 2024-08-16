<?php
header('Access-Control-Allow-Origin: *');

$username = "";
$password = "";
$hostname = "";
$dbname = "";

if ($_SERVER['SERVER_NAME'] === '5scorers') {
    $username = "root";
    $password = "root";
    $hostname = "localhost";
    $dbname = "db1633306_5scorers";
} else {
    $username = "u1633306_avfc";
    $password = "QfGq5AjH1";
    $hostname = "mysql4387int.cp.blacknight.com";
    $dbname = "db1633306_5scorers";
}


$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "DB connection failed";
    die("Connection failed: " . $conn->connect_error);
}
