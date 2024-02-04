<?php

include_once 'config.inc.php';

if (isset($_POST['fromDate'])) {

    try {
        $fromDate = $_POST['fromDate'];
        $toDate = $_POST['toDate'];
        //$sesChapt = $_POST['chapter'];


        $sql = "SELECT a.audit_id, CONCAT(u.user_firstname, ' ', u.user_lastname) AS user_name, a.* FROM `audit` AS a INNER JOIN users AS u ON u.user_id = a.audit_user_id WHERE audit_time  BETWEEN :fromDate AND :toDate ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":fromDate", $fromDate);
        $stmt->bindParam(":toDate", $toDate);
        $stmt->execute();
        // Fetch the results
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '
    <thead>
        <tr>
            <th scope="col">Audit ID</th>
            <th scope="col">User Name</th>
            <th scope="col">Action</th>
            <th scope="col">Date & Time</th>
          
        </tr>
    </thead>
    <tbody>';

        foreach ($result as $row) {
            echo '<tr>
        <td>' . $row['audit_id'] . '</td>
        <td>' . $row['user_name']. '</td>
        <td>' . $row['audit _action'] . '</td>
        <td>' . $row['audit_time'] . '</td>
    </tr>';
        }
        echo '</tbody>';
    } catch (\Throwable $th) {
        throw $th;
    }
}
