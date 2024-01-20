<?php
include '../includes/config.inc.php';
session_start();

?>
<div class="row">
    <div class="col-lg-9 col-sm-6 col-sm-12 m-auto mt-5">
        <div class="form-check form-check-inline">
            <ul>
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



    <div class="row">

        <?php 
        foreach ($records as $row) { ?>
            <div class="col-md-6 col-sm-12 col-lg-4  mt-5">
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
                            <!-- <button type="button" class="btn buy btn-primary btn-view " data-bs-toggle="modal" data-bs-target="#itemDetails" data-item-id="<?php echo $row['item_id'] ?>">
                                View
                            </button> -->
                            <a href="viewItem.php?itemid=<?php echo $row['item_id'] ?>">
                                <button type="button" class="btn buy btn-primary">
                                    View
                                </button>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <nav aria-label="Page navigation example">
        <ul class="pagination m-auto mt-5">
            <li class="page-item"><a class="page-link" href="?page=<?= $previous_page ?>">Previous</a></li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $next_page ?>">Next</a></li>
        </ul>
    </nav>

    <?php {
    } ?>



    <script>
        function getRadioValue() {
            // Get the selected radio button
            var selectedOption = document.querySelector('input[name="category"]:checked');

            console.log(selectedOption);
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

            var Sdata = document.getElementById('itemSearch').value;
            alert(Sdata);
            // Perform AJAX request using jQuery
            $.ajax({
                type: 'POST',
                url: './includes/searchQuery.inc.php',
                data: {
                    querySearch: Sdata
                },
                success: function(response) {
                    $('#ItemList').html(response);
                }
            });
        });
    </script>