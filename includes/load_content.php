<?php
if (isset($_POST['route'])) {
    $route = $_POST['route'];

    // You can include your logic here to generate content based on the route
    if ($route === '#items') {
        include '../components/itemTable.php';
    } elseif ($route === '#additem') {
        include '../components/additem.php';
    } elseif ($route === '#link') {
        include '../components/requestitem.php';
    } else {
        include '../components/itemTable.php';
    }
}
