<?php
$con = mysqli_connect("localhost","root","","mch");
// Check connection
if (mysqli_connect_errno($con))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = "SELECT * FROM parties";

$result = mysqli_query($con, $query);

while($row = mysqli_fetch_row($result)){
    print_r($row);
}
?>