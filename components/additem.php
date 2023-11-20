<div>
    <h1>this is the add item page</h1>


    <!--
         //TODO : INSERT THE DATA IN TABLE ACCORDING TO ITEM ID THEN GET THE WHOLE INFORMATION 
    -->
    <div>
        <input type="text" name="itemid" id="itemid">
        <button type="button" class="btn btn-success" id="btnAdd">insert to table</button>
    </div>


    <div>
        <table class="table table-striped table-inverse table-responsive">
            <thead class="thead-inverse">
                <tr>
                    <th>item id</th>
                    <th>item name</th>
                    <th>item quantity</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr id="templateRow" style="display: none">
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#btnAdd").click(function() {
            const datas = document.getElementById('itemid').value;
            $.ajax({
                type: "POST",
                url: "../includes/itemadd.php",
                data: {
                    datas: datas,
                },
                success: function(response) {
                    // try {
                    //     // Parse the JSON response
                    //     var rowData = JSON.parse(response)[0];

                    //     // Clone the template row and add it to the table
                    //     var templateRow = $("#templateRow").clone().removeAttr("id");
                    //     templateRow.find('td:eq(0)').text(rowData.name);

                    //     // Append the new row to the table
                    //     templateRow.appendTo("#tableBody");
                    // } catch (error) {
                    //     console.log(error)
                    // }
                    console.log(response['item_']);

                    // listData.map((a) => {
                    //     return (
                    //         id: a.item_id,


                    //     )
                    // })
                }
            });
        });
    });
</script>