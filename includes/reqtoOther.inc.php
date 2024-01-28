<?php
include '../includes/config.inc.php';

try {
    if (isset($_POST['userId'])) {
        $itemid = (int)$_POST['itemId'];
        $userid = (int)$_POST['userId'];
        $itemQuan = (int)$_POST['itemQuan'];
        $toChapterId = $_POST['toChapterId'];
        $fromChapterId = $_POST['fromChapterId'];
        
        $query = "INSERT INTO `ctochistory` (`history_item_id`, `history_quantity`, `history_user_id`, `history_status`, `history_isReturn`, `history_due_date`, `from_chapter`, `to_chapter`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($query);
        
        // Get the current date
        $currentDateTime = date('Y-m-d');
        // Set the start date
        $start_date = new DateTime($currentDateTime); // replace with your actual start date
        // Add 7 days to the start date
        $due_date = $start_date->modify('+7 days');
        // Format the due date as a string
        $due_date_str = $due_date->format('Y-m-d');
        
        $itemStatus = "process";
        $returnStatus = 0;
        
        $stmt->bindParam(1, $itemid, PDO::PARAM_INT);
        $stmt->bindParam(2, $itemQuan, PDO::PARAM_INT);
        $stmt->bindParam(3, $userid, PDO::PARAM_INT);
        $stmt->bindParam(4, $itemStatus, PDO::PARAM_STR);
        $stmt->bindParam(5, $returnStatus, PDO::PARAM_INT);
        $stmt->bindParam(6, $due_date_str, PDO::PARAM_STR);
        $stmt->bindParam(7, $fromChapterId, PDO::PARAM_STR);
        $stmt->bindParam(8, $toChapterId, PDO::PARAM_STR);
        
        $stmt->execute();

        echo " Requested Successfully!";
    } else {
        echo 'error';
    }
} catch (\Throwable $th) {
    throw $th;
}
?>