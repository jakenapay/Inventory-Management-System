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
            <br>

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
                                            echo '<th scope="col">Quantity</th>
                                            <th scope="col">Chapter</th>
                                            <th scope="col">Actions</th>';
                                        } else { // IF USERS
                                            echo '<th scope="col">Actions</th>'; 
                                        };
                                    ?>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    if (isset($_SESSION['CT'])) { // If the session category is set
                                        try{

                                            // If chapter is Manila and Admin
                                            if(($_SESSION['CH'] === 1) && ($_SESSION['CT'] === 1)) {
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
                                                        if (isset($_SESSION['CT']) && ($_SESSION['CT']) !== 0) {
                                                            echo '<td>' . $row['Quantity'] . '</td>';
                                                            echo '<td>' . $row['Chapter'] . '</td>';
                                                        }
                                                    ?>
                                                    <td class="actions">
                                                        <a href="http://" target="" rel="noopener noreferrer"><i class="fa-solid fa-eye"></i></a>
                                                        <a href="http://" target="" rel="noopener noreferrer"><i class="fa-solid fa-pen-to-square"></i></a>
                                                        <a href="http://" target="" rel="noopener noreferrer"><i class="fa-solid fa-trash"></i></i></a>
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

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>