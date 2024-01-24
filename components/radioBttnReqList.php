<?php
session_start();
 ?>
    <div class="row">
        <div class="col-lg-9 col-sm-6 col-sm-12 m-auto mt-5">
            <div class="form-check form-check-inline">
                <ul>
                    <li>
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="category" id="" value="0" onclick="getRadioValue()" checked>
                            All
                        </label>
                    </li>
                </ul>
                <?php
                $rbtnNameQuery = "SELECT * FROM `items_category`";
                $rbtnQ = $pdo->prepare($rbtnNameQuery);
                $rbtnQ->execute();

                $rbtnData = $rbtnQ->fetchAll(PDO::FETCH_ASSOC);
                if ($rbtnData) {

                    foreach ($rbtnData as $itemCateg) { ?>
                        <ul>
                            <li>
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="category" id="" value="<?php echo $itemCateg['item_category_id'] ?>" onclick="getRadioValue()">
                                    <?php echo $itemCateg['item_category_name'] ?>
                                </label>
                            </li>
                        </ul>
                    <?php } ?>
                <?php } else {
                } ?>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-sm-12 m-auto mt-5">
            <div class=" input-group">
                <div class="input-group">
                    <input type="search" class="form-control rounded" id="itemSearch" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                    <button type="button" class="btn btn-outline-success btnSearch" data-mdb-ripple-init>search</button>
                </div>
            </div>
        </div>
    </div>
<?php  ?>

<script>
    function getRadioValue() {
        // Get the selected radio button
        var selectedOption = document.querySelector('input[name="category"]:checked');
        
        // Check if a radio button is selected
        if (selectedOption.value == 0) {

            location.reload();
        }

        if (selectedOption) {
            // Display the selected value
            var selectedCategoryId = selectedOption.value;
            // Use AJAX to fetch data based on the selected category
            $.ajax({
                type: "POST",
                url: "./includes/itemlist.inc.php", // Replace with your server-side script
                data: {
                    categoryId: selectedCategoryId,
                },
                success: function(response) {
                    $('#ItemList').html(response);
                }
            });
        } else {
            alert("Please select an option");
        }
    }


    $('.btnSearch').click(function() {

        var Sdata = document.getElementById('itemSearch').value;
        alert(Sdata);
        // Perform AJAX request using jQuery
        $.ajax({
            type: 'POST',
            url: './includes/searchQuery.inc.php',
            data: {
                querySearch: Sdata
            },
            success: function(response) {
                $('#ItemList').html(response);
            }
        });
    });
</script>