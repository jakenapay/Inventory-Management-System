<?php
include 'config.inc.php';
session_start();

try {
    $admin = $_SESSION['EM'];
    $logMessage = "logged out";
    $query = "INSERT INTO `logs`(`log_user_email`, `log_action`) VALUES (:loguser,:logaction)";
    $res = $pdo->prepare($query);
    // Bind values to the placeholders
    $res->bindParam(":loguser", $admin);
    $res->bindParam(":logaction", $logMessage);
    $res->execute();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    header("location: ../home.php?m=" . $e->getMessage() . ""); // Failed
}


session_unset();
session_destroy();

header("location: ../index.php");
exit();
