<?php
require_once("mysql_utils.php");
$con = connect_to_mch();
//$admin_key  = mysqli_real_escape_string($con, $_COOKIE['admin_key']);
$party_id = mysqli_real_escape_string($con, $_COOKIE['party_id']);
if ($_POST['action']=="add"){
    $action = "add";
    $body = mysqli_real_escape_string($con, $_POST['body']);
    $query = "INSERT INTO queue_actions (`party_id`,`action`,`body`) VALUES ($party_id, '$action', '$body')";
    mysqli_query($con, $query);
    print json_encode(array("result" => "success"));
}
else{
    print json_encode(array("result"=>"failure"));
}

/*$query = "SELECT admin_key FROM parties WHERE id=$party_id";
$result= mysqli_query($con, $query);

if($row= mysqli_fetch_row($result,MYSQLI_ASSOC)){
    if ($row["admin_key"]== $admin_key)
}   */