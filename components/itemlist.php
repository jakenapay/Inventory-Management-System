<?php
function get_total_records($pdo)
{
    $userChapter = $_SESSION["CH"];
    $result = $pdo->query("SELECT COUNT(item_id) AS total FROM items WHERE item_chapter = $userChapter");

    // $result->bindParam(":ItemCategory" , $itemCategory, PDO::PARAM_INT);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

function get_records($pdo, $start, $limit,)
{
    $userChapter = $_SESSION["CH"];
    $sql = "SELECT * FROM items  WHERE item_chapter = $userChapter  LIMIT $start, $limit   ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$limit = 6; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;


$total_records = get_total_records($pdo, $start, $limit);
$total_pages = ceil($total_records / $limit);

$records = get_records($pdo, $start, $limit);

// Ensure current page is within valid range
$current_page = max(1, min($start, $total_pages));

// Calculate previous and next page numbers
$previous_page = max(1, $current_page - 1);
$next_page = min($total_pages, $current_page + 1);

?>



<div class="row ">

    <?php

    foreach ($records as $row) { ?>

        <!-- OLD CODE -->
        <!-- <div class="col-md-6 col-sm-12 col-lg-4  mt-5">
            <div class="card">
                <div class="imgBox">
                    <img src="./images/items/<?php // echo $row['item_image'] 
                                                ?>" alt="<?php echo $row['item_name'] ?>" class="mouse">
                </div>
                <div class="contentBox">
                    <input type="text" id="user_id" value="<?php // echo $_SESSION['ID'] 
                                                            ?>" hidden>
                    <input type="text" class="item_id" value="<?php // echo $row['item_id'] 
                                                                ?>" hidden>
                    <h3><?php // echo $row['item_name'] 
                        ?></h3>


                    <?php // if ($row['item_status'] == "disabled" or $row['item_quantity'] == 0) { 
                    ?>
                        <h6 class="price" style="color: red;">Item Unavialable</h6>
                        <button type="button" class="btn buy btn-primary btn-view " data-bs-toggle="modal" data-bs-target="#itemDetails" data-item-id="<?php echo $row['item_id'] ?>" disabled hidden>
                            Request
                        </button>
                    <?php // } else { 
                    ?>
                        <button type="button" class="btn buy btn-primary btn-view " data-bs-toggle="modal" data-bs-target="#itemDetails" data-item-id="<?php echo $row['item_id'] ?>">
                                View
                            </button>
                        <a href="viewItem.php?itemid=<?php // echo $row['item_id'] 
                                                        ?>">
                            <button type="button" class="btn buy btn-primary">
                                View
                            </button>
                        </a>
                    <?php // } 
                    ?>
                </div>
            </div>
        </div> -->

        <!--  NEW CODE -->
        <div class="col-12 col-md-4 col-lg-3 my-2">
            <div class="card">
                <!-- Hidden details -->
                <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                <!-- Image of the item -->
                <div class="image-parent">
                    <img class="card-img-top" src="./images/items/<?php echo $row['item_image'] ?>" alt="<?php echo $row['item_name'] ?>">
                </div>
                <div class="card-body">
                    <!-- Item title -->
                    <h5 class="card-title"><?php echo $row['item_name'] ?></h5>
                    <p class="card-text"><?php echo $row['item_description']; ?></p>

                    <?php if ($row['item_status'] == "disabled" or $row['item_quantity'] == 0) { ?>
                        <!-- <h6 class="price" style="color: red;">Item Unavailable</h6> -->
                        <a class="not-allowed btn btn-primary view-btn disabled" href="viewItem.php?itemid=<?php echo $row['item_id'] ?>">Item Unavailable</a>
                        <!-- <button type="button" class="btn buy btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#itemDetails" data-item-id="<?php echo $row['item_id'] ?>" disabled hidden>
                                Request
                            </button> -->
                    <?php } else { ?>
                        <a class="btn btn-primary view-btn" href="viewItem.php?itemid=<?php echo $row['item_id'] ?>">View</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

</div>
<nav aria-label="Page navigation example mb-5">
    <ul class="pagination m-auto my-5">
        <li class="page-item"><a class="page-link" href="?page=<?= $previous_page ?>">Previous</a></li>
        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
        <?php endfor; ?>
        <li class="page-item"><a class="page-link" href="?page=<?= $next_page ?>">Next</a></li>
    </ul>
</nav>



<script>
    // // Initialization for ES Users
    // document.addEventListener('DOMContentLoaded', function() {
    //     const user_id = document.getElementById("user_id").value;
    //     var modal = new bootstrap.Modal(document.getElementById('itemDetails'));
    //     var buttons = document.querySelectorAll('.btn-view');

    //     var getItemId = 0;

    // get the data to display in modal
    // // buttons.forEach(function(button) {
    // //     button.addEventListener('click', function() {
    // //         var itemId = this.getAttribute('data-item-id');
    // //         getItemId = itemId;
    // //         console.log('Button Clicked! Item ID:', itemId);
    // //         $.ajax({
    // //             type: "POST",
    // //             url: "./includes/getitem.inc.php",
    // //             data: {
    // //                 itemId: getItemId,
    // //             },
    // //             success: function(response) {

    // //                 console.log('Response received:', response);

    // //                 Parse the JSON response
    // //                 const itemInfo = JSON.parse(response);
    // //                 console.log('Parsed itemInfo:', itemInfo);

    // //                 Update modal content
    // //                 $(".itemImg").attr("src",
    // //                     `./images/items/${itemInfo.item_image}`);

    // //                 $(".item-name").html(`${itemInfo.item_name}`)
    // //                 $(".item-desc").html(`${itemInfo.item_description}`)
    // //                 $(".item-quan").html(`${itemInfo.item_quantity}`)
    // //                 $(".quanInput").html(
    // //                     `
    // //                 <input type="number" min="0" id="item-id" value="${itemInfo.item_id}" hidden> 
    // //                 <input id="item-quan" type="number" min="0" max="${itemInfo.item_quantity}">`
    // //                 )

    // //                 Show the modal
    // //                 $('#itemDetails').modal('show');
    // //             }
    // //         });
    // //     });
    // // });

    //     // Other modal logic remains the same

    //     //to Cart 
    //     var checkerBtn = document.querySelector('.checker');
    //     checkerBtn.addEventListener('click', function(e) {
    //         const spinner = $(e.currentTarget).find('.spinner-border');
    //         const svg = $(e.currentTarget).find('svg');
    //         const checkIcon = $(e.currentTarget).find('#checkIcon');

    //         spinner.removeClass('d-none');
    //         svg.addClass('d-none');
    //         checkIcon.addClass('d-none');

    //         // Find the 'item-id' input element within the same card
    //         const item_id_input = getItemId;
    //         if (item_id_input) {
    //             // Check if the element is found before accessing its value

    //             setTimeout(() => {
    //                 spinner.addClass('d-none');
    //                 svg.addClass('d-none');
    //                 checkIcon.removeClass('d-none');

    //                 $.ajax({
    //                     type: "POST",
    //                     url: "./includes/tocart.php",
    //                     data: {
    //                         itemid: item_id_input,
    //                         userid: user_id,
    //                     },
    //                     success: function(response) {
    //                         if (response) {
    //                             setTimeout(() => {
    //                                 checkIcon.addClass('d-none');
    //                                 svg.removeClass('d-none');
    //                             }, 3000);
    //                             location.reload();
    //                         }
    //                     }
    //                 });

    //             }, 5000);

    //         } else {
    //             console.error("Item ID input not found within the card:", card);
    //         }

    //     });

    //     var reqBtn = document.querySelector('.btnReq');
    //     reqBtn.addEventListener('click', function() {
    //         const itemQuan = document.getElementById("item-quan").value;
    //         const itemId = document.getElementById("item-id").value;
    //         const quanChecker = document.getElementById("quanChecker");
    //         var maxQuantity = parseInt(document.getElementById("item-quan").max, 10);
    //         console.log("item Id: " + itemId);
    //         console.log("user id: " + user_id);
    //         console.log("item quan:" + itemQuan);

    //         if (parseInt(itemQuan, 10) > maxQuantity) {
    //             quanChecker.style.display = "block";

    //             // Set a timeout to hide the element after 2 seconds
    //             setTimeout(function() {
    //                 quanChecker.style.display = "none";
    //             }, 2000);


    //         } else {
    //             // requesting item function  
    //             $.ajax({
    //                 type: "POST",
    //                 url: "./includes/itemreq.inc.php",
    //                 data: {
    //                     itemID: itemId,
    //                     itemQ: itemQuan,
    //                     userID: user_id
    //                 },
    //                 success: function(response) {
    //                     if (response) {
    //                         // working na Pwede na lagay yung PHP MAILER DITO
    //                         alert(response);
    //                         location.reload();
    //                     }
    //                 }
    //             });
    //         }


    //     });
    // });
</script>