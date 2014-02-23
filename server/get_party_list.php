<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/22/14
 * Time: 1:41 PM
 */
require_once("mysql_utils.php");
$con = connect_to_mch();

if(isset($_POST['lat']) && isset($_POST['long'])){
    $lat = floatval($_POST['lat']);
    $long = floatval($_POST['long']);
    $range = 0.005;
    $query = "SELECT `id`, `party_name`, `host` FROM parties WHERE (`lat` BETWEEN ".($lat-$range)." AND ".($lat+$range).") AND (`long` BETWEEN ".($long-$range)." AND ".($long+$range).");";

    $con = connect_to_mch();
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    print json_encode(array("result" => "success", "items" => $rows));
}
else{
    print json_encode(array("result" => "failure", "error" => "missing long or lat"));
}

mysqli_close($con);
?>