<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side Navbar</title>
    <style>
        /* Styles for the overlay */
        .overlay {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.7);
            overflow-x: hidden;
            transition: 0.5s;
        }

        /* Styles for the sidebar */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #333;
            padding-top: 60px;
            transition: 0.5s;
        }

        /* Styles for the sidebar content (icons or menu items) */
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 20px;
            color: #fff;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        /* Styles for the close button */
        .closebtn {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 30px;
            cursor: pointer;
            color: #fff;
        }

        /* Styles to shift the page content when sidebar is open */
        .page-content {
            transition: margin-left 0.5s;
            padding: 15px;
        }

        /* Styles to move page content to the right when sidebar is open */
        .page-content.active {
            margin-left: 250px;
        }
    </style>
</head>
<body>

<!-- The overlay -->
<div id="myOverlay" class="overlay"></div>

<!-- The sidebar -->
<div class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&#9665;</a>
    <a href="#">Option 1</a>
    <a href="#">Option 2</a>
    <a href="#">Option 3</a>
</div>

<!-- Page content -->
<div class="page-content">
    <h1>Your Page Content</h1>
    <p>This is the main content of your page.</p>
    <p>Click the menu icon to open the side navbar.</p>
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
</div>

<script>
    function openNav() {
        document.getElementById("myOverlay").style.width = "100%";
        document.querySelector(".sidebar").style.left = "0";
        document.querySelector(".page-content").classList.add("active");
    }

    function closeNav() {
        document.getElementById("myOverlay").style.width = "0";
        document.querySelector(".sidebar").style.left = "-250px";
        document.querySelector(".page-content").classList.remove("active");
    }
</script>

</body>
</html>
