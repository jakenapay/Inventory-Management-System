
<?php
include 'config.inc.php';

session_start();
if (isset($_POST['categoryId']) && isset($_POST['chapter'])) {

   include '../components/RBcategList.php';
}else{

   echo 'error';  
}
?>
