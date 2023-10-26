<!--//* Container  -->
<?php session_start();
 include '../includes/config.inc.php'
?>

<div class="row justify-content-center align-items-center">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-hover table-sm table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Measurement</th>
                        <!-- CONDITIONAL FOR ADMINS OR USERS -->
                        <?php
                        if (isset($_SESSION['user_category']) && ($_SESSION['user_category']) != 0) { // IF ADMIN
                            echo '<th scope="col">Quantity</th>';
                            echo ($_SESSION['user_chapter'] == 1) ? '<th scope="col">Chapter</th>' : '';
                        }
                        ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (isset($_SESSION['user_category'])) { // If the session category is set
                        try {

                            // If chapter is Manila and Admin
                            if (($_SESSION['user_chapter'] == 1) && ($_SESSION['user_chapter'] == 1)) {
                                // Run this code below
                                $query = "SELECT items.item_id AS ID,\n"
                                    . "items.item_name AS Name,\n"
                                    . "items_category.item_category_name AS Category,\n"
                                    . "items_unit_of_measure.item_uom_name AS Measurement,\n"
                                    . "items.item_quantity AS Quantity,\n"
                                    . "chapters.chapter_name AS Chapter\n"
                                    . "FROM items\n"
                                    . "INNER JOIN items_category ON items.item_category = items_category.item_category_id\n"
                                    . "INNER JOIN items_unit_of_measure ON items.item_measure = items_unit_of_measure.item_uom_id\n"
                                    . "INNER JOIN chapters ON items.item_chapter = chapters.chapter_id;";
                            } else if (($_SESSION['user_chapter'] != 1) && ($_SESSION['user_category'] == 1)) {
                                // Run this code below
                                $user_chapter = $_SESSION['user_chapter'];
                                $query = "SELECT 
                                                            items.item_id AS ID,
                                                            items.item_name AS Name,
                                                            items_category.item_category_name AS Category,
                                                            items_unit_of_measure.item_uom_name AS Measurement,
                                                            items.item_quantity AS Quantity,
                                                            chapters.chapter_name AS Chapter
                                                        FROM items
                                                        INNER JOIN items_category ON items.item_category = items_category.item_category_id
                                                        INNER JOIN items_unit_of_measure ON items.item_measure = items_unit_of_measure.item_uom_id
                                                        INNER JOIN chapters ON items.item_chapter = chapters.chapter_id
                                                        WHERE items.item_chapter = $user_chapter;";
                            } else {
                                $user_chapter = $_SESSION['CH'];
                                $query = "SELECT
                                                            items.item_id AS ID,
                                                            items.item_name AS Name,
                                                            items_category.item_category_name AS Category,
                                                            items_unit_of_measure.item_uom_name AS Measurement
                                                        FROM items
                                                        INNER JOIN items_category ON items.item_category = items_category.item_category_id
                                                        INNER JOIN items_unit_of_measure ON items.item_measure = items_unit_of_measure.item_uom_id
                                                        WHERE items.item_chapter = $user_chapter;";
                            }

                            // Prepare the query
                            $stmt = $pdo->query($query);

                            // Execute the query
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Process the result (e.g., display it)
                            foreach ($result as $row) {
                                // Access columns by their names, e.g., $row['column_name']
                    ?>
                                <!-- Break to continue HTML   -->
                                <tr>
                                    <td><?php echo $row['ID']; ?></td>
                                    <td><?php echo $row['Name']; ?></td>
                                    <td><?php echo $row['Category']; ?></td>
                                    <td><?php echo $row['Measurement']; ?></td>
                                    <?php
                                    if (isset($_SESSION['user_category']) && ($_SESSION['user_category']) != 0) { // If admin
                                        echo '<td>' . $row['Quantity'] . '</td>';

                                        // If admin and from Manila

                                        echo ($_SESSION['user_chapter'] == 1) ? '<td>' . $row['Chapter'] . '</td>' : '';
                                    }
                                    ?>
                                    <td>
                                        <?php if ($_SESSION['user_category'] == 1) {
                                            echo '
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip"  title="View"><i class="fa-solid fa-eye fa-md"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Edit"><i class="fa-solid fa-pen-to-square fa-md"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Delete"><i class="fa-solid fa-trash fa-md"></i></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Request"><i class="fa-solid fa-truck-arrow-right fa-md"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Return"><i class="fa-solid fa-rotate-left fa-md"></i></a>
                                                            ';
                                        } else {
                                            echo '
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="View"><i class="fa-solid fa-eye font-size fa-md"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Return"><i class="fa-solid fa-truck-arrow-right fa-md"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Return"><i class="fa-solid fa-rotate-left fa-md"></i></a>
                                                            ';
                                        }
                                        ?>
                                    </td>
                                </tr>
                    <?php
                            }
                        } catch (PDOException $e) {
                            // Handle database connection or query errors
                            echo "Error: " . $e->getMessage();
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add modal for new item -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="includes/items.inc.php" method="POST" enctype="multipart/form-data">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold" id="exampleModalLabel">New Item</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-12 py-1">
                            <label for="item_name">Name</label>
                            <input type="text" class="form-control form-control-sm text-capitalize" id="item_name" name="item_name" placeholder="Name" required>
                        </div>
                        <!-- Category -->
                        <div class="col-md-6 py-1">
                            <label for="item_category">Category</label>
                            <select name="item_category" id="item_category" class="form-control form-control-sm" required>
                                <option value="" disabled selected>Category</option>
                                <?php
                                try {
                                    $query = "SELECT * FROM items_category;";
                                    // Prepare the query
                                    $stmt = $pdo->query($query);

                                    // Execute the query
                                    $stmt->execute();

                                    // Fetch all rows as an associative array
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Process the result (e.g., display it)
                                    foreach ($result as $row) {
                                        // Access columns by their names, e.g., $row['column_name']
                                        echo '<option value="' . $row['item_category_id'] . '">' . $row['item_category_name'] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    // Handle database connection or query errors
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Measurement -->
                        <div class="col-md-6 py-1">
                            <label for="item_measure">Measurement</label>
                            <select name="item_measure" id="item_measure" class="form-control form-control-sm" required>
                                <option value="" disabled selected>Measurement</option>
                                <?php
                                try {
                                    $query = "SELECT * FROM items_unit_of_measure;";
                                    // Prepare the query
                                    $stmt = $pdo->query($query);

                                    // Execute the query
                                    $stmt->execute();

                                    // Fetch all rows as an associative array
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Process the result (e.g., display it)
                                    foreach ($result as $row) {
                                        // Access columns by their names, e.g., $row['column_name']
                                        echo '<option value="' . $row['item_uom_id'] . '">' . $row['item_uom_name'] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    // Handle database connection or query errors
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Quantity -->
                        <div class="col-md-6 py-1">
                            <label for="item_quantity">Quantity</label>
                            <input type="number" min="0" class="form-control form-control-sm" id="item_quantity" name="item_quantity" placeholder="Quantity" required>
                        </div>
                        <!-- Chapter -->
                        <div class="col-md-6 py-1">
                            <label for="item_chapter">Chapter</label>
                            <select name="item_chapter" id="item_chapter" class="form-control form-control-sm" required>
                                <option value="" disabled selected>Chapter</option>
                                <?php
                                try {
                                    if ($_SESSION['CH'] == 1) { // If admin's chapter is manila, it can choose different chapters
                                        $query = "SELECT * FROM `chapters` ORDER BY `chapters`.`chapter_name` ASC;";
                                    } else {
                                        $chapter = $_SESSION['CH'];
                                        $query = "SELECT * FROM `chapters` WHERE `chapter_id` = $chapter;";
                                    }
                                    // Prepare the query
                                    $stmt = $pdo->query($query);

                                    // Execute the query
                                    $stmt->execute();

                                    // Fetch all rows as an associative array
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Process the result (e.g., display it)
                                    foreach ($result as $row) {
                                        // Access columns by their names, e.g., $row['column_name']
                                        echo '<option value="' . $row['chapter_id'] . '">' . $row['chapter_name'] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    // Handle database connection or query errors
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Description -->
                        <div class="col-md-12 py-1">
                            <label for="item_description">Description</label>
                            <textarea name="item_description" id="item_description" cols="3" rows="1" class="form-control form-control-sm" placeholder="Description"></textarea>
                        </div>
                        <div class="mb-2 mt-2"></div>
                        <hr class="hr" />
                        <!-- Image -->
                        <div class="col-md-12 py-1">
                            <label for="item_image" class="pb-1">Image</label>
                            <input class="form-control form-control-sm" type="file" accept="image/*" name="item_image" id="item_image" required>
                        </div>
                        <!-- User's ID: Who is going to add the new item -->
                        <!-- For history/log purposes -->
                        <input type="hidden" value="<?php echo $_SESSION['ID']; ?>" name="user_id" id="user_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm btnRed" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-sm btnGreen text-light" name="add-item-btn" value="Add item">
                </div>
            </div>
        </div>
    </form>
</div>