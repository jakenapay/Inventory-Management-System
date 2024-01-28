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
    try {
        $stmt = $pdo->prepare("SELECT * FROM chapters WHERE chapter_id = :id");
        $stmt->bindParam(':id', $chapterId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $chapterId = $row['chapter_id'];
            $chapterName = $row['chapter_name'];
            $chapterAddress = $row['chapter_address'];

            echo '
        <div class="col-12 col-md-12 col-lg-12">
            <div class="py-1">
                <input type="text" class="form-control form-control-sm text-capitalize" id="chapter_id" name="chapter_id" placeholder="Chapter ID"  value="' . $chapterId . '">
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
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage();
    }
} else if (isset($_POST['save-edit-chapter-btn'])) {
    include 'config.inc.php';

    $chapterId = $_POST['chapter_id'];
    $chapterName = $_POST['chapter_name'];
    $chapterAddress = $_POST['chapter_address'];

    if (empty($chapterId) || empty($chapterName) || empty($chapterAddress)) {
        header("location: ../chapters.php?m=ic&id=$chapterId&n=$chapterName&a=$chapterAddress");
        exit();
    }

    $sql = "UPDATE chapters SET chapter_name = :name, chapter_address = :address WHERE chapter_id = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $chapterId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $chapterName, PDO::PARAM_STR);
        $stmt->bindParam(':address', $chapterAddress, PDO::PARAM_STR);
        $stmt->execute();
        header("location: ../chapters.php?m=us"); // Updated successfully
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        header("location: ../chapters.php?m=" . $e->getMessage() . ""); // Failed
        exit();
    }
} else {
    header("location: ../chapters.php?m=404");
    exit();
}
