<html>
<head>
    <meta name="viewport" content="width=device-width, user-scalable=no">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- FACEBOOK -->
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
      window.response = response
      FB.api(
            "/me",
            function (response) {
                window.me = response;
                $.ajax({
                    url: 'server/am_i_host.php',
                    type: 'post',
                    data: {fb: me.id},
                    success: check_host
                })
            }
        );
    } else {
        // TODO: Redirect home (They aren't logged in)
        // Actually you'll need to do the checks somewhere else
        // THis only fires on change
    }
  });
  };
</script>
<!-- IS_HOST -->
<script type="text/javascript">
    //IS_HOST
    function check_host(resp){
        if(resp == 'yes'){
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        }
    }
    //Load youtube iframe API
    function onYouTubeIframeAPIReady() {
        $("#queue_wrapper").append('<input type="submit" value="play" id="play" />')
        $('#play').bind('click', function(){ init_play($('#queue').children().last()); })
    }
</script>
    
</head>
<body>
<div id="fb-root"></div>
<div id="queue_wrapper">
<link type="text/css" href="css/style.css" rel="stylesheet" />
<link type="text/css" href="css/queue.css" rel="stylesheet" />
<a href="#" id="add_music_button">+ Add Music</a>
<div id="queue"></div>
<script type="text/javascript">
    $('#add_music_button').bind('click', function(){
        console.log('click')
        $('#queue_wrapper').hide();
        $('#search_wrapper').show();
    })
</script>
    
<script type="text/javascript">
    last_update = 0
    function apply(rule, list){
        body = $.parseJSON(rule.body)
        if(rule.action == 'add'){
//            console.log(body)
//            console.log(list.get()[0])
            list.prepend('<div class="item" id="'+body.videoId+'">'+
                    //'<img class="handle"'+
                    '<img src="'+body.thumb+'">'+
                    '<p class="song_title">'+truncate(body.title, 60)+'</p>'+
                    '<p class="song_guest">Added by '+truncate(body.guest, 30)+'</p>'+
                '</div>')
        }
        else if(rule.action == 'set_active' && !IS_HOST){
            set_active(body.videoId)
        }
    }
    function set_active(videoId){
        $('div.active').removeClass('active')
        $('div#'+videoId).addClass('active')
    }
    function truncate(str, size){
        if(str.length > size){
            return str.slice(0,size-4)+"..."
        }
        return str
    }

    var player
    function init_play(div, autoplay){
        if(autoplay == undefined) autoplay = true;
    //    console.log(selector.get(0));
    //    window.select = selector.get(0);
        div.children().hide()
        videoId = div.attr('id')
        send_set_active(videoId)
        div.append('<div id="player" />')
        player = new YT.Player('player', {
            height: '160',
            width: '200',
            videoId: videoId,
            events: {
                onReady: onPlayerReady,
                onStateChange: (autoplay ? onStateChange : function(){})
            }
        });
    }
    function onPlayerReady(e){
        e.target.playVideo();
    }
    function onStateChange(e){
        if(e.data == 0){
            div = $(e.target.a).parent('div.item')
            div.children("#player").remove()
            div.children().show()
            if(typeof div.prev() != "undefined"){
                init_play(div.prev());
            }
        }
    }

    function send_set_active(videoId){
        $.ajax({
            url: 'server/set_queue_action.php',
            type: 'post',
            data: {action: 'set_active',
                body: {videoId: videoId}}
        })
        set_active(videoId)
    }

var first = true
    function update_queue(js, status){
//        console.log(js)
        if(status == "success" && js.result == "success"){
            queue = $('#queue')
            for(i=0; i<js.items.length; i++){
                apply(js.items[i], queue)
            }
            if(js.items.length > 0) last_update = js.items[js.items.length-1].id
        }
//        console.log("set_timeout")
        setTimeout("get_queue_actions()", 2000)
    }

    function get_queue_actions(){
        $.ajax({
            url: "server/get_queue_actions.php",
            dataType: 'json',
            type: 'post',
            data: {last: last_update},
            success: update_queue
        })
    }
    console.log('load')

    get_queue_actions()
</script>
</div>
<?php require('search.php') ?>
</body>
</html>