<?php
include '../includes/config.inc.php';

try {
    if (isset($_POST['add-chapter-btn'])) {

        $container_name = $_POST['container_name'];
        $location_name = $_POST['location_name'];
        $chapter = $_POST['user_ch'];

        echo $container_name . '' . $location_name . '' . $chapter;

        $query = "INSERT INTO `item_location`(`location_name`, `container_name`, `chapter`) VALUES (?,?,?)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $location_name, PDO::PARAM_STR);
        $stmt->bindParam(2, $container_name, PDO::PARAM_STR);
        $stmt->bindParam(3, $chapter, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt === false) {
            throw new Exception("Error preparing the statement: " . print_r($pdo->errorInfo(), true));
        }
        header("Location: ../items.php#itemstrg");
    }
} catch (Throwable $th) {
    throw $th;
}
