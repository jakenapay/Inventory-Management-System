<?php
include_once '../includes/config.inc.php';
session_start();

$user_chapter = $_SESSION['CH'];
$query = "SELECT * FROM ctochistory 
INNER JOIN chapters ON ctochistory.from_chapter = chapters.chapter_id
INNER JOIN items ON ctochistory.history_item_id = items.item_id
INNER JOIN users ON ctochistory.history_user_id = users.user_id
WHERE to_chapter = $user_chapter;
";
?>
<div class="row justify-content-center align-items-center">
    <div class="col-12 col-sm12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="example" class="table table-hover table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Item Id</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>User</th>
                        <th>Request Status</th>
                        <th>Date Requested</th>
                        <th>Return?</th>
                        <th>Date Returned</th>
                        <th>Due Date</th>
                        <th>Chapter</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query($query);
                    // Execute the query
                    $stmt->execute();
                    // Fetch all rows as an associative array
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    // Process the result (e.g., display it)
                    foreach ($result as $row) {
                        // Access columns by their names, e.g., $row['column_name']
                    ?>
                        <tr>
                            <td><?php echo $row['history_id'] ?></td>
                            <td><?php echo $row['history_item_id'] ?></td>
                            <td><?php echo $row['item_name'] ?></td>
                            <td><?php echo $row['history_quantity'] ?></td>
                            <td class="text-capitalize"><?php echo $row['user_firstname'] . " " . $row["user_lastname"] ?></td>
                            <td class="text-capitalize"><?php echo $row['history_status'] ?></td>
                            <td><?php echo $row['history_date'] ?></td>
                            <td><?php //echo $row['history_isReturn'] 
                                if ($row['history_isReturn'] == 0) {
                                    echo "NO";
                                } else {
                                    echo "YES";
                                }
                                ?></td>
                            <td><?php echo $row['history_date_return'] ?></td>
                            <td><?php echo $row['history_due_date'] ?></td>
                            <td><?php echo $row['chapter_name'] ?></td>


                            <td>
                                <?php
                                if ($row['history_status'] == "process") {
                                    echo '<a href="http://" class="approve-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#approveModal" data-item-id="' . $row['history_id'] . '"  title="Delivered"><i class="fa-solid fa-check"></i></a>';
                                }
                                // echo '<a href="http://" class="decline-btn" target="" rel="noopener noreferrer" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#declineModal" data-item-id="' . $row['history_id'] . '" title="Decline"><i class="fa-solid fa-x"></i></a>';
                                ?>

                            </td>
                        </tr>

                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>
</div>



<!-- Approving request item modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="includes/requestItem.inc.php" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Mark as Delivered</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="approve_request_id" id="approve_request_id">
                    <h6>Are you sure you want to mark this request as delivered?</h6>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <input type="" name="user_id" id="user_id" value="<?php echo $_SESSION['ID']; ?>" hidden>
                    <button type="button" class="btn btn-secondary btnRed btn-sm px-2" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btnGreen text-light btn-sm mx-1" name="approve-request-item-btn" value="Mark as Delivered">
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    new DataTable('#example');

    $(document).ready(function() {

        // Enable the item from modal
        $('table').on('click', '.approve-btn', function(e) {
            e.preventDefault();
            var itemId = $(this).data('item-id');
            $.ajax({
                type: 'POST',
                url: 'includes/requestItem.inc.php',
                data: {
                    'approve-request-item-btn': true,
                    'item_id': itemId
                },
                success: function(response) {
                    $('#approve_request_id').val(itemId);
                    $('#approveModal').modal('show');
                }
            });
        });
    });
</script>