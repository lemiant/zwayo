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
function check_party($con, $party_id){
    $query = "SELECT id FROM parties WHERE id='$party_id'";
    $result = mysqli_query($con, $query);
    if(mysqli_fetch_row($result)){
        return true;
    }
    else return false;
}
function check_admin_key($con, $party_id, $admin_key){
    $query = "SELECT admin_key FROM parties WHERE id=$party_id" . ";";
    $result= mysqli_query($con, $query);

    if($row= mysqli_fetch_row($result,MYSQLI_ASSOC)){
        if ($row["admin_key"]== $admin_key){
            return true;
        }
    }
    return false;
}
function rand_letter($num=1){
    $key = "";
    for ($i=0; $i<$num; $i++)
	{
        $key .= chr(97+ mt_rand(0,25));
    }
	return $key;
}

$FAILLURE = "dd";
?>
