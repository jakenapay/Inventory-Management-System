<?php
include '../includes/config.inc.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tableData"])) {
    $tableData = $_POST["tableData"];
    //$userChapter = $_POST["chapter"];

    foreach ($tableData as $rowData) {
        $item_id = $rowData[0];  // Assuming item ID is in the first column
        $quantity = $rowData[4]; // Assuming quantity is in the third column

        // TODO: fix this line of code Update the existing quantity to updated quantity 

        // Prepare the update query
        try {
            //print_r($rowData);
            $query = $pdo->prepare("UPDATE items SET item_quantity = :new_quantity WHERE unique_item_id = :itemID");

            // Bind parameters
            $query->bindParam(':itemID', $item_id, PDO::PARAM_INT);
            $query->bindParam(':new_quantity', $quantity, PDO::PARAM_INT); // Assuming item_quantity is an integer, adjust the data type accordingly

            // Execute the query
            $query->execute();

            // Check if any rows were affected
            if ($query->rowCount() > 0) {
                // Data exists; update the quantity

                // Fetch the current quantity
                $select_sql = "SELECT item_quantity FROM items WHERE unique_item_id = :itemID";
                $select_query = $pdo->prepare($select_sql);
                $select_query->bindParam(':itemID', $item_id, PDO::PARAM_INT);
                $select_query->execute();

                $row = $select_query->fetch(PDO::FETCH_ASSOC);
                $current_quantity = $row["item_quantity"];

                // Calculate the new quantity
                $new_quantity = $current_quantity + $quantity;

                // Update the quantity
                $update_sql = "UPDATE items SET item_quantity = :new_quantity WHERE unique_item_id = :itemID";
                $update_query = $pdo->prepare($update_sql);
                $update_query->bindParam(':itemID', $item_id, PDO::PARAM_INT);
                $update_query->bindParam(':new_quantity', $quantity, PDO::PARAM_INT);
                $update_query->execute();

                try {

                    $admin = $_SESSION['ID'];
                    $auditMessage = "item restock";
                    $query = "INSERT INTO `audit`(`audit_user_id`, `audit _action`) VALUES (:audituser,:auditaction)";
                    $res = $pdo->prepare($query);
                    // Bind values to the placeholders
                    $res->bindParam(":audituser", $admin);
                    $res->bindParam(":auditaction", $auditMessage);
                    $res->execute();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    header("location: ../items.php?m=" . $e->getMessage() . ""); // Failed
                }

                // Send a response to the client
                echo "Quantity updated successfully.";
            } else {
                // No rows were affected, item not found
                echo "Item not found or no update needed.";
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
