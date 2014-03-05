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

    $query = "SELECT id, action, body FROM queue_actions WHERE `party_id`=$party_id AND `id` > $last ORDER BY id ASC";
    $result = mysqli_query($con, $query);
    $rows = array();
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }

    //Only set_active once
    $output = array();
    $s_a = False;
    $sai = 0 ;
    for($i=0; $i<count($rows); $i++){
        $row = $rows[$i];
        if($row['action'] == 'set_active'){
            $s_a = $row;
            $sai = $i;
        }
        else $output[] = $row;
    }
    if($s_a){
        if($sai == $i-1) $output[] = $s_a;
        else{
            $temp = array_pop($output);
            $output[] = $s_a;
            $output[] = $temp;
        }
    }

    print json_encode(array('result' => 'success', 'items' => $output));
}
else{
    die($FAILURE);
}
?>