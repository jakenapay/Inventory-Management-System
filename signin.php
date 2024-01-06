<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Inventory Management System</title>

    <!-- Headers and other attachments/CDN -->
    <?php include_once 'includes/headers.inc.php'; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>"> <!-- Theres a PHP code to remove the Cache -->
    <link rel="stylesheet" href="assets/css/login.css?v=<?php echo time(); ?>">

</head>

<body>

    <!-- Beginning of the code -->
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center vh-100">
            <div class="col-md-8 col-lg-6 col-sm-12">

                <div id="login-box">
                    <img id="image-devcon" src="https://uploads-ssl.webflow.com/6492dd5d65d1855cb14a6692/6494571343918c14057fe090_DEVCON%20Kids%20Logo%20Horizontal.png" alt="DevCon Kids Image" loading="lazy">
                    <p class="main-title">Request an Account</p>
                    <hr>
                    <form action="includes/signin.inc.php" method="post">
                        <?php include 'includes/message.inc.php'; ?>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <p class="labels">First Name</p>
                                <input class="userInput" id="firstname" name="firstname" type="text" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <p class="labels">Last Name</p>
                                <input class="userInput" id="lastname" name="lastname" type="text" required>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <p class="labels">Category</p>
                                <select name="user_category" id="user_category" class="text-capitalize userInput" required>
                                    <option value="" disabled selected>Select Category</option>
                                    <?php
                                    // To show the other option of the category
                                    try {
                                        include 'includes/config.inc.php';
                                        $sql = "SELECT * FROM category";
                                        // Prepare the query
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute();
                                        // Fetch all rows as an associative array
                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        // Process the result (e.g., display it)
                                        $return = ''; // Initialize the variable to hold the options
                                        foreach ($result as $row) {
                                            // Access columns by their names
                                            $return .= '<option value=' . $row["category_id"] . '>' . $row['category_name'] . '</option>';
                                        }
                                        echo $return; // Output all options after the loop
                                    } catch (PDOException $e) {
                                        // Handle database connection or query errors
                                        echo "Error: " . $e->getMessage();
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <p class="labels">Chapter</p>
                                <select name="user_chapter" id="user_chapter" class="text-capitalizze userInput" required>
                                    <option value="" disabled selected>Select Chapter</option>
                                    <?php
                                    try {
                                        include 'includes/config.inc.php';
                                        $sql = "SELECT * FROM chapters ORDER BY `chapters`.`chapter_name` ASC";
                                        // Prepare the query
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute();
                                        // Fetch all rows as an associative array
                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        // Process the result (e.g., display it)
                                        $return = ''; // Initialize the variable to hold the options
                                        foreach ($result as $row) {
                                            // Access columns by their names
                                            $return .= '<option value=' . $row["chapter_id"] . '>' . $row['chapter_name'] . '</option>';
                                        }
                                        echo $return; // Output all options after the loop
                                    } catch (PDOException $e) {
                                        // Handle database connection or query errors
                                        echo "Error: " . $e->getMessage();
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>
                        <p class="labels">Email Address</p>
                        <input class="userInput" id="email" name="email" type="email" pattern="/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/" required>
                        <p class="labels">Password</p>
                        <input class="userInput" id="password" name="password" type="password">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="toggle-password" name="" onclick="showHidePassword()">
                                <label id="label-toggle" class="custom-control-label" for="toggle-password">Show password</label>
                            </div>
                            <div class="labels m-0">
                                <a href="index.php" class="labels">Log in</a>
                            </div>
                        </div>
                        <button id="login-btn" class="button w-100" name="login-btn" type="submit">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</body>

</html>