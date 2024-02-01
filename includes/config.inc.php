<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "ims";

// for hostinger
// $servername = "localhost";
// $username = "u981678995_root";
// $password = "Ilovedevconkids1";
// $database = "u981678995_ims";
try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);

    // Set PDO to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // TIMEZONE IS SET FOR MANILA PH
    date_default_timezone_set("Asia/Manila");
    $now = date("Y-m-d H:i:s");
} catch (PDOException $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}