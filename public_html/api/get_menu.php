<?php
// SHOW ERRORS for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ALLOW CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'db_connect.php';

// Check connection again inside here
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database Connection Failed: " . $conn->connect_error]);
    exit();
}

$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);

$menu = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menu[] = $row;
    }
} else {
    // If no menu items, return an empty list, not an error
    $menu = []; 
}

echo json_encode($menu);
$conn->close();
?>