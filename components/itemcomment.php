<?php
$query = "SELECT * FROM item_feedback INNER JOIN users WHERE users.user_id = item_feedback.user_id AND item_feedback.item_id = $itemId";
$stmt = $pdo->prepare($query);
//$stmt->bindParam(':iID', $itemId, PDO::PARAM_INT);
$stmt->execute();
// Fetch data as an associative array
$feedbackData = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<?php

foreach ($feedbackData as $fData) { ?>
    <div class="d-flex justify-content-center mt-5 p-3">
        <div class="comment-widgets">

            <div class="d-flex flex-row comment-row m-t-0">
                <div class="p-2"><img src="./images/userProfiles/<?php echo $fData['user_image']?>" alt="user" width="50" class="rounded-circle"></div>
                <div class="comment-text ">
                    <h6 class="font-medium"><?php echo $fData['user_firstname'] ?></h6> <span class="m-b-15 d-block"><small><?php echo $fData['feedback'] ?>. </small></span>
                    <div class="comment-footer"> <span class="text-muted float-right"><small><?php echo $fData['date_of_feedback'] ?></small></span> </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>