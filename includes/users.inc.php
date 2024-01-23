<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require '../PHPMailer-master/PHPMailer-master/src/Exception.php';
// require '../PHPMailer-master/PHPMailer-master/src/PHPmailer.php';
// require '../PHPMailer-master/PHPMailer-master/src/SMTP.php';

session_start();

// Viewing of specific user
if (isset($_POST['user_view'])) {

    // In order to work with database
    include 'config.inc.php';

    // Get the user id from ajax
    $userId = $_POST['user_id'];
    try {
        // Prepare the SQL query
        $stmt = $pdo->prepare("SELECT 
            users.user_id AS id,
            CONCAT(users.user_firstname, ' ', users.user_lastname) AS name,
            users.user_email AS email,
            category.category_name AS category,
            chapters.chapter_name AS chapter,
            users.user_status AS status,
            users.user_image AS image
        FROM `users`
        INNER JOIN category ON users.user_category = category.category_id
        INNER JOIN chapters ON users.user_chapter = chapters.chapter_id
        WHERE user_id = :id");

        // bind the parameter with variable of the user
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        // Show/fetch the result 
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through the results
        foreach ($result as $row) {
            $id = $row['id'];
            $name = $row['name'];
            $email = $row['email'];
            $category = $row['category'];
            $chapter = $row['chapter'];
            $status = $row['status'];
            $image = $row['image'];

            $return = '
                <div class="col-md-6 py-1 d-flex align-items-center justify-content-center">';

            if ($image != '') {
                $return .= '<img src="images/userProfiles/' . $image . '" loading="lazy" class="img-thumbnail img-fluid" alt="' . $image . '">';
                $return .= '<input type="hidden" value="' . $image . '" name="old_image" id="old_image">';
            } else {
                $return .= '<img src="" name="user_image" alt="No image found" class="d-flex justify-content-center img-thumbnail img-fluid">';
            }
            $return .= '
                </div>
                <div class="col-md-6 py-1">
                    <div row>
                        <input type="hidden" class="form-control form-control-sm text-capitalize" id="user_id" name="user_id" placeholder="User ID" readonly value="' . $id . '">
                        <div class="col-md-12 py-1">
                            <label for="user_name">Name</label>
                            <input type="text" class="form-control form-control-sm text-capitalize" id="user_name" name="user_name" placeholder="Name" readonly value="' . $name . '">
                        </div>
                        <div class="col-md-12 py-1">
                            <label for="user_email">Email Address</label>
                            <input type="text" class="form-control form-control-sm" id="user_email" name="user_email" placeholder="Email" readonly value="' . $email . '">
                        </div>
                        <div class="col-md-12 py-1">
                            <label for="user_category">Category</label>
                            <input type="text" class="form-control form-control-sm text-capitalize" id="user_category" name="user_category" placeholder="Category" readonly value="' . $category . '">
                        </div>
                        <div class="col-md-12 py-1">
                            <label for="user_chapter">Chapter</label>
                            <input type="text" class="form-control form-control-sm text-capitalize" id="user_chapter" name="user_chapter" placeholder="Chapter" readonly value="' . $chapter . '">
                        </div>
                        <div class="col-md-12 py-1">
                            <label for="user_status">Status</label>
                            <input type="text" class="form-control form-control-sm text-capitalize" id="user_status" name="user_status" placeholder="Status" readonly value="' . $status . '">
                        </div>
                    </div>
                </div>
                ';

            echo $return;
        }
    } catch (PDOException $e) { // If didn't work then show error
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_POST['edit_view'])) { // For editing the information of the users

    // To work with database
    include 'config.inc.php';

    // Get the ID of the user that is being editing
    $userId = $_POST['user_id'];

    // Get the ID of the users who's editing the users
    // For logs, security, or history purposes
    // $editorUserId = $_POST['editor-user-id'];

    // Query to fetch the user and its information
    try {
        // Prepare the SQL query
        $stmt = $pdo->prepare("SELECT 
                users.user_id AS id,
                users.user_firstname AS firstname,
                users.user_lastname AS lastname,
                users.user_email AS email,
                category.category_name AS category,
                category.category_id AS category_id,
                chapters.chapter_name AS chapter,
                chapters.chapter_id AS chapter_id,
                users.user_status AS status,
                users.user_image AS image
                FROM `users`
                INNER JOIN category ON users.user_category = category.category_id
                INNER JOIN chapters ON users.user_chapter = chapters.chapter_id
                WHERE user_id = :id");

        // bind the parameter with variable of the user
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        // Show/fetch the result 
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through the results
        foreach ($result as $row) {
            $id = $row['id'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $email = $row['email'];
            $category = $row['category'];
            $categoryId = $row['category_id'];
            $chapter = $row['chapter'];
            $chapterId = $row['chapter_id'];
            $status = $row['status'];
            $image = $row['image'];

            // Then fetch the return or the editable inputs
            $return = '
                <div class="col-md-6 py-1">';

            if ($image != '') {
                $return .= '<img src="images/userProfiles/' . $image . '" loading="lazy" class="d-flex justify-content-center img-thumbnail img-fluid" alt="Image" name="user_image">';
                $return .= '<input type="hidden" value="' . $image . '" name="old_image" id="old_image"';
            } else {
                $return .= '<img src="" name="user_image" alt="No image found" class="d-flex justify-content-center img-thumbnail img-fluid">';
            }

            $return .= '
                    <hr class="w-100">
                    <label for="new_user_image">Upload new image</label>
                    <input type="file" class="form-control-file" id="new_user_image" name="new_user_image" accept="image/png, image/gif, image/jpeg">    
                </div>

                <div class="col-md-6 py-1">
                    <div row>
                        <input type="hidden" class="form-control form-control-sm text-capitalize" id="user_id" name="user_id" placeholder="User ID" value="' . $id . '">
                        <div class="col-md-12 py-1">
                            <label for="user_firstname">First Name</label>
                            <input type="text" class="form-control form-control-sm text-capitalize" id="user_firstname" name="user_firstname" placeholder="First Name" value="' . $firstname . '">
                        </div>
                        <div class="col-md-12 py-1">
                            <label for="user_lastname">Last Name</label>
                            <input type="text" class="form-control form-control-sm text-capitalize" id="user_lastname" name="user_lastname" placeholder="Last Name" value="' . $lastname . '">
                        </div>
                        <div class="col-md-12 py-1">
                            <label for="user_email">Email Address</label>
                            <input type="text" class="form-control form-control-sm" id="user_email" name="user_email" placeholder="Email" value="' . $email . '">
                        </div>
                        <div class="col-md-12 py-1">
                            <label for="user_category">Category</label>
                            <select name="user_category" id="user_category" class="text-capitalize form-control form-control-sm" required>
                            <option value="' . $categoryId . '" selected>' . $category . ' (Current)</option>';

            // To show the other option of the category
            try {
                $sql = "SELECT * FROM category";
                // Prepare the query
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                // Fetch all rows as an associative array
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Process the result (e.g., display it)
                foreach ($result as $row) {
                    // Access columns by their names
                    $return .= '<option value="' . $row["category_id"] . '">' . $row['category_name'] . '</option>';
                }
            } catch (PDOException $e) {
                // Handle database connection or query errors
                $return .= "Error: " . $e->getMessage();
            }

            // Continutation  
            $return .= '
                            </select>
                        </div>
                        <div class="col-md-12 py-1">
                            <label for="user_chapter">Chapter</label>
                            <select name="user_chapter" id="user_chapter" class="text-capitalize form-control form-control-sm" required>
                            <option value="' . $chapterId . '" selected>' . $chapter . ' (Current)</option>';

            // To show the other option of the chapter
            try {
                $sql = "SELECT * FROM chapters";
                // Prepare the query
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                // Fetch all rows as an associative array
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Process the result (e.g., display it)
                foreach ($result as $row) {
                    // Access columns by their names
                    $return .= '<option value="' . $row["chapter_id"] . '">' . $row['chapter_name'] . '</option>';
                }
            } catch (PDOException $e) {
                // Handle database connection or query errors
                $return .= "Error: " . $e->getMessage();
            }

            // Continutation  
            $return .= '
                            </select>  
                        </div>
                        <div class="col-md-12 py-1">
                            <label for="user_status">Status</label>
                            <select name="user_status" id="user_status" class="text-capitalize form-control form-control-sm" required>
                            <option value="' . $status . '" selected>' . $status . ' (Current)</option>';

            if ($status == 'active') {
                $return .= "<option value='inactive'>Inactive</option>";
            } else if ($status == 'inactive') {
                $return .= "<option value='active'>Active</option>";
            }


            // Continutation
            $return .= '
                            </select>
                        </div>
                    </div>
                </div>';

            echo $return;
        }
    } catch (PDOException $e) {
        // Handle query errors
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_POST['save-edit-user-btn'])) { // For saving the edited details of the user

    // To work with database
    include 'config.inc.php';

    // Get the edited information
    $id = $_POST['user_id'];
    $firstname = $_POST['user_firstname'];
    $lastname = $_POST['user_lastname'];
    $email = $_POST['user_email'];
    $category = $_POST['user_category'];
    $chapter = $_POST['user_chapter'];
    $status = $_POST['user_status'];

    // Get the new and old image
    $imageName = $_FILES['new_user_image']['name'];
    $imgTmpName = $_FILES['new_user_image']['tmp_name'];
    $imgType = $_FILES['new_user_image']['type'];
    $imgSize = $_FILES['new_user_image']['size'];
    $imgError = $_FILES['new_user_image']['error'];
    $old_img = $_POST['old_image']; // Name only

    // Check if there's any empty variable
    try {
        if (empty($id) || empty($firstname) || empty($lastname) || empty($category) || empty($chapter) || empty($email) || empty($status)) {
            header("location: ../users.php?m=ic");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        header("location: ../users.php?m=" . $e->getMessage() . ""); // Failed
        exit();
    }

    // Check for image if valid
    if (isset($imageName) && $imageName != "") {
        // Seperate extension and filename
        $imageTmpExt = explode('.', $imageName);
        $imageExt = strtolower(end($imageTmpExt));

        $allowed_ext = array('jpg', 'jpeg', 'png', 'pdf');
        $image_info = pathinfo($imageName);
        $image_ext = strtolower($image_info['extension']);

        if (!in_array($image_ext, $allowed_ext)) {
            header("location: ../users.php?m=itd");
            exit();
        }

        if ($imgSize > 2000000) {
            header("location: ../users.php?m=is");
            exit();
        }

        if ($imgError !== 0) {
            header("location: ../users.php?m=ie");
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

        // If all functions were passed then explode the image name and extension
        // Create a unique ID for the image
        // Upload the image to the folder
        $image_new_name = uniqid('', true) . "." . $image_ext;

        // Upload the image to folder of images
        $image_final_name = "IMG_" . $image_new_name;
        $folder = '../images/userProfiles/';
        move_uploaded_file($imgTmpName, $folder . $image_final_name);

        $sql = "UPDATE users SET user_firstname = :fname, user_lastname = :lname, user_email = :email, user_category = :category, user_chapter = :chapter, user_status = :status, user_image = :image WHERE user_id = :id";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':fname', $firstname);
            $stmt->bindParam(':lname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':chapter', $chapter);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':image', $image_final_name);
            $stmt->execute();

            header("location: ../users.php?m=us"); // Updated successfully
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            header("location: ../users.php?m=" . $e->getMessage() . ""); // Failed
            exit();
        }
    } else {
        $image_final_name = $old_img;
        $sql = "UPDATE users SET user_firstname = :fname, user_lastname = :lname, user_email = :email, user_category = :category, user_chapter = :chapter, user_status = :status, user_image = :image WHERE user_id = :id";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':fname', $firstname);
            $stmt->bindParam(':lname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':chapter', $chapter);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':image', $image_final_name);
            $stmt->execute();

            // NEW FEATURE
            $getUserStatusSql = "SELECT * FROM users WHERE user_id = :id LIMIT 1";
            try {
                $stmt = $pdo->prepare($getUserStatusSql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                // Fetch all rows as an associative array
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row && $row['user_status'] === 'active') {
                    // Email the user that their account is now activated!

                    // $mail = new PHPMailer(true);
                    // $mail->isSMTP();
                    // $mail->Host = 'smtp.gmail.com';
                    // $mail->SMTPAuth = true;
                    // $mail->Username = 'devconkidsinventory@gmail.com';
                    // $mail->Password = 'yipo vnsj ymki ldrl';
                    // $mail->SMTPSecure = 'tls';
                    // $mail->Port = 587;

                    // $mail->From = "devconkidsinventory@gmail.com"; //my gmail
                    // $mail->FromName = "DevCon Kids Inventory"; //sender name

                    // // details
                    // $subj = "Your account is now activated!";
                    // $receiver = $row['user_email'];
                    // $msg = '
                    //         <!DOCTYPE html>
                    //         <html lang="en">
                    //         <head>
                    //             <meta charset="UTF-8">
                    //             <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    //             <title>Account Activation</title>
                    //             <style>
                    //                 body {
                    //                     font-family: Arial, sans-serif;
                    //                     background-color: #f4f4f4;
                    //                     margin: 0;
                    //                     padding: 0;
                    //                 }

                    //                 .container {
                    //                     max-width: 600px;
                    //                     margin: 50px auto;
                    //                     background-color: #ffffff;
                    //                     padding: 20px;
                    //                     box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    //                 }

                    //                 h1 {
                    //                     color: #333;
                    //                 }

                    //                 p {
                    //                     color: #555;
                    //                 }

                    //                 .button {
                    //                     display: inline-block;
                    //                     padding: 10px 20px;
                    //                     background-color: #007BFF;
                    //                     color: #ffffff;
                    //                     text-decoration: none;
                    //                     border-radius: 5px;
                    //                 }

                    //                 .footer {
                    //                     margin-top: 20px;
                    //                     color: #777;
                    //                 }
                    //             </style>
                    //         </head>
                    //         <body>
                    //             <div class="container">
                    //                 <h1>Your Account is Now Activated!</h1>
                    //                 <p>
                    //                     Dear '.$row["user_firstname"].',<br><br>
                    //                     We are thrilled to inform you that your account with [Your Company Name] has been successfully activated! You can now enjoy all the benefits and features our platform has to offer.<br><br>
                    //                     Here are a few key details about your account:<br>
                    //                     <strong>User name:</strong> '.$row["user_firstname"]. ' ' .$row["user_lastname"].'<br>
                    //                     <strong>Email Address:</strong> '.$row["user_email"].'<br>
                    //                     <strong>Account Status:</strong> Active
                    //                 </p>
                    //                 <p>
                    //                     Feel free to log in to your account using the following link:<br>
                    //                     <a class="button" href="http://localhost/projects/Inventory-Management-System/index.php">Log In</a>
                    //                 </p>
                    //                 <p>If you have any questions or encounter any issues, please don\'t hesitate to contact our support team at [Support Email Address].</p>
                    //                 <div class="footer">
                    //                     Thank you for choosing [Your Company Name]. We look forward to providing you with an excellent experience!
                    //                 </div>
                    //             </div>
                    //         </body>
                    //         </html>
                    //         ';
                            
                    // $mail->addAddress($receiver);

                    // $mail->isHTML(true);
                    // $mail->Subject    = $subj;
                    // $mail->Body    = $msg;
                    // $mail->send();

                    header("Location: ../users.php?m=aca"); // Account activated and sent email
                    exit();
                    // Redirect to index where they can log in
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                header("location: ../users.php?m=" . $e->getMessage() . ""); // Failed
                exit();
            }

            header("location: ../users.php?m=us1"); // Updated successfully
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            header("location: ../users.php?m=" . $e->getMessage() . ""); // Failed
            exit();
        }
    }
}
