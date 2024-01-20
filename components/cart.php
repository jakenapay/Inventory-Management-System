<!-- Dropdown for cart -->
<style>
    /* Hide scrollbar for Chrome, Safari, and Edge */
    .dropdown-menu::-webkit-scrollbar {
        display: none;
    }

    /* Optional: Style the track and handle for better appearance */
    .dropdown-menu::-webkit-scrollbar-track {
        background-color: #f1f1f1;
    }

    .dropdown-menu::-webkit-scrollbar-thumb {
        background-color: #888;
    }
</style>

<div class="dropdown show">
    <a class="btn btn-sm dropdown-toggle" href="" role="button" id="dropdownCart" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="">
        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512">
            <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
            <style>
                svg {
                    fill: #121212;
                }
            </style>
            <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
        </svg>

        <?php
        $inCart = 0;
        $getAllinCartIDs = $pdo->prepare("SELECT COUNT(item_id) as cartCount FROM cart WHERE inCart = :itemInCart AND user_id = :id");
        $getAllinCartIDs->bindParam(':itemInCart', $inCart, PDO::PARAM_INT);
        $getAllinCartIDs->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
        $getAllinCartIDs->execute();
        $result = $getAllinCartIDs->fetch(PDO::FETCH_ASSOC);
        $cartCount = $result['cartCount'];
        if ($cartCount > 0) {
            echo "<span class='badge badge-warning' id='lblCartCount'> $cartCount </span>";
        }
        ?>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownCart" style="height: 170px; overflow-y:auto;  ">
        <?php

        $inCart = 0;
        $stmt = $pdo->prepare("SELECT cart.*, items.*, users.* 
                                        FROM cart 
                                        INNER JOIN items ON cart.item_id = items.item_id
                                        INNER JOIN users ON cart.user_id = users.user_id 
                                        WHERE cart.user_id = :id AND cart.inCart = :inCart");
        $stmt->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
        $stmt->bindParam(':inCart',  $inCart, PDO::PARAM_BOOL);
        $stmt->execute();

        // Fetch all rows as an associative array
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Process the result
        foreach ($result as $row) {    ?>
            <div class="border-bottom">
                <div class="row ">
                    <!-- <div class="col-lg-6">
                        <img src="./images/items/<?php echo $row['item_image'] ?>" class="img-fluid rounded-start" style=" max-width: 100%; max-height: 100%;" alt="Item Image">
                    </div> -->
                    <div class="">
                        <div class="card-body">
                            <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                            <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                            <div style="display: flex; justify-content: space-between;">
                                <h6 class="card-title d-inline-block text-truncate" style="max-width: 50%;"> <small><?php echo $row['item_name'] ?></small></h6>
                                <button class=" btn btn-primary btn-sm item-btn" data-toggle="modal" data-target="#cartModalList" data-image="<?php echo $row['item_image']; ?>" data-name="<?php echo $row['item_name']; ?>" data-description="<?php echo $row['item_description']; ?>" data-date="<?php echo $row['date_added']; ?>" data-id="<?php echo $_SESSION['ID']; ?>" data-item-id="<?php echo $row['item_id']; ?>" data-item-quantity="<?php echo $row['item_quantity']; ?>">View</button>
                            </div>
                            <!-- <p class="card-text"><small><?php echo $row['item_description'] ?></small></p> -->
                            <sub class="card-text">

                                <small class="text-body-secondary">
                                    <?php
                                    $timestamp = strtotime($row['date_added']);
                                    $dateTime = new DateTime();
                                    $dateTime->setTimestamp($timestamp);
                                    $date = $dateTime->format('Y-m-d');
                                    echo "Date: $date";
                                    ?>
                                </small>

                            </sub>
                        </div>
                    </div>

                </div>
            </div>
        <?php  }  ?>
    </div>



    <div class="modal fade" id="cartModalList" tabindex="-1" aria-labelledby="CartModalLabel" aria-hidden="true">
        <!-- Modal content -->
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="CartModalLable">Item Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="" class="img-fluid" alt="Item Image" id="modalItemImage">
                        </div>
                        <div class="col-md-6">
                            <div id="modalItemDetails" style="text-align: left;">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary checkout-btn">Check Out</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        const user_id = document.getElementById("user_id").value;
        var itemId;
        //displaying cart info in modal
        document.addEventListener('DOMContentLoaded', function() {
            const itemButtons = document.querySelectorAll('.item-btn');
            itemButtons.forEach(itemButton => {
                itemButton.addEventListener('click', function() {
                    const image = this.getAttribute('data-image');
                    const name = this.getAttribute('data-name');
                    const description = this.getAttribute('data-description');
                    const date = this.getAttribute('data-date');
                    const userId = this.getAttribute('data-id');
                    itemId = this.getAttribute('data-item-id');
                    const quantity = this.getAttribute('data-item-quantity');
                    // Use these retrieved values as needed
                    // For example, display in the modal
                    document.getElementById('modalItemImage').src =
                        `./images/items/${image}`;
                    document.getElementById('modalItemDetails').innerHTML = `
                        <h3 class="product"
                            style="font-size: 13pt;
                            margin-top: 5px;
                            font-weight: 500;
                            color: #666;
                            font-family: "Montserrat";">
                            ${name}
                        </h3>
                        <ul class="desc"
                         style="margin-top: 20px;
                             color: #777;">
                            <li>${description}</li>
                            <li>Item Quantity:<small id="itemQuantity">${quantity}</small></li>
                            <li>
                                <p> 
                                <input type="number" min="0" id="item-id" value="${itemId}" hidden>
                                    Item Description: <span id="item-desc"></span> </br>
                                    <sub> Stocks: <span id="item-stoc">${quantity}</span> </sub> <br>
                                    <input  id="item-quan" type="number" min="0" max="${quantity}">
                                </p>
                            </li>
                        </ul>
                          
    
                        <!-- Add more item details here -->
                    `;
                });
            });
        });
        //checkout button - for checkout the item
        $('.checkout-btn').click(function() {
            const itemQuan = document.getElementById("item-quan").value;
            console.log(itemQuan);
            console.log(itemId);
            console.log(user_id);
            $.ajax({
                type: "POST",
                url: "./includes/itemCO.inc.php",
                data: {
                    itemID: itemId,
                    itemQ: itemQuan,
                    userID: user_id
                },
                success: function(response) {
                    if (response) {
                        // if success lagay yung PHP MAILER DITO
                        alert(response);
                    }
                }
            });

        })

        // function increment() {
        //     var inputElement = document.getElementById("item-quan");
        //     var currentValue = parseInt(inputElement.value);
        //     var maxValue = parseInt(inputElement.getAttribute("max"));

        //     if (currentValue < maxValue) {
        //         inputElement.value = currentValue + 1;
        //     } else {
        //         // Optionally, handle the case where the maximum value is reached
        //         alert("Maximum quantity reached!");
        //     }
        // }

        // function decrement() {
        //     var inputElement = document.getElementById("item-quan");
        //     var currentValue = parseInt(inputElement.value);
        //     var minValue = parseInt(inputElement.getAttribute("min"));

        //     if (currentValue > minValue) {
        //         inputElement.value = currentValue - 1;
        //     } else {
        //         // Optionally, handle the case where the minimum value is reached
        //         alert("Minimum quantity reached!");
        //     }
        // }
    </script>