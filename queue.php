<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="css/queue.css" rel="stylesheet" /> 
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
    <a href="#" id="add_music_button">+ Add Music</a>
    <div id="queue" class="queue">
        <script id="__queue_item_tmpl" type="text/html">
            <div class="item" id="{{id}}">
                <img src="{{body.thumb}}">
                <p class="song_title">{{body.title.ellipsis_truncate(60)}}</p>
                <p class="song_guest">Added by {{body.guest.ellipsis_truncate(30)}}</p>
            </div>
        </script>
        <div id="player_div" class="item" style="display:none"> <div id="player"></div> </div>
    </div>
    <div id="play_button_div" style="padding: 8px; text-align:center; display: none;"><a class="button" id="play">&nbsp;Play All&nbsp;</a></div>
    </div>
    <!-- Script loading functions -->
<script type="text/javascript">
    function exists(identifiers){
        if(typeof identifiers == 'string') identifiers = [identifiers]
        for(var n=0; n<identifiers.length; n++){
            //console.log(identifiers)
            var parts = identifiers[n].split('.')
            node = window
            for(var i=0; i<parts.length; i++){
                node = node[parts[i]]
                if(typeof node == 'undefined') return false
            }
        }
        return true
    }
    function fallback(identifiers, backup){
        if(!exists(identifiers)) document.write('<script type="text/javascript" src="'+backup+'"></'+'script>');
    }
    function blocked_callback(identifiers, callback, refresh_rate){
        if(!refresh_rate) var refresh_rate = 50;
        return function(){
            if(exists(identifiers)) callback.apply(this, arguments)
            else{
                var t = this
                var args = arguments
                setTimeout(function(){ blocked_callback(identifiers, callback).apply(t, args) }, refresh_rate)
            }
        }
    }
</script>
    
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- Rendering Functions-->
<script type="text/javascript">
    last_update = 0
    function get_queue_actions(){
        $.ajax({
            url: "server/get_queue_actions.php",
            dataType: 'json',
            type: 'post',
            data: {last: last_update},
            success: blocked_callback(['templating', 'jQuery.fn.doubletap'], make_queue) //Default 50ms refresh
        })
    }
    function make_queue(){
        window.queue_item_tmpl = _.template($('#__queue_item_tmpl').html());
        update_queue.apply(this, arguments)
    }
    function update_queue(js, status){
        if(status == "success" && js.result == "success"){
            queue = $('#queue')
            for(i=0; i<js.items.length; i++){
                apply(js.items[i], queue)
            }
            if(js.items.length > 0) last_update = js.items[js.items.length-1].id
        }
        setTimeout(get_queue_actions, 2500)
    }
    function apply(rule, list){
        rule.body = $.parseJSON(rule.body)
        if(rule.action == 'add'){
            tr = $(queue_item_tmpl(rule))
            tr.data("videoId", rule.body.videoId)
            tr.doubletap(function(e){ play($(e.currentTarget)) }, function(){}, 400)
			tr.on("contextmenu", function(e){
				send_remove(rule.id);
                                $('#'+rule.id).remove();
				return false;
			})
            list.prepend(tr)
        }
        else if(rule.action == 'set_active' && player_div.css('display') == 'none'){
            set_active(rule.body.active_id)
        }
	else if(rule.action == 'remove'){
	    $('#'+rule.body.id).remove();
	}
    }

    function set_active(id){
        $('div.active').removeClass('active')
        $('div#'+id).addClass('active')
    }
    function send_set_active(id){
        $.ajax({
            url: 'server/set_queue_action.php',
            type: 'post',
            data: {action: 'set_active',
                    body: {active_id: id}}
        })
        set_active(id)
    }
	function send_remove(id){
        $.ajax({
            url: 'server/set_queue_action.php',
            type: 'post',
            data: {action: 'remove',
                    body: {id: id}}
        })
	}

    String.prototype.ellipsis_truncate = function(size){
        if(this.length > size){
            return this.slice(0,size-4)+"..."
        }
        return this
    }
    
    get_queue_actions()
</script>
    
<script type="text/javascript" src="js/jquery.doubletap.js"></script>
    
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
<script type="text/javascript">
    _.templateSettings = {
      interpolate : /\{\{(.+?)\}\}/g,
      execute : /\{\%(.+?)\%\}/g
    };
    window.templating = true //Flag for queue rendering
</script>
    
<!-- Youtube Functions -->
<script type="text/javascript">
    window.player_div = $('#player_div')
    
    //Load youtube API async
    var tag = document.createElement('script');
  tag.src = "https://www.youtube.com/iframe_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
  
  $(document).keydown(function(e) {
    switch(e.which) {
        case 32: //space
        togglePlay();
        break;

        case 37: // left
        playPrev();
        break;

        case 39: // right
        playNext();
        break;

        default: return;
    }
    e.preventDefault();
  })

    function playNext() {
        if(player_div.prevAll('.item').length >= 2){
            play(player_div.prevAll('.item')[1])
        } else {
            play($('#queue .item').not('#player_div').last())
        }
    }
    
    function playPrev() {
        if(player_div.nextAll('.item').length >= 1){
            play(player_div.nextAll('.item')[0])
        } else {
            play($('#queue .item').not('#player_div').first())
        }
    }

    function togglePlay() {
        if (!player_div.is(':visible')) {
            play($('#queue .item').not(player_div).last());
        } else if (player.getPlayerState() === 1) {
            player.pauseVideo();
        } else {
            player.playVideo();
        }
    }
 
    function play(t){
        place_after(t)
        play_above()
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
    function play_above(){
        var above = player_div.prev()
        player_div.show()
        videoId = above.data('videoId')
        player.loadVideoById({videoId: videoId, suggestedQuality: 'small'})
        send_set_active(above.attr('id'))
    }

    function onYouTubeIframeAPIReady(){
        window.player = new YT.Player('player', {
            height: '200',
            width: '300',
            playerVars: {
                playsinline: 1
            },
            events: {
                onReady: onPlayerReady,
                onStateChange: onPlayerStateChange
            }
        });
    } 
    function onPlayerReady(e){
        $('#play_button_div').show()
        $('#play').on('click', function(){ 
            play($('#queue .item').not(player_div).last())
        })
        if(player_div.css('display') != 'none') play_above(); //We tried to play a song without the YoutubeAPI
    }
    function onPlayerStateChange(e){
        if(e.data == 0){
           playNext();
        }
    } 
</script>
    
<!-- Load search -->    
<script type="text/javascript">
    $.ajax('search.php').success(function(result){ 
        $('body').append(result)
        $("#add_music_button").on('click', function(){
            $('#queue_wrapper').hide()
            $('#search_wrapper').show()
        })
    })
</script>
</body>
</html>
