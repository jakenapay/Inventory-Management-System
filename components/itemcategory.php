<?php
        include '../includes/config.inc.php';
        session_start();

        // Check if there's an id, if it has, then it's logged in
        // If there's no id, head back to login page
        if (!isset($_SESSION['ID']) and ($_SESSION['ID'] == '')) {
            header("location: index.php?m&id=1");
            exit();
        }

        // Check if you are from Manila, and you're an admin
        if (($_SESSION['CT'] != 1) && ($_SESSION['CH'] != 1)) { // If you're not from Manila and not Admin, run the code below
            header("location: home.php?m&ct!=1&ch!=1");
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
</head>

<body>

        <div class="section">
            <div class="row justify-content-center align-items-center mt-3 mb-2">
                <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                    <?php include '../includes/message.inc.php'; ?>
                </div>
            </div>
            <div class="container">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="row g-0 d-flex justify-content-between align-items-center">
                        <div class="col-12 col-md-6 col-6">
                            <h3 class="m-0 font-weight-light title-chapter">Item Categories</h3>
                        </div>
                        <div class="col-12 col-md-6 col-6">
                            <div class="nav-item nav-link d-flex justify-content-end">
                                <button type="button" id="addNewChapter" class="btn btn-success btnGreen btn-sm" data-toggle="modal" data-target="#categoryModal">
                                    <i class="fa-solid fa-plus addIcon"></i>New Category
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- SELECT ALL Categories -->
                                <?php
                                $sql = "SELECT * FROM items_category";
                                try {
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Loop through the results
                                    foreach ($result as $row) { ?>
                                        <tr>
                                            <td class="text-capitalize"><?php echo $row['item_category_id']; ?></td>
                                            <td class="text-capitalize"><?php echo $row['item_category_name']; ?></td>
                                            <td class="text-capitalize">
                                                <a href="#" class="edit-category-btn" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#editModal" data-category-id="<?php echo $row['item_category_id']; ?>" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php }
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                    header("location: ../items.php#itemcateg?m=" . $e->getMessage() . ""); // Failed
                                    exit();
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for new chapter -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="includes/category.inc.php" method="POST" enctype="multipart/form-data">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="categoryModalLabel">New Item Category</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-12 py-1">
                                <label for="items_category">Item Category</label>
                                <input type="text" class="form-control form-control-sm text-capitalize" id="items_category_name" name="items_category_name" placeholder="Item Category" required>
                            </div>
                            <div class="mb-2 mt-2"></div>
                            <hr class="hr" />
                            <!-- User's ID: Who is going to add the new chapter -->
                            <!-- For history/log purposes -->
                            <input type="hidden" value="<?php echo $_SESSION['ID']; ?>" name="user_id" id="user_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm btnRed" onclick="closeAndReload()">Close</button>
                        <input type="submit" class="btn btn-sm btnGreen text-light" name="add-category-btn" value="Add Category">
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- Edit modal for specific user -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="includes/category.inc.php" method="POST" enctype="multipart/form-data">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="categoryModalLabel">Edit Category</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-between align-items-center category-view">
                            <!-- This is where data being fetch from category.inc.php -->
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <!-- To get the user's ID and record it for history (log) -->
                        <input type="hidden" name="editor-user-id" id="editor-user-id" value="<?php echo $_SESSION['ID']; ?>">
                        <button type="button" class="btn btn-secondary btn-sm btnRed" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-sm btnGreen text-light mx-1" name="save-edit-category-btn" value="Save">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Showing modal and editable contents of specific item for Edit button
            $('table').on('click', '.edit-category-btn', function(e) {
                e.preventDefault();
                var categoryId = $(this).data('category-id');
                $.ajax({
                    type: 'POST',
                    url: 'includes/category.inc.php',
                    data: {
                        'edit_category_view': true,
                        'category_id': categoryId
                    },
                    success: function(response) {
                        console.log(response);
                        $('.category-view').html(response);
                        $('#editModal').modal('show');
                    }
                });
            });
        });
    </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->

</body>

</html>