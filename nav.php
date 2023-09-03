
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
                        <li><a href="home.php" class="<?php echo (($_SESSION['active_tab'] === 'home')) ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="items.php" class="<?php echo (($_SESSION['active_tab'] === 'items')) ? 'active' : ''; ?>">Items</a></li>
                        <li><a href="requests.php" class="<?php echo (($_SESSION['active_tab'] === 'requests')) ? 'active' : ''; ?>">Requests</a></li>
                        <li><a href="logs.php" class="<?php echo (($_SESSION['active_tab'] === 'logs')) ? 'active' : ''; ?>">Logs</a></li>
                    </ul>

                    <ul>
                        <li><a href="profile.php" id="name"><?php echo $_SESSION['FN']; ?> <i class="fa-solid fa-caret-down"></i></a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>