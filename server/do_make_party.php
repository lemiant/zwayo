<?php
    require_once('mysql_utils.php');
    $con = connect_to_mch();
    $USER_ID = verify_login($con);
    session_start();

    $party_name = mysqli_real_escape_string($con, $_SESSION['action_data']['party_name']);
    $secret_name = mysqli_real_escape_string($con, strtolower($_POST['secret_name']));
    $host = $USER_ID;

    $query = "INSERT INTO parties (party_name, secret_name, host) VALUES ('$party_name', '$secret_name', $host)";
    mysqli_query($con, $query);

    header("Location: join_party.php?secret_name=".$secret_name);
    die();
