<?php
require_once('server/mysql_utils.php');
$con = connect_to_mch();
$USER_ID = verify_login($con);

/*
* TODO
* Update secret key more often
*/

function login($fb_id, $name){
    //Accepts facebook id
    //Looks up user
    //Create if necessary
    //Else sets appropriate cookies
    global $con, $USER_ID;
    $query = "SELECT id, secret FROM users WHERE `fb_id`=$fb_id";
    $result = mysqli_query($con, $query);
    if($row = mysqli_fetch_assoc($result)){
        setcookie("USER_ID", $row['id'], time()+60*60*24*3000, '/');
        setcookie("secret", $row['secret'], time()+60*60*24*3000, '/', '', '', TRUE);
        $USER_ID = $row['id'];
    } else{
        create($fb_id, $name);
    }
}
function create($fb_id, $name){
    //Accept fb_id
    //Create User
    global $con, $USER_ID;
    $secret = randomString(50);
    $query = "INSERT INTO users (fb_id, name, secret) VALUES ('$fb_id', '$name', '$secret')";
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

  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/

if(!empty($_POST['action'])){
    session_start();
    $_SESSION['action_data'] = array("action"=>$_POST['action'], "party_name"=>$_POST['party_name']);
}
if($USER_ID){
    onward();
    exit;
}
else {
    require_once('server/facebook/facebook.php');

    $config = array(
    'appId' => '1401303503466142',
    'secret' => '7a8f746725b473fedaa909e21dbcb5a1',
    'allowSignedRequest' => false // optional but should be set to false for non-canvas apps
    );

    $facebook = new Facebook($config);
    $fb_id = $facebook->getUser();

    if($fb_id){
        try{
            $user_profile = $facebook->api('/me','GET');
            $name = $user_profile['name'];
            login($fb_id, $name);
            onward();
            exit;
        }
        catch(FacebookApiException $e){
            $FB_error = $e->getType().': '.$e->getMessage();
        }
    }
}
?>
<html>
    <meta name="viewport" content="width=device-width; initial-scale=1;">
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="css/login.css" rel="stylesheet" />
<body>
<?php if(!empty($FB_error))echo $FB_error ?>
<div id="wrapper">
    <div id="cell">
        <div id="inner">
            <p>Let your friends know who you are :)</p>
            <a href="<?php echo $facebook->getLoginUrl() ?>"><img id="fb-login" src="imgs/facebook.png"  /></a>
        </div>
    </div>
</div>
</body>
</html>
