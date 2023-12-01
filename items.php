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
    <!-- <script>
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
    </script> -->
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
                <?php
                if ($_SESSION['CT'] == "0") {

                    $userChapter = $_SESSION["CH"];
                    $query = $pdo->prepare("SELECT * FROM `items` WHERE item_chapter = :itemChapter AND item_status = :itemStatus ");
                    $query->bindParam(':itemChapter', $userChapter, PDO::PARAM_INT);
                    $query->bindValue(':itemStatus', "enabled", PDO::PARAM_STR);
                    $query->execute();
                    // set the resulting array to associative
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) { ?>
                        <div class="col-md-3 col-sm-4 col-lg-3 mb-4">
                            <div class="card h-100" data-item-id="<?php echo $row['item_id']; ?>">
                                <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                                <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                                <img src="./images/items/<?php echo $row['item_image'] ?>" class="card-img-top" alt="Item Image">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title"><?php echo $row['item_name'] ?></h6>
                                    <p class="card-text">
                                        <strong>Description:</strong> <?php echo $row['item_description'] ?><br>
                                        <strong>Status:</strong> <?php echo $row['item_status'] ?> <br>
                                        <sub><strong>Quantity:</strong> <?php echo $row['item_quantity'] ?></sub>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <button class="btn checker">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512">
                                                <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                                            </svg>
                                            <div class="spinner-border text-success d-none spinner-border-sm" id="spinner" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <i class="fa-solid fa-check d-none" id="checkIcon" style="color: #22511f;"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-req">
                                            Open Modal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Item Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img src="" class="img-fluid" alt="Item Image" id="modalItemImage">
                                            </div>
                                            <div class="col-md-6">
                                                <div id="modalItemDetails">


                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary req-btn">Request</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>


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
            const user_id = document.getElementById("user_id").value;


            $(".checker").click((e) => {
                //user id variable

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

                                alert("item added");
                                if (response) {
                                    setTimeout(() => {
                                        checkIcon.addClass('d-none');
                                        svg.removeClass('d-none');
                                    }, 3000);
                                    location.reload();
                                }
                            }
                        });

                    }, 5000);

                } else {
                    console.error("Item ID input not found within the card:", card);
                }
            })

            $(".btn-req").click(function(e) {

                // Stop the event from propagating further
                e.stopPropagation();

                // Find the closest parent element with the class 'card'
                const card = e.currentTarget.closest('.card');

                // Find the 'item-id' input element within the same card
                const item_id_input = card.querySelector('.item_id');


                const modalItemImage = $('#modalItemImage');

                const modalItemDetails = $('#additionalDetails');
                if (item_id_input) {
                    const item_id = parseInt(item_id_input.value);

                    // Update the modal content

                    $.ajax({
                        type: "POST",
                        url: "./includes/getitem.inc.php",
                        data: {
                            itemId: item_id,
                        },
                        success: function(response) {
                            const itemInfo = JSON.parse(response);



                            modalItemImage.attr('src',
                                `images/items/${itemInfo.item_image}`);

                            $('#modalItemDetails').html(`
                            <h6>Item Name: <span id="item-name">${itemInfo.item_name}</span></h5>
                            <input type="number" min="0" id="item-id" value= "${itemInfo.item_id}" hidden>
                            <div id="additionalDetails">
                            <p> 
                            Item Description: <span id="item-desc">${itemInfo.item_description}</span> </br>
                                Item Status :  <span id="item-stat"> ${itemInfo.item_status}</span> </br>
                                <sub> Stocks: <span id="item-stoc">${itemInfo.item_quantity}</span> </sub> <br>
                                
                                <input  id="item-quan" type="number" min="0" max="${itemInfo.item_quantity}">
                            </p>
                            
                            </div>
                        `);

                        }
                    });

                    // Show the modal programmatically
                    $('#exampleModal').modal("show");
                }
            });

            $(".req-btn").click(function() {
                const itemId = document.getElementById("item-id").value;
                const itemQuan = document.getElementById("item-quan").value;
                console.log("item Id: " + itemId);
                console.log("user id: " + user_id);
                console.log("item quan" + itemQuan);

                $.ajax({
                    type: "POST",
                    url: "./includes/itemreq.inc.php",
                    data: {
                        itemID: itemId,
                        itemQ: itemQuan,
                        userID: user_id
                    },
                    success: function(response) {
                        if (response) {
                            alert(response);
                        }
                    }
                });

            })

        });
    </script>

</body>

</html>