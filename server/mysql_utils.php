<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/22/14
 * Time: 12:01 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('active_settings.php');

function check_party_id($con, $party_id){
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

    if($row = mysqli_fetch_assoc($result)){
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
function fetch_all($result){
    $rows = array();
    while($row = mysqli_fetch_assoc($result)){
    $rows[] = $row;
    }
    return $rows;
}


function verify_login($con){
    global $USER_ID, $PARTY_ID, $USER_NAME;
    $USER_NAME = "";
    $PARTY_ID = 0;
    $USER_ID = 0;
    if(!empty($_COOKIE['USER_ID']) and !empty($_COOKIE['secret'])){
        $test_uid = mysqli_real_escape_string($con, $_COOKIE['USER_ID']);
        $query = "SELECT * FROM users WHERE id=$test_uid";
        $result = mysqli_query($con, $query);
        if($row = mysqli_fetch_assoc($result)){
            if($row['secret'] == $_COOKIE['secret']){
                $USER_ID = $row['id'];
                $PARTY_ID = $row['current_party'];
                $USER_NAME = $row['name'];
            }
        }
    }
    return $USER_ID;
}

function randomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$FAILURE = json_encode(array("result" => "failure"));

