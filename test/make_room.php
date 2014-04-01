<?php
define("GI_APP_ID", 3407);

function GI_token(){
    $headers = array(
    'Content-Type: application/json');
    $data = json_encode(array(
        'client_id' => 'mJh-yBf1QKc6qcmDk-vpFqRa35bHLSx5hwg3vtmKyT0',
        'client_secret' => 'DVPu73cHM9Rp3M7Ekkf7zyvJ3D48O3OtiQSI0YpbIbI'));

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://api.goinstant.net/v1/oauth/access_token",
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS  => $data,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true));
    $server_output = curl_exec ($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close ($ch);

    if($http_status != '201'){
        die("Error retrieving  a goinstant token ".$err."(".$http_status.")");
    }

    $result = json_decode($server_output);
    return $result->token;
}

function GI_create_room($room){
    $url = "https://api.goinstant.net/v1/apps/".GI_APP_ID."/rooms";
    $headers = array(
    'Content-Type: application/json',
    'Authorization: Bearer '.GI_token());
    $data = json_encode(array(
        'name' => $_GET['room']));

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS  => $data,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true));
    $server_output = curl_exec ($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close ($ch);
    return array($http_status, $server_output);
}


/*
if(!empty($_GET['room'])){
    
}
*/