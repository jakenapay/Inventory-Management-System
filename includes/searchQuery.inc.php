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
                <div class="col-md-6 col-sm-12 col-lg-4 mt-5">
                    <div class="card">
                        <div class="imgBox">
                            <img src="./images/items/<?php echo $row['item_image'] ?>" alt="<?php echo $row['item_name'] ?>" class="mouse">
                        </div>
                        <div class="contentBox">
                            <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                            <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                            <h3><?php echo $row['item_name'] ?></h3>

                            <?php if ($row['item_status'] == "disabled" or $row['item_quantity'] == 0) { ?>
                                <h6 class="price" style="color: red;">Item Unavailable</h6>
                                <button type="button" class="btn buy btn-primary btn-view-categ" data-bs-toggle="modal" data-bs-target="#itemDetailsModal" data-item-id="<?php echo $row['item_id'] ?>" disabled hidden>
                                    Request
                                </button>
                            <?php } else { ?>
                                <a href="viewItem.php?itemid=<?php echo $row['item_id'] ?>">
                                    <button type="button" class="btn buy btn-primary">
                                        View
                                    </button>
                                </a>
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