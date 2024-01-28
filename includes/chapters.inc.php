<?php
if (isset($_POST['add-chapter-btn'])) {

    include 'config.inc.php';
    // Get the data
    $chapterName = $_POST['chapter_name'];
    $chapterAddress = $_POST['chapter_address'];

    // If empty then go back
    if (empty($chapterName) && empty($chapterAddress)) {
        header("location: ../chapters.php?m=ic");
        exit();
    }

    try {
        // Your SQL query with placeholders
        $sql = "INSERT INTO `chapters` (`chapter_id`, `chapter_name`, `chapter_address`) VALUES (NULL, :chapterName, :chapterAddress)";
        // Prepare the statement
        $stmt = $pdo->prepare($sql);
        // Bind parameters
        $stmt->bindParam(':chapterName', $chapterName, PDO::PARAM_STR);
        $stmt->bindParam(':chapterAddress', $chapterAddress, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Redirect after successful insertion
        header("location: ../chapters.php?m=ca"); // Chapter Added Succesfully
        exit();
    } catch (PDOException $e) {
        // Handle the exception
        echo "Error: " . $e->getMessage();
        header("location: ../chapters.php?m=error");
        exit();
    }
} else if (isset($_POST['edit_chapter_view'])) {
    include 'config.inc.php';

    // Get data
    $chapterId = $_POST['chapter_id'];
    $stmt = $pdo->prepare("SELECT * FROM chapter WHERE chapter_id = :id");
    $stmt->bindParam(':id', $chapterId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        $chapterId = $row['chapter_id'];
        $chapterName = $row['chapter_name'];
        $chapterAddress = $row['chapter_address'];

        echo '
        <div class="col-6 col-md-6 col-lg-6">
            <div class="py-1">
                <input type="hidden" class="form-control form-control-sm text-capitalize" id="chapter_id" name="chapter_id" placeholder="Chapter ID"  value="' . $chapterId . '">
                <label for="chapter_name">Chapter Name</label>
                <input type="text" class="form-control form-control-sm text-capitalize" id="chapter_name" name="chapter_name" placeholder="Chapter Name"  value="' . $chapterName . '">
            </div>
            <div class="py-1">
                <label for="chapter_address">Chapter Address</label>
                <input type="text" class="form-control form-control-sm text-capitalize" id="chapter_address" name="chapter_address" placeholder="Chapter Address"  value="' . $chapterAddress . '">
            </div>
        </div>
        ';
    }
} else {
    header("location: ../chapters.php?m=404");
    exit();
}
