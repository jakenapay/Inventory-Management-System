<?php
include './includes/config.inc.php';
session_start();

// Check if there's an id, if it has, then it's logged in
// If there's no id, head back to login page
if (!isset($_SESSION['ID']) and ($_SESSION['ID'] == '')) {
    header("location: index.php");
    exit();
}

// To determine which is active page in nav bar
$_SESSION['active_tab'] = basename($_SERVER['SCRIPT_FILENAME']);


?>



<?php
$itemId = isset($_GET['itemid']) ? $_GET['itemid'] : null;
// Check if item id is set
if ($itemId !== null) {
    // Sanitize input to prevent SQL injection
    $itemId = intval($itemId);

    // Prepare and execute the query
    $query = "SELECT * FROM items INNER JOIN items_category AS itemCategory ON items.item_category = itemCategory.item_category_id INNER JOIN items_unit_of_measure AS iuom ON items.item_measure = iuom.item_uom_id INNER JOIN chapters 
    WHERE items.item_id = :itemid ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':itemid', $itemId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch data as an associative array
    $itemData = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Item ID not provided";
}






?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Headers and other attachments/CDN -->
    <?php include_once './includes/headers.inc.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/items.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/itemView.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./assets/css/comment.css?php echo time(); ?>">
</head>

<body>
    <?php include './nav.php'; ?>

    <div class="container p-0">
        <div class="row">
            <div class="left col-md-6 col-sm-12 col-lg-6">
                <input type="text" id="itemId" value="<?php echo $itemData['item_id'] ?>" hidden>
                <input type="text" id="userId" value="<?php echo $_SESSION['ID'] ?>" hidden>
                <img class="image-resize image-responsive" src="./images/items/<?php echo $itemData['item_image'] ?>">
            </div>
            <div class="right col-md-6 col-sm-12 col-lg-6">
                <h3 class="product text-capitalize"><?php echo $itemData['item_name']; ?></h3>
                <h6 class="categorie text-capitalize"><?php echo $itemData['item_category_name']; ?></h6>
                <?php
                // chapter id convert to chapter name
                try {
                    $chapterId = $itemData['item_chapter'];

                    // Set the PDO error mode to exception
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Your SQL query
                    $sql = "SELECT chapter_name FROM chapters WHERE chapter_id = :id";

                    // Prepare the statement
                    $stmt = $pdo->prepare($sql);

                    // Bind the parameter
                    $stmt->bindParam(':id', $chapterId, PDO::PARAM_INT);

                    // Execute the statement
                    $stmt->execute();

                    // Fetch the result
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $chapterName = $result['chapter_name'];
                } catch (PDOException $e) {
                    // Handle the exception
                    echo "Error: " . $e->getMessage();
                }
                ?>
                <h6 class="categorie text-capitalize"><?php echo $chapterName; ?></h6>
                <input type="text" id="chapterName" value="<?php echo $itemData["item_chapter"] ?>" hidden>
                <input type="text" id="fromChapter" value="<?php echo $_SESSION['CH'] ?>" hidden>
                <input type="text" id="userEmail" value="<?php echo $_SESSION['EM'] ?>" hidden>
                <input type="text" id="itemName" value="<?php echo $itemData['item_name'] ?>" hidden>
                <ul class="desc">
                    <li><?php echo $itemData['item_description']; ?></li>
                    <li>Item Unit Of Measure: <?php echo $itemData['item_uom_name']; ?> </li>
                    <li>Item Quantity: <small id="itemQuantity"><?php echo $itemData['item_quantity']; ?></small></li>
                    <li>
                        <div id="counter-container">
                            <button id="decrement-btn" onclick="decrementCounter()">-</button>
                            <input id="counter-value" type="text" value="1" min="1" oninput="checkMaxInput(this)">
                            <button id="increment-btn" onclick="incrementCounter()">+</button>
                        </div>
                    </li>
                </ul>
                <div class="my-3 row d-flex align-items-center justify-content-start">
                    <div class="col-12 col-md-6 col-lg-6">

                        <?php
                        if ($_SESSION['CH'] != $itemData['item_chapter']) {
                        ?>
                            <button class="btn btn-primary btn-block-lg w-100 req-other">Request to Other</button>
                        <?php } else { ?>
                            <button class="btn btn-primary w-100 item">Request</button>
                        <?php } ?>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <button class="btn cart">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512">
                                <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                            </svg>
                            <div class="spinner-border text-success d-none spinner-border-sm" id="spinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <i class="fa-solid fa-check d-none" id="checkIcon" style="color: #22511f;"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>


        <div class="row">
            <div class=" mt-5 ">
                <div class="mt-4 text-justify float-left">
                    <!-- <img src="./images/userProfiles/<?php echo $_SESSION['UI'] ?>" alt="" class="rounded-circle" width="40" height="40">
                    <h4 class="d-inline" style="margin-left: 10px; color:var(--purple);"><?php echo $_SESSION['FN'] . ' ' . $_SESSION['LN'] ?></h4> -->
                    <h3 style="margin-left: 20px;"><b>Comments</b></h3>
                    <p style="margin-left: 20px;">Write your Feedback</p>
                    <textarea name="comment" id="comment" cols="75" rows="3" class="d-inline" style="margin-left: 20px;"></textarea><button class="btn btn-primary post" style="margin-bottom: 65px; margin-left: 10px;">Post</button>
                </div>

            </div>
            <?php
            include './components/itemcomment.php';
            ?>
        </div>
    </div>
</body>

</html>

<script>
    var itemId = document.getElementById('itemId').value;
    var userId = document.getElementById('userId').value;
    var itemQuantityFromPHP = document.getElementById('itemQuantity').innerText;

    console.log("Item Quantity in JavaScript:", itemQuantityFromPHP);
    // Set maximum value

    const maxCounterValue = itemQuantityFromPHP;

    // Initialize counter value
    let counter = 0;

    // Function to increment the counter
    function incrementCounter() {
        if (counter < maxCounterValue) {
            counter++;
            updateCounter();
        }
    }

    // Function to decrement the counter
    function decrementCounter() {
        if (counter > 1) {
            counter--;
            updateCounter();
        }
    }

    // Function to update the counter value on the page
    function updateCounter() {
        document.getElementById('counter-value').value = counter;
    }

    function checkMaxInput(input) {
        // Get the maximum allowed value (e.g., 10)
        var maxAllowedValue = itemQuantityFromPHP;

        // Parse the input value as an integer
        var inputValue = Math.floor(parseFloat(input.value));

        // Check if the input value exceeds the maximum allowed value
        if (inputValue > maxAllowedValue) {
            // If exceeded, set the input value to the maximum allowed value
            input.value = maxAllowedValue;
        } else if (isNaN(inputValue) || inputValue < 1 || inputValue > itemQuantityFromPHP) {
            input.value = 1;
        } else {

            input.value = inputValue;
        }
    }

    $('.item').click(() => {
        var itemQuan = document.getElementById('counter-value').value;
        var userEM = document.getElementById('userEmail').value;
        var itemName = document.getElementById('itemName').value;

        $.ajax({
            type: "POST",
            url: "./includes/itemreq.inc.php",
            data: {
                itemID: itemId,
                itemQ: itemQuan,
                userID: userId,
                userEM: userEM,
                itemName: itemName,
            },
            success: function(response) {
                if (response) {
                    // working na Pwede na lagay yung PHP MAILER DITO
                    alert(response);
                    location.reload();
                }
            }
        });
    })

    $('.cart').click((e) => {
        const spinner = $(e.currentTarget).find('.spinner-border');
        const svg = $(e.currentTarget).find('svg');
        const checkIcon = $(e.currentTarget).find('#checkIcon');

        spinner.removeClass('d-none');
        svg.addClass('d-none');
        checkIcon.addClass('d-none');

        // Find the 'item-id' input element within the same card
        const item_id_input = itemId;
        console.log(item_id_input);
        if (item_id_input) {
            // Check if the element is found before accessing its value

            setTimeout(() => {
                spinner.addClass('d-none');
                svg.addClass('d-none');
                checkIcon.removeClass('d-none');

                $.ajax({
                    type: "POST",
                    url: "./includes/tocart.php",
                    data: {
                        itemid: item_id_input,
                        userid: userId,
                    },
                    success: function(response) {
                        if (response) {
                            setTimeout(() => {
                                checkIcon.addClass('d-none');
                                svg.removeClass('d-none');
                            }, 1000);
                            window.location = './itemAction.php'
                        }
                    }
                });

            }, 5000);

        } else {
            console.error("Item ID input not found within the card:", card);
        }
    })

    $('.post').click(function() {
        var feedback = document.getElementById('comment').value;

        $.ajax({
            type: "post",
            url: "./includes/itemcomments.inc.php",
            data: {
                itemId: itemId,
                userId: userId,
                feedback: feedback,
            },
            success: function(response) {
                if (response) {
                    location.reload();
                }
            }
        });

    })

    $('.req-other').click(function() {
        var itemQuan = document.getElementById('counter-value').value;
        var toChapterId = document.getElementById('chapterName').value;
        var fromChapterId = document.getElementById('fromChapter').value;
        console.log(itemId);
        console.log(userId);
        console.log(itemQuan);
        console.log(toChapterId);
        console.log(fromChapterId);


        $.ajax({
            type: "post",
            url: "./includes/reqtoOther.inc.php",
            data: {
                itemId: itemId,
                userId: userId,
                itemQuan: itemQuan,
                toChapterId: toChapterId,
                fromChapterId: fromChapterId,
            },
            success: function(response) {
                if (response) {
                    alert(response)
                    location.reload();
                }
            }
        });

    })
</script>