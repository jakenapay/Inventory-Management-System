<?php
include './config.inc.php';

if (isset($_POST["userid"])) {

    $user_id = $_POST["userid"];
    $item_id = $_POST["itemid"];
    $itemQuan = $_POST["itemquan"];


    // ? Prepare the SQL query
    $query = "INSERT INTO `cart` (`user_id`, `item_id` , `itemQuan`) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);

    // Check if the prepared statement is valid
    if ($stmt === false) {
        throw new Exception("Error preparing the statement: " . print_r($pdo->errorInfo(), true));
    }

    // Bind and execute the statement
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $item_id, PDO::PARAM_INT);
    $stmt->bindParam(3, $itemQuan, PDO::PARAM_INT);
    $stmt->execute();

    echo "Record inserted successfully.";
}
