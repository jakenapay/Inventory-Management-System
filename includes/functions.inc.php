<?php

// For login too
function login($conn, $email, $password)
{

    $sql = "SELECT * FROM users WHERE user_email='$em' LIMIT 1";
    $sql_result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($sql_result) > 0) {
        while ($row = mysqli_fetch_assoc($sql_result)) {
            if (password_verify($pw, $row['user_password'])) {
                session_start();
                $_SESSION["id"] = $row['user_id'];
                $_SESSION['fn'] = $row['user_firstname'];
                $_SESSION['ln'] = $row['user_lastname'];
                $_SESSION['em'] = $row['user_email'];
                $_SESSION['ct'] = $row['user_category'];
                $_SESSION['st'] = $row['user_status'];

                // active !== active = false; go to else
                // inactive !== active = true; run the inside code
                // and check if admin or not
                if ($row['user_status'] == 'active') {
                    // admin != admin = false; go to else
                    // user != admin = true; you're user only
                    if ($row['user_category'] !== 'admin') {
                        header("location: ../home.php");
                        exit();
                    } else {
                        header("location: ../index.php");
                        exit();
                    }
                } else {
                    // Account is inactive
                    header("location: ../login.php?m=inactiveAccount");
                    exit();
                }
            } else {
                // Wrong password 
                header("location: ../login.php?m=wrongPassword");
                exit();
            }
        }
    } else {
        // No results found
        header("location: ../login.php?m=userNotFound");
        exit();
    }
}