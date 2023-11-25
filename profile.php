<?php
include 'includes/config.inc.php';
session_start();

// Check if there's an id, if it has, then it's logged in
// If there's no id, head back to login page
if (!isset($_SESSION['ID']) and ($_SESSION['ID'] == '')) {
    header("location: index.php");
    exit();
}

// To determine which is active page in nav bar
$_SESSION['active_tab'] = basename($_SERVER['SCRIPT_FILENAME']);

// Get the user profile
try {
    $query = "SELECT * FROM users WHERE user_id = {$_SESSION['ID']} LIMIT 1;";
    // Prepare the query
    $stmt = $pdo->query($query);
    // Execute the query
    $stmt->execute();
    // Fetch all rows as an associative array
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
    // Process the result (e.g., display it)
    foreach ($result as $row) {
        // Access columns by their names, e.g., $row['column_name']
        // Get all details to refresh automatically after being updated
        $firstname = $row['user_firstname'];
        $lastname = $row['user_lastname'];
        $email = $row['user_email'];
        $category = $row['user_category'];
        $chapter = $row['user_chapter'];
        $old_img = $row['user_image'];
    }
} catch (PDOException $e) {
    // Handle database connection or query errors
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Headers and other attachments/CDN -->
    <?php include_once 'includes/headers.inc.php'; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/nav.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/items.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/profile.css?v=<?php echo time(); ?>">
    

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <!-- Javascript for Datatables.net  -->
    <script>
        $(document).ready(function() {
            $('table').DataTable();
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        });

        setTimeout(function(){ 
            document.getElementById("msg").style.display = "none"; // hide the element after 3 seconds
        }, 5000);
    </script>
</head>

<body>

    <?php include 'nav.php';?>

    <div id="wrapper">
        <div class="section px-5 pt-4">
            
            <!-- Messages (error msgs or information) -->
            <div class="row justify-content-center align-items-center mt-3">
                <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                    <?php include 'includes/message.inc.php';?>
                </div>
            </div>

            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-sm-12 col-md-12 col-lg-11">
                    <form action="includes/profile.inc.php" method="POST" enctype="multipart/form-data">
                        <div class="row d-flex justify-content-center align-items-center">
                            <!-- Container for details like name, email -->

                            <!-- User profile picture -->
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                                <!-- <img src="images/userProfiles/" loading="lazy" class="d-flex justify-content-center img-thumbnail img-fluid" alt="Image" name="user_image"> -->
                                <?php
                                if ($old_img != '') {
                                    // if there's an existing image then echo the image
                                    echo '<img src="images/userProfiles/' . $old_img . '" alt="" loading="lazy" class="d-flex justify-content-center img-thumbnail img-fluid" alt="Image">';
                                } else {
                                    echo '<img src="images/userProfiles/" loading="lazy" class="d-flex justify-content-center img-thumbnail img-fluid" alt="No image found">';
                                }
                                ?>
                                <!-- hidden -->
                                <input type="hidden" name="old_img" value="<?php echo $old_img; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $_SESSION['ID']; ?>">
                                <hr>
                                <label class="label">Update image: </label>
                                <input type="file" accept="image/*" name="new_user_image" id="new_user_image">
                            
                            </div>
                            <!-- User details -->
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <!-- Name -->
                                <div class="col-12 col-md-12 col-lg-12 py-1">
                                    <label for="user_firstname">Given Name</label>
                                    <input type="text" class="form-control form-control-sm text-capitalize" id="user_firstname" name="user_firstname" placeholder="Given Name" required value="<?php echo $firstname;?>">
                                </div>
                                <!-- Surname -->
                                <div class="col-12 col-md-12 col-lg-12 py-1">
                                    <label for="user_lastname">Surname</label>
                                    <input type="text" class="form-control form-control-sm text-capitalize" id="user_lastname" name="user_lastname" placeholder="Last Name" required value="<?php echo $lastname;?>">
                                </div>
                                <!-- Email address -->
                                <div class="col-12 col-md-12 col-lg-12 py-1">
                                    <label for="user_email">Email Address</label>
                                    <input type="email" class="form-control form-control-sm" id="user_email" name="user_email" placeholder="Email Address" required value="<?php echo $email;?>">
                                </div>
                                <!-- Select the session category from database -->
                                <?php
                                try {
                                    $sql = "SELECT category_id, category_name FROM category WHERE category_id = {$_SESSION['CT']}";

                                    // Prepare the query
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();

                                    // Fetch the no. of session category but in category name
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Process the result
                                    foreach($result as $row) {
                                        $category_id = $row['category_id'];
                                        $category_name = $row['category_name'];
                                    }
                                } catch (PDOException $e) {
                                    // Handle database connection or query errors
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                                <!-- CATEGORY -->
                                <div class="col-12 col-md-12 col-lg-12 py-1">
                                    <label for="item_category">Category</label>
                                    <input type="text" class="form-control form-control-sm text-capitalize" id="user_category" name="user_category" placeholder="Given Name" required value="<?php echo $category_name;?>" disabled>
                                </div>
                                <!-- Select the session category from database -->
                                <?php
                                try {
                                    $sql = "SELECT chapter_id, chapter_name FROM chapters WHERE chapter_id = {$_SESSION['CH']}";

                                    // Prepare the query
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();

                                    // Fetch the no. of session category but in category name
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Process the result
                                    foreach($result as $row) {
                                        $chapter_id = $row['chapter_id'];
                                        $chapter_name = $row['chapter_name'];
                                    }
                                } catch (PDOException $e) {
                                    // Handle database connection or query errors
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                                <!-- CHAPTER -->
                                <div class="col-12 col-md-12 col-lg-12 py-1">   
                                    <label for="chapter">Chapter</label>
                                    <input type="text" class="form-control form-control-sm text-capitalize" id="user_firstname" name="user_chapter" placeholder="Given Name" required value="<?php echo $chapter_name;?>" disabled>
                                </div>
                                <!-- Save button -->
                                <div class="mt-3 d-block justify-content-center align-items-center">
                                    <input type="submit" class="btn btn-sm btnGreen w-100 text-light" name="save-profile-btn" value="Save Changes">
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>


    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>