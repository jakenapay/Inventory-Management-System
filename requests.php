<?php
include './includes/config.inc.php';
session_start();

// Check if there's an id, if it has, then it's logged in
// If there's no id, head back to login page
if (!isset($_SESSION['ID']) and ($_SESSION['ID'] == '')) {
    header("location: index.php");
    exit();
}

// To determine which is active page in nav bar
$_SESSION['active_tab'] = basename($_SERVER['SCRIPT_FILENAME']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Headers and other attachments/CDN -->
    <?php include_once 'includes/headers.inc.php'; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/items.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/requests.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <!-- Javascript for Datatables.net  -->
    <script>
        $(document).ready(function() {
            $('table').DataTable();
        });

        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $('#myModal').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus')
        });

        setTimeout(function() {
            document.getElementById("msg").style.display = "none"; // hide the element after 3 seconds
        }, 5000);
    </script>
</head>

<body>

    <?php include 'nav.php'; ?>

    <div id="wrapper">
        <div class="section px-5 pt-4">
            <div class="row justify-content-center align-items-center">
                <!-- TECHNOLOGY -->
                <div class="box col-sm-12 col-md-3 col-lg-3">
                    <i class="fa-solid fa-circle-check icon"></i>
                    <p class="m-0">Approved</p>
                    <?php
                    // Prepare and execute the query
                    $query = "SELECT COUNT(history_status) FROM history WHERE history_status = 'approved'";
                    $stmt = $pdo->query($query);

                    // Fetch the count
                    $count = $stmt->fetchColumn();

                    // Check if the count was successfully fetched
                    if ($count !== false) {
                        echo '<p class="m-0">' . $count . '</p>'; // Output the count
                    } else {
                        echo "Error fetching count"; // Handle the error if fetch failed
                    }
                    ?>
                </div>
                <!-- CONSUMABLE -->
                <div class="box col-sm-12 col-md-3 col-lg-3">
                    <i class="fa-solid fa-spinner icon"></i>
                    <p class="m-0">Pending</p>
                    <?php
                    // Prepare and execute the query
                    $query = "SELECT COUNT(history_status) FROM history WHERE history_status = 'pending'";
                    $stmt = $pdo->query($query);

                    // Fetch the count
                    $count = $stmt->fetchColumn();

                    // Check if the count was successfully fetched
                    if ($count !== false) {
                        echo '<p class="m-0">' . $count . '</p>'; // Output the count
                    } else {
                        echo "Error fetching count"; // Handle the error if fetch failed
                    }
                    ?>
                </div>
                <!-- OFFICE SUPPLY -->
                <div class="box col-sm-12 col-md-3 col-lg-3">
                    <i class="fa-solid fa-ban icon"></i>
                    <p class="m-0 px-2">Declined</p>
                    <?php
                    // Prepare and execute the query
                    $query = "SELECT COUNT(history_status) FROM history WHERE history_status = 'declined'";
                    $stmt = $pdo->query($query);

                    // Fetch the count
                    $count = $stmt->fetchColumn();

                    // Check if the count was successfully fetched
                    if ($count !== false) {
                        echo '<p class="m-0">' . $count . '</p>'; // Output the count
                    } else {
                        echo "Error fetching count"; // Handle the error if fetch failed
                    }
                    ?>
                </div>
            </div>

            <!-- Messages (error msgs or information) -->
            <div class="row justify-content-center align-items-center mt-3">
                <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                    <?php include 'includes/message.inc.php'; ?>
                </div>
            </div>

            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-sm-12 col-md-12 col-lg-11">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Request by</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Return Status</th>
                                    <th>Date Return</th>
                                    <th>Due Return Date</th>
                                    <?php echo ($_SESSION['CT'] == 1) ? "<th>Actions</th>" : ""; ?>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (isset($_SESSION['CT'])) { // If the session category is set
                                    try {

                                        // If chapter is Manila and Admin
                                        if ($_SESSION['CT'] == 1) {

                                            // If admin is from manila then show all the chapters requests
                                            if ($_SESSION['CH'] == 1) {
                                                // Run this code below
                                                $query = "SELECT h.history_id AS ID,
                                                    i.item_name AS Item,
                                                    h.history_quantity AS Quantity,
                                                    CONCAT(u.user_firstname, ' ', u.user_lastname) AS 'Request by',
                                                    h.history_status AS Status,
                                                    h.isReturned AS isReturned,
                                                    h.history_date_return AS dateReturn,
                                                    h.history_due_date AS dueDate
                                                    h.history_date AS Date
                                                    FROM history AS h
                                                    INNER JOIN items AS i ON h.history_item_id = i.item_id
                                                    INNER JOIN users AS u ON h.history_user_id = u.user_id;";
                                            } else {
                                                $query = "SELECT h.history_id AS ID,
                                                    i.item_name AS Item,
                                                    h.history_quantity AS Quantity,
                                                    CONCAT(u.user_firstname, ' ', u.user_lastname) AS 'Request by',
                                                    h.history_status AS Status,
                                                    h.history_date AS Date,
                                                    h.isReturned AS isReturned,
                                                    h.history_date_return AS dateReturn,
                                                    h.history_due_date AS dueDate
                                                    FROM history AS h
                                                    INNER JOIN items AS i ON h.history_item_id = i.item_id
                                                    INNER JOIN users AS u ON h.history_user_id = u.user_id
                                                    WHERE u.user_chapter =" . $_SESSION['CH'] . ";";
                                            }
                                        } else if ($_SESSION['CT'] == 0) { // Users' POV
                                            // Run this code below
                                            $query = "SELECT h.history_id AS ID,
                                                i.item_name AS Item,
                                                i.item_id AS itemID,
                                                h.history_quantity AS Quantity,
                                                CONCAT(u.user_firstname, ' ', u.user_lastname) AS 'Request by',
                                                h.history_status AS Status,
                                                h.history_date AS Date,
                                                h.isReturned AS isReturned,
                                                h.history_date_return AS dateReturn,
                                                h.history_due_date AS dueDate
                                                FROM history AS h
                                                INNER JOIN items AS i ON h.history_item_id = i.item_id
                                                INNER JOIN users AS u ON h.history_user_id = u.user_id
                                                WHERE u.user_chapter = " . $_SESSION['CH'] . "
                                                AND u.user_id = " . $_SESSION['ID'] . ";";
                                        }

                                        // Prepare the query
                                        $stmt = $pdo->query($query);

                                        // Execute the query
                                        $stmt->execute();

                                        // Fetch all rows as an associative array
                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        // Process the result (e.g., display it)
                                        foreach ($result as $row) {
                                            // Access columns by their names, e.g., $row['column_name']
                                ?>
                                            <!-- Break to continue HTML   -->
                                            <tr>
                                                <td><?php echo $row['ID']; ?></td>
                                                <td><?php echo $row['Item']; ?></td>
                                                <td><?php echo $row['Quantity']; ?></td>
                                                <td class="text-capitalize"><?php echo $row['Request by']; ?></td>
                                                <?php
                                                // Show status of items
                                                if ($row['Status'] == 'approved') {
                                                    echo '<td class="text-success text-capitalize small">' . $row['Status'] . '</td>';
                                                } else if ($row['Status'] == 'pending') {
                                                    echo '<td class="text-warning text-capitalize small">' . $row['Status'] . '</td>';
                                                } else if ($row['Status'] == 'declined') {
                                                    echo '<td class="text-danger text-capitalize small">' . $row['Status'] . '</td>';
                                                }

                                                ?>


                                                <td><?php echo $row['Date']; ?></td>

                                                <td>
                                                    <?php

                                                    if ($row['isReturned'] == 0) {
                                                        echo 'No';
                                                    } else {
                                                        if ($_SESSION['CT'] == 1) {
                                                            echo 'Yes';
                                                        } else {
                                                            echo '<a href="http://" class="feedback-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#feedbackModal" data-item-id="' . $row['itemID'] . '" title="feedback"><i class="fa-sharp fa-solid fa-comments fa-" style="color: #07f223;"></i></a>';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['dateReturn'] ?>
                                                </td>

                                                <td>
                                                    asdasdas
                                                </td>
                                                <!-- <?php if ($_SESSION['CT'] == 1) { ?>
                                                    <td>
                                                    <?php
                                                            if ($row['isReturned'] == 0) {
                                                                echo '<a href="http://" class="approve-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#approveModal" data-item-id="' . $row['ID'] . '" title="Approve"><i class="fa-solid fa-check"></i></a>';
                                                                echo '<a href="http://" class="decline-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#declineModal" data-item-id="' . $row['ID'] . '" title="Decline"><i class="fa-solid fa-x"></i></a>';
                                                            } else {
                                                                // echo '<a href="http://" class="approve-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#approveModal" data-item-id="' . $row['ID'] . '" title="Approve" ><i class="fa-solid fa-check"></i></a>';
                                                                // echo '<a href="http://" class="decline-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#declineModal" data-item-id="' . $row['ID'] . '" title="Decline" ><i class="fa-solid fa-x"></i></a>';
                                                            }
                                                        } ?>
                                                    </td> -->




                                                <?php if ($_SESSION['CT'] == 1) { ?>
                                                    <td>
                                                    <?php
                                                    if ($row['isReturned'] == 0) {
                                                        echo '<a href="http://" class="approve-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#approveModal" data-item-id="' . $row['ID'] . '" title="Approve"><i class="fa-solid fa-check"></i></a>';
                                                        // echo '<a href="http://" class="decline-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#declineModal" data-item-id="' . $row['ID'] . '" title="Decline"><i class="fa-solid fa-x"></i></a>';
                                                    } else {
                                                        // echo '<a href="http://" class="approve-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#approveModal" data-item-id="' . $row['ID'] . '" title="Approve" ><i class="fa-solid fa-check"></i></a>';
                                                        // echo '<a href="http://" class="decline-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#declineModal" data-item-id="' . $row['ID'] . '" title="Decline" ><i class="fa-solid fa-x"></i></a>';
                                                    }
                                                } ?>

                                            </tr>
                                <?php
                                        }
                                    } catch (PDOException $e) {
                                        // Handle database connection or query errors
                                        echo "Error: " . $e->getMessage();
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Approving request item modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="includes/requests.inc.php" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Approve Requested Item</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="approve_request_id" id="approve_request_id">
                        <h6>Are you sure you want to approve this request?</h6>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <input type="" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>" hidden>
                        <button type="button" class="btn btn-secondary btnRed btn-sm px-2" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btnGreen text-light btn-sm mx-1" name="approve-request-item-btn" value="Approve">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Item Feedback modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="includes/" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Feedback</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="approve_request_id" id="approve_request_id">
                        <div class="comment-widgets ">
                            <!-- Comment Row -->
                            <div class="d-flex flex-row  m-t-0">
                                <div class="p-2"><img src="./images/userProfiles/<?php echo $_SESSION['UI'] ?>" alt="user" width="50" class="rounded-circle"></div>
                                <div class="comment-text ">
                                    <h6 class=""><small><?php echo $_SESSION['FN'] . " " . $_SESSION['LN'] ?> </small></h6> <span class="m-b-15 d-block"> <textarea id="w3review" name="w3review" rows="3" cols="35"></textarea> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <input type="" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>" hidden>
                        <button type="button" class="btn btn-secondary btnRed btn-sm px-2" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btnGreen text-light btn-sm mx-1" name="approve-request-item-btn" value="Feedback">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Declining request item modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="includes/requests.inc.php" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Decline Requested Item</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="decline_request_id" id="decline_request_id">
                        <h6>Are you sure you want to decline this request?</h6>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>">
                        <button type="button" class="btn btn-secondary btnRed btn-sm px-2" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btnGreen text-light btn-sm mx-1" name="decline-request-item-btn" value="Decline">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Approving the requested item from modal
            $('table').on('click', '.approve-btn', function(e) {
                e.preventDefault();
                var itemId = $(this).data('item-id');

                console.log(itemId)
                $.ajax({
                    type: 'POST',
                    url: 'includes/items.inc.php',
                    data: {
                        'approve-request-item-btn': true,
                        'item_id': itemId
                    },
                    success: function(response) {
                        $('#approve_request_id').val(itemId);
                        $('#approveModal').modal('show');
                    }
                });
            });

            // Approving the requested item from modal
            $('table').on('click', '.decline-btn', function(e) {
                e.preventDefault();
                var itemId = $(this).data('item-id');
                $.ajax({
                    type: 'POST',
                    url: 'includes/requests.inc.php',
                    data: {
                        'decline-request-item-btn': true,
                        'item_id': itemId
                    },
                    success: function(response) {
                        $('#decline_request_id').val(itemId);
                        $('#declineModal').modal('show');
                    }
                });
            });

            $('table').on('click', '.feedback-btn', function(e) {
                e.preventDefault();
                var itemId = $(this).data('item-id');
                alert(itemId);
                $.ajax({
                    type: 'POST',
                    url: 'includes/requests.inc.php',
                    data: {
                        'decline-request-item-btn': true,
                        'item_id': itemId
                    },
                    success: function(response) {
                        $('#decline_request_id').val(itemId);
                        $('#declineModal').modal('show');
                    }
                });
            });

        });
    </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

</body>

</html>