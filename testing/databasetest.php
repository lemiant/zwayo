<?php
	$con = mysqli_connect("localhost","root","bronte","mch");

	if (mysqli_connect_errno($con)) {
   		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$lat = 20.0;
    $long = 2.00;
    $range = 0.005;
    $query = "SELECT 'id', 'party_name', 'host' FROM parties WHERE ('lat' BETWEEN ".($lat-$range)." AND ".($lat+$range).") AND ('longitude' BETWEEN ".($long-$range)." AND ".($long+$range).");";
    $query = "SELECT * from parties;";
    echo $query;
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // echo $rows[1];
    // $rows = mysqli_fetch_assoc($result);
    // echo 'rows ';
    // print_r($rows);
    // echo ' rows';
    print json_encode(array("result" => "success", "items" => $rows));

    mysqli_close($con);
?>