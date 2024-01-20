<?php
include './config.inc.php';
if (isset($_POST['userID'])) {

    $item_id = $_POST['itemID'];
    $user_id = $_POST['userID'];
    $itemQ = $_POST['itemQ'];
    try {
        $query = "INSERT INTO `history`(`history_item_id`, `history_quantity`, `history_user_id`, `history_status` , `history_due_date`) VALUES (?,?,?,?,?)";
        $stmt = $pdo->prepare($query);

        $currentDateTime = date('Y-m-d');
        // Set the start date
        $start_date = new DateTime($currentDateTime); // replace with your actual start date
        // Add 7 days to the start date
        $due_date = $start_date->modify('+7 days');
        // Format the due date as a string
        $due_date_str = $due_date->format('Y-m-d');

        // Assuming $itemid, $itemq, $userid, and $item_id are defined elsewhere
        $stmt->bindParam(1, $item_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $itemQ, PDO::PARAM_INT);
        $stmt->bindParam(3, $user_id, PDO::PARAM_INT);
        $stmt->bindValue(4, "approved", PDO::PARAM_STR);
        $stmt->bindValue(5, $due_date_str, PDO::PARAM_STR);
        // Assuming $history_date is the date you want to insert
        // $history_date = date("Y-m-d H:i:s");
        // $stmt->bindParam(5, $history_date, PDO::PARAM_STR);

        $stmt->execute();

        $receiver = 'yugo2801@gmail.com';
        $subj = 'Item Check Out';
        $msg = 'item Check Out and request approved ';
        
        include './forEmail/sendEmail.php';


        // Additional code after successful execution, if needed
        echo "Record inserted successfully!";
    } catch (PDOException $e) {
        // Handle the error here
        echo "Error: " . $e->getMessage();
    }
}
