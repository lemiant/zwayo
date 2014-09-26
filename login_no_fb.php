<?php

require_once('server/mysql_utils.php');
$con = connect_to_mch();
$USER_ID = verify_login($con);
session_start();

/*
* TODO
* Update secret key more often
*/

function create($name){
    //Accept fb_id
    //Create User
    global $con, $USER_ID;
    $secret = randomString(50);
    $query = "INSERT INTO users (fb_id, name, secret) VALUES ('12345', '$name', '$secret')";
    mysqli_query($con, $query);

    $USER_ID = mysqli_insert_id($con);
    setcookie("USER_ID", $USER_ID, time()+60*60*24*3000, '/');
    setcookie("secret", $secret, time()+60*60*24*3000, '/', '', '', TRUE);
}
function onward(){
    global $con, $USER_ID;
    if(!empty($_SESSION['action_data']) && $_SESSION['action_data']['action'] == 'make_party'){
        header("Location: make_party.php");
        die();
    } else if(!empty($_SESSION['action_data']) && $_SESSION['action_data']['action'] == 'join_party'){
        header("Location: server/join_party.php?secret_name=".strtolower($_SESSION['action_data']['party_name']));
        die();
    }
    echo $USER_ID;
    if(!empty($_SESSION))print_r($_SESSION);
}

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

if(!empty($_POST['action'])){
    $_SESSION['action_data'] = array("action"=>$_POST['action'], "party_name"=>$_POST['party_name']);
}
if(!empty($_POST['name'])){
    create($_POST['name']);
}
if($USER_ID){
    onward();
    exit;
}
?>
<html>
    <meta name="viewport" content="width=device-width; initial-scale=1;">
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="css/login.css" rel="stylesheet" />
<body>
<div id="wrapper">
    <div id="cell">
        <div id="inner">
            <style>
               #name {
margin: -8 0 10;
height: 40px;
font-size: 18px;
padding: 10px;
width: 200px;
border-radius: 5px;
border: lightgrey 4px solid;
	       }
            </style>
<script>
function submit(){ document.getElementById('myForm').submit() }
</script>
            <p>Let your friends know who you are :)</p>
            <form id="myForm" action="" method="POST" ><input id="name" name="name" type="text" placeholder="Your Name" /><br />
            <a class="button submit" onclick="submit()">Get it on</a></form>
        </div>
    </div>
</div>
</body>
</html>
