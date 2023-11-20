<?php

if (isset($_POST['save-profile-btn'])) { // Saving profile information after ediiting

    // Set the includes
    include 'config.inc.php';

    // check first if there's an id of user
    if (!isset($_POST['user_id'])) {
        // go back to edit panel
        header("location: ../profile.php?m=nid"); // No id of user
        exit();
    }

    // Get the new information
    $id = $_POST['user_id'];
    $firstname = $_POST['user_firstname'];
    $lastname = $_POST['user_lastname'];
    $email = $_POST['user_email'];
    $old_img = $_POST['old_img'];

    // Check if there's any empty variable
    if (empty($id) || empty($firstname) || empty($lastname) || empty($email)) {
        header("location: ../profile.php?m=ef"); // Empty fields
        exit();
    }
    
    if(!empty($_FILES['new_user_image']['name'])) { // If image new has a value

        // Image details
        $imageName = $_FILES['new_user_image']['name'];
        $imgTmpName = $_FILES['new_user_image']['tmp_name'];
        $imgType = $_FILES['new_user_image']['type'];
        $imgSize = $_FILES['new_user_image']['size'];
        $imgError = $_FILES['new_user_image']['error'];
        

        // Seperate extension and filename
        $imageTmpExt = explode('.', $imageName);
        $imageExt = strtolower(end($imageTmpExt));

        $allowed_ext = array('jpg', 'jpeg', 'png', 'pdf');
        $image_info = pathinfo($imageName);
        $image_ext = strtolower($image_info['extension']);

        if (!in_array($image_ext, $allowed_ext)) {
            header("location: ../profile.php?m=itd"); // Upload image only
            exit();
        }
        if ($imgSize > 2000000) {
            header("location: ../profile.php?m=is"); // Upload image less than 2MB
            exit();
        }
        if ($imgError !== 0) {
            header("location: ../profile.php?m=ie"); // Upload image with no error
            exit();
        }

        // If all functions were passed then explode the image name and extension
        // Declare path and old pic name, and unlink/delete it from folder of images
        if (isset($old_img) && ($old_img != '')) {
            $path = "../images/userProfiles/" . $old_img;
            if (!unlink($path)) {
                echo "You have an error deleting image";
            }
        }

        // Create a unique ID for the image
        // Upload the image to the folder
        $imageNewName = uniqid('', true) . "." . $imageExt;

        // Upload the image to upload folder (product_img)
        $img = 'IMG_' . $imageNewName;
        $folder = '../images/userProfiles/';
        move_uploaded_file($imgTmpName, $folder . $img);

        // Query to database
        $sql = "UPDATE users SET user_firstname = :firstname, user_lastname = :lastname, user_email = :email, user_image = :image WHERE user_id = :id";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':image', $img, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            header("location: ../profile.php?m=us"); // Updated successfully
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            header("location: ../profile.php?m=".$e->getMessage().""); // Failed
            exit();
        }

    } else {
        $img = $old_img;

        // Query to database
        $sql = "UPDATE users SET user_firstname = :firstname, user_lastname = :lastname, user_email = :email, user_image = :image WHERE user_id = :id";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':image', $img, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            header("location: ../profile.php?m=us"); // Updated successfully
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            header("location: ../profile.php?m=".$e->getMessage().""); // Failed
            exit();
        }
    }


} else { // Go back to profile page
    header("location: ../profile.php");
    exit();
}