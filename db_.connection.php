<?php


$servername = "localhost";
$username = "UTS SIG";
$password = "";
$database = "UTS SIG";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
}
?>