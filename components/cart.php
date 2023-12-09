<!-- Dropdown for cart -->
<div class="dropdown show">
    <a class="btn btn-sm dropdown-toggle" href="" role="button" id="dropdownCart" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512">
            <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
            <style>
            svg {
                fill: #121212;
            }
            </style>
            <path
                d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
        </svg>

        <?php
        if ($totalIDs > 0) {
            echo "<span class='badge badge-warning' id='lblCartCount'> $totalIDs</span>";
        }
        ?>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownCart" style="height: 300px; overflow-y: auto; width: 300px;">
        <?php
        try {

            $stmt = $pdo->prepare("SELECT cart.*, items.*, users.* 
                                        FROM cart 
                                        INNER JOIN items ON cart.item_id = items.item_id
                                        INNER JOIN users ON cart.user_id = users.user_id 
                                        WHERE cart.user_id = :id");
            $stmt->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
            $stmt->execute();

            // Fetch all rows as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process the result
            foreach ($result as $row) {    ?>
        <div class="border-bottom mb-3" style="width: 260px; height: 225px">
            <div class="row g-0">
                <div class="col-md-6">
                    <img src="../images/items/<?php echo $row['item_image']; ?>" class="img-fluid rounded-start"
                        style="width: 100%; max-height: 100%;" alt="Item Image">
                </div>
                <div class="col-md-6">
                    <div class="card-body" style="height: 200px">
                        <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                        <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                        <h6 class="card-title"><?php echo $row['item_name'] ?></h6>
                        <p class="card-text"><?php echo $row['item_description'] ?></p>
                        <sub class="card-text">
                            <small class="text-body-secondary">
                                <?php
                                echo  "Quantity: ".$row['item_quantity'] . "<br>"    
                                ?>
                            </small>
                            <small class="text-body-secondary">
                                <?php
                                
                                        $timestamp = strtotime($row['date_added']);
                                        $dateTime = new DateTime();
                                        $dateTime->setTimestamp($timestamp);
                                        $date = $dateTime->format('Y-m-d');
                                        echo "Date: $date";
                                        ?>
                            </small>

                            <button class="btn btn-primary btn-sm item-btn" data-toggle="modal"
                                data-target="#exampleModal" data-image="<?php echo $row['item_image']; ?>"
                                data-name="<?php echo $row['item_name']; ?>"
                                data-description="<?php echo $row['item_description']; ?>"
                                data-date="<?php echo $row['date_added']; ?>" data-id="<?php echo $_SESSION['ID']; ?>"
                                data-item-id="<?php echo $row['item_id']; ?>"
                                data-item-quantity="<?php echo $row['item_quantity'];?>">View</button>
                        </sub>

                    </div>
                </div>
            </div>
        </div>
        <?php  }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } ?>
    </div>
    <div class=" modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <!-- Modal content -->
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Item Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="" class="img-fluid" alt="Item Image" id="modalItemImage">
                        </div>
                        <div class="col-md-6">
                            <div id="modalItemDetails">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary req-btn">Request</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
    const user_id = document.getElementById("user_id").value;
    var itemId;
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
                const  quantity = this.getAttribute('data-item-quantity');
                // Use these retrieved values as needed
                // For example, display in the modal
                document.getElementById('modalItemImage').src =
                    `../images/items/${image}`;
                document.getElementById('modalItemDetails').innerHTML = `
            <h6 class="card-title">${name}</h6>
            <p class="card-text">${description}</p>
            <sub class="card-text">
                <small class="text-body-secondary" hidden>Item ID: ${itemId}</small>
                <small class="text-body-secondary" >Item Quantity: ${quantity}</small>
                <input  id="item-quan" type="number" min="0" max="${quantity}"> <br>
                <small class="text-body-secondary">Date: ${date}</small>
            </sub>
            <!-- Add more item details here -->
        `;
            });
        });
    });


    $('.req-btn').click(function() {
        const itemQuan = document.getElementById("item-quan").value;
        $.ajax({
            type: "POST",
            url: "./includes/itemreq.inc.php",
            data: {
                itemID: itemId,
                itemQ: itemQuan,
                userID: user_id
            },
            success: function(response) {
                if (response) {
                    alert(response);
                }
            }
        });

    })
    </script>