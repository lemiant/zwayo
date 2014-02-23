<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/22/14
 * Time: 2:37 PM
 */
require_once("mysql_utils.php");
$con = connect_to_mch();

if(!empty($_COOKIE['party_id'])){
    $party_id = mysqli_real_escape_string($con, $_COOKIE['party_id']);
    if(isset($_POST['last'])) $last = mysqli_real_escape_string($con, $_POST['last']);
    else $last = 0;

    $query = "SELECT id, action, body FROM queue_actions WHERE `party_id`=$party_id AND `id` > $last";
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //Only set_active once
    $output = array();
    $s_a = False;
    foreach($rows as $row){
        if($row['action'] == 'set_active') $s_a = $row;
        else $output[] = $row;
    }
    if($s_a) $output[] = $s_a;

    print json_encode(array('result' => 'success', 'items' => $output));
}
else{
    die($FAILURE);
}
?>