
<?php
include 'config.inc.php';

session_start();
if (isset($_POST['categoryId'])) {

   include '../components/RBcategList.php';
}

// Continue with the rest of your code or display the results as needed
