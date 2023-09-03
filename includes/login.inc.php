<?php

if (isset($_POST['login-btn'])) {

    // include other php process
    include_once 'config.inc.php';
    // include_once 'functions.inc.php';

    // get data from login
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("location: ../index.php?m=ef"); // ef meaning is Empty Fields
    }

    $sql = "SELECT * FROM users WHERE user_email='$email' LIMIT 1";
    $sql_result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($sql_result) > 0) { // Meaning there's 1 result -> Then run the code below
        while($row = mysqli_fetch_assoc($sql_result)) {
            if(password_verify($password, $row['user_password'])) { // Verification if the inserted password and the fetched password from DB are match
                
                // If matched, start the session, and assign the other details to sessions
                session_start();
                $_SESSION['ID'] = $row['user_id'];
                $_SESSION['FN'] = $row['user_firstname'];
                $_SESSION['LN'] = $row['user_lastname'];
                $_SESSION['EM'] = $row['user_email'];
                $_SESSION['CT'] = $row['user_category'];

                // Redirect to the home page, and exit this file
                header("location: ../home.php");
                exit();
            } else { // Wrong password here
                header("location: ../index.php?m=wp"); // wp stands for Wrong Password
                exit();
            }
        }
    } else {
        // No results found
        header("location: ../index.php?m=unf"); // unf meaning is User Not Found
        exit();
    }
} else {
    header("location: ../index.php");
    exit();
}
