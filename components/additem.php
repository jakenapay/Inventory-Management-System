<?php
session_start();
?>

<style>
    .heading {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .left-side {
        flex: 1;
    }

    .right-side {
        padding-left: 20px;
        /* Adjust the padding as needed */
    }

    #btnAdd.btn.btn-success {
        color: #fff;
        background-color: var(--purple);
        border-color: var(--purple);
        margin-left: 5px;
    }

    #getdata.btn.btn-success {
        color: #fff;
        background-color: var(--purple);
        border-color: var(--purple);
        margin-top: 10px;
    }
</style>

<div>
    <div class="heading">
        <div class="left-side">
            <h4>Scan item barcode to add existing item</h4>
        </div>

        <div class="right-side">
            <div>
                <div id="last-barcode"></div>
                <input type="text" name="itemid" id="itemid">
                <button type="button" class="btn btn-success" id="btnAdd"><span class="fas fa-plus"></span> Add Item</button>
            </div>
        </div>
    </div>

    <!-- paayos neto lee -->
    <div class="modal modal-error" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alert</h5>
                </div>
                <div class="modal-body">
                    <p>The id not existing</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php




    ?>
    <div>
        <input type="text" value="<?php echo $_SESSION['CH'] ?>" hidden id="chapter">
        <h6 id="error"></h6>
        <table class="table table-striped table-inverse table-responsive">
            <thead class="thead-inverse">
                <tr>
                    <th>Item id</th>
                    <th>Item Name</th>
                    <th>Item Quantity</th>
                    <th>Item Stored</th>
                    <th>Total</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr id="templateRow" style="display: none">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><button type="button" class="btn btn-danger btn-delete">Delete</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-success" id="getdata">Add Items</button>
    </div>
</div>
<?php

?>


<script>
    $(document).ready(function() {

        $("#tableBody").on("click", ".btn-delete", function() {
            // Get the parent row and remove it
            $(this).closest("tr").remove();
        });
        //TODO : INSERT THE DATA IN TABLE ACCORDING TO ITEM ID THEN GET THE WHOLE INFORMATION 
        // => Done 
        //$('#myModal').modal('hide');
        const chapter = document.getElementById('chapter').value;
        $("#btnAdd").click(function() {
            const datas = document.getElementById('itemid').value;

            const tableBody = $("#tableBody");
            const existingRow = tableBody.find("tr[data-item-id='" + datas + "']");

            if (existingRow.length > 0) {
                // ? If the item already exists, increment its quantity by 1
                const quantityCell = existingRow.find("td").eq(2);
                const newQuantity = parseInt(quantityCell.text()) + 1;
                quantityCell.text(newQuantity);


                // ? GET the total of item quantity and item stored and print in table column
                const itemStored = existingRow.find("td").eq(3);
                const totalCell = existingRow.find("td").eq(4);
                const totalsum = parseInt(itemStored.text()) + newQuantity;
                totalCell.text(totalsum);

            } else {
                $.ajax({
                    type: "POST",
                    url: "./includes/itemadd.php",
                    data: {
                        datas: datas,
                        chapter: chapter,
                    },
                    success: function(response) {
                        if (response == "error") {
                            console.log(response);
                            $('.modal-error').modal('show');
                            setInterval(function() {
                                $('.modal-error').modal('hide');
                            }, 5000);

                        } else {
                            // ? Initialize respoonse
                            const itemInfo = JSON.parse(response);
                            // ? Create a new row for the table
                            const newRow = $("#templateRow").clone().removeAttr("style")
                                .appendTo("#tableBody");

                            var tot = Number(itemInfo.item_quantity) + 1;

                            // ? Populate the table cells with item information
                            //# item id cell
                            newRow.find("td").eq(0).text(itemInfo.unique_item_id);
                            //# item name cell
                            newRow.find("td").eq(1).text(itemInfo.item_name);
                            //# item quantity cell
                            newRow.find("td").eq(2).text(1);
                            //# item stored cell
                            newRow.find("td").eq(3).text(itemInfo.item_quantity);
                            //# item total cell
                            newRow.find("td").eq(4).text(tot);

                            // newRow.attr("data-item-id", datas)
                        }

                    }
                });
            }
        });
        //TODO : FETCH ALL THE DATA IN TABLE TO JSON & SAVE TO DB
        // -> Working 
        $("#getdata").click(function() {
            var tableData = [];
            $(".table tbody tr").each(function() {
                var rowData = [];
                $(this)
                    .find("td")
                    .each(function() {
                        rowData.push($(this).text());
                    });
                tableData.push(rowData);
            });

            //? Filter out empty arrays
            tableData = tableData.filter(function(row) {
                return row.some(function(cell) {
                    return cell.trim() !== ""; // Check if any cell is not empty
                });
            });
            console.log(tableData);
            // Send the data to the server using AJAX
            $.ajax({
                type: "POST",
                url: "./includes/table-db.php", // Replace with the URL of your server-side script
                data: {
                    tableData: tableData,

                },
                success: function(response) {
                    // Handle the response from the server

                    if (response) {
                        console.log(response);
                        $(".table tbody tr").remove();
                    }

                },
                error: function() {
                    // Handle errors
                }
            });
        });

    });

    var barcode = '';
    var interval;

    document.addEventListener('keydown', function(evt) {
        if (interval) {
            clearInterval(interval);
        }

        if (evt.code == 'Enter') {
            handleBarcode(barcode);
            barcode = '';
            return;
        }

        if (evt.key != 'Shift') {
            barcode += evt.key;
        }

        interval = setInterval(() => {
            barcode = '';
            clearInterval(interval);
        }, 20);
    });

    function handleBarcode(scannedBarcode) {
        document.querySelector('#last-barcode').textContent = scannedBarcode;

        const tableBody = $("#tableBody");
        const existingRow = tableBody.find("tr[data-item-id='" + scannedBarcode + "']");


        const chapter = document.getElementById('chapter').value;

        if (existingRow.length > 0) {
            // ? If the item already exists, increment its quantity by 1
            const quantityCell = existingRow.find("td").eq(2);
            const newQuantity = parseInt(quantityCell.text()) + 1;
            quantityCell.text(newQuantity);


            // ? GET the total of item quantity and item stored and print in table column
            const itemStored = existingRow.find("td").eq(3);
            const totalCell = existingRow.find("td").eq(4);
            const totalsum = parseInt(itemStored.text()) + newQuantity;
            totalCell.text(totalsum);

        } else {
            $.ajax({
                type: "POST",
                url: "./includes/itemadd.php",
                data: {
                    datas: scannedBarcode,
                    chapter: chapter,
                },
                success: function(response) {
                    if (response == "error") {
                        $("#error").text("ID not found in the database").fadeIn();
                        setTimeout(function() {
                            $("#error").fadeOut();
                        }, 5000);


                    } else {
                        // ? Initialize respoonse
                        const itemInfo = JSON.parse(response);
                        // ? Create a new row for the table

                        const newRow = $("#templateRow").clone().removeAttr("style")
                            .appendTo("#tableBody");

                        var tot = Number(itemInfo.item_quantity) + 1;

                        // ? Populate the table cells with item information
                        //# item id cell
                        newRow.find("td").eq(0).text(itemInfo.unique_item_id);
                        //# item name cell
                        newRow.find("td").eq(1).text(itemInfo.item_name);
                        //# item quantity cell
                        newRow.find("td").eq(2).text(1);
                        //# item stored cell
                        newRow.find("td").eq(3).text(itemInfo.item_quantity);
                        //# item total cell
                        newRow.find("td").eq(4).text(tot);

                        newRow.attr("data-item-id", scannedBarcode)


                    }

                }
            });
        }
    }
</script>