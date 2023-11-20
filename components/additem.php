<?php
session_start();
?>

<div>
    <h1>this is the add existing item page</h1>
    <div>
        <input type="text" name="itemid" id="itemid" autofocus>
        <button type="button" class="btn btn-success" id="btnAdd">insert to table</button>
    </div>

    <div class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alert</h5>
                </div>
                <div class="modal-body">
                    <p>The id not Existing</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    

    <div>
        <input type="text" value="<?php echo $_SESSION['CH'] ?>" hidden id="chapter">
        <table class="table table-striped table-inverse table-responsive">
            <thead class="thead-inverse">
                <tr>
                    <th>item id</th>
                    <th>item name</th>
                    <th>item quantity</th>
                    <th>item stored</th>
                    <th>total</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr id="templateRow" style="display: none">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-success" id="getdata">get all data in the table</button>
    </div>
</div>

<script>
$(document).ready(function() {

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
                        $('.modal').modal('show');
                        setInterval(function() {
                            $('.modal').modal('hide');
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
                        newRow.find("td").eq(0).text(itemInfo.item_id);
                        //# item name cell
                        newRow.find("td").eq(1).text(itemInfo.item_name);
                        //# item quantity cell
                        newRow.find("td").eq(2).text(1);
                        //# item stored cell
                        newRow.find("td").eq(3).text(itemInfo.item_quantity);
                        //# item total cell
                        newRow.find("td").eq(4).text(tot);

                        newRow.attr("data-item-id", datas)
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

        // // Send the data to the server using AJAX
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
</script>