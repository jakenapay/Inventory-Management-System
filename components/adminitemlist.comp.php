<?php
function get_total_records($pdo)
{
    $userChapter = $_SESSION["CH"];
    $result = $pdo->query("SELECT COUNT(item_id) AS total FROM items ");

    // $result->bindParam(":ItemCategory" , $itemCategory, PDO::PARAM_INT);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

function get_records($pdo, $start, $limit,)
{
    $userChapter = $_SESSION["CH"];
    $sql = "SELECT * FROM items  LIMIT $start, $limit   ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$limit = 8; // Number of records per page
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