<?php

if (isset($_POST['login-btn'])) {
    //* include other php process
    include_once 'config.inc.php';
    //* include_once 'functions.inc.php';

    //* get data from login
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("location: ../view/index.php?m=ef"); //* ef meaning is Empty Fields
        exit();
    }

    try {
        //* Prepare a SELECT statement to retrieve user data based on the provided email
        $sql = "SELECT * FROM users WHERE user_email=:email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();



        if ($stmt->rowCount() > 0) { //* Meaning there's 1 result -> Then run the code below
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $row['user_password'])) { //* Verification if the inserted password and the fetched password from DB are a match
                //* If matched, start the session, and assign the other details to sessions
                session_start();
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_firstname'] = $row['user_firstname'];
                $_SESSION['user_lastname'] = $row['user_lastname'];
                $_SESSION['user_email'] = $row['user_email'];
                $_SESSION['user_category'] = $row['user_category'];
                $_SESSION['user_chapter'] = $row['user_chapter'];

                //* Redirect to the home page, and exit this file
                header("location: ../view/home.php");
                exit();
            } else { //* Wrong password here
                header("location: ../view/index.php?m=wp"); //* wp stands for Wrong Password
                exit();
                
            }
        } else {
            //* No results found
            header("location: ../view/index.php?m=unf"); //* unf meaning is User Not Found
            exit();
        }
    } catch (PDOException $e) {
        //* Handle database connection or query errors
        echo $e->getMessage();
        header("location: ../view/index.php?m=db_error");
        exit();
    }
} else {
    header("location: ../view/index.php");
    exit();
}
