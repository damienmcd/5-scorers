<?php
header('Access-Control-Allow-Origin: *');

$username = "root";
$password = "root";
$hostname = "localhost";
$dbname = "5scorers";

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "DB connection failed";
    die("Connection failed: " . $conn->connect_error);
}
