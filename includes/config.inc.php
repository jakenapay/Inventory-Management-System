<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "ims";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// TIMEZONE IS SET FOR MANILA PH
date_default_timezone_set("Asia/Manila");
$now = date("Y-m-d H:i:s");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
