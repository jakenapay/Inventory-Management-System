          <!-- FOR CHARTS -->
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

            function getTotalItemsByCategory($pdo, $categoryId)
            {
                try {
                    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM items WHERE item_category = :categoryId");
                    $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
                    $stmt->execute();

                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    return $result['total'];
                } catch (PDOException $e) {
                    echo "Failed to get total items: " . $e->getMessage();
                    return 0;
                }
            }

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
            $itemPercentageData = array();

            // Get the total count of all items
            $totalItems = 0;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $totalItems += $row['total'];
            }

            // Calculate and populate the array with percentages
            $result->execute();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                // Get category name based on category ID
                $categoryName = isset($categoryData[$row['item_category']]) ? $categoryData[$row['item_category']] : 'Unknown';

                $totalCategoryItems = getTotalItemsByCategory($pdo, $row['item_category']);
                $percentage = round(($totalCategoryItems / $totalItems) * 100, 2);

                $itemPercentageData[] = array(
                    "category_id" => $row['item_category'],
                    "category_name" => $categoryName,
                    "total_items" => $totalCategoryItems,
                    "percentage" => $percentage
                );
            }

            // Convert data array to JSON format
            $pie1 = json_encode($itemPercentageData);

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

                $highestMonthAccepted = ''; // Initialize variables to store the highest month
                $highestMonthReturned = '';

                // Find the highest month for accepted and returned items
                foreach ($dataPoints as $dataPoint) {
                    if ($dataPoint['y'] == max(array_column($dataPoints, 'y'))) {
                        $highestMonthAccepted = $dataPoint['label'];
                    }
                    if ($dataPoint['returned'] == max(array_column($dataPoints, 'returned'))) {
                        $highestMonthReturned = $dataPoint['label'];
                    }
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
            ORDER BY request_count DESC
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

            // Function to get user ID by name
            function getUserIdByName($userName)
            {
                global $pdo; // Assuming $pdo is your database connection

                try {
                    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE CONCAT(user_firstname, ' ', user_lastname) = :userName");
                    $stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
                    $stmt->execute();

                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result) {
                        return $result['user_id'];
                    } else {
                        return null; // User not found
                    }
                } catch (PDOException $e) {
                    echo "Failed to get user ID: " . $e->getMessage();
                    return null;
                }
            }

            // Function to get chapter by user ID
            function getChapterByUserId($userId)
            {
                global $pdo; // Assuming $pdo is your database connection

                try {
                    $stmt = $pdo->prepare("SELECT chapter_name FROM chapters WHERE chapter_id = (SELECT user_chapter FROM users WHERE user_id = :userId)");
                    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                    $stmt->execute();

                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result) {
                        return $result['chapter_name'];
                    } else {
                        return "Unknown"; // If chapter not found, return "Unknown" or handle it as needed
                    }
                } catch (PDOException $e) {
                    echo "Failed to get chapter: " . $e->getMessage();
                    return "Unknown"; // Handle error by returning "Unknown" or handle it as needed
                }
            }

            ?>

          <!DOCTYPE html>
          <html lang="en">

          <head>
              <meta charset="UTF-8">
              <meta http-equiv="X-UA-Compatible" content="IE=edge">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <!-- Include jQuery -->
              <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
              <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
              <!-- CSS -->
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
              <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
              <link rel="stylesheet" href="assets/css/nav.css?v=<?php echo time(); ?>">
              <link rel="stylesheet" href="assets/css/home.css?v=<?php echo time(); ?>">
              <link rel="stylesheet" href="assets/css/analytics.css?v=<?php echo time(); ?>">
              <link rel="stylesheet" href="assets/css/print_page.css?v=<?php echo time(); ?>">

              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-pO3t7S9e6j+54Qe47eqKbAYZ9k0mw0pNEca0Vc83P3QE6mzV3JpWGfo8yo2I5f5z" crossorigin="anonymous">
              </script>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRPe0rDEtvf2h+w2jcJfAZ3MTmFcIHd6v9aR3ZlpJ" crossorigin="anonymous">
              <style>
                  table {
                      border-collapse: collapse;
                      width: 100%;
                  }

                  th,
                  td {
                      border: 1px solid #dddddd;
                      text-align: left;
                      padding: 8px;
                  }

                  th {
                      background-color: #f2f2f2;
                  }

                  @media print {
                      #printButton {
                          display: none;
                      }
                  }
              </style>
              <!-- font awesome icons -->
              <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
              <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
          </head>

          <body id="body">

              <?php $graphToPrint = isset($_GET['print']) ? $_GET['print'] : null; ?>

              <div id="wrapper" style="padding-top: 2rem;" class="pmx-5">
                  <!-- BUTTONS -->
                  <div class="mx-5" style="padding-bottom: 2rem;">
                      <!-- Button that print page -->
                      <button id="printButton" class="btn btn-sm btnGreen text-white"><i class="text-white fa-solid fa-print" style="padding-left: 0;"></i>Print Page</button>
                      <!-- Button to save as PDF -->
                      <button id="savePdfButton" onclick="generatePDF()" class="btn btn-sm btnPurple text-white"><i class="text-white fa-solid fa-download" style="padding-left: 0;"></i>Save as PDF</button>
                  </div>

                  <?php if (password_verify('requestedItemsByMonth', $graphToPrint)) { ?>
                      <!-- 2 -->
                      <div class="mx-5">
                          <!-- requestedItemsByMonth -->
                          <h3 class="title mt-5">Insights: Approved and Returned Items Over Time</h3>
                          <p>
                              The month with the highest number of accepted requests is: <?php echo $highestMonthAccepted; ?><br>
                              The month with the highest number of returned items is: <?php echo $highestMonthReturned; ?>
                          </p>
                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>Month</th>
                                      <th>Accepted</th>
                                      <th>Returned</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($dataPoints as $dataPoint) : ?>
                                      <tr>
                                          <td><?php echo $dataPoint['label']; ?></td>
                                          <td><?php echo $dataPoint['y']; ?></td>
                                          <td><?php echo $dataPoint['returned']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>
                                  <!-- Summary Row -->
                                  <tr>
                                      <td><strong>Total</strong></td>
                                      <td><strong><?php echo array_sum(array_column($dataPoints, 'y')); ?></strong></td>
                                      <td><strong><?php echo array_sum(array_column($dataPoints, 'returned')); ?></strong></td>
                                  </tr>
                              </tbody>
                          </table>

                          <!-- Footer -->
                          <footer>
                              <br>
                              <p class="text-muted text-sm">DevconKids Inventory &copy; <?php echo date("Y"); ?> | <?php echo date("F j, Y H:i:s"); ?></p>
                          </footer>
                      </div>

                  <?php } else if (password_verify('mostRequestedItems', $graphToPrint)) { ?>
                      <!-- 3 -->
                      <div class="mx-5">
                          <!-- mostRequestedItems -->
                          <h2 class="title mt-5">Insights: Most Requested Items</h2>
                          <?php
                            $maxRequestsItem = null;
                            $maxRequests = 0;

                            foreach ($barChartDataPoints as $dataPoint) {
                                // Check if the current item has more requests than the current max
                                if ($dataPoint['y'] > $maxRequests) {
                                    $maxRequests = $dataPoint['y'];
                                    $maxRequestsItem = $dataPoint['label'];
                                }
                            }
                            ?>
                          <p>Items with the highest number of requests, such as "<strong><?php echo $maxRequestsItem; ?></strong>", are likely to be restocked more frequently.</p>
                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>Item</th>
                                      <th>Requests</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($barChartDataPoints as $dataPoint) : ?>
                                      <tr>
                                          <td><?php echo $dataPoint['label']; ?></td>
                                          <td><?php echo $dataPoint['y']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>

                                  <!-- Total Row -->
                                  <tr>
                                      <td><strong>Total</strong></td>
                                      <td><strong><?php echo array_sum(array_column($barChartDataPoints, 'y')); ?></strong></td>
                                  </tr>
                              </tbody>
                          </table>
                          <!-- Footer -->
                          <footer>
                              <br>
                              <p class="text-muted text-sm">DevconKids Inventory &copy; <?php echo date("Y"); ?> | <?php echo date("F j, Y H:i:s"); ?></p>
                          </footer>
                      </div>

                  <?php } else if (password_verify('yourTopItems', $graphToPrint)) { ?>
                      <div class="mx-5">
                          <!-- yourTopItems -->
                          <h2 class="title mt-5">Insights: Most Requested Items by You</h2>
                          <?php
                            $maxRequestsItemTwo = null;
                            $maxRequestsTwo = 0;

                            foreach ($barChartTwoDataPoints as $dataPoint) {
                                // Check if the current item has more requests than the current max
                                if ($dataPoint['y'] > $maxRequestsTwo) {
                                    $maxRequestsTwo = $dataPoint['y'];
                                    $maxRequestsItemTwo = $dataPoint['label'];
                                }
                            }
                            ?>

                          <p>Items with the highest number of requests by you, such as "<strong><?php echo $maxRequestsItemTwo; ?></strong>", are likely to be restocked more frequently.</p>
                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>Item</th>
                                      <th>Requests</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($barChartTwoDataPoints as $dataPoint) : ?>
                                      <tr>
                                          <td><?php echo $dataPoint['label']; ?></td>
                                          <td><?php echo $dataPoint['y']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>

                                  <!-- Total Row -->
                                  <tr>
                                      <td><strong>Total</strong></td>
                                      <td><strong><?php echo array_sum(array_column($barChartTwoDataPoints, 'y')); ?></strong></td>
                                  </tr>
                              </tbody>
                          </table>

                          <!-- Footer -->
                          <footer>
                              <br>
                              <p class="text-muted text-sm">DevconKids Inventory &copy; <?php echo date("Y"); ?> | <?php echo date("F j, Y H:i:s"); ?></p>
                          </footer>
                      </div>


                  <?php } else if (password_verify('chaptersTotalTransactions', $graphToPrint) || (password_verify('chaptersTotalRequestCount', $graphToPrint))) { ?>
                      <!-- 5 -->
                      <div class="mx-5">
                          <!-- chaptersTotalTransactions -->
                          <?php
                            $maxTransactionsChapter = null;
                            $maxTransactionsCount = 0;

                            foreach ($chapterTotalTransactions as $dataPoint) {
                                // Check if the current chapter has more transactions than the current max
                                if ($dataPoint['y'] > $maxTransactionsCount) {
                                    $maxTransactionsCount = $dataPoint['y'];
                                    $maxTransactionsChapter = $dataPoint['label'];
                                }
                            }
                            ?>

                          <h2 class="title mt-5">Insights: Chapters Total Transaction Count</h2>
                          <p>The chapter with the most total transaction count as of <?php echo date("F j, Y H:i:s"); ?> is the chapter "<strong><?php echo $maxTransactionsChapter; ?></strong>".</p>
                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>Chapter/Branch</th>
                                      <th>Total Request Count</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($chapterTotalTransactions as $dataPoint) : ?>
                                      <tr>
                                          <td><?php echo $dataPoint['label']; ?></td>
                                          <td><?php echo $dataPoint['y']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>

                                  <!-- Total Row -->
                                  <tr>
                                      <td><strong>Total</strong></td>
                                      <td><strong><?php echo array_sum(array_column($chapterTotalTransactions, 'y')); ?></strong></td>
                                  </tr>
                              </tbody>
                          </table>
                          <!-- Footer -->
                          <footer>
                              <br>
                              <p class="text-muted text-sm">DevconKids Inventory &copy; <?php echo date("Y"); ?> | <?php echo date("F j, Y H:i:s"); ?></p>
                          </footer>
                      </div>
                  <?php } else if (password_verify('itemPercentage', $graphToPrint)) { ?>
                      <div class="mx-5">
                          <!-- 6 -->
                          <!-- itemPercentage -->
                          <h2 class="title mt-5">Insights: Item Percentage</h2>
                          <p>This is the percentage of the items in terms of category</p>
                          <?php if (is_array($itemPercentageData)) : ?>
                              <table border="1">
                                  <thead>
                                      <tr>
                                          <th>Item Category</th>
                                          <th>No. of Items</th>
                                          <th>Percentage</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php
                                        $totalItems = 0;
                                        $totalPercentage = 0;

                                        foreach ($itemPercentageData as $item) :
                                            $totalItems += $item['total_items'];
                                            $totalPercentage += $item['percentage'];
                                        ?>
                                          <tr>
                                              <td><?php echo $item['category_name']; ?></td>
                                              <td><?php echo $item['total_items']; ?></td>
                                              <td><?php echo $item['percentage']; ?>%</td>
                                          </tr>
                                      <?php endforeach; ?>
                                      <!-- Total Percentage Row -->
                                      <tr>
                                          <td><strong>Total</strong></td>
                                          <td><strong><?php echo $totalItems; ?></strong></td>
                                          <td><strong><?php echo $totalPercentage; ?>%</strong></td>
                                      </tr>
                                  </tbody>
                              </table>
                          <?php else : ?>
                              <p>No Data Found.</p>
                          <?php endif; ?>
                          <!-- Footer -->
                          <footer>
                              <br>
                              <p class="text-muted text-sm">DevconKids Inventory &copy; <?php echo date("Y"); ?> | <?php echo date("F j, Y H:i:s"); ?></p>
                          </footer>
                      </div>

                  <?php } else if (password_verify('usersTotalRequestsCount', $graphToPrint)) { ?>
                      <!-- 7 -->
                      <div class="mx-5">
                          <h2 class="title">Insights: Users Total Requests Count</h2>
                          <!-- Summary -->
                          <?php
                            $maxRequestsUser = null;
                            $maxRequestsCount = 0;

                            foreach ($userRequestsCountDataPoints as $dataPoint) {
                                if ($dataPoint['y'] > $maxRequestsCount) {
                                    $maxRequestsCount = $dataPoint['y'];
                                    $maxRequestsUser = $dataPoint['label'];
                                }
                            }
                            ?>
                          <p class="summary">The user with the highest total request count is "<?php echo $maxRequestsUser; ?>".</p>

                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>User</th>
                                      <th>Total Requests Count</th>
                                      <th>Chapter</th> <!-- Add a new column for Chapter -->
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                    $topUsers = array_slice($userRequestsCountDataPoints, 0, 5); // Get top 5 users

                                    foreach ($topUsers as $dataPoint) : ?>
                                      <?php
                                        // Assuming there's a relationship between users and chapters, modify the query accordingly
                                        $userId = getUserIdByName($dataPoint['label']); // Replace this with the actual function to get user ID by name
                                        $chapter = getChapterByUserId($userId); // Replace this with the actual function to get chapter by user ID
                                        ?>
                                      <tr>
                                          <td class="text-capitalize"><?php echo $dataPoint['label']; ?></td>
                                          <td><?php echo $dataPoint['y']; ?> transactions</td>
                                          <td><?php echo $chapter; ?></td> <!-- Display the chapter information -->
                                      </tr>
                                  <?php endforeach; ?>
                              </tbody>
                          </table>

                          <!-- Footer -->
                          <footer>
                              <br>
                              <p class="text-muted text-sm">DevconKids Inventory &copy; <?php echo date("Y"); ?> | <?php echo date("F j, Y H:i:s"); ?></p>
                          </footer>
                      </div>

                  <?php } else if (password_verify('printAll', $graphToPrint)) { ?>
                      <div class="mx-5">
                          <?php
                            try {
                                $sql = "SELECT item_name, item_quantity FROM items";
                                $stmt = $pdo->query($sql);
                                $itemsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }

                            // Function to determine remarks based on quantity
                            function getRemarks($quantity)
                            {
                                if ($quantity === 0) {
                                    return 'Out of Stock';
                                } elseif ($quantity > 50) {
                                    return 'High Stock';
                                } elseif ($quantity <= 20) {
                                    return 'Low Stock';
                                } else {
                                    return 'Normal Stock';
                                }
                            }

                            // Function to determine Bootstrap text color class based on quantity
                            function getQuantityColorClass($quantity)
                            {
                                if ($quantity === 0) {
                                    return 'text-danger'; // Red text
                                } elseif ($quantity > 50) {
                                    return 'text-success'; // Green text
                                } elseif ($quantity <= 20) {
                                    return 'text-warning'; // Yellow text
                                } else {
                                    return 'text-success'; // Green text for normal stock
                                }
                            }
                            ?>

                          <!-- Display the data in the specified format -->
                          <h3 class="title">Insights: Items Stock Overview</h3>
                          <?php
                            try {
                                // Your SQL query to fetch low-stock items
                                $sql = "SELECT item_name, item_quantity FROM items WHERE item_quantity <= 20";
                                // Prepare and execute the query
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                // Fetch the results as an associative array
                                $lowStockItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Display summary paragraph for low-stock items
                                echo '<h3 class="title">Low Stock Items Summary</h3>';
                                if (!empty($lowStockItems)) {
                                    echo '<p class="text-muted">';
                                    echo 'The following items are currently low in stock (quantity less than or equal to 20): ';
                                    foreach ($lowStockItems as $item) {


                                        echo '<strong class="text-capitalize">' . $item['item_name'] . '</strong>, ';
                                    }
                                    echo 'consider restocking these items to avoid shortages.';
                                    echo '</p>';
                                } else {
                                    echo '<p>No low-stock items found.</p>';
                                }
                            } catch (Exception $e) {
                                // Handle exceptions, e.g., log the error or display a user-friendly message
                                echo '<p>Error: ' . $e->getMessage() . '</p>';
                            }
                            ?>


                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>Item Name</th>
                                      <th>Quantity</th>
                                      <th>Remarks</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($itemsData as $item) : ?>
                                      <tr>
                                          <td><?php echo $item['item_name']; ?></td>
                                          <td class=""><?php echo $item['item_quantity']; ?></td>
                                          <td class="<?php echo getQuantityColorClass($item['item_quantity']); ?>"><?php echo getRemarks($item['item_quantity']); ?></td>
                                      </tr>
                                  <?php endforeach; ?>
                                  <!-- Summary Row -->
                                  <tr>
                                      <td><strong>Total</strong></td>
                                      <td class=""><strong><?php echo array_sum(array_column($itemsData, 'item_quantity')); ?></strong></td>
                                      <td></td> <!-- Leave the Remarks column empty for the total row -->
                                  </tr>
                              </tbody>
                          </table>

                          <!-- itemPercentage -->
                          <h2 class="title mt-5">Insights: Item Percentage</h2>
                          <p>This is the percentage of the items in terms of category</p>
                          <?php if (is_array($itemPercentageData)) : ?>
                              <table border="1">
                                  <thead>
                                      <tr>
                                          <th>Item Category</th>
                                          <th>No. of Items</th>
                                          <th>Percentage</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php
                                        $totalItems = 0;
                                        $totalPercentage = 0;

                                        foreach ($itemPercentageData as $item) :
                                            $totalItems += $item['total_items'];
                                            $totalPercentage += $item['percentage'];
                                        ?>
                                          <tr>
                                              <td><?php echo $item['category_name']; ?></td>
                                              <td><?php echo $item['total_items']; ?></td>
                                              <td><?php echo $item['percentage']; ?>%</td>
                                          </tr>
                                      <?php endforeach; ?>
                                      <!-- Total Percentage Row -->
                                      <tr>
                                          <td><strong>Total</strong></td>
                                          <td><strong><?php echo $totalItems; ?></strong></td>
                                          <td><strong><?php echo $totalPercentage; ?>%</strong></td>
                                      </tr>
                                  </tbody>
                              </table>
                          <?php else : ?>
                              <p>No Data Found.</p>
                          <?php endif; ?>

                          <!-- requestedItemsByMonth -->
                          <h3 class="title mt-5">Insights: Approved and Returned Items Over Time</h3>
                          <p>
                              The month with the highest number of accepted requests is: <?php echo $highestMonthAccepted; ?><br>
                              The month with the highest number of returned items is: <?php echo $highestMonthReturned; ?>
                          </p>
                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>Month</th>
                                      <th>Accepted</th>
                                      <th>Returned</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($dataPoints as $dataPoint) : ?>
                                      <tr>
                                          <td><?php echo $dataPoint['label']; ?></td>
                                          <td><?php echo $dataPoint['y']; ?></td>
                                          <td><?php echo $dataPoint['returned']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>
                                  <!-- Summary Row -->
                                  <tr>
                                      <td><strong>Total</strong></td>
                                      <td><strong><?php echo array_sum(array_column($dataPoints, 'y')); ?></strong></td>
                                      <td><strong><?php echo array_sum(array_column($dataPoints, 'returned')); ?></strong></td>
                                  </tr>
                              </tbody>
                          </table>

                          <!-- mostRequestedItems -->
                          <h2 class="title mt-5">Insights: Most Requested Items</h2>
                          <?php
                            $maxRequestsItem = null;
                            $maxRequests = 0;

                            foreach ($barChartDataPoints as $dataPoint) {
                                // Check if the current item has more requests than the current max
                                if ($dataPoint['y'] > $maxRequests) {
                                    $maxRequests = $dataPoint['y'];
                                    $maxRequestsItem = $dataPoint['label'];
                                }
                            }
                            ?>
                          <p>Items with the highest number of requests, such as "<strong><?php echo $maxRequestsItem; ?></strong>", are likely to be restocked more frequently.</p>
                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>Item</th>
                                      <th>Requests</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($barChartDataPoints as $dataPoint) : ?>
                                      <tr>
                                          <td><?php echo $dataPoint['label']; ?></td>
                                          <td><?php echo $dataPoint['y']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>

                                  <!-- Total Row -->
                                  <tr>
                                      <td><strong>Total</strong></td>
                                      <td><strong><?php echo array_sum(array_column($barChartDataPoints, 'y')); ?></strong></td>
                                  </tr>
                              </tbody>
                          </table>

                          <!-- yourTopItems -->
                          <h2 class="title mt-5">Insights: Most Requested Items by You</h2>
                          <?php
                            $maxRequestsItemTwo = null;
                            $maxRequestsTwo = 0;

                            foreach ($barChartTwoDataPoints as $dataPoint) {
                                // Check if the current item has more requests than the current max
                                if ($dataPoint['y'] > $maxRequestsTwo) {
                                    $maxRequestsTwo = $dataPoint['y'];
                                    $maxRequestsItemTwo = $dataPoint['label'];
                                }
                            }
                            ?>

                          <p>Items with the highest number of requests by you, such as "<strong><?php echo $maxRequestsItemTwo; ?></strong>", are likely to be restocked more frequently.</p>
                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>Item</th>
                                      <th>Requests</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($barChartTwoDataPoints as $dataPoint) : ?>
                                      <tr>
                                          <td><?php echo $dataPoint['label']; ?></td>
                                          <td><?php echo $dataPoint['y']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>

                                  <!-- Total Row -->
                                  <tr>
                                      <td><strong>Total</strong></td>
                                      <td><strong><?php echo array_sum(array_column($barChartTwoDataPoints, 'y')); ?></strong></td>
                                  </tr>
                              </tbody>
                          </table>

                          <!-- chaptersTotalTransactions -->
                          <?php
                            $maxTransactionsChapter = null;
                            $maxTransactionsCount = 0;

                            foreach ($chapterTotalTransactions as $dataPoint) {
                                // Check if the current chapter has more transactions than the current max
                                if ($dataPoint['y'] > $maxTransactionsCount) {
                                    $maxTransactionsCount = $dataPoint['y'];
                                    $maxTransactionsChapter = $dataPoint['label'];
                                }
                            }
                            ?>

                          <h2 class="title mt-5">Insights: Chapters Total Transaction Count</h2>
                          <p>The chapter with the most total transaction count as of <?php echo date("F j, Y H:i:s"); ?> is the chapter "<strong><?php echo $maxTransactionsChapter; ?></strong>".</p>
                          <table border="1">
                              <thead>
                                  <tr>
                                      <th>Chapter/Branch</th>
                                      <th>Total Request Count</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach ($chapterTotalTransactions as $dataPoint) : ?>
                                      <tr>
                                          <td><?php echo $dataPoint['label']; ?></td>
                                          <td><?php echo $dataPoint['y']; ?></td>
                                      </tr>
                                  <?php endforeach; ?>

                                  <!-- Total Row -->
                                  <tr>
                                      <td><strong>Total</strong></td>
                                      <td><strong><?php echo array_sum(array_column($chapterTotalTransactions, 'y')); ?></strong></td>
                                  </tr>
                              </tbody>
                          </table>


                          <!-- Footer -->
                          <footer>
                              <br>
                              <p class="text-muted text-sm">DevconKids Inventory &copy; <?php echo date("Y"); ?> | <?php echo date("F j, Y H:i:s"); ?></p>
                          </footer>
                      </div>
                  <?php }; ?>
              </div>
              <script>
                  function hideButtons() {
                      const printButton = document.getElementById("printButton");
                      const savePdfButton = document.getElementById("savePdfButton");

                      if (printButton && savePdfButton) {
                          printButton.style.display = "none";
                          savePdfButton.style.display = "none";
                      } else {
                          console.error('Buttons not found.');
                      }
                  }

                  function showButtons() {
                      const printButton = document.getElementById("printButton");
                      const savePdfButton = document.getElementById("savePdfButton");

                      if (printButton && savePdfButton) {
                          printButton.style.display = "unset";
                          savePdfButton.style.display = "unset";
                      } else {
                          console.error('Buttons not found.');
                      }
                  }


                  function generatePDF() {
                      hideButtons();
                      const element = document.getElementById('body');
                      const formattedDate = new Date().toLocaleDateString('en-GB').replace(/\//g, '-');
                      const options = {
                          margin: 10,
                          filename: 'FileName_' + formattedDate + '.pdf',
                          image: {
                              type: 'jpeg',
                              quality: 1.0
                          },
                          html2canvas: {
                              scale: 4
                          },
                          jsPDF: {
                              unit: 'mm',
                              format: 'a4',
                              orientation: 'portrait'
                          }
                      };
                      html2pdf(element, options);
                      setTimeout(function() {
                          showButtons();
                      }, 2000);
                  }


                  // Event listener for keydown event on the document
                  document.addEventListener('keydown', function(event) {
                      // Check if Ctrl key and 'P' key are pressed simultaneously
                      if (event.ctrlKey && event.key === 'p') {
                          // Hide the button (replace 'savePdfButton' with the actual ID of your button)
                          var savePdfButton = document.getElementById('savePdfButton');
                          var printButton = document.getElementById('printButton');
                          if (printButton && savePdfButton) {
                              printButton.style.display = 'none';
                              savePdfButton.style.display = 'none';
                          }
                      }
                  });

                  function printPage() {
                      window.print();
                  }

                  var printButton = document.getElementById('printButton'); // Replace 'printButton' with the actual ID of your button
                  var savePdfButton = document.getElementById('savePdfButton'); // Replace 'printButton' with the actual ID of your button
                  if (printButton) {
                      printButton.addEventListener('click', function() {
                          savePdfButton.style.display = 'none';
                          printPage();
                      });
                  }



                  //   $(document).ready(function() {
                  //       var printableContent;

                  //       // Your AJAX request example
                  //       $.ajax({
                  //           url: 'print_page.php', // Replace with the actual PHP file or endpoint
                  //           type: 'GET',
                  //           dataType: 'html',
                  //           success: function(response) {
                  //               // Save the response for later use
                  //               printableContent = response;
                  //           },
                  //           error: function(error) {
                  //               console.error('Error:', error);
                  //           }
                  //       });

                  //       // Event listener for the print button
                  //       $('#printButton').on('click', function() {
                  //           // Hide both buttons
                  //           $('#printButton, #savePdfButton').hide();

                  //           // Check if printableContent is loaded
                  //           if (printableContent) {
                  //               // Print the saved content
                  //               printContent(printableContent);
                  //           } else {
                  //               console.error('Printable content is not loaded yet.');
                  //           }
                  //       });

                  //       // Event listener for afterprint
                  //       window.addEventListener('afterprint', function() {
                  //           // Remove the wrapper after printing
                  //           $('#printWrapper').remove();

                  //           // Show both buttons after printing is done
                  //           $('#printButton, #savePdfButton').show();
                  //       });

                  //       // Function to print the content
                  //       function printContent(content) {
                  //           // Create a wrapper element
                  //           var wrapper = $('<div id="printWrapper"></div>');

                  //           // Append the content to the wrapper
                  //           wrapper.html(content);

                  //           // Append the wrapper to the body
                  //           $('body').append(wrapper);

                  //           // Trigger the print action
                  //           window.print();
                  //       }
                  //   });
              </script>
          </body>