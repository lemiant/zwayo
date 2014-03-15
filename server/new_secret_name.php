<?php
    require_once('mysql_utils.php');
    $con = connect_to_mch();
    require_once('animals.php');
    require_once('adjectives.php');

    function new_secret_name(){
        global $adjectives, $animals;
        $secret_name = $adjectives[array_rand($adjectives)].' '.$animals[array_rand($animals)];
        $query = "SELECT * FROM parties WHERE secret_name='$secret_name'";
        $result = mysqli_query($con, $query);
        if($row = mysqli_fetch_assoc($result)) return new_secret_name(); //Collission :(
        else return $secret_name; //No Collision :)
    }

    echo json_encode(array('result' => 'success', 'secret_name' => new_secret_name()))
?>
