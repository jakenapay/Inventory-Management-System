<?php

if (isset($_POST['login-btn'])) {
    // defaults

    // user_status = inactive -- after requesting an account
    // users who requested an account, needs to be activated by the admin of their same branch

    // user_category = selected by them -- after requesting an account
    // users who requested an account can select which category of account they're requesting

    // same as user_chapter -- they can choose which chapter they are located

    include_once 'config.inc.php';

    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $category = $_POST['user_category'];
    $chapter = $_POST['user_chapter'];

    // defaults
    $image = 'defaultProfile.jpg';
    $status = 'inactive';

    if (empty($firstName) || empty($lastName) || $category == "" || $chapter == "" || empty($email) || empty($password)) {
        header("location: ../signin.php?m=ef"); // Empty fields 
        exit();
    }


    // Check if there's an email existing
    try {

        $sql = "SELECT * FROM users WHERE user_email = :email";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            header("location: ../signin.php?m=eme"); // Email existing 
            exit();
        }
    } catch (PDOException $e) {
        // Handle database connection or query errors
        echo "Error: " . $e->getMessage();
    }

    try {
        // defaults
        // $image = 'defaultProfile.jpg';
        // $status = 'inactive';

        // hash by md5
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);


        $sql = "INSERT INTO users (user_firstname, user_lastname, user_email, user_password, user_category, user_chapter, user_image, user_status)
            VALUES (:firstname, :lastname, :email, :password, :category, :chapter, :image, :status)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':firstname', $firstName);
        $stmt->bindParam(':lastname', $lastName);
        $stmt->bindParam(':password', $passwordHashed); // Hashed password is the one that is getting into database
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':chapter', $chapter);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            header("location: ../index.php?m=rqs"); // Request Success
            exit();
        } else {
            header("location: ../signin.php?m=rqf"); // Request Failed
            exit();
        }
    } catch (PDOException $e) {
        // Handle database error
        echo "Error: " . $e->getMessage();
    }
}  else {
    header("location: ../index.php");
    exit();
}
