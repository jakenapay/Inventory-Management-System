<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Inventory Management System</title>

    <!-- Headers and other attachments/CDN -->
    <?php include_once 'includes/headers.inc.php'; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>"> <!-- There's a PHP code to remove the Cache -->
    <link rel="stylesheet" href="assets/css/login.css?v=<?php echo time(); ?>">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/css/landing.css" rel="stylesheet">

    <!-- Logo -->
    <img src="https://uploads-ssl.webflow.com/6492dd5d65d1855cb14a6692/6494571343918c14057fe090_DEVCON%20Kids%20Logo%20Horizontal.png" alt="Your Logo" class="logo-in-upper-middle">
    <script>
        setTimeout(function(){ 
            document.getElementById("msg").style.display = "none"; // hide the element after 3 seconds
        }, 5000);
    </script>
</head>
<body class="login-page">

    <!-- Beginning of the code -->

    <div class="container">
        <div class="row d-flex justify-content-center align-items-center vh-100">
            <!-- First Column with Title -->
            <div class="col-md-4 col-lg-6 col-sm-12">
                <div id="title-box" style="margin-bottom: 10px;"> <!-- Adjust the margin-bottom value -->
                    <h1 class="homepage-title">Web-Based Inventory Management System</h1>
                </div>
                <p class ="title_description" style="margin-right: 10px;">Efficiently manage your inventory with our user-friendly web-based system. DEVCON Kids is an international non-profit organization aiming to make computer science accessible and fun for kids. With a focus on kids from underserved communities, we aim to teach the valuable skills necessary for them to participate in an ever-digital future – and thrive in it.</p>   
            </div>
            
            <!-- Second Column with Login Form -->
            <div class="col-md-8 col-lg-6 col-sm-12">
                <div id="login-box">
                    <img id="image-devcon" src="https://uploads-ssl.webflow.com/6492dd5d65d1855cb14a6692/6494571343918c14057fe090_DEVCON%20Kids%20Logo%20Horizontal.png" alt="DevCon Kids Image" loading="lazy">
                    <p class="main-title">Inventory Management</p>
                    <hr>
                    <form action="includes/login.inc.php" method="post">
                        <?php include 'includes/message.inc.php';?>
                        <p class="labels">Email Address</p>
                        <input class="userInput" id="email" name="email" type="email" pattern="/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/">
                        <p class="labels">Password</p>
                        <input class="userInput" id="password" name="password" type="password">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="toggle-password" name="" onclick="showHidePassword()">
                                <label id="label-toggle" class="custom-control-label" for="toggle-password">Show password</label>
                            </div>
                            
                        </div>
                        <button id="login-btn" class="button w-100" name="login-btn" type="submit">login</button>
                        <div class="text-center mt-4">
                                <div class="labels m-0">
                                Don't have an account yet?<a href="signin.php" id="label" class="labels-button">Register Here</a>
                                    </div>
                              </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'landing-page.php' ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
