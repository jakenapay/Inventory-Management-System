<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <!-- Headers and other attachments/CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/items.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/itemlist.css?v=<?php echo time(); ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://uploads-ssl.webflow.com/6492dd5d65d1855cb14a6692/649425d6d5c2567ce414d063_favicon.svg" rel="shortcut icon" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <style>
        .card-img,
        .card-img-top {
            padding: 1rem 0 1rem;
        }

        .pagination .page-link {
            color: #000000;
            /* Black color */
        }

        .pagination .page-link:hover {
            color: #000000;
            /* Black color on hover */
        }

        .view-btn,
        .btn-primary,
        .btn-primary:hover,
        .btn-primary:active {
            background-color: var(--green);
            border-color: var(--green);
        }

        .btn-primary.disabled,
        .btn-primary:disabled {
            background-color: var(--orange);
            border-color: var(--orange);
        }

        .card {
            /* width: 18rem; */
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            /* Optional: Add a border for visual separation */
            border-radius: 8px;
            /* Optional: Add rounded corners */
            overflow: hidden;
        }

        .image-parent {
            width: 100%;
            margin: 0 auto;
            overflow: hidden;
            height: 200px;
        }

        /* .card-img-top {
  left: 0;
  right: 0;
  margin: auto;
  position: absolute;
} */

        .image-resize {
            height: 200px;
            object-fit: cover;
            object-position: center center;
        }


        .card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .card-text {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            -webkit-line-clamp: 1;
            /* Show only 2 lines */
            text-overflow: ellipsis;
            margin-bottom: auto;
            /* Push the text to the top, keeping the "View" button at the bottom */
        }

        .view-btn {
            margin-top: 1rem;
            width: 100%;
            align-self: flex-end;
            /* Align the button to the bottom of the card */
        }
    </style>
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
            <?php } ?>
        <?php } else {
            echo "No records";
        } ?>
    </div>
</body>

</html>