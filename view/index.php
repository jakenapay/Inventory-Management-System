<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Inventory Management System</title>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <?php include '../assets/CDN.php';?>
    <!-- Headers and other attachments/CDN -->
    <?php include_once '../includes/headers.inc.php'; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>"> <!-- Theres a PHP code to remove the Cache -->
    <link rel="stylesheet" href="../assets/css/login.css?v=<?php echo time(); ?>">

</head>

<body>

    <!-- Beginning of the code -->
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center vh-100">
            <div class="col-md-8 col-lg-6 col-sm-12">
                <div id="login-box">
                    <img id="image-devcon" src="https://uploads-ssl.webflow.com/6492dd5d65d1855cb14a6692/6494571343918c14057fe090_DEVCON%20Kids%20Logo%20Horizontal.png" alt="DevCon Kids Image" loading="lazy">
                    <p class="main-title">Inventory Management</p>
                    <hr>
                    <form action="../includes/login.inc.php" method="post">
                        <p class="msg"><?php include '../includes/message.inc.php'; ?></p>
                        <p class="labels">Email Address</p>
                        <input class="userInput" id="email" name="email" type="email" pattern="/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/" autofocus>
                        <p class="labels">Password</p>
                        <input class="userInput" id="password" name="password" type="password">
                        <div class="mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="toggle-password" name="" onclick="showHidePassword()">
                                <label id="label-toggle" class="custom-control-label" for="toggle-password">Show password</label>
                            </div>
                        </div>
                        <button id="login-btn" class="button" name="login-btn" type="submit">login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</body>

<script src="../assets/javascript/main.js"></script>

</html>