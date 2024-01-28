<div class="row">
    <?php
    session_start();
    include '../includes/config.inc.php';

    if (isset($_POST['querySearch']) && isset($_POST['chapter'])) {
        $searchQuery = $_POST['querySearch'];

        $user_chapter = $_POST['chapter'];
        $result = $pdo->query("SELECT * FROM items WHERE item_name LIKE '%$searchQuery%' AND item_chapter = $user_chapter");
        $records = $result->fetchAll(PDO::FETCH_ASSOC);

        if ($result->rowCount() > 0) { // Check if there are results before entering the loop
            foreach ($records as $row) { ?>
                <div class="col-12 col-md-4 col-lg-3 my-2">
                    <div class="card">
                        <!-- Hidden details -->
                        <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                        <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                        <!-- Image of the item -->
                        <div class="image-parent">
                            <img class="card-img-top image-responsive image-resize" src="./images/items/<?php echo $row['item_image'] ?>" alt="<?php echo $row['item_name'] ?>">
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
    <?php }
        } else {
            // No results found message
            echo '<p>No items found for the search: ' . htmlspecialchars($searchQuery) . '</p>';
        }
    }
    ?>
</div>