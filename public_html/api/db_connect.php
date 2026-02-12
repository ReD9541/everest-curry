<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing Database Connection...<br>";

$host = "localhost"; 
$user = "u849450853_everest_admin";  
$pass = "9Q+bsS6E9O"; 
$dbname = "u849450853_everest_db";  

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "✅ SUCCESS! Connected to the database.";
$conn->set_charset("utf8mb4"); 
?>