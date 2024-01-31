<?php
include './config.inc.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';




if (isset($_POST['userID'])) {
    $item_id = $_POST['itemID'];
    $user_id = $_POST['userID'];
    $itemQ = $_POST['itemQ'];
    $itemName = $_POST['itemName'];
    $userEM = $_POST['userEM'];
    $url = 'http://localhost/Inventory-Management-System/viewItem.php?itemid=' . $item_id;
    try {
        $query = "INSERT INTO `history`(`history_item_id`, `history_quantity`, `history_user_id`, `history_status` , `history_due_date`) VALUES (?,?,?,?,?)";
        $stmt = $pdo->prepare($query);
        // Get the current date
        $currentDateTime = date('Y-m-d');
        // Set the start date
        $start_date = new DateTime($currentDateTime); // replace with your actual start date
        // Add 7 days to the start date
        $due_date = $start_date->modify('+3 days');
        // Format the due date as a string
        $due_date_str = $due_date->format('Y-m-d');

        // Assuming $itemid, $itemq, $userid, and $item_id are defined elsewhere
        $stmt->bindParam(1, $item_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $itemQ, PDO::PARAM_INT);
        $stmt->bindParam(3, $user_id, PDO::PARAM_INT);
        $stmt->bindValue(4, "approved", PDO::PARAM_STR);
        $stmt->bindValue(5, $due_date_str, PDO::PARAM_STR);

        if ($stmt->execute()) {
            try {

                $mail = new PHPMailer(true);                      
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'devconkidsinventory@gmail.com';
                $mail->Password   = 'yipo vnsj ymki ldrl';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('devconkidsinventory@gmail.com', 'Dev Kids Admin');
                $mail->addAddress($userEM);     //Add a recipient

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Here is the subject';
                $mail->Body    = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Item Approval Notification</title>
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

                        .button {
                            display: inline-block;
                            padding: 10px 20px;
                            background-color: #007BFF;
                            color: #ffffff;
                            text-decoration: none;
                            border-radius: 5px;
                        }

                        .footer {
                            margin-top: 20px;
                            color: #777;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>Your Item Request has been Approved!</h1>
                        <p>
                            Dear ' . $userEM . ',<br><br>
                            We are pleased to inform you that your requested item has been approved! You can now proceed to collect or use the item as needed.<br><br>
                            Here are the details of your approved item:<br>
                            <strong>Item Name: ' . $itemName . '</strong> <br>
                            <strong>Approval Status:</strong> Approved
                        </p>
                        <p>
                           Click This Button To Post Feedback of This Item.<br>
                           <a href="' . $url . '"> <button class="button">Item Link</button></a>
                        </p>
                        <div class="footer">
                            Thank you for using our service. We appreciate your business!
                        </div>
                    </div>
                </body>
                </html>';


                $mail->send();
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        // Additional code after successful execution, if needed
        echo " Requested Successfully!";
    } catch (PDOException $e) {
        // Handle the error here
        echo "Error: " . $e->getMessage();
    }
}
