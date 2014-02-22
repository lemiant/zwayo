<?php
require_once("mysql_utils.php");
$con = connect_to_mch();
if(isset($_POST['action']) && $_POST['action'] == 'add'){
    $party_name = mysqli_real_escape_string($con, $_POST['party_name']);
    $guest_name = mysqli_real_escape_string($con, $_POST['guest_name']);
    $admin_key = rand_letter(3);
    $lat = mysqli_real_escape_string($con, $_POST['lat']);
    $long = mysqli_real_escape_string($con, $_POST['long']);
    $query = "INSERT INTO parties (`party_name`, `host`, `admin_key`, `lat`, `long`) VALUES ('$party_name', '$guest_name', '$admin_key', '$lat', '$long')";
    mysqli_query($con, $query);
    $party_id = mysqli_insert_id($con);
}
else{
    $guest_name = mysqli_real_escape_string($con, $_POST['guest_name']);
    $party_id = mysqli_real_escape_string($con, $_POST['party_id']);
    $query = "SELECT id FROM parties WHERE id='$party_id'";
    $result = mysqli_query($con, $query);
    if(!mysqli_fetch_row($result)){
        die('Party ID does not exist');
    }
}
setcookie("party_id", $party_id, time()+3600*72, '/');
setcookie("guest_name", $guest_name, time()+3600*72, '/');
if(isset($admin_key)) setcookie("admin_key", $admin_key, time()+3600*72, '/');

header("Loaction: /queue.php");
?>