<?php
require_once("mysql_utils.php");
$con = connect_to_mch();

$party_id = mysqli_real_escape_string($con, $_COOKIE['party_id']);
if(!check_party_id($con, $party_id)) die($FAILURE);
if($_POST['action']=="add" or
  ($_POST['action'] == 'set_active' and !empty($_POST['admin_key']) and check_admin_key($con, $party_id, $_POST['admin_key']))){
    $action = mysqli_real_escape_string($con, $_POST['action']);
    $_POST['body']['guest'] = $_COOKIE['guest_name'];
    $body = mysqli_real_escape_string($con, json_encode($_POST['body']));
    $query = "INSERT INTO queue_actions (`party_id`,`action`,`body`) VALUES ($party_id, '$action', '$body')";
    print_r($query);
    mysqli_query($con, $query);
    mysqli_close($con);
    print json_encode(array("result" => "success"));
}
else {
    mysqli_close($con);
    die($FAILURE);
}
?>