<?php
include 'includes/config.inc.php';

if (!isset($_GET['email'])) {
    header("location: emailCode.php");
    exit();
}

if (isset($_POST['enter-pass-btn'])) {

    $p1 = $_POST['p1'];
    $p2 = $_POST['p2'];
    $email = $_GET['email'];
    $code = $_GET['code'];

    // check if empty user email
    if (empty($_POST['p1']) or empty($_POST['p2']) or empty($_GET['code'])) {
        header("location: newPassword.php?email=$email&code=$code&m=ef");
        exit();
    }


    // compare if pw match
    if ($p1 != $p2) {
        header("location: newPassword.php?email=$email&code=$code&m=pnm");
        exit();
    }

    try {
        // Hash the new password
        $hashedPassword = password_hash($p1, PASSWORD_DEFAULT);
        // Prepare the SQL statement
        $updatePasswordSql = "UPDATE users SET user_password = :hashedPassword WHERE user_email = :email AND user_code = :code";
        $stmt = $pdo->prepare($updatePasswordSql);

        // Bind parameters
        $stmt->bindParam(':hashedPassword', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':code', $code, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Check if the query was successful
        if (!$stmt->rowCount()) {
            header("location: newPassword.php?email=$email&code=$code&m=404");
            exit();
        }
    } catch (PDOException $e) {
        // Handle any PDO exceptions
        echo "Error: " . $e->getMessage();
        exit();
    }

    echo '<script>alert("Password changed successfully");</script>';
    header("location: index.php?=us");
    exit();
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
                    <p class="main-title">New Password</p>
                    <hr>
                    <form action="" method="post">
                        <?php include 'includes/message.inc.php'; ?>
                        <p class="labels">Password</p>
                        <input required class="userInput" id="p1" name="p1" type="password" minlength="8">
                        <p class="labels">Confirm Password</p>
                        <input required class="userInput" id="p2" name="p2" type="password" minlength="8">
                        <div class="mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="toggle-password" name="" onclick="showHidePassword()">
                                <label id="label-toggle" class="custom-control-label" for="toggle-password">Show password</label>
                            </div>
                        </div>
                        <button id="enter-pass-btn" class="button w-100" name="enter-pass-btn" type="submit">Submit</button>
                        <div class="text-center mt-4">
                            <div class="labels m-0">
                                Already have an account?<a href="index.php" id="label" class="labels-button">Login Here</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showHidePassword() {
            var x = document.getElementById("p1");
            var x2 = document.getElementById("p2");
            var y = document.getElementById("label-toggle");
            if (x.type === "password") {
                x.type = "text";
                x2.type = "text";
                y.innerHTML = "Hide Password";
            } else {
                x.type = "password";
                x2.type = "password";
                y.innerHTML = "Show Password";
            }
        }
    </script>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>