<html>
<head>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
<link type='text/css' href='css/index.css' rel='stylesheet' media='screen' />
    <style>
        body{
            font-family: Arial, sans-serif;
            background-color: #333333;
            color: white;
        }
    </style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="js/jquery.modal.js"></script>
<link type='text/css' href='css/modal.css' rel='stylesheet' media='screen' />
<script type="text/javascript">
//Facebook
    // Load the SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));
    
    window.fbAsyncInit = function() {
  FB.init({
    appId      : '1401303503466142',
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : false  // parse XFBML
  });
  $('#fb-login').on('click', function(){ FB.login(undefined, {scope: 'user_friends'}); })
  FB.Event.subscribe('auth.authResponseChange', function(response) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (response.status === 'connected') {
          $.modal.close()
          window.friends = []
          FB.api('/me', function(resp){
                window.me = resp
                friends.push(me.id)
                fb_finish('me')
            })
          FB.api('/me/friends?fields=id', function(resp){
                console.log('friends down')
                data = resp['data']
                for(var i=0; i<data.length; i++){
                    friends.push(data[i].id)
                }
                fb_finish('friends')
          })
    } else {
      $('#fb-modal').modal({close: false,
                            containerCss: {height: 'auto', width:'70%'}, 
                            dataCss: {padding: '0'}})
    }
  });
  };
    
    fb_done = []
    function fb_finish(query){
        fb_done.push(query)
        if(fb_done.length >= 2) {
            $.ajax({
                url: 'server/get_party_list.php',
                type: 'post',
                dataType: 'json',
                data: {friends: friends},
                success: populate_parties
            })
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function(){
        //request_parties()
    })
    function populate_parties(result, status){
        console.log('pop')
        for(i=0; i<result.items.length; i++){
            row = result.items[i];
            $("#party_list").append($('<div class="item" />').append(
                                    '<h2>'+row.party_name+'</h2>'+
                                    '<div class="extra"><input type="submit" value="Join the party!" onclick="join_party('+row.id+')"></div>'))
        }
    }
    
    function join_party(party_id){
        $('<form action="server/join_party.php" method="POST"><input type="hidden" name="party_id" value="'+party_id+'" /></form>').submit()
    }
    function make_party(){
        div = $('div#make_party')
        $('<form action="server/join_party.php" method="POST">'+
          '<input type="hidden" name="action" value="make_party"/>'+
          '<input type="hidden" name="party_name" value="'+div.find('#party_name_input').get(0).value+'" />'+
          '<input type="hidden" name="host_fb" value="'+me.id+'" />'+
          '<input type="hidden" name="host_name" value="'+me.name+'" />'+
          '</form>').submit()
    }
</script>
</head>
<body>
<div id="fb-root"></div>
<div id="header">Zwayo</div>
    <div class="item">
        <h2>Make Party</h2>
            <div id="make_party" class="extra">
                <input type="text" id="party_name_input" name="party_name" placeholder="Party Name" /><br />
                <input type="submit" value="Make party" onclick="make_party()" />
            </div>
        </form></div>
    </div>
<div id="party_list"></div>
<div id="fb-modal" style="display: none">
    <p>Hard to have a party without friends ;)</p>
    <a href="#"><img id="fb-login" src="imgs/facebook.png" /></a>
</div>

</body>
<!--
function getLocation()
{
    console.log('gl')
    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(request_parties);
    }
    else{x.innerHTML = "Geolocation is not supported by this browser.";}
}
-->
</html>

