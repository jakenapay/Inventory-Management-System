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
$_SESSION['active_tab'] = 'items';
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
</head>

<body>

    <?php include 'nav.php';?>

    <div id="wrapper">
        <div class="section px-5 pt-4">
            <div class="row justify-content-center align-items-center">

                <!-- TECHNOLOGY -->
                <div class="box col-sm-12 col-md-2 col-lg-2">
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
                <div class="box col-sm-12 col-md-2 col-lg-2">
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
                <div class="box col-sm-12 col-md-2 col-lg-2">
                    <div class="d-flex justify-content-center align-items-center">
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
            </div>
            
            <div class="row justift-content center align-items-center">
                
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>