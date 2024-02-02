<?php
//library to use phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
                session_start();
                $_SESSION['ID'] = $row['user_id'];
                $_SESSION['FN'] = $row['user_firstname'];
                $_SESSION['LN'] = $row['user_lastname'];
                $_SESSION['EM'] = $row['user_email'];
                $_SESSION['CT'] = $row['user_category'];
                $_SESSION['CH'] = $row['user_chapter'];
                $_SESSION['UI'] = $row['user_image'];

                // If user is active then log in and redirect to index
                if ($row['user_status'] !== "inactive") {
                    require("../phpmailer/src/Exception.php");
                    require("../phpmailer/src/PHPMailer.php");
                    require("../phpmailer/src/SMTP.php");

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

                    $subj = "Your code to log in.";
                    // email of the user
                    $receiver = $_SESSION['EM'];

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
                            header("location: home.php?m=unf");
                            exit();
                        } else {
                            $mail->send();
                        }
                    } catch (PDOException $e) {
                        // Handle any PDO exceptions
                        echo "Error: " . $e->getMessage();
                        exit();
                    }


                    // Redirect to the home page, and exit this file
                    header("location: ../enterCodeLogin.php?email=$receiver");
                    exit();
                } else {
                    header("location: ../index.php?m=iac"); // inactive account
                    exit();
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
