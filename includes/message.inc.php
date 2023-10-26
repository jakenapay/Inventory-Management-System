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
        case "ln":
            echo "Login needed";
            break;
        case "itd":
            echo "Upload image only";
            break;
        case "is":
            echo "Upload image less than 2MB";
            break;
        case "ie":
            echo "Upload image with no error";
            break;
        case "ue":
            echo "Error adding item";
            break;
        case "ia":
            echo "Item added successfully";
            break;
        default:
            echo "Unknown error occured";
            break;
    }
}

