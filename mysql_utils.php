<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/22/14
 * Time: 12:01 PM
 */

function connect_to_mch(){
    return mysqli_connect("localhost","root","","mch");
}
function rand_letter($num=1){
    $key = "";
    for ($i=0; $i<$num; $i++)
	{
        $key .= chr(97+ mt_rand(0,25));
    }
	return $key;
}

?>
