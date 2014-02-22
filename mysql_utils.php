<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/22/14
 * Time: 12:01 PM
 */

function connect_to_mch(){
    $con = mysqli_connect("localhost","root","","mch");
}
function rand_letter($num){
    $key = "";
    for (int $i=0; $i<$num; $i++)
	{
        $key .=chr(97+ mt_rand(0,25));
    }
	return $key;
}

?>
