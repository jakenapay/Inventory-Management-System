<?php

include './config.inc.php';
if (isset($_POST['userID'])) {
    $item_id = $_POST['itemID'];
    $user_id = $_POST['userID'];
    $itemQ = $_POST['itemQ'];
    $itemName = $_POST['itemName'];
    $userEM = $_POST['userEM'];

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



        // // Fetch item name
        // $itemQuery = "SELECT item_name FROM items WHERE item_id = :item_id";
        // $stmt = $pdo->prepare($itemQuery);
        // $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        // $stmt->execute();
        // $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // $itemName = $result['item_name'];
        // echo $itemName;

        // // Fetch user name
        // $userQuery = "SELECT user_firstname , user_lastname ,user_email FROM users WHERE user_id = :user_id";
        // $stmt = $pdo->prepare($userQuery);  // Use a different variable name for the statement
        // $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        // $stmt->execute();
        // $res = $stmt->fetch(PDO::FETCH_ASSOC);

        // $userName = $res['user_firstname'] . ' ' . $res['user_lastname'];
        // echo $userName;

        if ($stmt->execute()) {
            $receiver = "yugo2801@gmail.com";
            $subj = 'Item';
            $msg = '<!DOCTYPE html>
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
                        Dear '.$userEM.',<br><br>
                        We are pleased to inform you that your requested item has been approved! You can now proceed to collect or use the item as needed.<br><br>
                        Here are the details of your approved item:<br>
                        <strong>Item Name: '.$itemName.'</strong> <br>
                        <strong>Approval Status:</strong> Approved
                    </p>
                    <p>
                        If you have any further questions or need assistance, feel free to contact us.<br>
                        <a class="button" href="[Your Website URL]">Visit Our Website</a>
                    </p>
                    <div class="footer">
                        Thank you for using our service. We appreciate your business!
                    </div>
                </div>
            </body>
            </html>';
            include_once './forEmail/sendEmail.php';
        }

        // Additional code after successful execution, if needed
        echo "Record inserted successfully!";
    } catch (PDOException $e) {
        // Handle the error here
        echo "Error: " . $e->getMessage();
    }
}
