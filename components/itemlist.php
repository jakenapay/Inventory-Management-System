<link rel="stylesheet" href="assets/css/itemlist.css?v=<?php echo time(); ?>">
<?php

function get_total_records($pdo)
{
    $result = $pdo->query("SELECT COUNT(item_id) AS total FROM items ");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

function get_records($pdo, $start, $limit)
{
    $userChapter = $_SESSION["CH"];
    $sql = "SELECT * FROM items  LIMIT $start, $limit ";
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
?>


<?php
if ($_SESSION['CT'] == "0") {
    foreach ($records as $row) { ?>
        <div class="col-md-6 col-sm-12 col-lg-4 mb-2 m-auto pt-5">
            <div class="card">

                <div class="imgBox">
                    <img src="./images/items/<?php echo $row['item_image'] ?>" alt="<?php echo $row['item_name'] ?>" class="mouse">
                </div>

                <div class="contentBox">
                    <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                    <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                    <h3><?php echo $row['item_name'] ?></h3>


                    <?php if ($row['item_status'] == "disabled" or $row['item_quantity'] == 0) { ?>
                        <h6 class="price" style="color: red;">Item Unavialable</h6>
                        <button type="button" class="btn buy btn-primary btn-view " data-bs-toggle="modal" data-bs-target="#itemDetails" data-item-id="<?php echo $row['item_id'] ?>" disabled hidden>
                            Request
                        </button>
                    <?php } else { ?>
                        <button type="button" class="btn buy btn-primary btn-view " data-bs-toggle="modal" data-bs-target="#itemDetails" data-item-id="<?php echo $row['item_id'] ?>">
                            View
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="itemDetails" tabindex="-1" aria-labelledby="ItemDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ItemDetailsModalLabel">Item Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="" class="img-fluid itemImg" alt="Item Image">
                            </div>
                            <div class="col-md-6 itemdesc">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
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
                        </div>
                        <button type="button" class="btn btn-secondary btnReq">Request</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- </form> -->



    <?php } ?>

    <nav aria-label="Page navigation example">
        <ul class="pagination m-auto">
            <li class="page-item"><a class="page-link" href="?page=<?= $i = $i - 1 ?>">Previous</a></li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>


<?php } else {
    include './components/ActionComponent.php';
} ?>






<script>
    document.addEventListener('DOMContentLoaded', function() {
        const user_id = document.getElementById("user_id").value;
        var modal = new bootstrap.Modal(document.getElementById('itemDetails'));
        var buttons = document.querySelectorAll('.btn-view');

        var getItemId = 0;
        
        //get the data to display in modal
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                var itemId = this.getAttribute('data-item-id');
                getItemId = itemId;
                console.log('Button Clicked! Item ID:', itemId);
                $.ajax({
                    type: "POST",
                    url: "./includes/getitem.inc.php",
                    data: {
                        itemId: getItemId,
                    },
                    success: function(response) {

                        console.log('Response received:', response);

                        // Parse the JSON response
                        const itemInfo = JSON.parse(response);
                        console.log('Parsed itemInfo:', itemInfo);

                        // Update modal content
                        $(".itemImg").attr("src",
                            `./images/items/${itemInfo.item_image}`);


                        $(".itemdesc").html(`
                            <h6>Item Name: <span id="item-name">${itemInfo.item_name}</span></h5>
                            <input type="number" min="0" id="item-id" value="${itemInfo.item_id}" hidden>
                            <div id="additionalDetails">
                                <p> 
                                    Item Description: <span id="item-desc">${itemInfo.item_description}</span> </br>
                                    Item Status: <span id="item-stat">${itemInfo.item_status}</span> </br>
                                    <sub> Stocks: <span id="item-stoc">${itemInfo.item_quantity}</span> </sub> <br>
                                    <input id="item-quan" type="number" min="0" max="${itemInfo.item_quantity}">
                                   
                                    <h6 id="quanChecker" style="display: none; color:red;"> <small>insufficient Stocks</small></h6>
                                </p>
                            </div>
                        `);



                        // Show the modal
                        $('#itemDetails').modal('show');
                    }
                });
            });
        });

        // Other modal logic remains the same

        //to Cart 
        var checkerBtn = document.querySelector('.checker');
        checkerBtn.addEventListener('click', function(e) {
            const spinner = $(e.currentTarget).find('.spinner-border');
            const svg = $(e.currentTarget).find('svg');
            const checkIcon = $(e.currentTarget).find('#checkIcon');

            spinner.removeClass('d-none');
            svg.addClass('d-none');
            checkIcon.addClass('d-none');

            // Find the 'item-id' input element within the same card
            const item_id_input = getItemId;
            if (item_id_input) {
                // Check if the element is found before accessing its value

                setTimeout(() => {
                    spinner.addClass('d-none');
                    svg.addClass('d-none');
                    checkIcon.removeClass('d-none');

                    $.ajax({
                        type: "POST",
                        url: "./includes/tocart.php",
                        data: {
                            itemid: item_id_input,
                            userid: user_id,
                        },
                        success: function(response) {
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

        });

        var reqBtn = document.querySelector('.btnReq');
        reqBtn.addEventListener('click', function() {
            const itemQuan = document.getElementById("item-quan").value;
            const itemId = document.getElementById("item-id").value;
            const quanChecker = document.getElementById("quanChecker");
            var maxQuantity = parseInt(document.getElementById("item-quan").max, 10);
            console.log("item Id: " + itemId);
            console.log("user id: " + user_id);
            console.log("item quan:" + itemQuan);

            if (parseInt(itemQuan, 10) > maxQuantity) {
                quanChecker.style.display = "block";

                // Set a timeout to hide the element after 2 seconds
                setTimeout(function() {
                    quanChecker.style.display = "none";
                }, 2000);


            } else {
                // requesting item function  
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
                            // working na Pwede na lagay yung PHP MAILER DITO
                            alert(response);
                            location.reload();
                        }
                    }
                });
            }


        });
    });
</script>