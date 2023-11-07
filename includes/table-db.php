<?php
include '../includes/config.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tableData"])) {
    $tableData = $_POST["tableData"];
    $userChapter = $_POST["chapter"];

    foreach ($tableData as $rowData) {
        $item_id = $rowData[0];  // Assuming item ID is in the first column
        $quantity = $rowData[2]; // Assuming quantity is in the third column


        // TODO: fix this line of code Update the existing quantity to updated quantity 



        $query = $pdo->prepare("UPDATE items SET item_quantity = new_quantity WHERE item_id = $item_id");
        $query->bindParam(':itemID', $itemID, PDO::PARAM_INT); // Assuming item_id is an integer, adjust the data type accordingly
        $query->bindParam(':userChapter', $userChapter, PDO::PARAM_INT);
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

        // Check if the item already exists in the database
        // $sql = "SELECT * FROM items WHERE item_id = $item_id , item_chapter = $userChapter"; 
        // $result = $conn->query($sql);

        // if ($result->num_rows > 0) {
        //     // Data exists; update the quantity
        //     $row = $result->fetch_assoc();
        //     $new_quantity = $row["item_quantity"] + $quantity;

        //     $update_sql = "UPDATE items SET item_quantity = $new_quantity WHERE item_id = $item_id";
        //     $conn->query($update_sql);
        // } else {
        //     // Data doesn't exist; insert a new row
        //     $insert_sql = "INSERT INTO your_table_name (item_id, item_quantity) VALUES ($item_id, $quantity)";
        //     $conn->query($insert_sql);
        // }
    }
}
