<?php
// Error messages or messages
$message = "";

if(isset($_GET['m'])) {

    // If the "m" is set then do the switch case
    switch ($_GET['m']) {
        case "ef":
            echo "Empty fields";
            break;
        case "unf":
            echo "User not found";
            break;
        case "wp";
            echo "Wrong password";
            break;
        default:
            echo "Unknown error occured";
            break;
    }
}

