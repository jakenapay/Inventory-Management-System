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

// Pie chart 1 - For items
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

// Line chart 1 - For total items count by months
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

// New Chart here

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
    <link rel="stylesheet" href="assets/css/analytics.css?v=<?php echo time(); ?>">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-pO3t7S9e6j+54Qe47eqKbAYZ9k0mw0pNEca0Vc83P3QE6mzV3JpWGfo8yo2I5f5z" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRPe0rDEtvf2h+w2jcJfAZ3MTmFcIHd6v9aR3ZlpJ" crossorigin="anonymous">


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

    <!-- CDN for chartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- For canvaJS charts -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <!-- Ajax for chart -->
    <script>
        window.onload = function() {

            // For pie chart 1
            var pieChartData = <?php echo $pie1; ?>;
            var pieChartConfig = {
                type: 'pie',
                data: {
                    labels: pieChartData.map(data => data.label),
                    datasets: [{
                        data: pieChartData.map(data => data.y),
                        backgroundColor: [
                            'rgba(113, 180, 6, 0.7)',
                            'rgba(232, 202, 4, 0.7)',
                            'rgba(234, 100, 29, 0.7)'
                        ],
                        borderColor: [
                            'rgba(113, 180, 6, 1)',
                            'rgba(232, 202, 4, 1)',
                            'rgba(234, 100, 29, 1)',
                            // Add more colors if needed
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Items by Category'
                        },
                        legend: {
                            display: true,
                            position: 'right'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.parsed || 0;
                                    return label + ': ' + value;
                                }
                            }
                        }
                    }
                }
            };
            var pieChartCanvas = document.getElementById('pieChart1').getContext('2d');
            new Chart(pieChartCanvas, pieChartConfig);


            // For line chart 1
            var lineChartData2 = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
            var lineChartConfig = {
                type: 'line',
                data: {
                    labels: lineChartData2.map(data => data.label),
                    datasets: [{
                        label: 'Items Count per Month',
                        data: lineChartData2.map(data => data.y),
                        fill: false,
                        borderColor: 'rgba(113, 180, 6, 1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Requested Items per Month'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Months'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total Items Count'
                            }
                        }
                    }
                }
            };
            var lineChartCanvas = document.getElementById('lineChart1').getContext('2d');
            new Chart(lineChartCanvas, lineChartConfig);

            // New Chart here

        }
    </script>
</head>

<body>

    <?php include 'nav.php'; ?>

    <div id="wrapper">
        <div class="section px-5 pt-4">
            <div class="row">
                <!-- cart -->
                <div class="col">
                    <div class=" justify-content-end justify-content-center-md" style=" text-align: end; margin-right: 100px;">
                        <?php include './components/cart.php' ?>
                    </div>
                </div>
            </div>
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
            <!-- <div class="mt-5 row d-flex justify-content-center align-items-center mb-3">
                <div class="col-sm-12 col-md-12 col-lg-4 border rounded">
                    <p class="chart-title">Item Percentage</p>
                    <canvas id="pieChart1"></canvas>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-8 border rounded">
                    <p class="chart-title">Requested Items by Month</p>
                    <canvas id="lineChart1"></canvas>
                </div>
            </div> -->
            <!-- <div class="mt-5 row justify-content-center align-items-start mb-3">
                <div class="col-sm-12 col-md-12 col-lg-4 border rounded d-flex flex-column">
                    <p class="chart-title">Item Percentage</p>
                    <canvas id="pieChart1" style="flex: 1;"></canvas>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-8 border rounded d-flex flex-column">
                    <p class="chart-title">Requested Items by Month</p>
                    <canvas id="lineChart1" style="flex: 1;"></canvas>
                </div>
            </div> -->

            <div class="mt-5 row justify-content-center align-items-start mb-3">
                <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
                    <div class="border rounded p-3">
                        <p class="chart-title">Item Percentage</p>
                        <canvas id="pieChart1" style="width: 100%;"></canvas>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-8">
                    <div class="border rounded p-3">
                        <p class="chart-title">Requested Items by Month</p>
                        <canvas id="lineChart1" style="width: 100%;"></canvas>
                    </div>
                </div>
            </div>

        </div>
        <br>
    </div>




</body>


</html>