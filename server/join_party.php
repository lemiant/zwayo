<?php
require_once("mysql_utils.php");
$con = connect_to_mch();

if(isset($_POST['action']) && $_POST['action'] == 'make_party' && !empty($_POST['host_fb']) && !empty($_POST['party_name']) && !empty($_POST['host_name'])){
    $party_name = mysqli_real_escape_string($con, $_POST['party_name']);
    $host_fb = mysqli_real_escape_string($con, $_POST['host_fb']);
    $host_name = mysqli_real_escape_string($con, $_POST['host_name']);
    $query = "INSERT INTO parties (`party_name`, `host_fb`, `host_name`) VALUES ('$party_name', '$host_fb', '$host_name')";
    //echo $query;
    mysqli_query($con, $query);
    $party_id = mysqli_insert_id($con);
}
else if(!empty($_POST['party_id'])){ //Will already die if party
    $party_id = mysqli_real_escape_string($con, $_POST['party_id']);
    if(!check_party_id($con, $party_id)) die('Not a real party_id');
}
else {
    mysqli_close($con);
    die('Arguments not valid');
}
setcookie("party_id", $party_id, time()+3600*72, '/');

mysqli_close($con);

header("Location: ../queue.php");
?>