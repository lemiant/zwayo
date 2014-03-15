<?php
require_once("mysql_utils.php");
$con = connect_to_mch();
verify_login($con); //$USER_ID and $PARTY_ID and $USER_NAME

if($_POST['action']=="add"){
    $_POST['body']['guest'] = $USER_NAME;
}
if($_POST['action']=='add' or $_POST['action']=='set_active'){
    $action = mysqli_real_escape_string($con, $_POST['action']);
    $body = mysqli_real_escape_string($con, json_encode($_POST['body']));
    $query = "INSERT INTO queue_actions (`party_id`,`action`,`body`) VALUES ($PARTY_ID, '$action', '$body')";
    mysqli_query($con, $query);
    mysqli_close($con);
    print json_encode(array("result" => "success"));
}
else {
    mysqli_close($con);
    die($FAILURE);
}
?>