<?php
include 'includes/config.inc.php';
session_start();

// Check if there's an id, if it has, then it's logged in
// If there's no id, head back to login page
if (!isset($_SESSION['ID']) and ($_SESSION['ID'] == '')) {
    header("location: index.php");
    exit();
}

// if you're a user then go back to index.php
if ($_SESSION['CT'] == '0') {
    header("location: home.php");
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
    <!-- Add more CSS links here -->
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <!-- Javascript for Datatables.net  -->
    <script>
        $(document).ready(function() {
            $('table').DataTable({
        "order": [[0, "desc"]] // Assuming user ID is in the first column (index 0)
    });
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
    <!-- Navigation bar -->
    <?php include 'nav.php';?>

    <div id="wrapper">
        <div class="section px-lg-5 pt-4">
            <!-- Contents here -->

            <!-- Error/Information Messages -->
            <div class="row justify-content-center align-items-center mt-3">
                <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                    <?php include 'includes/message.inc.php'; ?>
                </div>
            </div>

            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Category</th>
                                        <th>Chapters</th>
                                        <!-- On progress  -->
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    if (isset($_SESSION['CT'])) { // If the session category is set
                                        try {

                                            // If chapter is Manila and Admin
                                            if (($_SESSION['CH'] == 1) && ($_SESSION['CT'] == 1)) {
                                                // Run this code below
                                                $query = "SELECT users.user_id AS id,\n"
                                                    . "CONCAT(users.user_firstname, ' ', users.user_lastname) AS name,\n"
                                                    . "users.user_email AS email,\n"
                                                    . "category.category_name AS category,\n"
                                                    . "chapters.chapter_name AS chapters,\n"
                                                    . "users.user_status AS status,\n"
                                                    . "users.user_image AS image\n"
                                                    . "FROM `users`\n"
                                                    . "INNER JOIN category ON users.user_category = category.category_id\n"
                                                    . "INNER JOIN chapters ON users.user_chapter = chapters.chapter_id;";
                                            } else if (($_SESSION['CH'] != 1) && ($_SESSION['CT'] == 1)) { // If chapter is not Manila, but admin
                                                // Run this code below
                                                // Show the chapter where the admin located
                                                $user_chapter = $_SESSION['CH'];
                                                $query = "SELECT users.user_id AS id,\n"
                                                . "CONCAT(users.user_firstname, ' ', users.user_lastname) AS name,\n"
                                                . "users.user_email AS email,\n"
                                                . "category.category_name AS category,\n"
                                                . "chapters.chapter_name AS chapters,\n"
                                                . "users.user_status AS status,\n"
                                                . "users.user_image AS image\n"
                                                . "FROM `users`\n"
                                                . "INNER JOIN category ON users.user_category = category.category_id\n"
                                                . "INNER JOIN chapters ON users.user_chapter = chapters.chapter_id WHERE users.user_chapter = $user_chapter";
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
                                                    <td class="text-capitalize"><?php echo $row['id']; ?></td>
                                                    <td class="text-capitalize"><?php echo $row['name']; ?></td>
                                                    <td class=""><?php echo $row['email']; ?></td>
                                                    <td class="text-capitalize"><?php echo $row['category']; ?></td>
                                                    <td class="text-capitalize"><?php echo $row['chapters']; ?></td>

                                                    <!-- Show status of users whether active or inactive -->
                                                    <?php
                                                        if ($row['status'] == 'active') {
                                                            echo '<td class="text-success text-capitalize small">' . $row['status'] . '</td>';
                                                        } else if ($row['status'] == 'inactive') {
                                                            echo '<td class="text-danger text-capitalize small">' . $row['status'] . '</td>';
                                                        }
                                                    ?>
                                                    <td>
                                                        <?php
                                                            echo '<a href="http://" class="view-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#viewModal" data-user-id="' . $row['id'] . '" title="View"><i class="fa-solid fa-eye"></i></a>';
                                                            echo '<a href="http://" class="edit-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#editModal" data-user-id="' . $row['id'] . '" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                                                        ?>
                                                    </td>

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
    </div>

    <!-- View modal for specific user -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="includes/users.inc.php" method="POST" enctype="multipart/form-data">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="exampleModalLabel">View User</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-between align-items-center user-view">
                            <!-- This is where data being fetch from users.inc.php -->
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <!-- To get the user's ID and record it for history (log) OR something -->
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>">
                        <button type="button" class="btn btn-secondary btn-sm btnRed" data-bs-dismiss="modal">Close</button>

                        <!-- For future updates, this is where you should add -->
                        <div class="d-flex justify-content-between align-items-center">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Edit modal for specific user -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="includes/users.inc.php" method="POST" enctype="multipart/form-data">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Edit User Profile</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-between align-items-center user-view-edit">
                            <!-- This is where data being fetch from users.inc.php -->
                        </div>
                        <a href="http://" target="_blank" rel="noopener noreferrer"></a>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <!-- To get the user's ID and record it for history (log) -->
                        <input type="hidden" name="editor-user-id" id="editor-user-id" value="<?php echo $_SESSION['ID']; ?>">
                        <button type="button" class="btn btn-secondary btn-sm btnRed" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-sm btnGreen text-light mx-1" name="save-edit-user-btn" value="Save">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Show specific user
            $('table').on('click', '.view-btn', function(e) {
                e.preventDefault();
                var userId = $(this).data('user-id');
                $.ajax({
                    type: 'POST',
                    url: 'includes/users.inc.php',
                    data: {
                        'user_view': true,
                        'user_id': userId
                    },
                    success: function(response) {
                        $('.user-view').html(response);
                        $('#viewModal').modal('show');
                    }
                });
            });

            // Show specific user and edit it
            $('table').on('click', '.edit-btn', function(e) {
                e.preventDefault();
                var userId = $(this).data('user-id');
                $.ajax({
                    type: 'POST',
                    url: 'includes/users.inc.php',
                    data: {
                        'edit_view': true,
                        'user_id': userId
                    },
                    success: function(response) {
                        $('.user-view-edit').html(response);
                        $('#editModal').modal('show');
                    }
                });
            });

            // Save the edit modal of user profile

        });
    </script>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>
