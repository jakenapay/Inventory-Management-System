<?php
include 'includes/config.inc.php';
//library to use phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (isset($_POST['send-code-btn'])) {
    include 'includes/config.inc.php';
    // check if empty user email
    if (empty($_POST['email'])) {
        header("location: emailCode.php?m=ef");
        exit();
    }

    // generate code
    function generateRandomString($length = 4)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    require 'PHPMailer-master/PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/PHPMailer-master/src/PHPmailer.php';
    require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

    $code = generateRandomString($length = 4);
    $msg = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verification Code</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
    
            .container {
                max-width: 600px;
                margin: 50px auto;
                background-color: #ffffff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
    
            h1 {
                color: #333;
            }
    
            p {
                color: #555;
            }
    
            .code {
                font-size: 24px;
                font-weight: bold;
                color: #007BFF;
            }
    
            .footer {
                margin-top: 20px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Verification Code Received</h1>
            <p>
                Thank you for using our service. You have received a verification code. Please use the following code to complete the verification process:<br><br>
                <span class="code">' . $code . '</span>
            </p>
            <p>
                If you did not request this code or have any concerns, please contact us immediately.<br>
                Do NOT share this code for security reasons.
            </p>
            <div class="footer">
                Thank you for choosing our service. We appreciate your trust!
            </div>
        </div>
    </body>
    </html>';

    $subj = "Your code to retrieve your account.";
    // email of the user
    $receiver = $_POST['email'];


    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'devconkidsinventory@gmail.com';
    $mail->Password = 'yipo vnsj ymki ldrl';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->From = "devconkidsinventory@gmail.com"; //my gmail
    $mail->FromName = "Dev Kids Admin -"; //sender name

    $mail->addAddress($receiver); //email reciever

    $mail->isHTML(true); //this line is to allow the html
    $mail->Subject    = $subj;
    $mail->Body    = $msg;
    $mail->send();

    try {
        // Prepare the SQL statement
        $updateCodeSql = "UPDATE users SET user_code = :code WHERE user_email = :receiver";
        $stmt = $pdo->prepare($updateCodeSql);
        // Bind parameters
        $stmt->bindParam(':code', $code, PDO::PARAM_STR);
        $stmt->bindParam(':receiver', $receiver, PDO::PARAM_STR);
    
        // Execute the query
        $stmt->execute();
    
        if ($stmt->rowCount() === 0) {
            header("location: emailCode.php?m=cuf");
            exit();
        }
    } catch (PDOException $e) {
        // Handle any PDO exceptions
        echo "Error: " . $e->getMessage();
        exit();
    }
    

    header("location: enterCode.php?email=$receiver");
    exit();
}

?>
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
                    <p class="main-title">Forgot Password</p>
                    <hr>
                    <form action="" method="post">
                        <?php include 'includes/message.inc.php'; ?>
                        <p class="labels">Email Address</p>
                        <input required class="userInput" id="email" name="email" type="email" pattern="/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/">

                        <button id="login-btn" class="button w-100" name="send-code-btn" type="submit">Send Code</button>
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




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>