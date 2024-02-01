<?php
if (isset($_POST['add-category-btn'])) {

    include 'config.inc.php';
    // Get the data
    $categoryName = $_POST['items_category_name'];

    // If empty then go back
    if (empty($categoryName)) {
        header("location: ../items.php#itemcateg?m=ic");
        exit();
    }

    try {
        // Your SQL query with placeholders
        $sql = "INSERT INTO `items_category` (`item_category_id`, `item_category_name`) VALUES (NULL, :name)";
        // Prepare the statement
        $stmt = $pdo->prepare($sql);
        // Bind parameters
        $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Redirect after successful insertion
        header("location: ../items.php#itemcateg?m=ca"); // Chapter Added Succesfully
        exit();
    } catch (PDOException $e) {
        // Handle the exception
        echo "Error: " . $e->getMessage();
        header("location: ../items.php#itemcateg?m=error");
        exit();
    }
} else if (isset($_POST['edit_category_view'])) {
    include 'config.inc.php';
    // Get data
    $categoryId = $_POST['category_id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM items_category WHERE item_category_id = :id");
        $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $categoryId = $row['item_category_id'];
            $categoryName = $row['item_category_name'];

            echo '
        <div class="col-12 col-md-12 col-lg-12">
            <div class="py-1">
                <input type="hidden" class="form-control form-control-sm text-capitalize" id="item_category_id" name="item_category_id" placeholder="Chapter ID"  value="' . $categoryId . '" required>
                <label for="item_category_name">Item Category Name</label>
                <input type="text" class="form-control form-control-sm text-capitalize" id="item_category_name" name="item_category_name" placeholder="Chapter Name"  value="' . $categoryName . '" required>
            </div>
        </div>
        ';
        }
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage();
    }
} else if (isset($_POST['save-edit-category-btn'])) {
    include 'config.inc.php';

    $categoryId = $_POST['item_category_id'];
    $categoryName = $_POST['item_category_name'];

    $sql = "UPDATE items_category SET item_category_name = :name WHERE item_category_id = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $categoryName, PDO::PARAM_STR);
        $stmt->execute();
        header("location: ../items.php#itemcateg?m=us"); // Updated successfully
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        header("location: ../items.php#itemcateg?m=" . $e->getMessage() . ""); // Failed
        exit();
    }
} else {
    header("location: ../items.php#itemcateg?m=404");
    exit();
}
