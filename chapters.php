<?php
include 'includes/config.inc.php';
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
    <?php include_once 'includes/headers.inc.php'; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/chapters.css?v=<?php echo time(); ?>">
</head>

<body>

    <?php include 'nav.php'; ?>
    <div id="wrapper">
        <div class="section mt-5">
            <div class="row justify-content-center align-items-center mt-3 mb-2">
                <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                    <?php include 'includes/message.inc.php'; ?>
                </div>
            </div>
            <div class="container">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="row g-0 d-flex justify-content-between align-items-center">
                        <div class="col-12 col-md-6 col-6">
                            <h3 class="m-0 font-weight-light title-chapter">Chapters</h3>
                        </div>
                        <div class="col-12 col-md-6 col-6 ">
                            <li class="nav-item nav-link d-flex justify-content-end" id="addNewChapter">
                                <button type="button" class="btn btn-success btnGreen btn-sm" data-toggle="modal" data-target="#exampleModal">
                                    <i class="fa-solid fa-plus addIcon"></i>New Chapter
                                </button>
                            </li>
                        </div>
                    </div>
                    <hr>
                    <!-- SELECT ALL CHAPTERS -->
                    <?php
                    $sql = "SELECT * FROM chapters";
                    try {
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Loop through the results
                        foreach ($result as $row) { ?>
                            <!-- Show chapters with Card -->
                            <div class="col-12 col-md-4 col-lg-3 py-2">
                                <div class="card" style="height: 150px;">
                                    <!-- <img class="card-img-top" src="..." alt="<?php echo $row['chapter_name']; ?>"> -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-start flex-column">
                                            <div class="mb-auto p-2">
                                                <h5 class="card-text font-weight-bold text-capitalize pb-0 mb-0"><?php echo $row['chapter_name']; ?></h5>
                                                <p class="card-text text-secondary text-capitalize"><?php echo $row['chapter_address']; ?></p>
                                            </div>
                                            <div class="col-12 p-2">
                                                <!-- Edit icon  -->
                                                <a href="http://" class="edit-chapter-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#editModal" data-chapter-id="<?php echo $row['chapter_id']; ?>" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                        header("location: ../items.php?m=" . $e->getMessage() . ""); // Failed
                        exit();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for new chapter -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="includes/chapters.inc.php" method="POST" enctype="multipart/form-data">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="exampleModalLabel">New Chapter</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-12 py-1">
                                <label for="chapter_name">Name</label>
                                <input type="text" class="form-control form-control-sm text-capitalize" id="chapter_name" name="chapter_name" placeholder="Chapter Name" required>
                            </div>
                            <div class="col-md-12 py-1">
                                <label for="chapter_address">Address</label>
                                <input type="text" class="form-control form-control-sm text-capitalize" id="chapter_address" name="chapter_address" placeholder="Chapter Address" required>
                            </div>
                            <div class="mb-2 mt-2"></div>
                            <hr class="hr" />
                            <!-- User's ID: Who is going to add the new chapter -->
                            <!-- For history/log purposes -->
                            <input type="hidden" value="<?php echo $_SESSION['ID']; ?>" name="user_id" id="user_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btnRed btn-secondary btn-sm btnRed" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-sm btnGreen text-light" name="add-chapter-btn" value="Add Chapter">
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- Modal for edit specific chapter -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="includes/chapters.inc.php" method="POST" enctype="multipart/form-data">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Edit Chapter</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="chapter_view" class="row justify-content-between align-items-center chapter-view">
                            <!-- This is where data being fetch from chapters.inc.php -->
                        </div>
                        <a href="http://" target="_blank" rel="noopener noreferrer"></a>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <!-- To get the user's ID and record it for history (log) -->
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>">
                        <button type="button" class="btn btn-secondary btn-sm btnRed" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-sm btnGreen text-light mx-1" name="save-edit-chapter-btn" value="Save">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Showing modal and editable contents of specific item for Edit button
            $('table').on('click', '.edit-chapter-btn', function(e) {
                e.preventDefault();
                var chaptId = $(this).data('chapter-id');
                $.ajax({
                    type: 'POST',
                    url: 'includes/chapters.inc.php',
                    data: {
                        'edit_chapter_view': true,
                        'chapter_id': chaptId
                    },
                    success: function(response) {
                        $('#chapter_view').html(response);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>