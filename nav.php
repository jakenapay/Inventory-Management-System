<div class="section mainnav">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-10">
            <?php
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

            <nav>
                <!-- Image logo -->
                <div class="logo">
                    <a href="home.php"><img
                            src="https://uploads-ssl.webflow.com/6492dd5d65d1855cb14a6692/6494571343918c14057fe090_DEVCON%20Kids%20Logo%20Horizontal.png"
                            alt="Logo" loading="lazy"></a>

                </div>

                <!-- Links -->
                <div class="links d-flex">
                    <ul class="d-flex justify-content-center align-items-center">
                        <li><a href="home.php"
                                class="<?php echo (($_SESSION['active_tab'] === 'home.php')) ? 'active' : ''; ?>">Home</a>
                        </li>
                        <li><a href="items.php"
                                class="<?php echo (($_SESSION['active_tab'] === 'items.php')) ? 'active' : ''; ?>">Items</a>
                        </li>
                        <li><a href="requests.php"
                                class="<?php echo (($_SESSION['active_tab'] === 'requests.php')) ? 'active' : ''; ?>">Requests</a>
                        </li>

                        <!-- FOR CHAPTERS LINK -->
                        <!-- Only admins and manila chapter can see this page -->
                        <?php
                        if (($_SESSION['CT'] === 1) && ($_SESSION['CH'] === 1)) {
                            $activeClass = ($_SESSION['active_tab'] === 'chapters.php') ? 'active' : '';
                            echo '<li><a href="chapters.php" class="' . $activeClass . '">Chapters</a></li>';
                        }
                        ?>
                        <li><a href="logs.php"
                                class="<?php echo (($_SESSION['active_tab'] === 'logs.php')) ? 'active' : ''; ?>">Logs</a>
                        </li>
                        <li><a href="users.php"
                                class="<?php echo (($_SESSION['active_tab'] === 'users.php')) ? 'active' : ''; ?>">Users</a>
                        </li>
                    </ul>
                    <ul class="d-flex justify-content-center align-items-center">
                        <!-- <li><a href="profile.php" id="name"><?php echo $_SESSION['FN']; ?> <i class="fa-solid fa-caret-down"></i></a></li> -->
                        <!-- Dropdown -->
                        <div class="dropdown show">
                            <a class="btn btn-sm dropdown-toggle" href="" role="button" id="dropdownCart"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 576 512">
                                    <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <style>
                                    svg {
                                        fill: #121212;
                                    }
                                    </style>
                                    <path
                                        d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                                </svg>

                                <?php
                                if ($totalIDs > 0) {
                                    echo "<span class='badge badge-warning' id='lblCartCount'> $totalIDs</span>";
                                }
                                ?>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownCart">
                                <?php
                                try {
                                    
                                    $stmt = $pdo->prepare("SELECT cart.*, items.*, users.* 
                                        FROM cart 
                                        INNER JOIN items ON cart.item_id = items.item_id
                                        INNER JOIN users ON cart.user_id = users.user_id 
                                        WHERE cart.user_id = :id");
                                     $stmt->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
                                     $stmt->execute();

                                    // Fetch all rows as an associative array
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Process the result
                                    foreach ($result as $row) {    ?>
                                        <div class="border-bottom" style="width: 250px; max-height: 400px; overflow-y: auto;">
                                            <div class="row g-0">
                                                <div class="col-md-6">
                                                    <img src="images\items\<?php echo $row['item_image'] ?>"
                                                        class="img-fluid rounded-start" style="width: 100%; max-height: 100%;"
                                                        alt="Item Image">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card-body">
                                                        <h6 class="card-title"><?php echo $row['item_name'] ?></h6>
                                                        <p class="card-text"><?php echo $row['item_description'] ?></p>
                                                        <sub class="card-text">
                                                            <small class="text-body-secondary">
                                                                <?php
                                                                        $timestamp = strtotime($row['date_added']);
                                                                        $dateTime = new DateTime();
                                                                        $dateTime->setTimestamp($timestamp);
                                                                        $date = $dateTime->format('Y-m-d');
                                                                        echo "Date: $date";
                                                                        ?>
                                                            </small>
                                                        </sub>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Your dropdown content goes here -->
                                            <!-- Make sure this content is long enough to exceed 400px in height to see the scrollbar -->
                                        </div>
                                    <?php  }
                                    } catch (PDOException $e) {
                                        echo "Error: " . $e->getMessage();
                                    } ?>
                            </div>
                        </div>

                        <div class="dropdown">

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
                            <div class="rounded-image" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <img src="images/userProfiles/<?php echo $userImage; ?>" class="img-fluid mw-50"
                                    alt="User Image">
                            </div>
                            <!-- Dropdown menu -->
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <p id="profile-name" class="dropdown-item" href="includes/logout.inc.php"><span><?php echo $_SESSION['FN'];?></span></p>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="profile.php">Profile</a>
                                <a class="dropdown-item" href="about.php">About</a>
                                <a class="dropdown-item" href="faqs.php">FAQs</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item logout" href="includes/logout.inc.php"><span>Log out</span><i
                                        class="fa-solid fa-arrow-right-from-bracket"></i></a>
                            </div>
                        </div>

                    </ul>

                    <!-- For items.php -->
                    <?php
                    if ($_SESSION['active_tab'] == 'items.php') {
                        if (!empty($_SESSION['ID']) && ($_SESSION['CT'] == 1)) { // If you're logged in and an admin you can add item
                            echo '<ul><button type="button" class="btn btn-success btnGreen btn-sm" data-toggle="modal" data-target="#exampleModal"><i class="fa-solid fa-plus addIcon"></i>New item
                                </button></ul>';
                        }
                    }
                    ?>
                </div>
            </nav>
        </div>
    </div>
</div>