<?php
//library to use phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function generate_session_token()
{
    return bin2hex(random_bytes(32));
}

function store_session_token_in_database($user_id, $session_token, $user_role)
{

    // Store session token in a database or another persistent storage along with the role
    // Example: Your database query here to store the session token and role
    include 'config.inc.php';
    $updateSessionSql = "UPDATE users SET session_token = :sessiontoken WHERE user_id = :userid AND user_category = :user_role";
    $upstmt = $pdo->prepare($updateSessionSql);
    // Bind parameters
    $upstmt->bindParam(':sessiontoken', $session_token, PDO::PARAM_STR);
    $upstmt->bindParam(':userid', $user_id, PDO::PARAM_STR);
    $upstmt->bindParam(':user_role', $user_role, PDO::PARAM_STR);
    // Execute the query
    $upstmt->execute();

    try {
    } catch (\Throwable $th) {
        throw $th;
    }
}

function get_session_token_from_database($user_id, $user_role)
{
    include 'config.inc.php';
    try {
        $sql = "SELECT session_token FROM users WHERE user_id = :user_id AND user_category = :user_role LIMIT 1";
        $getstmt = $pdo->prepare($sql);
        $getstmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $getstmt->bindParam(':user_role', $user_role, PDO::PARAM_STR);
        $getstmt->execute();
        if ($getstmt->rowCount() > 0) {
            $result = $getstmt->fetch(PDO::FETCH_ASSOC);
            return $result['session_token']; // Adjust this based on your actual column name
        } else {
            return null; // Or handle the case when the session token is not found
        }
    } catch (PDOException $e) {
        // Handle database connection or query errors
        // You might want to log the error or take appropriate action
        $e->getMessage();
        return null;
    }
}

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

            
                echo '<pre>';
                print_r($_SESSION);
                echo '</pre>';

         
                // // Retrieve the stored session token from the database
                $stored_session_token = get_session_token_from_database($row['user_id'], $row['user_category']);
                if ($stored_session_token ===  $_SESSION['session_token']) {
                    // If user is active then log in and redirect to index
                    if ($row['user_status'] !== "inactive") {
                        // Redirect to the home page, and exit this file
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
                } else {
                    // The session token is not valid, take appropriate action (e.g., logout)
                    // header('Location: logout.inc.php');
                    // exit();
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
