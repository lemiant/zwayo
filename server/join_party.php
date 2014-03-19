<?php
    require_once("mysql_utils.php");
    $con = connect_to_mch();
    $USER_ID = verify_login($con);
    session_start();

    $secret_name = mysqli_real_escape_string($con, strtolower($_GET['secret_name']));
    $query = "SELECT id FROM parties WHERE secret_name='$secret_name'";
    $result = mysqli_query($con, $query);
    if($row = mysqli_fetch_assoc($result)){
        $query = "UPDATE users SET current_party=".$row['id']." WHERE id=$USER_ID";
        mysqli_query($con, $query);

        header("Location: ../queue.php");
        die();
    }
    else echo "Not a party";
