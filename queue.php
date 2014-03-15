<html>
<head>
    <meta name="viewport" content="width=device-width; initial-scale=1;">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- IS_HOST -->
<script type="text/javascript">
    $(document).ready(function(){

                var isiOS = false;
                var agent = navigator.userAgent.toLowerCase();
                if(agent.indexOf('iphone') >= 0 || agent.indexOf('ipad') >= 0){
                       isiOS = true;
                }

                $.fn.doubletap = function(onDoubleTapCallback, onTapCallback, delay){
                    var eventName, action;
                    delay = delay == null? 500 : delay;
                    eventName = isiOS == true? 'touchend' : 'click';

                    $(this).bind(eventName, function(event){
                        var now = new Date().getTime();
                        var lastTouch = $(this).data('lastTouch') || now + 1 /** the first time this will make delta a negative number */;
                        var delta = now - lastTouch;
                        clearTimeout(action);
                        if(delta < 500 && delta > 0){
                            if(onDoubleTapCallback != null && typeof onDoubleTapCallback == 'function'){
                                onDoubleTapCallback(event);
                            }
                        }else{
                            $(this).data('lastTouch', now);
                            action = setTimeout(function(evt){
                                if(onTapCallback != null && typeof onTapCallback == 'function'){
                                    onTapCallback(evt);
                                }
                                clearTimeout(action);   // clear the timeout
                            }, delay, [event]);
                        }
                        $(this).data('lastTouch', now);
                    });
                };
        
        player_div = $('#player_div')

        get_queue_actions()

        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    })
    //Load youtube iframe API
    function onYouTubeIframeAPIReady() {
        $("#queue_wrapper").append('<div style="padding: 8px; text-align:center;"><a class="button" id="play">&nbsp;Play All&nbsp;</a></div>')
        player = new YT.Player('player', {
            height: '200',
            width: '300',
            playerVars: {
                playsinline: 1
            },
            events: {
                onReady: function(){ $('#play').bind('click', function(){ place_after($('#queue .item').not(player_div).last()); play_above() }) },
                onStateChange: onStateChange
            }
        });
    }
    function play_above(){
        var above = player_div.prev()
        if(above){
            player_div.show()
            videoId = above.attr('id')
            player.loadVideoById({videoId: videoId, suggestedQuality: 'small'})
            send_set_active(videoId)
        }
        else{
            player_div.hide()
        }
    }
    function onStateChange(e){
        console.log(e)
        if(e.data == 0){
            if(player_div.prev().prev().length){
                place_after(player_div.prev().prev())
                play_above()
            }
            else{
                place_after($('.item').not('#player_div').last())
                play_above()
            }
        }
    }
    function place_after(t){
        t = $(t)
        while(t.next('#player_div').length == 0){
            if(t.prevAll('#player_div').length){ //is above
                player_div.before(player_div.next())
            } else if(t.nextAll('#player_div').length){
                player_div.after(player_div.prev())
            }
        }

    }
</script>
    <style type="text/css">
    body{
        user-select: none;
        -moz-user-select: none;
        -webkit-user-select: none;
    }
    </style>

</head>
<body>
<div id="fb-root"></div>
<div id="queue_wrapper">
<link type="text/css" href="css/style.css" rel="stylesheet" />
<link type="text/css" href="css/queue.css" rel="stylesheet" />
<a href="#" id="add_music_button">+ Add Music</a>
<div id="queue" class="queue">

    <div id="player_div" class="item" style="display:none"> <div id="player"></div> </div>
</div>
<script type="text/javascript">
    $('#add_music_button').on('click', function(){
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
            tr = $('<div class="item" id="'+body.videoId+'">'+
                    '<img src="'+body.thumb+'">'+
                    '<p class="song_title">'+truncate(body.title, 60)+'</p>'+
                    '<p class="song_guest">Added by '+truncate(body.guest, 30)+'</p>'+
                '</div>')
            tr.doubletap(function(e){ place_after($(e.target).closest('.item')); play_above(); }, function(){}, 400)
            list.prepend(tr)
        }
        else if(rule.action == 'set_active' && player_div.css('display') == 'none'){
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
        setTimeout("get_queue_actions()", 2500)
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
</script>
</div>
<?php require('search.php') ?>
</body>
</html>
