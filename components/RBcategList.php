<div class="row">
    <?php
    $categId = $_POST['categoryId'];
    $user_chapter = $_SESSION['CH'];


    if ($_SESSION['CT'] == "0") {
        $result = $pdo->query("SELECT * FROM items WHERE item_category = $categId AND item_chapter = $user_chapter");
        $records = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach ($records as $row) { ?>
            <div class="col-md-6 col-sm-12 col-lg-4 mt-5">
                <div class="card">
                    <div class="imgBox">
                        <img src="./images/items/<?php echo $row['item_image'] ?>" alt="<?php echo $row['item_name'] ?>" class="mouse">
                    </div>
                    <div class="contentBox">
                        <input type="text" id="user_id" value="<?php echo $_SESSION['ID'] ?>" hidden>
                        <input type="text" class="item_id" value="<?php echo $row['item_id'] ?>" hidden>
                        <h3><?php echo $row['item_name'] ?></h3>

                        <?php if ($row['item_status'] == "disabled" or $row['item_quantity'] == 0) { ?>
                            <h6 class="price" style="color: red;">Item Unavailable</h6>
                            <button type="button" class="btn buy btn-primary btn-view-categ" data-bs-toggle="modal" data-bs-target="#itemDetailsModal" data-item-id="<?php echo $row['item_id'] ?>" disabled hidden>
                                Request
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn buy btn-primary btn-view-categ" data-bs-toggle="modal" data-bs-target="#itemDetailsModal" data-item-id="<?php echo $row['item_id'] ?>">
                                View
                            </button>
                        <?php } ?>
                    </div>
                </div>

                <div class="modal fade" id="itemDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-6">
                                        <img src="" class="img-fluid itemImg" alt="Item Image" style="max-width: 100%; max-height: 100%; width: 175px; height: 200px; text-align: center;">
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-lg-6 mt-3 ">
                                        <h6 class="item-name"></h6>
                                        <p class="item-desc"></p>
                                        <div style="display: flex; flex-direction: column; align-self: flex-end;">
                                            <p class="quanInput"></p>
                                            <sub>stock: <span class="item-quan"></span></sub>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    <?php } else {
        echo "No records";
    } ?>
</div>
<script>
    // // Initialization for ES Users
    document.addEventListener('DOMContentLoaded', function() {
        const user_id = document.getElementById("user_id").value;
        var modal = new bootstrap.Modal(document.getElementById('itemDetailsModal'));
        var buttons = document.querySelectorAll('.btn-view-categ');

        var getItemId = 0;

        //get the data to display in modal
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                var itemId = this.getAttribute('data-item-id');
                getItemId = itemId;
                console.log(itemId);
                // Uncomment and adjust the code below for AJAX requests
                $.ajax({
                    type: "POST",
                    url: "../includes/getitem.inc.php",
                    data: {
                        itemId: getItemId,
                    },
                    success: function(response) {
                        console.log('Response received:', response);

                        // Parse the JSON response
                        const itemInfo = JSON.parse(response);
                        console.log('Parsed itemInfo:', itemInfo);

                        // Update modal content
                        $(".itemImg").attr("src", `./images/items/${itemInfo.item_image}`);
                        $(".item-name").html(`${itemInfo.item_name}`)
                        $(".item-desc").html(`${itemInfo.item_description}`)
                        $(".item-quan").html(`${itemInfo.item_quantity}`)
                        $(".quanInput").html(`
                        <input type="number" min="0" id="item-id" value="${itemInfo.item_id}" hidden> 
                        <input id="item-quan" type="number" min="0" max="${itemInfo.item_quantity}">
                    `);

                        // Show the modal
                        $('#itemDetailsModal').modal('show');
                    }
                });
            });
        });

        // Other modal logic remains the same

        //to Cart 
        var checkerBtn = document.querySelector('.checker');
        checkerBtn.addEventListener('click', function(e) {
            // ... (your existing logic)
        });

        var reqBtn = document.querySelector('.btnReq');
        reqBtn.addEventListener('click', function() {
            // ... (your existing logic)
        });
    });
</script>