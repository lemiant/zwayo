<html>
<head>
    <meta name="viewport" content="width=device-width; initial-scale=1;">
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
        $('#party_name_input').on('click', function(e){e.stopPropagation(); e.preventDefault()})
        $(document).on('click', 'div.item', function(){
            //alert('hi')
            div = $(this)
            if(!div.hasClass('open')){
                $('div.item.open').removeClass('open').find('div.extra').slideUp()
                div.addClass('open')
                div.find('div.extra').slideDown()
            }
            else{
                div.removeClass('open')
                div.find('div.extra').slideUp()   
            }
        })
        $('#fb-modal').modal({close: false,
                            containerCss: {height: '130px', width:'70%'}
        })
    })
    function populate_parties(result, status){
        console.log('pop')
        for(i=0; i<result.items.length; i++){
            row = result.items[i];
            $("#party_list").append($('<div class="item" onclick="" />').append(
                                    '<h2>'+row.party_name+'</h2>'+
                                    '<div class="extra"><a class="button" onclick="join_party(event,'+row.id+')">Join the party!</a></div>'))
        }
    }
    
    function join_party(e, party_id){
        e.preventDefault()
        e.stopPropagation()
        $('<form action="server/join_party.php" method="POST"><input type="hidden" name="party_id" value="'+party_id+'" /></form>').submit()
    }
    function make_party(e){
        alert('hiya')
        e.preventDefault()
        e.stopPropagation()
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
    <div id="make" class="item">
        <h2 onclick="">Make Party</h2>
            <div id="make_party" class="extra">
                <input type="text" style="margin-bottom: 10px; font-size: 15px;" id="party_name_input" name="party_name" placeholder="Party Name" /><br />
                <a class="button" onclick="make_party(event)">Make Party</a>
            </div>
        </form></div>
    </div>
<div id="party_list"></div>
<div id="fb-modal" style="display: none">
    <p>Hard to have a party without friends ;)</p>
    <a href="#"><img id="fb-login" src="imgs/facebook.png" /></a>
    <div></div>
</div>

</body>
</html>

