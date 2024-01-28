<?php

use PHPMailer\PHPMailer\PHPMailer;


require '../PHPMailer-master/PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/PHPMailer-master/src/PHPmailer.php';
require '../PHPMailer-master/PHPMailer-master/src/SMTP.php';


if (isset($_POST['approve-request-item-btn'])) {
    include 'config.inc.php';

    $historyRequestId = $_POST['approve_request_id'];
    $stmt = $pdo->prepare("UPDATE `ctochistory` SET `history_status` = 'delivered' WHERE `history_id` = :historyId;");
    // Bind parameter
    $stmt->bindParam(':historyId', $historyRequestId, PDO::PARAM_INT);
    // Execute the query
    $stmt->execute();
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    try {
        // Prepare the SQL statement to retrieve data
        $stmt = $pdo->prepare("
        SELECT *, CONCAT(u.user_firstname, ' ', u.user_lastname) AS user_name
        FROM `ctochistory` AS c 
        INNER JOIN users AS u ON u.user_id = c.history_user_id
        INNER JOIN items AS i ON c.history_item_id = i.item_id
        WHERE c.history_id = :historyId
    ");

        // Bind parameter
        $stmt->bindParam(':historyId', $historyRequestId, PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        // Output the result (you can customize this part based on your needs)
        if ($result) {
            $userName = $result['user_name'];
            $itemName = $result['item_name'];
            $userEmail = $result['user_email'];
            $historyReq = $result['history_id'];

            // EMAIL SENDING
            $receiver = $userEmail;
            $subj = "Your request has been approved!";
            $msg = '<!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Item Delivered Notification</title>
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
                                <h1>Your Requested Item Has Been Delivered!</h1>
                                <p class="text-capitalize">
                                    Dear ' . $userName . ',<br><br>
                                    We are excited to inform you that the item you requested has been successfully delivered! You can now wait and use the requested item for your needs.<br><br>
                                    Here are some details about the delivered item:<br>
                                    <strong>History Request ID: ' . $historyReq . '</strong> <br>
                                    <strong>Item Name: ' . $itemName . '</strong> <br>

                                    Expect the item to be delivered in 3-5 business days.
                                </p>
                                <p>
                                    If you have any questions or concerns, please feel free to contact our support team.<br>
                                    Thank you for using our services!
                                </p>
                                <div class="footer">
                                    Best regards,<br>
                                    DevconKids Inventory
                                </div>
                            </div>
                        </body>
                        </html>';

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
            $mail->addAddress($receiver);
            $mail->isHTML(true);
            $mail->Subject    = $subj;
            $mail->Body    = $msg;
            $mail->send();

            header("location: ../items.php#itemreq?m=as");
            exit();
        } else {
            echo "No records found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("location: ../requestItem.php?m=404");
    exit();
}
