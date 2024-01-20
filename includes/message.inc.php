<?php
// Error messages or messages
$message = "";

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
        case "pm";
            echo "<p id='msg' class='msg'>Password Mismatch</p>";
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
        case "rqf":
            echo "<p id='msg' class='msg'>Request failed</p>";
            break;
        case "iac":
            echo "<p id='msg' class='msg'>Inactive account</p>";
            break;
        case "ia":
            echo "<p id='msg' class='msgOk'>Item added successfully</p>";
            break;
        case "ss":
            echo "<p id='msg' class='msgOk'>Requested successfully</p>";
            break;
        case "aca":
            echo "<p id='msg' class='msgOk'>Account activated successfully</p>";
            break;
        case "us":
            echo "<p id='msg' class='msgOk'>Updated successfully</p>";
            break;
        case "ifs":
            echo "<p id='msg' class='msg'>Insufficient quantity</p>";
            break;
        case "ds":
            echo "<p id='msg' class='msgOk'>Deleted successfully</p>";
            break;
        case "dcs":
            echo "<p id='msg' class='msgOk'>Declined successfully</p>";
            break;
        case "as":
            echo "<p id='msg' class='msgOk'>Approved successfully</p>";
            break;
        case "rqs":
            echo "<p id='msg' class='msgOk'>Requested successfully</p>";
            break;
        default:
            echo "<p id='msg' class='msg'>Unknown error occured</p>";
            break;
    }
}

