<?php
include './config.inc.php';

if (isset($_POST["Submit"])) {

    $user_id = $_POST["user_id"];
    $item_id = $_POST["item_id"];


    // $query = "INSERT INTO `cart`(`user_id`, `item_id`) VALUES ('?','?')";
    // $stmt = $pdo->prepare($query);

    // $stmt->execute(array($user_id, $item_id));

    // if($stmt == true){
    //     echo "data inserted";
    // }
    // echo "this is user id :" . $user_id . "&" . "this is item_id: " . $item_id;

    // ? Prepare the SQL query
    $query = "INSERT INTO `cart` (`user_id`, `item_id`) VALUES (?, ?)";
    $stmt = $pdo->prepare($query);

    // Check if the prepared statement is valid
    if ($stmt === false) {
        throw new Exception("Error preparing the statement: " . print_r($pdo->errorInfo(), true));
    }

    // Bind and execute the statement
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $item_id, PDO::PARAM_INT);
    $stmt->execute();

    echo "Record inserted successfully.";
}
