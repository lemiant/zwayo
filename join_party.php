<?php
require_once("mysql_utils.php");
$con = connect_to_mch();

if(isset($_POST['action']) && $_POST['action'] == 'make_party'
    && (!empty($_POST['guest_name']) && isset($_POST['lat']) && isset($_POST['long']) && is_numeric($_POST['lat']) && is_numeric($_POST['long']))){
    $party_name = mysqli_real_escape_string($con, $_POST['party_name']);
    $guest_name = mysqli_real_escape_string($con, $_POST['guest_name']);
    $admin_key = rand_letter(3);
    $lat = floatval($_POST['lat']);
    $long = floatval($_POST['long']);
    $query = "INSERT INTO parties (`party_name`, `host`, `admin_key`, `lat`, `long`) VALUES ('$party_name', '$guest_name', '$admin_key', $lat, $long)";

    mysqli_query($con, $query);
    $party_id = mysqli_insert_id($con);
}
else if(!empty($_POST['guest_name'])){ //Will already die if party
    $guest_name = mysqli_real_escape_string($con, $_POST['guest_name']);
    $party_id = mysqli_real_escape_string($con, $_POST['party_id']);
    if(!check_party_id($con, $party_id)) die('Not a real party_id');
}
else {
    mysqli_close($con);
    die('Arguments not valid');
}

setcookie("party_id", $party_id, time()+3600*72, '/');
setcookie("guest_name", $guest_name, time()+3600*72, '/');
if(isset($admin_key)) setcookie("admin_key", $admin_key, time()+3600*72, '/');

mysqli_close($con);

header("Loaction: /queue.php");
?>