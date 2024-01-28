<div class="row">
    <?php
    $categId = $_POST['categoryId'];
    $user_chapter = $_SESSION['CH'];

    if ($_SESSION['CT'] == "0") {
        $result = $pdo->query("SELECT * FROM items WHERE item_category = $categId AND item_chapter = $user_chapter");
        $records = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($records as $row) { ?>
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
            <?php } ?>
        <?php } else {
            echo "No records";
        } ?>
    </div>