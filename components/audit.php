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
                        <h3 class="m-0 font-weight-light title-chapter">Audits</h3>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Audit ID</th>
                                <th>Name</th>
                                <th>Action</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- SELECT ALL CHAPTERS -->
                            <?php
                            $sql = "SELECT a.audit_id, CONCAT(u.user_firstname, ' ', u.user_lastname) AS user_name, a.* FROM `audit` AS a INNER JOIN users AS u ON u.user_id = a.audit_user_id;";
                            try {
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Loop through the results
                                foreach ($result as $row) { ?>
                                    <tr>
                                        <td class="text-capitalize"><?php echo $row['audit_id']; ?></td>
                                        <td class="text-capitalize"><?php echo $row['user_name']; ?></td>
                                        <td class="text-capitalize"><?php echo $row['audit _action']; ?></td>
                                        <td class="text-capitalize"><?php echo $row['audit_time']; ?></td>
                                    </tr>
                            <?php }
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                                header("location: ../items.php#audit?m=" . $e->getMessage() . ""); // Failed
                                exit();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="section mt-3">
                    <div>
                        <input type="hidden" id="session_chapter" value="<?php echo $_SESSION['CH'] ?>">
                        <input type="date" name="from_date" id="from_date">
                        <input type="date" name="to_date" id="to_date">
                        <button class="btn btn-success btn-sm bn_reset">Reset</button>
                        <button class="btn btn-success btn-sm bn_generate">Generate</button>
                        <button class="btn btn-success btn-sm" id="printAudit">Print</button>
                    </div>


                    <div>
                        <table id="tblData" class="table-hover table-sm">

                        </table>


                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    $('.bn_generate').click(function() {
        const fromDate = document.getElementById('from_date').value;
        const toDate = document.getElementById('to_date').value;

        console.log(fromDate)
        console.log(toDate)
        //const chapter = document.getElementById('session_chapter').value;
        //console.log(chapter);
        $.ajax({
            type: "post",
            url: "./includes/audit.inc.php",
            data: {
                fromDate: fromDate,
                toDate: toDate,
            },
            success: function(response) {
                console.log(response)
                if (response) {
                    $('#tblData').html(response);
                }
            }
        });

    })

    $('.bn_reset').click(() => {
        $('#tblData').html("");
    })

    document.getElementById("printAudit").addEventListener("click", function() {
        // Open a new window and print the table content
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print</title></head><body>');
        printWindow.document.write('<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; } th { background-color: #f2f2f2; }</style>');
        printWindow.document.write(document.getElementById("tblData").outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
</script>