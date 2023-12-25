<?php

include 'view.php';
include '../includes/headers.inc.php';
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$total_records = get_total_records($pdo, $start, $limit);
$total_pages = ceil($total_records / $limit);

$records = get_records($pdo, $start, $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination Example</title>
</head>

<body>
    <h1>Records</h1>
    <!-- <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
        </tr> -->
    <?php foreach ($records as $record) : ?>
        <!-- <tr>
                <td><?= $record['item_id'] ?></td>
                <td><?= $record['name'] ?></td>
                <td><?= $record['description'] ?></td>
            </tr> -->
        <div class="col-md-3 col-sm-4 col-lg-3 mb-4">
            <div class="card h-100" data-item-id="<?php echo $record['item_id']; ?>">
                <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                <input type="text" class="item_id" value="<?php echo $record['item_id'] ?>" hidden>
                <img src="./images/items/<?php echo $record['item_image'] ?>" class="card-img-top" alt="Item Image">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title"><?php echo $record['item_name'] ?></h6>
                    <p class="card-text">
                        <strong>Description:</strong> <?php echo $record['item_description'] ?><br>
                        <strong>Status:</strong> <?php echo $record['item_status'] ?> <br>
                        <sub><strong>Quantity:</strong> <?php echo $record['item_quantity'] ?></sub>
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
                            View
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

    <?php endforeach; ?>
    <!-- </table> -->

    <div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <li class="page-item"> <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                <?php endfor; ?>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>

        </nav>
    </div>

    <script>
        // You can add additional JavaScript logic here if needed
    </script>
</body>

</html>