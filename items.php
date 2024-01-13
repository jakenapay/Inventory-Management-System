<?php
include './includes/config.inc.php';
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/items.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/itemlist.css?v=<?php echo time(); ?>">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
</head>

<body>
    <style>
        .card-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    <?php include './nav.php'; ?>

    <div id="wrapper">
        <div class="section px-5 pt-4">
            <div class="row">
                <!-- cart -->
                <div class="col">
                    <div class=" justify-content-end justify-content-center-md" style=" text-align: end; margin-right: 100px;">
                        <?php include './components/cart.php' ?>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center align-items-center">
                <!-- TECHNOLOGY -->
                <div class="box col-sm-12 col-md-4 col-lg-3">
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
                        echo '<p class="m-0">' . $count . '</p>'; // Output the count
                    } else {
                        echo "Error fetching count"; // Handle the error if fetch failed
                    }
                    ?>
                </div>
                <!-- CONSUMABLE -->
                <div class="box col-sm-12 col-md-4 col-lg-3">
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
                        echo '<p class="m-0">' . $count . '</p>'; // Output the count
                    } else {
                        echo "Error fetching count"; // Handle the error if fetch failed
                    }
                    ?>
                </div>
                <!-- OFFICE SUPPLY -->
                <div class="box col-sm-12 col-md-4 col-lg-3">
                    <i class="fa-solid fa-stapler icon"></i>
                    <p class="m-0 px-2">HQ Supply</p>
                    <?php
                    // Prepare and execute the query
                    $query = "SELECT COUNT(item_category) FROM items WHERE item_category = 3";
                    $stmt = $pdo->query($query);

                    // Fetch the count
                    $count = $stmt->fetchColumn();

                    // Check if the count was successfully fetched
                    if ($count !== false) {
                        echo '<p class="m-0">' . $count . '</p>'; // Output the count
                    } else {
                        echo "Error fetching count"; // Handle the error if fetch failed
                    }
                    ?>
                </div>
            </div>
        </div>

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
                                <?php
                                require_once './vendor/autoload.php';

                                use Ramsey\Uuid\Uuid;

                                function generateShortRandomNumberString($length)
                                {
                                    $characters = '0123456789';
                                    $randomString = '';

                                    for ($i = 0; $i < $length; $i++) {
                                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                                    }

                                    return $randomString;
                                }

                                // Generate a short random string of numbers with a length of 6 to 7
                                $shortRandomNumberString = generateShortRandomNumberString(rand(6, 7));

                                echo $shortRandomNumberString;

                                ?>
                                <!-- Name -->
                                <div class="col-md-12 py-1">
                                    <input type="text" class="form-control form-control-sm text-capitalize" id="item_unique_id" name="item_unique_id" value="<?php echo  $shortRandomNumberString ?>" placeholder="Name" hidden>
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

        <div class="container">
            <?php include './components/ActionComponent.php'; ?>
        </div>
    </div>
    </div>


</body>

</html>

<script>
    // function updateItemCards(items) {

    //     // Update your UI with the fetched data
    //     // For example, replace the existing items in the page with the new items
    //     const itemsContainer = document.querySelector('.row');
    //     itemsContainer.innerHTML = '';

    //     items.forEach(function(item) {
    //         // Create HTML elements for each item and append to the container
    //         const itemCard = document.createElement('div');
    //         itemCard.classList.add('col-md-6', 'col-sm-12', 'col-lg-4', 'mt-5');
    //         // ... (create and append other elements as needed)
    //         itemsContainer.appendChild(itemCard);
    //     });

    //     console.error('Response is not an array:', items);
    //     // Handle the case where the response is not an array
    //     // (e.g., display an error message or handle it in another way)
</script>