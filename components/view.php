<?php
include '../includes/config.inc.php';
function get_total_records($pdo) {
    $result = $pdo->query("SELECT COUNT(item_id) AS total FROM items");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

function get_records($pdo, $start, $limit) {
    $sql = "SELECT * FROM items LIMIT $start, $limit";
    $result = $pdo->query($sql);
    $records = array();

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $records[] = $row;
        }
    }
    return $records;
}
?>