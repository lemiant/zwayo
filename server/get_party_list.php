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
    $query = "SELECT `id`, `party_name`, `host_name` FROM parties WHERE `host_fb` IN ";
    array_walk($_POST['friends'], function(&$friend) use (&$con){
        $friend = mysqli_real_escape_string($con, $friend)
    });
    $query .= '('.implode(',', $_POST['friends']).')';
    $result = mysqli_query($con, $query);
    $rows = fetch_all($result);

    print json_encode(array("result" => "success", "items" => $rows));
}
else{
    print json_encode(array("result" => "failure", "error" => "no friends"));
}

mysqli_close($con);
