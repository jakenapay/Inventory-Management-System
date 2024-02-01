<?php
include '../includes/config.inc.php';
if (isset($_POST['selectedValue'])) {
    $selectedContainer = $_POST['selectedValue'];
    $chapter = $_POST['chapter'];

    if ($selectedContainer == "all") {

        $query = "SELECT * FROM items WHERE item_chapter = :chapter ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":chapter", $chapter, PDO::PARAM_STR);
        // Execute the statement
        $stmt->execute();
        // Fetch the results
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through the results (if needed)
        echo '
        <thead>
            <tr>
                <th scope="col">UUID</th>
                <th scope="col">Item Name</th>
                <th scope="col">Item Quantity</th>
                <th scope="col">Item Status</th>
                <th scope="col">Item Location</th>
                <th scope="col">Item Chapter</th>
                <th scope="col">Item Cost</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($result as $row) {
            echo '<tr>
            <td>' . $row['unique_item_id'] . '</td>
            <td>' . $row['item_name'] . '</td>
            <td>' . $row['item_quantity'] . '</td>
            <td>' . $row['item_status'] . '</td>
            <td>' . $row['item_location'] . '</td>
            <td>' . $row['item_chapter'] . '</td>
            <td>' . $row['item_cost'] . '</td>
        </tr>';
        }
        echo '</tbody>';
    } else {

        $query = "SELECT * FROM items WHERE item_location = :container AND item_chapter = :chapter";
        $stmt = $pdo->prepare($query);

        // Bind the parameter
        $stmt->bindParam(":container", $selectedContainer, PDO::PARAM_STR);
        $stmt->bindParam(":chapter", $chapter, PDO::PARAM_STR);
        // Execute the statement
        $stmt->execute();

        // Fetch the results
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through the results (if needed)
        echo '
        <thead>
            <tr>
                <th scope="col">UUID</th>
                <th scope="col">Item Name</th>
                <th scope="col">Item Quantity</th>
                <th scope="col">Item Status</th>
                <th scope="col">Item Location</th>
                <th scope="col">Item Chapter</th>
                <th scope="col">Item Cost</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($result as $row) {
            echo '<tr>
            <td>' . $row['unique_item_id'] . '</td>
            <td>' . $row['item_name'] . '</td>
            <td>' . $row['item_quantity'] . '</td>
            <td>' . $row['item_status'] . '</td>
            <td>' . $row['item_location'] . '</td>
            <td>' . $row['item_chapter'] . '</td>
            <td>' . $row['item_cost'] . '</td>
        </tr>';
        }
        echo '</tbody>';
    }
}
