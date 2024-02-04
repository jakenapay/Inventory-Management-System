<?php
//library to use phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['login-btn'])) {
    // include other php process
    include_once 'config.inc.php';

    // get data from login
    $email = $_POST['email'];
    $password = $_POST['password'];



    if (empty($email) || empty($password)) {
        header("location: ../index.php?m=ef"); // ef meaning is Empty Fields
        exit();
    }

    try {
        // Prepare a SELECT statement to retrieve user data based on the provided email
        $sql = "SELECT * FROM users WHERE user_email=:email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) { // Meaning there's 1 result -> Then run the code below
            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            if (password_verify($password, $row['user_password'])) { // Verification if the inserted password and the fetched password from DB are a match
                // If matched, start the session, and assign the other details to sessions

                // $session_name = ($row['user_category'] == 1) ? 'admin_session' : 'user_session';
                // session_name($session_name);
                // echo session_name();

                session_start();

                $_SESSION['ID'] = $row['user_id'];
                $_SESSION['FN'] = $row['user_firstname'];
                $_SESSION['LN'] = $row['user_lastname'];
                $_SESSION['EM'] = $row['user_email'];
                $_SESSION['CT'] = $row['user_category'];
                $_SESSION['CH'] = $row['user_chapter'];
                $_SESSION['UI'] = $row['user_image'];
                $_SESSION['session_token'] = $row['session_token'];


                // echo '<pre>';
                // print_r($_SESSION);
                // echo '</pre>';

                // If user is active then log in and redirect to index
                if ($row['user_status'] !== "inactive") {
                    // Redirect to the home page, and exit this file

                    $subdomain = getSubdomain();

                    if (($subdomain == "www") && ($_SESSION['CT'] == 1)) {
                        // IF ADMIN AND AT USER PAGE THEN GO TO ADMIN PAGE
                        $redirectURL = "https://admin.devconkidsinventory.tech/index.php";
                        // PHP code to generate JavaScript
                        echo '<script>';
                        echo 'var proceed = confirm("You are trying to log in as admin. Please log in again.");';
                        echo 'if (proceed) {';
                        echo '   window.location.href = "' . $redirectURL . '";';
                        echo '} else {';
                        echo '   alert("You chose not to proceed.");';
                        echo '}';
                        echo '</script>';
                        exit(); // Make sure to exit to prevent further output
                    } else if (($subdomain == "admin") && ($_SESSION['CT'] == 0)) {
                        // If USER AND AT ADMIN PAGE THEN GO TO USER PAGE
                        $redirectURL = "https://www.devconkidsinventory.tech/index.php";
                        // PHP code to generate JavaScript
                        echo '<script>';
                        echo 'var proceed = confirm("You are trying to log in as user. Please log in again.");';
                        echo 'if (proceed) {';
                        echo '   window.location.href = "' . $redirectURL . '";';
                        echo '} else {';
                        echo '   alert("You chose not to proceed.");';
                        echo '}';
                        echo '</script>';
                        exit(); // Make sure to exit to prevent further output
                    }

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
                    header("location: ../home.php");
                    exit();
                } else {
                    header("location: ../index.php?m=iac"); // inactive account
                    exit();
                }
            } else { // Wrong password here
                header("location: ../index.php?m=wp"); // wp stands for Wrong Password
                exit();
            }
        } else {
            // No results found
            header("location: ../index.php?m=unf"); // unf meaning is User Not Found
            exit();
        }
    } catch (PDOException $e) {
        // Handle database connection or query errors
        header("location: ../index.php?m=db_error");
        exit();
    }
} else {
    header("location: ../index.php");
    exit();
}
