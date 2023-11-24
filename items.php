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

    <?php include 'nav.php'; ?>

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
                <?php

                if ($_SESSION['CT'] == "0") {

                    $userChapter = $_SESSION["CH"];
                    $query = $pdo->prepare("SELECT * FROM `items` WHERE item_chapter = :itemChapter ");
                    $query->bindParam(':itemChapter', $userChapter, PDO::PARAM_INT);
                    $query->execute();

                    // set the resulting array to associative
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) { ?>
                        <!-- <form action="./includes/tocart.php" method="POST"> -->
                        <div class="row">
                            <div class="col">
                                <div class="card" style="width: 18rem;">
                                    <input type="text" class="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                                    <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                                    <img src="./images/items/<?php echo $row['item_image'] ?>" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h1 class="card-title"><?php echo $row['item_name'] ?></h6>
                                            <h6 class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</h6>
                                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                            <button class="btn btn-primary checker" data-user-id="<?php echo $_SESSION['ID'] ?>" data-item-id="<?php echo $row['item_id'] ?>">to Cart</button>
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

    <script>
        $(document).ready(() => {
            //  const user_id = document.getElementById("user_id").value;
            //  const item_id = document.getElementById("item_id").value;
            // const all_buttons = document.querySelectorAll('.checker')
            // console.log(all_buttons);
            // // $(".checker").click(() => {
            // // const user_id = $(this).prevAll('.user-id:first').val();
            // // const item_id = $(this).prevAll('.item-id:first').val();

            // // alert("User ID: " + user_id + ", Item ID: " + item_id);
            // all_buttons.forEach(bt => (
            //     bt.addEventListener('click', (e) => {
            //         e.preventDefault();


            //         // Find the closest parent element with the class 'card'
            //         const card = e.currentTarget.closest('.card');

            //         // Find the 'item-id' input element within the same card
            //         const item_id_input = card.querySelector('.itemid');

            //         if (item_id_input) {
            //             // Check if the element is found before accessing its value
            //             const item_id = parseInt(item_id_input).value;
            //             alert("Item ID: " + item_id);
            //         } else {
            //             console.error("Item ID input not found within the card:", card);
            //         }
            //     })
            // ))

            $(".checker").click((e) => {

                const all_buttons = document.querySelectorAll('.checker')
                // Find the closest parent element with the class 'card'
                const card = e.currentTarget.closest('.card');

                // Find the 'item-id' input element within the same card
                const item_id_input = card.querySelector('.item_id');

                if (item_id_input) {
                    // Check if the element is found before accessing its value
                    const item_id = parseInt(item_id_input.value);
                    alert("Item ID: " + item_id);
                } else {
                    console.error("Item ID input not found within the card:", card);
                }
            })

        })
        // })
    </script>

</body>

</html>