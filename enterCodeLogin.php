<?php
include 'includes/config.inc.php';
session_start();
//library to use phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_GET['email'])) {
    header("location: index.php");
    exit();
}

if (isset($_POST['enter-code-login-btn'])) {

    // check if empty user email
    if (empty($_POST['code'])) {
        header("location: enterCodeLogin.php?m=ef");
        exit();
    }

    $code = $_POST['code'];
    $email = $_GET['email'];

    try {
        // Prepare the SQL statement
        $sql = "SELECT user_code, user_email FROM users WHERE user_email = '$email'";
        $stmt = $pdo->prepare($sql);
        // Execute the query
        $stmt->execute();

        /// Fetch the result as an associative array
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the query was successful
        if ($row) {
            $code_db = $row['user_code'];
            $email_db = $row['user_email'];
        } else {
            header("location: enterCodeLogin.php?m=404");
            exit();
        }
    } catch (PDOException $e) {
        // Handle any PDO exceptions
        echo "Error: " . $e->getMessage();
        exit();
    }

    // code matching
    if ($code == $code_db) {
        try {
            $admin = $_SESSION['ID'];
            $logMessage = "logged in";
            $query = "INSERT INTO `audit`(`audit_user_id`, `audit _action`) VALUES (:loguser,:logaction)";
            $res = $pdo->prepare($query);
            // Bind values to the placeholders
            $res->bindParam(":loguser", $admin);
            $res->bindParam(":logaction", $logMessage);
            $res->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            header("location: index.php?m=" . $e->getMessage() . ""); // Failed
        }

        header("location: home.php");
        exit();
    } else {
        header("location: enterCode.php?email=$email&m=wrongcode");
        exit();
    }
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
    <link rel="stylesheet" href="assets/css/login.css?v=<?php echo time(); ?>">

    <script>
        setTimeout(function() {
            document.getElementById("msg").style.display = "none"; // hide the element after 3 seconds
        }, 5000);
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
</head>

<body>

    <div class="container">
        <div class="row d-flex justify-content-center align-items-center vh-100">

            <!-- Second Column with Login Form -->
            <div class="col-md-8 col-lg-6 col-sm-12">
                <div id="login-box">
                    <img id="image-devcon" src="https://uploads-ssl.webflow.com/6492dd5d65d1855cb14a6692/6494571343918c14057fe090_DEVCON%20Kids%20Logo%20Horizontal.png" alt="DevCon Kids Image" loading="lazy">
                    <p class="main-title">Log in \ Enter Code</p>
                    <hr>
                    <form action="" method="post">
                        <?php include 'includes/message.inc.php'; ?>
                        <!-- <p class="labels">Code</p> -->
                        <input required class="userInput" id="code" name="code" type="text">

                        <button id="enter-code-btn" class="button w-100" name="enter-code-login-btn" type="submit">Submit</button>
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

</html>