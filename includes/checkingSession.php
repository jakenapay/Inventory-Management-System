<?php
include 'config.inc.php';
$sql = "SELECT * FROM users WHERE user_email=:email LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$session_name = ($row['user_category'] == 1) ? 'admin_session' : 'user_session';
session_name($session_name);

echo 'Session Name: ' . session_name();