<?php
// Error messages or messages
$message = "";
define('msg', 'msg');
define('msgOk', 'msgOk');

if(isset($_GET['m'])) {

    // If the "m" is set then do the switch case
    switch ($_GET['m']) {
        case "ef":
            echo "<p id='msg' class='msg'>Empty fields</p>";
            break;
        case "unf":
            echo "<p id='msg' class='msg'>User not found</p>";
            break;
        case "wp";
            echo "<p id='msg' class='msg'>Wrong password</p>";
            break;
        case "ln":
            echo "<p id='msg' class='msg'>Login needed</p>";
            break;
        case "itd":
            echo "<p id='msg' class='msg'>Upload image only</p>";
            break;
        case "is":
            echo "<p id='msg' class='msg'>Upload image less than 2MB</p>";
            break;
        case "ie":
            echo "<p id='msg' class='msg'>Upload image with no error</p>";
            break;
        case "ue":
            echo "<p id='msg' class='msg'>Error adding item</p>";
            break;
        case "ia":
            echo "<p id='msg' class='msgOk'>Item added successfully</p>";
            break;
        default:
            echo "<p id='msg' class='msg'>Unknown error occured</p>";
            break;
    }
}

