<?php
if(!empty($_POST['fb']) && !empty($_COOKIE['party_id'])){
    require_once("mysql_utils.php");
    $con = connect_to_mch();
    
    $party_id = mysqli_real_escape_string($con, $_COOKIE['party_id']);
    $query = "SELECT host_fb FROM parties WHERE `id`=$party_id";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    if($row['host_fb'] == $_POST['fb']) print "yes";
    else print "no";
    mysqli_close($con);
}
else{
    
    die('Arguments not valid');
}
?>