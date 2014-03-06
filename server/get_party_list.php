<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/22/14
 * Time: 1:41 PM
 */
require_once("mysql_utils.php");
$con = connect_to_mch();
if(isset($_POST['friends'])){
    $range = 0.005;
    $query = "SELECT `id`, `party_name`, `host_name` FROM parties WHERE ";
    $bits = array();
    foreach($_POST['friends'] as $friend){
        $bits[] = "`host_fb`=".mysqli_real_escape_string($con, $friend);
    }
    $query .= implode(' OR ', $bits);
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    print json_encode(array("result" => "success", "items" => $rows));
}
else{
    print json_encode(array("result" => "failure", "error" => "no friends"));
}

mysqli_close($con);
?>