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
    <?php include_once './includes/headers.inc.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/items.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/itemlist.css?v=<?php echo time(); ?>">


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
    <?php include './nav.php';
    ?>

    <div id="wrapper">
        <div class="section px-5 pt-4">
            <div class="row">
                <!-- cart -->
                <div class="col">
                    <div class=" justify-content-end justify-content-center-md" style=" text-align: end; margin-right: 100px;">
                        <?php include './components/cart.php'; ?>

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

        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-sm-6 col-sm-12 m-auto mt-5">
                    <div class="form-check form-check-inline">
                        <ul>
                        <input type="text" id="chapter" value="<?php echo $_SESSION['CH'];?> " hidden>
                            <li>
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="category" id="" value="0" onclick="getRadioValue()" checked>
                                    All
                                </label>
                            </li>
                        </ul>
                        <?php
                        $rbtnNameQuery = "SELECT * FROM `items_category`";
                        $rbtnQ = $pdo->prepare($rbtnNameQuery);
                        $rbtnQ->execute();

                        $rbtnData = $rbtnQ->fetchAll(PDO::FETCH_ASSOC);
                        if ($rbtnData) {

                            foreach ($rbtnData as $itemCateg) { ?>
                                <ul>
                                    <li>
                                        
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="category" id="" value="<?php echo $itemCateg['item_category_id'] ?>" onclick="getRadioValue()">
                                            <?php echo $itemCateg['item_category_name'] ?>
                                        </label>
                                    </li>
                                </ul>
                            <?php } ?>
                        <?php } else {
                        } ?>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-sm-12 m-auto mt-5">
                    <div class=" input-group">
                        <div class="input-group">
                            <input type="search" class="form-control rounded" id="itemSearch" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                            <button type="button" class="btn btn-outline-success btnSearch" data-mdb-ripple-init>search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="ItemList">
                <?php include './components/itemlist.php'; ?>
            </div>
        </div>
    </div>

</body>

</html>

<script>
    function getRadioValue() {
        // Get the selected radio button
        var selectedOption = document.querySelector('input[name="category"]:checked');
        const chptr = document.getElementById('chapter').value;
        // Check if a radio button is selected
        if (selectedOption.value == 0) {

            location.reload();
        }

        if (selectedOption) {
            // Display the selected value
            var selectedCategoryId = selectedOption.value;
            // Use AJAX to fetch data based on the selected category
            $.ajax({
                type: "POST",
                url: "./includes/itemlist.inc.php", // Replace with your server-side script
                data: {
                    categoryId: selectedCategoryId,
                    chapter: chptr,
                },
                success: function(response) {
                    $('#ItemList').html(response);
                }
            });
        } else {
            alert("Please select an option");
        }
    }


    $('.btnSearch').click(function() {
        const chptr = document.getElementById('chapter').value;
        var Sdata = document.getElementById('itemSearch').value;
       
        // Perform AJAX request using jQuery
        $.ajax({
            type: 'POST',
            url: './includes/searchQuery.inc.php',
            data: {
                querySearch: Sdata,
                chapter: chptr,
            },
            success: function(response) {
                $('#ItemList').html(response);
                $('input[name="category"]').prop('checked', false);
            }
        });
    });
</script>