<?php
include 'includes/config.inc.php';
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Headers and other attachments/CDN -->
    <?php include_once 'includes/headers.inc.php'; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/items.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <!-- Javascript for Datatables.net  -->
    <script>
        $(document).ready(function() {
            $('table').DataTable();
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        });

        setTimeout(function(){ 
            document.getElementById("msg").style.display = "none"; // hide the element after 3 seconds
        }, 5000);
    </script>
</head>

<body>

    <?php include 'nav.php';?>

    <div id="wrapper">
        <div class="section px-5 pt-4">
            <div class="row justify-content-center align-items-center">
                <!-- TECHNOLOGY -->
                <div class="box col-sm-12 col-md-3 col-lg-3">
                    <i class="fa-solid fa-computer icon"></i>
                    <p class="m-0">Technology</p>
                    <?php 
                        // Prepare and execute the query
                        $query = "SELECT COUNT(item_category) FROM items WHERE item_category = 1";
                        $stmt = $pdo->query($query);

                        // Fetch the count
                        $count = $stmt->fetchColumn();

                        // Check if the count was successfully fetched
                        if ($count !== false) {
                            echo '<p class="m-0">'.$count.'</p>'; // Output the count
                        } else {
                            echo "Error fetching count"; // Handle the error if fetch failed
                        }
                    ?>
                </div>
                <!-- CONSUMABLE -->
                <div class="box col-sm-12 col-md-3 col-lg-3">
                    <i class="fa-solid fa-glass-water icon"></i>
                    <p class="m-0">Consumable</p>
                    <?php 
                        // Prepare and execute the query
                        $query = "SELECT COUNT(item_category) FROM items WHERE item_category = 2";
                        $stmt = $pdo->query($query);

                        // Fetch the count
                        $count = $stmt->fetchColumn();

                        // Check if the count was successfully fetched
                        if ($count !== false) {
                            echo '<p class="m-0">'.$count.'</p>'; // Output the count
                        } else {
                            echo "Error fetching count"; // Handle the error if fetch failed
                        }
                    ?>
                </div>
                <!-- OFFICE SUPPLY -->
                <div class="box col-sm-12 col-md-3 col-lg-3">
                    <i class="fa-solid fa-stapler icon"></i>
                    <p class="m-0 px-2">Office Supply</p>
                    <?php 
                        // Prepare and execute the query
                        $query = "SELECT COUNT(item_category) FROM items WHERE item_category = 3";
                        $stmt = $pdo->query($query);

                        // Fetch the count
                        $count = $stmt->fetchColumn();

                        // Check if the count was successfully fetched
                        if ($count !== false) {
                            echo '<p class="m-0">'.$count.'</p>'; // Output the count
                        } else {
                            echo "Error fetching count"; // Handle the error if fetch failed
                        }
                    ?>
                </div>
            </div>

            <div class="row justify-content-center align-items-center mt-3">
                <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                    <?php include 'includes/message.inc.php';?>
                </div>
            </div>

            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-sm-12 col-md-12 col-lg-11">
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
                                        if (isset($_SESSION['CT']) && ($_SESSION['CT']) != 0) { // IF ADMIN
                                            echo '<th scope="col">Quantity</th>';
                                            echo ($_SESSION['CH'] == 1) ? '<th scope="col">Chapter</th>' : '';
                                        }
                                    ?>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    if (isset($_SESSION['CT'])) { // If the session category is set
                                        try{

                                            // If chapter is Manila and Admin
                                            if(($_SESSION['CH'] == 1) && ($_SESSION['CT'] == 1)) {
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
                                            } else if (($_SESSION['CH'] != 1) && ($_SESSION['CT'] == 1)) {
                                                // Run this code below
                                                $user_chapter = $_SESSION['CH'];
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
                                                        if (isset($_SESSION['CT']) && ($_SESSION['CT']) != 0) { // If admin
                                                            echo '<td>' . $row['Quantity'] . '</td>';

                                                            // If admin and from Manila
                                                            
                                                            echo ($_SESSION['CH'] == 1) ? '<td>'.$row['Chapter'].'</td>' : '';
                                                        }
                                                    ?>
                                                    <td>
                                                        <?php if ($_SESSION['CT'] == 1) {
                                                            echo '
                                                            <a href="http://" class="view-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#viewModal" data-item-id="'.$row['ID'].'" title="View"><i class="fa-solid fa-eye"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-bs-toggle="tooltip" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Delete"><i class="fa-solid fa-trash"></i></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Request"><i class="fa-solid fa-truck-arrow-right"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Return"><i class="fa-solid fa-rotate-left"></i></a>
                                                            ';
                                                        } else {
                                                            echo '
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="View"><i class="fa-solid fa-eye"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Return"><i class="fa-solid fa-truck-arrow-right"></i></a>
                                                            <a href="http://" target="" rel="noopener noreferrer" data-toggle="tooltip" title="Return"><i class="fa-solid fa-rotate-left"></i></a>
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
                                        try{
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
                                                echo '<option value="'.$row['item_category_id'].'">'.$row['item_category_name'].'</option>';
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
                                        try{
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
                                                echo '<option value="'.$row['item_uom_id'].'">'.$row['item_uom_name'].'</option>';
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
                                        try{
                                            if($_SESSION['CH'] == 1) { // If admin's chapter is manila, it can choose different chapters
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
                                                echo '<option value="'.$row['chapter_id'].'">'.$row['chapter_name'].'</option>';
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

    <script>
        $(document).ready(function() {
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
        });
    </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
                                  
</body>

</html>