<?php
session_start();

// Adding new item
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
else if (isset($_POST['check_view'])) { // View existing item
    include 'config.inc.php';

    $itemId = $_POST['item_id']; // Item's specific ID
    // SQL query for selecting the specific item
    $stmt  = $pdo->prepare("SELECT items.item_id, 
    items.item_name, 
    items_category.item_category_name, 
    items_unit_of_measure.item_uom_name, 
    items.item_quantity, 
    chapters.chapter_name, 
    items.item_description, 
    items.item_image 
    FROM items 
    INNER JOIN items_category ON items.item_category = items_category.item_category_id 
    INNER JOIN items_unit_of_measure ON items.item_measure = items_unit_of_measure.item_uom_id
    INNER JOIN chapters ON items.item_chapter = chapters.chapter_id
    WHERE item_id = :param_value");
    $stmt->bindParam(':param_value', $itemId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop through the results
    foreach ($result as $row) {
        $id = $row['item_id'];
        $name = $row['item_name'];
        $category = $row['item_category_name'];
        $measure = $row['item_uom_name'];
        $quantity = $row['item_quantity'];
        $chapter = $row['chapter_name'];
        $description = $row['item_description'];
        $image = $row['item_image'];

        $return = '
        <div class="col-md-6 py-1">
            <div row>
                <input type="hidden" class="form-control form-control-sm text-capitalize" id="item_id" name="item_id" placeholder="item_id" readonly value="'.$id.'">
                <div class="col-md-12 py-1">
                    <label for="item_name">Name</label>
                    <input type="text" class="form-control form-control-sm text-capitalize" id="item_name" name="item_name" placeholder="Name" readonly value="'.$name.'">
                </div>
                <div class="col-md-12 py-1">
                    <label for="item_category">Category</label>
                    <input type="text" class="form-control form-control-sm text-capitalize" id="item_category" name="item_category" placeholder="Category" readonly value="'.$category.'">
                </div>
                <div class="col-md-12 py-1">
                    <label for="item_measure">Measurement</label>
                    <input type="text" class="form-control form-control-sm text-capitalize" id="item_measure" name="item_measure" placeholder="Measurement" readonly value="'.$measure.'">
                </div>
                <div class="col-md-12 py-1">
                    <label for="item_quantity">Quantity</label>
                    <input type="text" class="form-control form-control-sm text-capitalize" id="item_quantity" name="item_quantity" placeholder="Quantity" readonly value="'.$quantity.'">
                </div>
                <div class="col-md-12 py-1">
                    <label for="item_chapter">Chapter</label>
                    <input type="text" class="form-control form-control-sm text-capitalize" id="item_chapter" name="item_chapter" placeholder="Chapter" readonly value="'.$chapter.'">
                </div>
                <div class="col-md-12 py-1">
                    <label for="item_description">Description</label>
                    <textarea name="item_description" id="item_description" cols="3" rows="3" class="form-control form-control-sm" placeholder="Description" readonly>'.$description.'</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-6 py-1">
            <img src="images/items/'.$image.'" loading="lazy" class="img-thumbnail img-fluid" alt="Image">
        </div>
        ';

        echo $return;
    }
}
else if (isset($_POST['req-item-btn'])) { // For requesting of item
    include 'config.inc.php';
    
    $userId = $_POST['user_id'];
    $itemId = $_POST['item_id'];

    if (empty($userId) && empty($itemId)) {
        header("location: ../items.php?m=ic");
    }

    $reqQuantity = $_POST['req-quantity']; // The quantity of item that the user is about to request
    $currentQuantity = $_POST['item_quantity']; // The current or existing quantity of the item

    if (empty($reqQuantity) || empty($currentQuantity)) { // If both quantities are empty, go back to items.php
        header("location: ../items.php?m=nq");
    }

    // Computations
    // Requesting qty minus to the qty of item and store in a variable
    $leftQuantity = $currentQuantity - $reqQuantity; // Example: 20 = 30 - 10;
    if ($leftQuantity < 0) { // If insufficient quantity, for example: -1 (leftQuantity)= 30(currentQuantity) - 31(reqQuantity)
        header("location: ../items.php?m=ifs");
        exit();
    }

    try {

        // Prepare the SQL statement with placeholders
        // Inserting into history table with a status of PENDING, this will show in REQUESTS page
        $sql = "INSERT INTO history(`history_item_id`, `history_quantity`, `history_user_id`, `history_status`, `history_date`) VALUES (:itemID, :quantity, :userID, 'pending', NOW())";
    
        // Prepare the statement
        $stmt = $pdo->prepare($sql);
    
        // Bind parameters to placeholders
        $stmt->bindParam(':itemID', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $reqQuantity, PDO::PARAM_INT);
        $stmt->bindParam(':userID', $userId, PDO::PARAM_INT);
    
        // Execute the prepared statement
        if ($stmt->execute()) {
            header("location: ../items.php?m=ss");
            exit();
        } else {
            echo "<script>alert('Error requesting product.');window.location.replace('../items.php');</script>";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
else if (isset($_POST['edit_view'])) { // For editing of item
    include 'config.inc.php';

    $itemId = $_POST['item_id']; // Specific item's ID

    // SQL query for selecting the item
    $stmt  = $pdo->prepare("SELECT items.item_id, 
    items.item_name, 
    items_category.item_category_name, 
    items_unit_of_measure.item_uom_name, 
    items.item_quantity, 
    chapters.chapter_name, 
    items.item_description, 
    items.item_image 
    FROM items 
    INNER JOIN items_category ON items.item_category = items_category.item_category_id 
    INNER JOIN items_unit_of_measure ON items.item_measure = items_unit_of_measure.item_uom_id
    INNER JOIN chapters ON items.item_chapter = chapters.chapter_id
    WHERE item_id = :param_value");
    $stmt->bindParam(':param_value', $itemId, PDO::PARAM_INT); // Assign the variable to the stmt
    $stmt->execute(); // Execute SQL query
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop through the results
    foreach ($result as $row) {
        $id = $row['item_id'];
        $name = $row['item_name'];
        $category = $row['item_category_name'];
        $measure = $row['item_uom_name'];
        $quantity = $row['item_quantity'];
        $chapter = $row['chapter_name'];
        $description = $row['item_description'];
        $image = $row['item_image'];

        // Get the id of measurement and chapter for their value in the select elements
        // THIS IS FOR THE SELECT ELEMENT IN THE EDIT MODAL
        try{
            $sql = "SELECT * FROM items_unit_of_measure WHERE item_uom_name = :uom_name";
            // Prepare the query
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':uom_name', $measure, PDO::PARAM_STR);
            $stmt->execute();

            // Fetch all rows as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                                                
            // Process the result (e.g., display it)
            foreach ($result as $row) {
                // Access columns by their names, e.g., $row['column_name']
                $uom_value = $row['item_uom_id'];
            }
        } catch (PDOException $e) {
            // Handle database connection or query errors
            echo "Error: " . $e->getMessage();
        }

         // Get the id of ITEM CATEGORY for their value in the select elements
        // THIS IS FOR THE SELECT ELEMENT IN THE EDIT MODAL
        try{
            $sql = "SELECT * FROM items_category WHERE item_category_name = :category_name";
            // Prepare the query
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':category_name', $category, PDO::PARAM_STR);
            $stmt->execute();

            // Fetch all rows as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                                                
            // Process the result (e.g., display it)
            foreach ($result as $row) {
                // Access columns by their names, e.g., $row['column_name']
                $cat_value = $row['item_category_id'];
            }
        } catch (PDOException $e) {
            // Handle database connection or query errors
            echo "Error: " . $e->getMessage();
        }

         // Get the id of ITEM CHAPTER for their value in the select elements
        // THIS IS FOR THE SELECT ELEMENT IN THE EDIT MODAL
        try{
            $sql = "SELECT * FROM chapters WHERE chapter_name = :chapter_name";
            // Prepare the query
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':chapter_name', $chapter, PDO::PARAM_STR);
            $stmt->execute();

            // Fetch all rows as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                                                
            // Process the result (e.g., display it)
            foreach ($result as $row) {
                // Access columns by their names, e.g., $row['column_name']
                $chapt_value = $row['chapter_id'];
            }
        } catch (PDOException $e) {
            // Handle database connection or query errors
            echo "Error: " . $e->getMessage();
        }

        $return = '
        <div class="col-md-6 py-1">
            <div row>
                <input type="hidden" class="form-control form-control-sm text-capitalize" id="item_id" name="item_id" placeholder="item_id"  value="'.$id.'">
                <div class="col-md-12 py-1">
                    <label for="item_name">Name</label>
                    <input type="text" class="form-control form-control-sm text-capitalize" id="item_name" name="item_name" placeholder="Name"  value="'.$name.'">
                </div>
                <div class="col-md-12 py-1">
                    <label for="item_category">Category</label>
                    <select name="item_category" id="item_category" class="form-control form-control-sm" required>
                    <option value="'.$cat_value.'" selected>'. $category .' (Current)</option>';

                    // To show the other option of the measurement
                    try {
                        $sql = "SELECT * FROM items_category";
                        // Prepare the query
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        
                        // Fetch all rows as an associative array
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                        
                        // Process the result (e.g., display it)
                        foreach ($result as $row) {
                            // Access columns by their names
                            $return .= '<option value="' . $row["item_category_id"] . '">' . $row['item_category_name'] . '</option>';
                        }
                    } catch (PDOException $e) {
                        // Handle database connection or query errors
                        $return .= "Error: " . $e->getMessage();
                    }
                
               
                $return .= '
                    </select>
                    </div>
                    <div class="col-md-12 py-1">
                    <label for="item_measure">Measurement</label>
                    <select name="item_measure" id="item_measure" class="form-control form-control-sm" required>
                        <option value="'.$uom_value.'" selected>'. $measure .' (Current)</option>';
                        
                        // To show the other option of the measurement
                        try {
                            $sql = "SELECT * FROM items_unit_of_measure";
                            // Prepare the query
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            
                            // Fetch all rows as an associative array
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                            
                            // Process the result (e.g., display it)
                            foreach ($result as $row) {
                                // Access columns by their names
                                $return .= '<option value="' . $row["item_uom_id"] . '">' . $row['item_uom_name'] . '</option>';
                            }
                        } catch (PDOException $e) {
                            // Handle database connection or query errors
                            $return .= "Error: " . $e->getMessage();
                        }
                        
        $return .= '
                    </select>
                </div>
                <div class="col-md-12 py-1">
                    <label for="item_quantity">Quantity <a class="text-decoration-none" href="restock.php" target="" rel="noopener noreferrer">(Restock)  </a></label>
                    <input type="text" class="form-control form-control-sm text-capitalize" id="item_quantity" name="item_quantity" placeholder="Quantity" readonly value="'.$quantity.'">
                </div>';

                if (($_SESSION['CT'] === 1) && ($_SESSION['CH'] === 1)) {
                    $return .= '
                    <div class="col-md-12 py-1">
                        <label for="item_chapter">Chapter</label>
                        <select name="item_chapter" id="item_chapter" class="form-control form-control-sm" required>
                            <option value="'.$chapt_value.'" selected>'. $chapter .' (Current)</option>';

                        // To show the other option of the measurement
                        try {
                            $sql = "SELECT * FROM chapters";
                            // Prepare the query
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            
                            // Fetch all rows as an associative array
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
                            
                            // Process the result (e.g., display it)
                            foreach ($result as $row) {
                                // Access columns by their names
                                $return .= '<option value="' . $row["chapter_id"] . '">' . $row['chapter_name'] . '</option>';
                            }
                        } catch (PDOException $e) {
                            // Handle database connection or query errors
                            $return .= "Error: " . $e->getMessage();
                        }

                    
                }

                $return .= '
                    </select>
                </div>
                <div class="col-md-12 py-1">
                    <label for="item_description">Description</label>
                    <textarea name="item_description" id="item_description" cols="3" rows="3" class="form-control form-control-sm" placeholder="Description" >'.$description.'</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-6 py-1">';
        
        if ($image != "") {
            $return .= '<img src="images/items/'.$image.'" loading="lazy" class="d-flex justify-content-center img-thumbnail img-fluid" alt="Image" name="item_image">';
        } else {
            $return .= '<img src="" name="item_image" alt="No image found" class="d-flex justify-content-center img-thumbnail img-fluid">';
        }

        $return .= '
            <hr class="w-100">
            <label for="new_item_image">Upload new image</label>
            <input type="file" class="form-control-file" id="new_item_image" name="new_item_image" accept="image/png, image/gif, image/jpeg">    
        </div>';
        
        echo $return;
    }
}
else if (isset($_POST['save-edit-item-btn'])) { // For saving edit item btn
    include 'config.inc.php';

    // Item's information
    $userId = $_POST['user_id'];
    $itemId = $_POST['item_id'];
    $name = $_POST['item_name'];
    $category = $_POST['item_category'];
    $measure = $_POST['item_measure'];
    $chapter = $_POST['item_chapter'];
    $description = $_POST['item_description'];
    $imageName = $_FILES['new_item_image']['name'];
    $imgTmpName = $_FILES['new_item_image']['tmp_name'];
    $imgType = $_FILES['new_item_image']['type'];
    $imgSize = $_FILES['new_item_image']['size'];
    $imgError = $_FILES['new_item_image']['error'];
    $old_img = $_FILES['item_image']['name'];

    // Check if there's any empty variable
    if (empty($userId) || empty($itemId) || empty($name) || empty($category) || empty($measure) || empty($chapter) || empty($description)) {
        header("location: ../items.php?m=ic");
        exit();
    }

    // Check for image if valid
    if(isset($imageName) && $imageName != "") {
        // Seperate extension and filename
        $imageTmpExt = explode('.', $imageName);
        $imageExt = strtolower(end($imageTmpExt));

        $allowed_ext = array('jpg', 'jpeg', 'png', 'pdf');
        $image_info = pathinfo($imageName);
        $image_ext = strtolower($image_info['extension']);

        if (!in_array($image_ext, $allowed_ext)) {
            header("location: ../items.php?m=itd");
            exit();
        }
    
        if ($imgSize > 2000000) {
            header("location: ../items.php?m=is");
            exit();
        }
    
        if ($imgError !== 0) {
            header("location: ../items.php?m=ie");
            exit();
        }
    } else {
        $imageTmpExt = explode('.', $old_img);
        $imageExt = strtolower(end($imageTmpExt));

        $allowed_ext = array('jpg', 'jpeg', 'png', 'pdf');
        $image_info = pathinfo($imageName);
        $image_ext = strtolower($image_info['extension']);
    }

    // If all functions were passed then explode the image name and extension
    // Create a unique ID for the image
    // Upload the image to the folder
    $image_new_name = uniqid('', true) . "." . $image_ext;

    // Upload the image to folder of images
    $image_final_name = "IMG_" . $image_new_name;
    $folder = '../images/items/';
    move_uploaded_file($imgTmpName, $folder . $image_final_name);

    $sql = "UPDATE items SET item_name = :name, item_category = :category, item_measure = :measure, item_chapter = :chapter, item_description = :description, item_image = :image WHERE item_id = :itemId";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':itemId', $itemId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':measure', $measure);
        $stmt->bindParam(':chapter', $chapter);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image_final_name);
        $stmt->execute();

        header("location: ../items.php?m=us"); // Updated successfully
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        header("location: ../items.php?m=".$e->getMessage().""); // Failed
        exit();
    }
}
else if (isset($_POST['disable-item-btn'])) { // For disabling item
    include 'config.inc.php';

    $itemId = $_POST['del_id']; // Get the item's ID that is about to disable
    $userId = $_POST['user_id']; // Get the users' ID

    if (empty($itemId) && empty($userId)) { // Check if the disable itemID and userID is empty para pag wala to, babalik sila sa item.php
        header("location: ../items.php?m=404"); // Wala akong maisip na error Code HAHAH
        exit();
    }

    $sql = "UPDATE items SET item_status = 'disabled' WHERE item_id = :itemId";
    // Probably dadagdagan dito para sa logs table
    try {
        $stmt = $pdo->prepare($sql);
        // Bind the parameter
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        // Execute the statement
        $stmt->execute();

        // Updated successfully
        header("location: ../items.php?m=ds");
        exit();
    } catch (PDOException $e) {
        // Get error message if failed
        echo "Error: " . $e->getMessage();
        header("location: ../items.php?m=".$e->getMessage().""); // Failed
        exit();
    }
} 
else if (isset($_POST['enable-item-btn'])) { // For enabling item
    include 'config.inc.php';

    $itemId = $_POST['enbl_id']; // Get the item's ID that is about to enable
    $userId = $_POST['user_id']; // Get the users' ID

    if (empty($itemId) && empty($userId)) { // Check if the enable itemID and userID is empty para pag wala to, babalik sila sa item.php
        header("location: ../items.php?m=404"); // Wala akong maisip na error Code HAHAH
        exit();
    }

    $sql = "UPDATE items SET item_status = 'enabled' WHERE item_id = :itemId";
    // Probably dadagdagan dito para sa logs table
    try {
        $stmt = $pdo->prepare($sql);
        // Bind the parameter
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        // Execute the statement
        $stmt->execute();

        // Updated successfully
        header("location: ../items.php?m=ds");
        exit();
    } catch (PDOException $e) {
        // Get error message if failed
        echo "Error: " . $e->getMessage();
        header("location: ../items.php?m=".$e->getMessage().""); // Failed
        exit();
    }

}
else {
    header("location: ../items.php?m=404");
    exit();
}



?>

