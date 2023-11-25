<link rel="stylesheet" href="../assets/css/nav.css?v=<?php echo time(); ?>">

<link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
<div class="section mainnav">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-10">

            <nav>
                <!-- Image logo -->
                <div class="logo">
                    <a href="home.php"><img src="https://uploads-ssl.webflow.com/6492dd5d65d1855cb14a6692/6494571343918c14057fe090_DEVCON%20Kids%20Logo%20Horizontal.png" alt="Logo" loading="lazy"></a>
                </div>

                <!-- Links -->
                <div class="links d-flex">
                    <ul>
                        <li><a href="home.php" class="<?php echo (($_SESSION['active_tab'] === 'home.php')) ? 'active' : ''; ?>">Home</a>
                        </li>
                        <li><a href="items.php" class="<?php echo (($_SESSION['active_tab'] === 'items.php')) ? 'active' : ''; ?>">Items</a>
                        </li>
                        <li><a href="requests.php" class="<?php echo (($_SESSION['active_tab'] === 'requests.php')) ? 'active' : ''; ?>">Requests</a>
                        </li>

                        <!-- FOR CHAPTERS LINK -->
                        <!-- Only admins and manila chapter can see this page -->
                        <?php
                        if (($_SESSION['user_chapter'] === 1) && ($_SESSION['user_category'] === 1)) {
                            $activeClass = ($_SESSION['active_tab'] === 'chapters.php') ? 'active' : '';
                            echo '<li><a href="chapters.php" class="' . $activeClass . '">Chapters</a></li>';
                        }
                        ?>
                        <li><a href="logs.php" class="<?php echo (($_SESSION['active_tab'] === 'logs.php')) ? 'active' : ''; ?>">Logs</a>
                        </li>
                    </ul>

                    <ul>
                        <!-- <li><a href="profile.php" id="name"><?php echo $_SESSION['firs']; ?> <i class="fa-solid fa-caret-down"></i></a></li> -->
                        <!-- Dropdown -->
                        <div class="btn-group">
                            <button type="button" id="btn-more-action" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <?php
                                echo $_SESSION['user_firstname'];
                                ?>
                            </button>
                            <div id="more-action-menu" class="dropdown-menu">
                                <a class="dropdown-item" href="#">Profile</a>
                                <a class="dropdown-item" href="#">About</a>
                                <a class="dropdown-item" href="#">FAQs</a>
                                <div class="dropdown-divider"></div>
                                <div class="">
                                    <a class="dropdown-item logout" href="includes/logout.inc.php"><span>Log
                                            out</span><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
                                </div>
                            </div>
                        </div>
                    </ul>

                    <!-- For items.php -->
                    <?php
                    if ($_SESSION['active_tab'] == 'items.php') {
                        if (!empty($_SESSION['user_id']) && ($_SESSION['user_chapter'] == 1)) { // If you're logged in and an admin you can add item
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