<?php
include '../includes/config.inc.php';

if (isset($_POST['userId'])) {
    $itemid = $_POST['itemId'];
    $userid = $_POST['userId'];
    $feedback = $_POST['feedback'];

    $query = "INSERT INTO `item_feedback`(`item_id`, `user_id`, `feedback`) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(1, $itemid, PDO::PARAM_INT);
    $stmt->bindParam(2, $userid, PDO::PARAM_INT);
    $stmt->bindParam(3, $feedback, PDO::PARAM_STR);

    $stmt->execute();

    echo "Record inserted successfully!";
} else {
    echo 'error';
}
?>