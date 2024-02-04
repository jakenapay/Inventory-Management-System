<?php
include 'includes/config.inc.php';
// Get the subdomain from the current URL
// Display the subdomain
// $subdomain = getSubdomain();
// echo $subdomain;


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

// For line chart 1 - for both approved and returned items
try {
    $sql = "SELECT DATE_FORMAT(history_date, '%Y-%m') AS month, 
                   SUM(CASE WHEN history_status = 'approved' THEN 1 ELSE 0 END) AS approved_count,
                   SUM(CASE WHEN history_status = 'approved' AND isReturned = 1 THEN 1 ELSE 0 END) AS returned_count
            FROM history
            GROUP BY YEAR(history_date), MONTH(history_date)
            ORDER BY YEAR(history_date), MONTH(history_date)";

    $stmt = $pdo->query($sql);

    $dataPoints = array();

    // Initialize an array to store counts for each month
    $monthlyApprovedCounts = array_fill_keys(array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'), 0);
    $monthlyReturnedCounts = array_fill_keys(array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'), 0);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $month = date("m", strtotime($row['month']));
        $approvedCount = $row['approved_count'];
        $returnedCount = $row['returned_count'];

        // Update the counts for the corresponding months
        $monthlyApprovedCounts[$month] = $approvedCount;
        $monthlyReturnedCounts[$month] = $returnedCount;
    }

    // Convert the counts to the required format
    foreach ($monthlyApprovedCounts as $month => $approvedCount) {
        $fullMonth = date("F", strtotime("2022-$month-01"));
        $returnedCount = $monthlyReturnedCounts[$month];

        $dataPoints[] = array("y" => $approvedCount, "returned" => $returnedCount, "label" => $fullMonth);
    }
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage();
}

// For bar chart 1 - Most requested item
try {
    $sql = "SELECT history_item_id, COUNT(*) AS request_count
            FROM history
            GROUP BY history_item_id
            ORDER BY request_count ASC
            LIMIT 5"; // Fetch the top 5 most requested items

    $stmt = $pdo->query($sql);

    $barChartDataPoints = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $itemId = $row['history_item_id'];

        // Fetch item name based on history_item_id
        $itemName = getItemName($itemId, $pdo);

        $requestCount = $row['request_count'];

        $barChartDataPoints[] = array("y" => $requestCount, "label" => $itemName);
    }
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage();
}

// For bar chart 1 - Most requested item
function getItemName($itemId, $pdo)
{
    // Assuming you have an 'items' table with columns 'item_id' and 'item_name'
    $sql = "SELECT item_name FROM items WHERE item_id = :itemId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result) ? $result['item_name'] : 'Unknown';
}

// For bar chart 2 but for specific users 
try {
    // Replace $adminId with the actual admin's user ID
    $adminId = $_SESSION['ID']; // Example admin ID

    $sql = "SELECT h.history_item_id, COUNT(*) AS request_count
            FROM history h
            JOIN users u ON h.history_user_id = u.user_id
            WHERE h.history_status = 'approved' AND h.history_user_id = :admin_id
            GROUP BY h.history_item_id
            ORDER BY request_count ASC
            LIMIT 5";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':admin_id', $adminId, PDO::PARAM_INT);
    $stmt->execute();

    $barChartTwoDataPoints = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $itemId = $row['history_item_id'];

        // Fetch item details using another query
        $itemSql = "SELECT item_name FROM items WHERE item_id = :item_id";
        $itemStmt = $pdo->prepare($itemSql);
        $itemStmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $itemStmt->execute();
        $itemRow = $itemStmt->fetch(PDO::FETCH_ASSOC);

        if ($itemRow) {
            $itemName = $itemRow['item_name'];

            $barChartTwoDataPoints[] = array("y" => $row['request_count'], "label" => $itemName);
        }
    }
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage();
}

// For pie chart of users total requests count
try {
    $sql = "SELECT u.user_id, CONCAT(u.user_firstname, ' ', u.user_lastname) AS 'user_name', COUNT(*) AS request_count
            FROM history h
            JOIN users u ON h.history_user_id = u.user_id
            WHERE h.history_status = 'approved'
            GROUP BY u.user_id
            ORDER BY request_count DESC LIMIT 5";

    $stmt = $pdo->query($sql);

    $userRequestsCountDataPoints = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $userId = $row['user_id'];
        $userName = $row['user_name'];
        $requestCount = $row['request_count'];

        $userRequestsCountDataPoints[] = array("y" => $requestCount, "label" => $userName);
    }
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage();
}

try {
    // Establish your database connection here

    // Fetch the chapters/branches
    $chapterSql = "SELECT chapter_name FROM chapters";
    $chapterStmt = $pdo->query($chapterSql);
    $chapters = $chapterStmt->fetchAll(PDO::FETCH_COLUMN);

    // Initialize an associative array to store counts for each chapter
    $chapterCounts = array_fill_keys($chapters, 0);

    // Fetch the count of items for each user's chapter
    $dataSql = "SELECT c.chapter_name, COUNT(*) AS transaction_count
                FROM history h
                JOIN users u ON h.history_user_id = u.user_id
                JOIN chapters c ON u.user_chapter = c.chapter_id
                WHERE h.history_status = 'approved'
                GROUP BY c.chapter_id";

    $dataStmt = $pdo->query($dataSql);
    while ($row = $dataStmt->fetch(PDO::FETCH_ASSOC)) {
        $chapterName = $row['chapter_name'];
        $chapterCounts[$chapterName] = $row['transaction_count'];
    }

    // Extract counts in the correct order based on $chapters
    $chapterTotalTransactionsDataPoints = array_values(array_intersect_key($chapterCounts, array_flip($chapters)));
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage();
}

// Bar graph for 
try {
    // Fetch total transaction counts for each chapter
    $sql = "SELECT c.chapter_name, COUNT(*) AS transaction_count
            FROM history h
            JOIN users u ON h.history_user_id = u.user_id
            JOIN chapters c ON u.user_chapter = c.chapter_id
            GROUP BY c.chapter_id";

    $stmt = $pdo->query($sql);

    $chapterTotalTransactions = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $chapterName = $row['chapter_name'];
        $transactionCount = $row['transaction_count'];

        $chapterTotalTransactions[] = array("y" => $transactionCount, "label" => $chapterName);
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
    <?php include_once './includes/headers.inc.php'; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/analytics.css?v=<?php echo time(); ?>">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-pO3t7S9e6j+54Qe47eqKbAYZ9k0mw0pNEca0Vc83P3QE6mzV3JpWGfo8yo2I5f5z" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRPe0rDEtvf2h+w2jcJfAZ3MTmFcIHd6v9aR3ZlpJ" crossorigin="anonymous">


    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script> -->

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

            // COLOR PALLETE
            // backgroundColor: [
            //                 'rgba(113, 180, 6, 0.7)',
            //                 'rgba(232, 202, 4, 0.7)',
            //                 'rgba(234, 100, 29, 0.7)',
            //                 'rgba(120, 8, 255, 0.7)',
            //                 'rgba(54, 162, 235, 0.7)',
            //             ],
            //             borderColor: [
            //                 'rgba(113, 180, 6, 1)',
            //                 'rgba(232, 202, 4, 1)',
            //                 'rgba(234, 100, 29, 1)',
            //                 'rgba(120, 8, 255, 1)',
            //                 'rgba(54, 162, 235, 1)',
            //             ],

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
                            'rgba(234, 100, 29, 0.7)',
                            'rgba(120, 8, 255, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                        ],
                        borderColor: [
                            'rgba(113, 180, 6, 1)',
                            'rgba(232, 202, 4, 1)',
                            'rgba(234, 100, 29, 1)',
                            'rgba(120, 8, 255, 1)',
                            'rgba(54, 162, 235, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: false,
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
                            label: 'Approved Items Count per Month',
                            data: lineChartData2.map(data => data.y),
                            fill: true,
                            borderColor: 'rgba(113, 180, 6, 1)',
                            backgroundColor: 'rgba(113, 180, 6, 0.1)',
                            tension: 0.1
                        },
                        {
                            label: 'Returned Items Count per Month',
                            data: lineChartData2.map(data => data.returned),
                            fill: true,
                            borderColor: 'rgba(234, 100, 29, 1)',
                            backgroundColor: 'rgba(234, 100, 29, 0.1)',
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: false,
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

            // For horizontal bar chart 1
            var barChartData = <?php echo json_encode($barChartDataPoints, JSON_NUMERIC_CHECK); ?>;
            var barChartConfig = {
                type: 'bar',
                data: {
                    labels: barChartData.map(data => data.label),
                    datasets: [{
                        label: 'Requested Items',
                        data: barChartData.map(data => data.y),
                        backgroundColor: [
                            'rgba(232, 202, 4, 0.7)',
                        ],
                        borderColor: [
                            'rgba(232, 202, 4, 1)',

                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Items'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total'
                            }
                        }
                    }
                }
            };
            var barChartCanvas = document.getElementById('barChart1').getContext('2d');
            new Chart(barChartCanvas, barChartConfig);

            // For bar chart 2 for specific ADMIN
            var adminPersonalData = <?php echo json_encode($barChartTwoDataPoints, JSON_NUMERIC_CHECK); ?>;
            var barChartConfig2 = {
                type: 'bar',
                data: {
                    labels: adminPersonalData.map(data => data.label),
                    datasets: [{
                        label: 'Top Requested Items',
                        data: adminPersonalData.map(data => data.y),
                        backgroundColor: [
                            'rgba(234, 100, 29, 0.7)',

                        ],
                        borderColor: [
                            'rgba(234, 100, 29, 1)',

                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: false,
                            text: '<?php echo $_SESSION['FN']; ?>\'s Most Requested Items'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Items'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Request Count'
                            }
                        }
                    }
                }
            };
            var barChartCanvas = document.getElementById('barChart2').getContext('2d');
            new Chart(barChartCanvas, barChartConfig2);

            // For pie chart that shows top 5 user total counts
            var userDataPoints = <?php echo json_encode($userRequestsCountDataPoints, JSON_NUMERIC_CHECK); ?>;
            // For donut chart
            var donutChartData = {
                labels: userDataPoints.map(data => data.label),
                datasets: [{
                    data: userDataPoints.map(data => data.y),
                    backgroundColor: [
                        'rgba(113, 180, 6, 0.7)',
                        'rgba(232, 202, 4, 0.7)',
                        'rgba(234, 100, 29, 0.7)',
                        'rgba(120, 8, 255, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                    ],
                    borderColor: [
                        'rgba(113, 180, 6, 1)',
                        'rgba(232, 202, 4, 1)',
                        'rgba(234, 100, 29, 1)',
                        'rgba(120, 8, 255, 1)',
                        'rgba(54, 162, 235, 1)',
                    ],
                    borderWidth: 1
                }]
            };
            var donutChartConfig = {
                type: 'pie',
                data: donutChartData,
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Top 5 Users'
                        },
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            };
            var donutChartCanvas = document.getElementById('donutChart').getContext('2d');
            new Chart(donutChartCanvas, donutChartConfig);

            // For radar chart
            const data = {
                labels: <?php echo json_encode($chapters); ?>,
                datasets: [{
                    label: 'Total Transactions',
                    data: <?php echo json_encode($chapterTotalTransactionsDataPoints); ?>,
                    fill: true,
                    backgroundColor: 'rgba(113, 180, 6, 0.2)',
                    borderColor: 'rgb(113, 180, 6)',
                    pointBackgroundColor: 'rgb(113, 180, 6)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(113, 180, 6)'
                }]
            };
            const config = {
                type: 'radar',
                data: data,
                options: {
                    elements: {
                        line: {
                            borderWidth: 3
                        }
                    }
                }
            };
            // Get the radar chart canvas
            const radarChartCanvas = document.getElementById('radarChart').getContext('2d');
            // Create the radar chart
            new Chart(radarChartCanvas, config);


            // NEW
            var barChartData = <?php echo json_encode($chapterTotalTransactions, JSON_NUMERIC_CHECK); ?>;
            var barChartConfig = {
                type: 'bar',
                data: {
                    labels: barChartData.map(data => data.label),
                    datasets: [{
                        label: 'Chapters',
                        data: barChartData.map(data => data.y),
                        backgroundColor: 'rgba(120, 8, 255, 0.7)',
                        borderColor: 'rgba(120, 8, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Chapters'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total Transactions'
                            }
                        }
                    }
                }
            };

            var barChartCanvas = document.getElementById('chapterTransactionsChart').getContext('2d');
            new Chart(barChartCanvas, barChartConfig);
        };
    </script>
</head>

<body>

    <?php include 'nav.php'; ?>

    <div id="wrapper">
        <div class="section px-lg-5 pt-4">
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

            <?php if (($_SESSION['CT'] === 1)) { ?>
                <form action="print_page.php" method="get" target="_blank">
                    <input type="hidden" name="print" value="<?php echo password_hash('printAll', PASSWORD_DEFAULT); ?>">
                    <button type="submit" class="btn btn-sm btnGreen btnFloat text-white"><i class="text-white fa-solid fa-print" style="padding-left: 0;"></i>Print All</button>
                </form>
                <div class="mt-5 row justify-content-center align-items-start mb-3">
                    <!-- Users total requests count -->
                    <div class="col-sm-12 col-md-12 col-lg-6 mb-4">
                        <form action="print_page.php" method="get" target="_blank">
                            <div class="border rounded p-3">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <p class="m-0 chart-title">Chapters Total Requests Count</p>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-end">
                                        <input type="hidden" name="print" value="<?php echo password_hash('chaptersTotalRequestCount', PASSWORD_DEFAULT); ?>">
                                        <button type="submit" class="btn btn-sm btnGreen text-white"><i class="text-white fa-solid fa-print" style="padding-left: 0;"></i>Print</button>
                                    </div>
                                </div>
                                <canvas id="chapterTransactionsChart" style="width: 100%;"></canvas>
                            </div>
                        </form>
                    </div>
                    <!-- Requested items by month -->
                    <div class="col-sm-12 col-md-12 col-lg-6 mb-3">
                        <form action="print_page.php" method="get" target="_blank">
                            <div class="border rounded p-3">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <p class="m-0 chart-title">Requested Items by Month</p>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-end">
                                        <input type="hidden" name="print" value="<?php echo password_hash('requestedItemsByMonth', PASSWORD_DEFAULT); ?>">
                                        <button type="submit" class="btn btn-sm btnGreen text-white"><i class="text-white fa-solid fa-print" style="padding-left: 0;"></i>Print</button>
                                    </div>
                                </div>
                                <canvas id="lineChart1" style="width: 100%;"></canvas>
                            </div>
                        </form>
                    </div>
                    <!-- Most requested items -->
                    <div class="col-sm-12 col-md-12 col-lg-6 mb-3">
                        <form action="print_page.php" method="get" target="_blank">
                            <div class="border rounded p-3">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <p class="m-0 chart-title">Most Requested Items</p>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-end">
                                        <input type="hidden" name="print" value="<?php echo password_hash('mostRequestedItems', PASSWORD_DEFAULT); ?>">
                                        <button type="submit" class="btn btn-sm btnGreen text-white"><i class="text-white fa-solid fa-print" style="padding-left: 0;"></i>Print</button>
                                    </div>
                                </div>
                                <canvas id="barChart1" style="width: 100%;"></canvas>
                            </div>
                        </form>
                    </div>
                    <!-- Your top items -->
                    <div class="col-sm-12 col-md-12 col-lg-6 mb-3">
                        <form action="print_page.php" method="get" target="_blank">
                            <div class="border rounded p-3">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <p class="m-0 chart-title">Your Top Items</p>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-end">
                                        <input type="hidden" name="print" value="<?php echo password_hash('yourTopItems', PASSWORD_DEFAULT); ?>">
                                        <button type="submit" class="btn btn-sm btnGreen text-white"><i class="text-white fa-solid fa-print" style="padding-left: 0;"></i>Print</button>
                                    </div>
                                </div>
                                <canvas id="barChart2" style="width: 100%;"></canvas>
                            </div>
                        </form>
                    </div>
                    <!-- Chapter total transactions -->
                    <div class="col-sm-12 col-md-12 col-lg-4 mb-4">
                        <form action="print_page.php" method="get" target="_blank">
                            <div class="border rounded p-3">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <p class="m-0 chart-title">Chapters Total Transactions</p>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-end">
                                        <input type="hidden" name="print" value="<?php echo password_hash('chaptersTotalTransactions', PASSWORD_DEFAULT); ?>">
                                        <button type="submit" class="btn btn-sm btnGreen text-white"><i class="text-white fa-solid fa-print" style="padding-left: 0;"></i>Print</button>
                                    </div>
                                </div>
                                <canvas id="radarChart" style="width: 100%;"></canvas>
                            </div>
                        </form>
                    </div>
                    <!-- Users total requests count -->
                    <div class="col-sm-12 col-md-12 col-lg-4 mb-4">
                        <form action="print_page.php" method="get" target="_blank">
                            <div class="border rounded p-3">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <p class="m-0 chart-title">Users Total Requests Count</p>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-end">
                                        <input type="hidden" name="print" value="<?php echo password_hash('usersTotalRequestsCount', PASSWORD_DEFAULT); ?>">
                                        <button type="submit" class="btn btn-sm btnGreen text-white"><i class="text-white fa-solid fa-print" style="padding-left: 0;"></i>Print</button>
                                    </div>
                                </div>
                                <canvas id="donutChart" style="width: 100%;"></canvas>
                            </div>
                        </form>
                    </div>
                    <!-- Item Percentage -->
                    <div class="col-sm-12 col-md-12 col-lg-4 mb-3">
                        <form action="print_page.php" method="get" target="_blank">
                            <div class="border rounded p-3">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <p class="m-0 chart-title">Item Percentage</p>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-end">
                                        <input type="hidden" name="print" value="<?php echo password_hash('itemPercentage', PASSWORD_DEFAULT); ?>">
                                        <button type="submit" class="btn btn-sm btnGreen text-white"><i class="text-white fa-solid fa-print" style="padding-left: 0;"></i>Print</button>
                                    </div>
                                </div>
                                <canvas id="pieChart1" style="width: 100%;"></canvas>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <?php
            // Check if the user is an admin
            $isAdmin = ($_SESSION['CT'] === 1);

            // If the user is not an admin, include landing-page.php
            if (!$isAdmin) {
                include 'landing-page.php';
            }
            ?>
        </div>
        <br>
    </div>



</body>


</html>