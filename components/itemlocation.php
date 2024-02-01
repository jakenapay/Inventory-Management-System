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
                        <h3 class="m-0 font-weight-light title-chapter">Item Location</h3>
                    </div>
                    <div class="col-12 col-md-6 col-6">
                        <div class="nav-item nav-link d-flex justify-content-end">
                            <button type="button" id="addNewChapter" class="btn btn-success btnGreen btn-sm" data-toggle="modal" data-target="#LocationModal">
                                <i class="fa-solid fa-plus addIcon"></i>New Location/Storage
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
                                <th>Container Name</th>
                                <th>Location Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- SELECT ALL CHAPTERS -->
                            <?php
                            $chapter = $_SESSION['CH'];
                            $sql = "SELECT * FROM item_location WHERE chapter = $chapter ";
                            try {
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Loop through the results
                                foreach ($result as $row) { ?>
                                    <tr>
                                        <td class="text-capitalize"><?php echo $row['location_id']; ?></td>
                                        <td class="text-capitalize"><?php echo $row['container_name']; ?></td>
                                        <td class="text-capitalize"><?php echo $row['location_name']; ?></td>
                                    </tr>
                            <?php }
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                                header("location: ../chapters.php?m=" . $e->getMessage() . ""); // Failed
                                exit();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal for new chapter -->
    <div class="modal fade" id="LocationModal" tabindex="-1" role="dialog" aria-labelledby="LocationModalLabel" aria-hidden="true">
        <form action="includes/itemlocation.inc.php" method="POST" enctype="multipart/form-data">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title font-weight-bold" id="LocationModalLabel">New Container/Location</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-12 py-1">
                                <label for="container_name">Container Name</label>
                                <input type="text" class="form-control form-control-sm text-capitalize" id="container_name" name="container_name" placeholder="Container" required>
                            </div>
                            <div class="col-md-12 py-1">
                                <label for="location_name">Location</label>
                                <input type="text" class="form-control form-control-sm text-capitalize" id="location_name" name="location_name" placeholder="location_name" required>
                            </div>
                            <div class="mb-2 mt-2"></div>
                            <hr class="hr" />
                            <input type="hidden" value="<?php echo $_SESSION['CH']; ?>" name="user_ch" id="user_ch">
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


    <!-- Edit modal for specific user -->
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
                        <div class="row justify-content-between align-items-center chapter-view">
                            <!-- This is where data being fetch from chapters.inc.php -->
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <!-- To get the user's ID and record it for history (log) -->
                        <input type="hidden" name="editor-user-id" id="editor-user-id" value="<?php echo $_SESSION['ID']; ?>">
                        <button type="button" class="btn btn-secondary btn-sm btnRed" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-sm btnGreen text-light mx-1" name="save-edit-chapter-btn" value="Save">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="container-fluid">
        <form id="myForm" action="includes/itemcontainer.inc.php" method="post">
            <select name="item_container" class="form-select form-select-sm mt-5" id="item_container" onchange="getSelectedValue()">
                <option value="">Select</option>
                <?php
                foreach ($result as $row) { ?>
                    <option value="<?php echo $row['container_name'] ?>"><?php echo $row['container_name'] ?></option>
                <?php }
                ?>
                <option value="all">All</option>
            </select>
        </form>

        <button id="printButton">Print Page</button>
    </div>

    <table class="table" id="dTable">

    </table>

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
                        console.log(response);
                        $('.chapter-view').html(response);
                        $('#editModal').modal('show');
                    }
                });
            });
        });

        function getSelectedValue() {
            const user_ch = document.getElementById('user_ch').value;
            // Get the <select> element
            var selectElement = document.getElementById("item_container");

            // Get the selected option's value
            var selectedValue = selectElement.value;

            // Use the selected value as needed
            console.log("Selected Item Container: " + selectedValue);
            console.log(user_ch);
            // If you want to submit the form with the selected value, you can uncomment the following line:
            //document.getElementById("myForm").submit();

            $.ajax({
                type: "POST",
                url: "includes/itemcontainer.inc.php",
                data: {
                    selectedValue: selectedValue,
                    chapter: user_ch
                },
                success: function(response) {
                    $('#dTable').html(response);
                }
            });
        }

        document.getElementById("printButton").addEventListener("click", function() {
            // Open a new window and print the table content
            var printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print</title></head><body>');
            printWindow.document.write('<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; } th { background-color: #f2f2f2; }</style>');
            printWindow.document.write(document.getElementById("dTable").outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
    </script>

   
</body>

</html>