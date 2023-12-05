<?php
include 'includes/config.inc.php';
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

<!-- FOR CHARTS -->
<?php
try {

    // Fetching category names from the database
    $categoryData = array();
    $sqlCategories = "SELECT item_category_id, item_category_name FROM items_category";
    $resultCategories = $pdo->query($sqlCategories);

    while ($row = $resultCategories->fetch(PDO::FETCH_ASSOC)) {
        $categoryData[$row['item_category_id']] = $row['item_category_name'];
    }

    // SQL query to fetch item category and quantity
    $sql = "SELECT item_category, COUNT(*) AS total FROM items GROUP BY item_category";

    $result = $pdo->query($sql);

    // Initializing the data array
    $dataPoints = array();

    // Loop through the fetched data and populate the array
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Get category name based on category ID
        $categoryName = isset($categoryData[$row['item_category']]) ? $categoryData[$row['item_category']] : 'Unknown';

        $dataPoints[] = array("label" => $categoryName, "y" => (int)$row['total']);
    }

    // Convert data array to JSON format
    $pie1 = json_encode($dataPoints);
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage();
}

try {
    // Establish your database connection here

    $sql = "SELECT MONTH(history_date) AS month, COUNT(*) AS total_count
            FROM history
            WHERE history_status = 'approved'
            GROUP BY YEAR(history_date), MONTH(history_date)
            ORDER BY YEAR(history_date) DESC, MONTH(history_date) DESC";

    $stmt = $pdo->query($sql);

    $dataPoints = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $month = date("F Y", mktime(0, 0, 0, $row['month'], 1, date("Y")));
        $totalCount = $row['total_count'];

        $dataPoints[] = array("y" => $totalCount, "label" => $month);
    }
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage();
}
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
    <link rel="stylesheet" href="assets/css/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/cart.css?v=<?php echo time(); ?>">

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
    </script>

    <!-- For chartJS charts -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <!-- Ajax for chart -->
    <script>
        window.onload = function() {
            var pie1 = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Items by Category",
                    fontSize: 19,
                    fontFamily: 'Helvetica'
                },
                backgroundColor: "#f8f8f8",
                data: [{
                    type: "pie",
                    showInLegend: null,
                    toolTipContent: "{label}: <strong>{y}</strong>",
                    indexLabel: "{label} - #percent%",
                    dataPoints: <?php echo $pie1; ?>
                }]
            });

            var lineChart = new CanvasJS.Chart("lineChart1", {
                title: {
                    text: "History Items Count per Month",
                    fontSize: 19,
                    fontFamily: 'Helvetica',
                },
                backgroundColor: "#f8f8f8",
                axisY: {
                    title: "Total History Items Count"
                },
                axisX: {
                    title: "Months"
                },
                data: [{
                    type: "line",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });

            // Render charts
            pie1.render();
            lineChart.render();
        }
    </script>
</head>

<body>

    <?php include 'nav.php'; ?>

    <div id="wrapper">
        <div class="section px-5 pt-4">
            <div class="row justify-content-center align-items-center">
                <!-- TECHNOLOGY -->
                <div class="box col-sm-12 col-md-4 col-lg-2">
                    <i class="fa-solid fa-computer icon"></i>
                    <p class="m-0">Technology</p>
                    <?php
                    // Prepare and execute the query
                    $query = "SELECT COUNT(item_category) FROM items WHERE item_category = 1";
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
                <div class="box col-sm-12 col-md-4 col-lg-2">
                    <i class="fa-solid fa-glass-water icon"></i>
                    <p class="m-0">Consumable</p>
                    <?php
                    // Prepare and execute the query
                    $query = "SELECT COUNT(item_category) FROM items WHERE item_category = 2";
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
                <div class="box col-sm-12 col-md-4 col-lg-2">
                    <i class="fa-solid fa-stapler icon"></i>
                    <p class="m-0 px-2">HQ Supply</p>
                    <?php
                    // Prepare and execute the query
                    $query = "SELECT COUNT(item_category) FROM items WHERE item_category = 3";
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
                <!-- CHAPTER -->
                <div class="box col-sm-12 col-md-4 col-lg-2">
                    <i class="fa-solid fa-map icon"></i>
                    <p class="m-0 px-2">Chapters</p>
                    <?php
                    // Prepare and execute the query
                    $query = "SELECT COUNT(*) FROM chapters";
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
            <div class="mt-5 row d-flex justify-content-center align-items-center">
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div id="chartContainer"></div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div id="lineChart1"></div>
                </div>
            </div>

        </div>
        <br>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://cdn.canvasjs.com/canvasjs.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>


</html>