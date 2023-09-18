<?php
session_start();

if (isset($_POST['add-item-btn'])) {
    include 'config.inc.php';

    // Check if logged in
    if (empty($_SESSION['ID'])) {
        header("location: ../index.php?m=ln");
        exit(); 
    }

    $admin = $_POST['user_id'];
    $name = $_POST['item_name'];
    $category = $_POST['item_category'];
    $measure = $_POST['item_measure'];
    $quantity = $_POST['item_quantity'];
    $chapter = $_POST['item_chapter'];
    $description = $_POST['item_description'];

    $image = $_FILES['item_image']['name'];
    $tmp_img_name = $_FILES['item_image']['tmp_name'];
    $image_type = $_FILES['item_image']['type'];
    $image_size = $_FILES['item_image']['size'];
    $image_error = $_FILES['item_image']['error'];

    $allowed_ext = array('jpg', 'jpeg', 'png', 'pdf');
    $image_info = pathinfo($image);
    $image_ext = strtolower($image_info['extension']);

    if (empty($name) || empty($category) || empty($measure) || empty($quantity) || empty($chapter) || empty($description) || empty($image)) {
        header("location: ../items.php?m=ef");
        exit();
    }

    if (!in_array($image_ext, $allowed_ext)) {
        header("location: ../items.php?m=itd");
        exit();
    }

    if ($image_size > 2000000) {
        header("location: ../items.php?m=is");
        exit();
    }

    if ($image_error !== 0) {
        header("location: ../items.php?m=ie");
        exit();
    }
    
    $image_new_name = uniqid('', true) . "." . $image_ext;
    $image_final_name = 'IMG_' . $image_new_name;
    $folder = '../images/items/';
    move_uploaded_file($tmp_img_name, $folder . $image_final_name);

    $sql = "INSERT INTO `items` (`item_name`, `item_category`, `item_measure`, `item_quantity`, `item_chapter`, `item_description`, `item_image`) VALUES (:value1, :value2, :value3, :value4, :value5, :value6, :value7); ";
    $stmt = $pdo->prepare($sql);

    // Bind values to the placeholders
    $stmt->bindParam(':value1', $name);
    $stmt->bindParam(':value2', $category);
    $stmt->bindParam(':value3', $measure);
    $stmt->bindParam(':value4', $quantity);
    $stmt->bindParam(':value5', $chapter);
    $stmt->bindParam(':value6', $description);
    $stmt->bindParam(':value7', $image_final_name);

    try {
        $stmt->execute();
        header("location: ../items.php?m=ia"); // Record inserted successfully
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        header("location: ../items.php?m=".$e->getMessage().""); // Failed
        exit();
    }
    
}
?>
