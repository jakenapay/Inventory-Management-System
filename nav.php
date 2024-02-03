<?php
// Get the user's image to show in the navigation bar

try {
    $stmt = $pdo->prepare("SELECT user_image FROM users WHERE user_id = :id");
    $stmt->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userImage = $row['user_image'];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// To get the value of cart
include './includes/config.inc.php';
$user_id = $_SESSION['ID'];
$query = $pdo->prepare("SELECT COUNT(user_id) AS total_ids FROM `cart` WHERE user_id = :userID");
$query->bindParam("userID", $user_id,  PDO::PARAM_INT);
$query->execute();
// Fetch the result
$result = $query->fetch(PDO::FETCH_ASSOC);

// Access the total_ids value
$totalIDs = $result['total_ids'];

?>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-sm-5 px-md-5 px-lg-5 fixed-top mainnav">
        <a class="navbar-brand logo" href="home.php">
            <img src="https://uploads-ssl.webflow.com/6492dd5d65d1855cb14a6692/6494571343918c14057fe090_DEVCON%20Kids%20Logo%20Horizontal.png" alt="Logo" loading="lazy">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end align-items-center" id="navbarNav">
            <ul class="navbar-nav d-flex justify-content-center align-items-center">
                <li class="nav-item active">
                    <a class="nav-link <?php echo (($_SESSION['active_tab'] === 'home.php')) ? 'active' : ''; ?>" href="home.php">Home</a>
                </li>
                <?php if ($_SESSION['CT'] == 0) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (($_SESSION['active_tab'] === 'items.php')) ? 'active' : ''; ?>" href="itemAction.php">Items</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (($_SESSION['active_tab'] === 'items.php')) ? 'active' : ''; ?>" href="items.php">Inventory</a>
                    </li>
                <?php } ?>

                <?php if ($_SESSION['CT'] == 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (($_SESSION['active_tab'] === 'adminItemList.php')) ? 'active' : ''; ?>" href="adminItemList.php">Items</a>
                    </li>
                <?php } ?>


                <li class="nav-item">
                    <a class="nav-link <?php echo (($_SESSION['active_tab'] === 'requests.php')) ? 'active' : ''; ?>" href="requests.php">Requests</a>
                </li>

                <!-- <?php if ($_SESSION['CT'] == 1 && $_SESSION['CH'] == 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_SESSION['active_tab'] === 'chapters.php') ? 'active' : ''; ?>" href="chapters.php">Chapters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_SESSION['active_tab'] === 'category.php') ? 'active' : ''; ?>" href="category.php">Categories</a>
                    </li>
                <?php }  ?> -->


                <?php
                if (($_SESSION['CT'] === 1) && ($_SESSION['CH'] === 1) || ($_SESSION['CT'] == 1)) {
                    echo '<li class="nav-item">
                        <a class="nav-link ' . (($_SESSION['active_tab'] === 'users.php') ? 'active' : '') . '" href="users.php">Users</a>
                    </li>';
                } ?>

                <!-- For items.php -->
                <?php
                if ($_SESSION['active_tab'] == 'items.php') {
                    if (!empty($_SESSION['ID']) && ($_SESSION['CT'] == 1)) {
                        echo '
                        <li class="nav-item nav-link" id="addNewItemBtn">
                            <button type="button" class="btn btn-success btnGreen btn-sm" data-toggle="modal" data-target="#exampleModal">
                                <i class="fa-solid fa-plus addIcon"></i>New item
                            </button>
                        </li>

                     ';
                    }
                }
                ?>

                <!-- For mobile -->
                <li class="nav-item dropdown" id="profile-dropdown-mobile">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION['FN'] . " " . $_SESSION['LN']; ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="profile.php">Profile</a>
                        <a class="dropdown-item" href="faq.php">FAQs</a>
                        <a class="dropdown-item" href="#devconkids.php">Devcon Kids</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item logout" href="includes/logout.inc.php"><span>Log Out</span><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
                    </div>
                </li>

                <!-- Display none if the users is on mobile -->
                <!-- Display block if the users is on desktop/laptop -->
                <li class="nav-item dropdown pull-right">
                    <!-- Get the user's image to show in the navigation bar -->
                    <?php
                    try {
                        $stmt = $pdo->prepare("SELECT user_image FROM users WHERE user_id = :id");
                        $stmt->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $userImage = $row['user_image'];
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }

                    ?>
            
                    <!-- Image trigger -->
                    <div class="rounded-image image-profile" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="images/userProfiles/<?php echo $userImage; ?>" class="img-fluid" alt="User Image">
                    </div>
                    <!-- Dropdown menu -->
                    <div class="dropdown-menu dropdown-menu-left px-2" aria-labelledby="dropdownMenuButton">
                        <!-- <p id="profile-name" class="dropdown-item" href="includes/logout.inc.php">
                            <span><?php echo $_SESSION['FN']; ?></span></p> -->
                        <a class="dropdown-item disabled text-dark pt-2 pe-none"><?php echo $_SESSION['FN']; ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php">Profile</a>
                        <a class="dropdown-item" href="#about">About</a>
                        <a class="dropdown-item" href="#faq">FAQs</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item logout" href="includes/logout.inc.php"><span>Log Out</span></a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>