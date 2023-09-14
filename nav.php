
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
                        <li><a href="home.php" class="<?php echo (($_SESSION['active_tab'] === 'home.php')) ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="items.php" class="<?php echo (($_SESSION['active_tab'] === 'items.php')) ? 'active' : ''; ?>">Items</a></li>
                        <li><a href="requests.php" class="<?php echo (($_SESSION['active_tab'] === 'requests.php')) ? 'active' : ''; ?>">Requests</a></li>
                        
                        <!-- FOR CHAPTERS LINK -->
                        <!-- Only admins and manila chapter can see this page -->
                        <?php
                            if (($_SESSION['CT'] === 1) && ($_SESSION['CH'] === 1)) {
                                $activeClass = ($_SESSION['active_tab'] === 'chapters.php') ? 'active' : '';
                                echo '<li><a href="chapters.php" class="' . $activeClass . '">Chapters</a></li>';
                            }
                        ?>
                        <li><a href="logs.php" class="<?php echo (($_SESSION['active_tab'] === 'logs.php')) ? 'active' : ''; ?>">Logs</a></li>
                    </ul>

                    <ul>
                        <!-- <li><a href="profile.php" id="name"><?php echo $_SESSION['FN']; ?> <i class="fa-solid fa-caret-down"></i></a></li> -->
                        <!-- Dropdown -->
                        <div class="btn-group">
                            <button type="button" id="btn-more-action" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $_SESSION['FN']; ?>
                            </button>
                            <div id="more-action-menu" class="dropdown-menu">
                                <a class="dropdown-item" href="#">Profile</a>
                                <a class="dropdown-item" href="#">About</a>
                                <a class="dropdown-item" href="#">FAQs</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="includes/logout.inc.php">Log out</a>
                            </div>
                        </div>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>