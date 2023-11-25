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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <!-- Javascript for Datatables.net  -->
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

        setTimeout(function() {
            document.getElementById("msg").style.display = "none"; // hide the element after 3 seconds
        }, 5000);
    </script>
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
            <div class="row justify-content-center align-items-center mb-3">
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
                        echo '<p class="m-0">' . $count . '</p>'; // Output the count
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
                        echo '<p class="m-0">' . $count . '</p>'; // Output the count
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
                        echo '<p class="m-0">' . $count . '</p>'; // Output the count
                    } else {
                        echo "Error fetching count"; // Handle the error if fetch failed
                    }
                    ?>
                </div>
            </div>
            

            <div class="container">
                <div class="row">
                    <?php

                    if ($_SESSION['CT'] == "0") {

                        //$userChapter = $_SESSION["CH"];
                        $query = $pdo->prepare("SELECT * FROM `items`  ");
                        //$query->bindParam(':itemChapter', $userChapter, PDO::PARAM_INT);
                        $query->execute();

                        // set the resulting array to associative
                        $result = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) { ?>
                            <!-- <form action="./includes/tocart.php" method="POST"> -->

                            <div class="col-md-3 col-sm-4 col-lg-3 mb-4">
                                <div class="card  h-100 d-flex flex-column">
                                    <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                                    <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                                    <img src="./images/items/<?php echo $row['item_image'] ?>" class="card-img-top" style="max-width: 100%; min-width: 50px;" alt="...">
                                    <div class="card-body  d-flex flex-column">
                                        <h6 class="card-title mb-0"><?php echo $row['item_name'] ?></h5>
                                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                            <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
                                            <div>
                                                <button class="btn checker">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                        <style>
                                                            svg {
                                                                fill: #2a511f
                                                            }
                                                        </style>
                                                        <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                                                    </svg>

                                                    <div class=" spinner-border text-success d-none spinner-border-sm" id="spinner" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>

                                                    <i class="fa-solid fa-check d-none" id="checkIcon" style="color: #22511f;"></i>
                                                </button>
                                                <button class="btn-sm btn-success">Request</button>
                                            </div>
                                    </div>
                                </div>
                            </div>




                            <!-- </form> -->
                    <?php }
                    } else {
                        include './components/ActionComponent.php';
                    } ?>


                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(() => {

            $(".checker").click((e) => {
                //user id variable
                const user_id = document.getElementById("user_id").value;



                const spinner = $(e.currentTarget).find('.spinner-border');
                const svg = $(e.currentTarget).find('svg');
                const checkIcon = $(e.currentTarget).find('#checkIcon');

                spinner.removeClass('d-none');
                svg.addClass('d-none');
                checkIcon.addClass('d-none');



                // Find the closest parent element with the class 'card'
                const card = e.currentTarget.closest('.card');

                // Find the 'item-id' input element within the same card
                const item_id_input = card.querySelector('.item_id');

                if (item_id_input) {
                    // Check if the element is found before accessing its value
                    const item_id = parseInt(item_id_input.value);
                    alert("Item ID: " + item_id + "user id: " + user_id);
                    setTimeout(() => {
                        spinner.addClass('d-none');
                        svg.addClass('d-none');
                        checkIcon.removeClass('d-none');

                        $.ajax({
                            type: "POST",
                            url: "./includes/tocart.php",
                            data: {
                                itemid: item_id,
                                userid: user_id
                            },

                            success: function(response) {

                                if (response) {
                                    setTimeout(() => {
                                        checkIcon.addClass('d-none');
                                        svg.removeClass('d-none');
                                    }, 3000);
                                }
                            }
                        });

                    }, 5000);

                } else {
                    console.error("Item ID input not found within the card:", card);
                }


            })

        })
        // })
    </script>

</body>

</html>