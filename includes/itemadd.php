<?php
include '../includes/config.inc.php';

if (isset($_POST['datas'])) {

    $itemID = $_POST['datas'];
    $query = $pdo->prepare("SELECT * FROM items WHERE item_id = :itemID");
    $query->bindParam(':itemID', $itemID, PDO::PARAM_INT); // Assuming item_id is an integer, adjust the data type accordingly

    if ($query->execute()) {
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Return the result as JSON
            echo json_encode($item);
        } else {
            // Handle the case where the item with the provided item_id was not found
            echo json_encode(['error' => 'Item not found']);
        }
    } else {
        // Handle any database query execution errors
        echo json_encode(['error' => 'Database error']);
    }
}
