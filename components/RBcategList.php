
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    
    <div class="row">
        <?php 
        $categId = $_POST['categoryId'];
        $chapter = $_POST['chapter'];
        $result = $pdo->prepare("SELECT * FROM items WHERE item_category = :categId AND item_chapter = :chapter");
        $result->bindParam(':categId', $categId, PDO::PARAM_INT);
        $result->bindParam(':chapter', $chapter, PDO::PARAM_INT);
        $result->execute();
        
        $records = $result->fetchAll(PDO::FETCH_ASSOC);
        if ($records) {
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
            <?php } ?>
        <?php } else {
            echo "No records";
        } ?>
    </div>
</body>

</html>