<?php
if (isset($_POST['route'])) {
    $route = $_POST['route'];
    // You can include your logic here to generate content based on the route
    if ($route === '#items') {
        include '../components/itemTable.php';
    } elseif ($route === '#additem') {
        include '../components/additem.php';
    } elseif ($route === '#itemreq') {
        include '../components/requestitem.php';
    } elseif ($route === '#itemstrg') {
        include '../components/itemlocation.php';
    }elseif($route === '#itemcrit'){
        include '../components/criticaltable.php';
    }elseif($route === '#itemcateg'){
        include '../components/itemcategory.php';
    }elseif($route === '#chapters'){
        include '../components/chapters.php';
    }elseif($route === '#audit'){
        include '../components/audit.php';
    } else {
        include '../components/itemTable.php';
    }
}
