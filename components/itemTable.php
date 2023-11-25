<?php
include '../includes/config.inc.php';
session_start();

// Check if there's an id, if it has, then it's logged in
// If there's no id, head back to login page
if (!isset($_SESSION['ID']) and ($_SESSION['ID'] == '')) {
    header("location: index.php");
    exit();
}

// To determine which is active page in nav bar
$_SESSION['active_tab'] = basename($_SERVER['SCRIPT_FILENAME']);
?>

<script>
    $(document).ready(function() {
        $('table').DataTable();
    });

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $('#myModal').on('shown.bs.modal', function() {
        $('#myInput').trigger('focus')
    });
</script>

<div class="row justify-content-center align-items-center mt-3">
    <div class="col-12 col-sm-12 col-md-10 col-lg-10">
        <?php include '../includes/message.inc.php'; ?>
    </div>
</div>

<div class="row justify-content-center align-items-center">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Measurement</th>
                        <!-- CONDITIONAL FOR ADMINS OR USERS -->
                        <?php
                        if (isset($_SESSION['CT']) && ($_SESSION['CT']) != 0) { // IF ADMIN
                            echo '<th scope="col">Quantity</th>';
                            echo ($_SESSION['CH'] == 1) ? '<th scope="col">Chapter</th>' : '';
                            echo '<th scope="col">Status</th>';
                        }
                        ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (isset($_SESSION['CT'])) { // If the session category is set
                        try {

                            // If chapter is Manila and Admin
                            if (($_SESSION['CH'] == 1) && ($_SESSION['CT'] == 1)) {
                                // Run this code below
                                $query = "SELECT items.item_id AS ID,\n"
                                    . "items.item_name AS Name,\n"
                                    . "items_category.item_category_name AS Category,\n"
                                    . "items_unit_of_measure.item_uom_name AS Measurement,\n"
                                    . "items.item_quantity AS Quantity,\n"
                                    . "chapters.chapter_name AS Chapter,\n"
                                    . "items.item_status AS Status\n"
                                    . "FROM items\n"
                                    . "INNER JOIN items_category ON items.item_category = items_category.item_category_id\n"
                                    . "INNER JOIN items_unit_of_measure ON items.item_measure = items_unit_of_measure.item_uom_id\n"
                                    . "INNER JOIN chapters ON items.item_chapter = chapters.chapter_id AND items.item_quantity > 0;";
                            } else if (($_SESSION['CH'] != 1) && ($_SESSION['CT'] == 1)) { // If chapter is not Manila, but admin
                                // Run this code below
                                $user_chapter = $_SESSION['CH'];
                                $query = "SELECT 
                                                            items.item_id AS ID,
                                                            items.item_name AS Name,
                                                            items_category.item_category_name AS Category,
                                                            items_unit_of_measure.item_uom_name AS Measurement,
                                                            items.item_quantity AS Quantity,
                                                            chapters.chapter_name AS Chapter,
                                                            items.item_status AS Status
                                                        FROM items
                                                        INNER JOIN items_category ON items.item_category = items_category.item_category_id
                                                        INNER JOIN items_unit_of_measure ON items.item_measure = items_unit_of_measure.item_uom_id
                                                        INNER JOIN chapters ON items.item_chapter = chapters.chapter_id
                                                        WHERE items.item_chapter = $user_chapter
                                                        AND items.item_quantity > 0;";
                            } else { // Users only and show items according to their chapter
                                $user_chapter = $_SESSION['CH'];
                                $query = "SELECT
                                                            items.item_id AS ID,
                                                            items.item_name AS Name,
                                                            items_category.item_category_name AS Category,
                                                            items_unit_of_measure.item_uom_name AS Measurement,
                                                            items.item_status AS Status
                                                        FROM items
                                                        INNER JOIN items_category ON items.item_category = items_category.item_category_id
                                                        INNER JOIN items_unit_of_measure ON items.item_measure = items_unit_of_measure.item_uom_id
                                                        WHERE items.item_chapter = $user_chapter
                                                        AND items.item_status = 'enabled'
                                                        AND items.item_quantity > 0;";
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
                                    if (isset($_SESSION['CT']) && ($_SESSION['CT']) != 0) { // If admin
                                        echo '<td>' . $row['Quantity'] . '</td>';

                                        // If admin and from Manila
                                        echo ($_SESSION['CH'] == 1) ? '<td>' . $row['Chapter'] . '</td>' : '';

                                        // Show status of items
                                        if ($row['Status'] == 'enabled') {
                                            echo '<td class="text-success text-capitalize small">' . $row['Status'] . '</td>';
                                        } else if ($row['Status'] == 'disabled') {
                                            echo '<td class="text-danger text-capitalize small">' . $row['Status'] . '</td>';
                                        }
                                    }
                                    ?>
                                    <td>
                                        <?php if ($_SESSION['CT'] == 1) { // If category is admin then  buttons show below
                                            echo '<a href="http://" class="view-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#viewModal" data-item-id="' . $row['ID'] . '" title="View"><i class="fa-solid fa-eye"></i></a>';
                                            echo '<a href="http://" class="edit-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#editModal" data-item-id="' . $row['ID'] . '" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';

                                            if ($row['Status'] == 'enabled') { // If enable status of the item then show the disable button, vice versa
                                                echo '<a href="http://" class="disabled-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#disabledModal" data-item-id="' . $row['ID'] . '" title="Disable"><i class="fa-solid fa-ban"></i></a>';
                                            } else if ($row['Status'] == 'disabled') {
                                                echo '<a href="http://" class="enabled-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#enabledModal" data-item-id="' . $row['ID'] . '" title="Enable"><i class="fa-solid fa-check"></i></a>';
                                            }

                                            echo '<a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Request"><i class="fa-solid fa-truck-arrow-right"></i></a>';
                                        } else {
                                            echo '<a href="http://" class="view-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#viewModal" data-item-id="' . $row['ID'] . '" title="View"><i class="fa-solid fa-eye"></i></a>';
                                            echo '<a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Request"><i class="fa-solid fa-truck-arrow-right"></i></a>';
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
                    <button type="button" class="btn btn-sm btnRed btn-secondary btn-sm btnRed" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-sm btnGreen text-light" name="add-item-btn" value="Add item">
                </div>
            </div>
        </div>
    </form>
</div>

<!-- View modal for specific item -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="includes/items.inc.php" method="POST" enctype="multipart/form-data">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold" id="exampleModalLabel">View Item</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-between align-items-center item-view">
                        <!-- This is where data being fetch from items.inc.php -->
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <!-- To get the user's ID and record it for history (log) -->
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>">
                    <button type="button" class="btn btn-secondary btn-sm btnRed" data-bs-dismiss="modal">Close</button>
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="number" class="form-control form-control-sm mx-1" name="req-quantity" id="req-quantity" placeholder="1-1000" min="1" max="1000" required>
                        <input type="submit" class="btn btn-sm btnGreen text-light mx-1" name="req-item-btn" value="Request">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Edit modal for specific item -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="includes/items.inc.php" method="POST" enctype="multipart/form-data">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Edit Item</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-between align-items-center item-view">
                        <!-- This is where data being fetch from items.inc.php -->
                    </div>
                    <a href="http://" target="_blank" rel="noopener noreferrer"></a>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <!-- To get the user's ID and record it for history (log) -->
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>">
                    <button type="button" class="btn btn-secondary btn-sm btnRed" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-sm btnGreen text-light mx-1" name="save-edit-item-btn" value="Save">
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Disabling item Modal -->
<div class="modal fade" id="disabledModal" tabindex="-1" role="dialog" aria-labelledby="disabledModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="includes/items.inc.php" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Disable Item</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="del_id" id="del_id">
                    <h6>Are you sure you want to disable this item?</h6>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>">
                    <button type="button" class="btn btn-secondary btnRed btn-sm px-2" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btnGreen text-light btn-sm mx-1" name="disable-item-btn" value="Disable">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Enabling item Modal -->
<div class="modal fade" id="enabledModal" tabindex="-1" role="dialog" aria-labelledby="enabledModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="includes/items.inc.php" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Enable Item</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="enbl_id" id="enbl_id">
                    <h6>Are you sure you want to enable this item?</h6>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>">
                    <button type="button" class="btn btn-secondary btnRed btn-sm px-2" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btnGreen text-light btn-sm mx-1" name="enable-item-btn" value="Enable">
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {

        // Shows modal and specific item for View button
        $('table').on('click', '.view-btn', function(e) {
            e.preventDefault();
            var itemId = $(this).data('item-id');
            $.ajax({
                type: 'POST',
                url: 'includes/items.inc.php',
                data: {
                    'check_view': true,
                    'item_id': itemId
                },
                success: function(response) {
                    $('.item-view').html(response);
                    $('#viewModal').modal('show');
                }
            });
        });

        // Showing modal and editable contents of specific item for Edit button
        $('table').on('click', '.edit-btn', function(e) {
            e.preventDefault();
            var itemId = $(this).data('item-id');
            $.ajax({
                type: 'POST',
                url: 'includes/items.inc.php',
                data: {
                    'edit_view': true,
                    'item_id': itemId
                },
                success: function(response) {
                    $('.item-view').html(response);
                    $('#editModal').modal('show');
                }
            });
        });

        // Disable the item from modal
        $('table').on('click', '.disabled-btn', function(e) {
            e.preventDefault();
            var itemId = $(this).data('item-id');
            $.ajax({
                type: 'POST',
                url: 'includes/items.inc.php',
                data: {
                    'disable-item-btn': true,
                    'item_id': itemId
                },
                success: function(response) {
                    $('#del_id').val(itemId);
                    $('#disabledModal').modal('show');
                }
            });
        });

        // Enable the item from modal
        $('table').on('click', '.enabled-btn', function(e) {
            e.preventDefault();
            var itemId = $(this).data('item-id');
            $.ajax({
                type: 'POST',
                url: 'includes/items.inc.php',
                data: {
                    'enabled-item-btn': true,
                    'item_id': itemId
                },
                success: function(response) {
                    $('#enbl_id').val(itemId);
                    $('#enabledModal').modal('show');
                }
            });
        });
    });
</script>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>