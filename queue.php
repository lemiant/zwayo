<html>
<head>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
        WebFontConfig = {
            google: { families: [ 'Exo+2::latin' ] }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
                    '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })(); </script>
</head>
<body>
<div id="queue_wrapper">
<style type="text/css">
    body{
        font-family: Arial, sans-serif;
    }
    p.song_title{
        color: #cccccc;
        font-size: 13px;
        margin: 3px;
    }
    p.song_guest{
        color: #cccccc;
        font-size: 10px;
        margin: 5px 0 2px 0;
    }
    html, body{
        padding: 0;
        margin: 0;
    }
    #queue li img{
        float: left;
        height: 60px;
        margin: 0 10px;
    }
    #queue li{
        border-top: solid black 1px;
        border-bottom: solid black 1px;
        width: 100%;
        background-color: #333333;
        list-style-type: none;
        height: 70px;
        padding: 0;
        margin: 0;
    }
    #queue div.item{
        height: 60px;
        margin: 5px;
    }
    ul#queue{
        padding: 0;
        margin: 0
    }

    #queue li.active{
        background-color: #8A360F;
    }
    #queue.host li.active{
        height: 170px;
    }
    #queue.host li.active iframe{
        margin:5px 15;
    }

    #add_music_button{
        border-top: solid black 1px;
        border-bottom: solid black 1px;
        width: 100%;
        display: block;
        text-align: center;
        background-color: #B86E00;
        padding: 5px;
        color: black;
        font-weight: bold;
        padding: 5px 0;
    }
</style>
<a href="#" id="add_music_button">+ Add Music</a>
<ul id="queue"></ul>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
    $('#add_music_button').bind('click', function(){
        console.log('click')
        $('#queue_wrapper').hide();
        $('#search_wrapper').show();
    })
</script>
<script type="text/javascript">
    function getCookie(name) {
        var dc = document.cookie;
        var prefix = name + "=";
        var begin = dc.indexOf("; " + prefix);
        if (begin == -1) {
            begin = dc.indexOf(prefix);
            if (begin != 0) return null;
        }
        else
        {
            begin += 2;
            var end = document.cookie.indexOf(";", begin);
            if (end == -1) {
                end = dc.length;
            }
        }
        return unescape(dc.substring(begin + prefix.length, end));
    }
</script>

<script type="text/javascript">
    if(getCookie("admin_key")){
        IS_HOST = true;
        $('#queue').addClass("host")
    }
    else IS_HOST = false
    last_update = 0
    function apply(rule, list){
        body = $.parseJSON(rule.body)
        if(rule.action == 'add'){
//            console.log(body)
//            console.log(list.get()[0])
            list.prepend('<li class="container">'+
                '<div class="item" id="'+body.videoId+'">'+
                    //'<img class="handle"'+
                    '<img src="'+body.thumb+'">'+
                    '<p class="song_title">'+truncate(body.title, 60)+'</p>'+
                    '<p class="song_guest">Added by '+truncate(body.guest, 30)+'</p>'+
                    '</div>'+
                '</li>')
        }
        else if(rule.action == 'set_active' && !IS_HOST){
            set_active(body.videoId)
        }
    }
    function set_active(videoId){
        $('li.active').removeClass('active')
        $('div#'+videoId).parent().addClass('active')
    }
    function truncate(str, size){
        if(str.length > size){
            return str.slice(0,size-4)+"..."
        }
        return str
    }

    var player
    function init_play(selector, autoplay){
        if(autoplay == undefined) autoplay = true;
        console.log(selector.get(0));
        window.select = selector.get(0);
        div = selector.children('div.item')
        console.log(div)
        if(typeof div != 'undefined'){
            div.hide()
            videoId = div.get(0).id
            send_set_active(videoId)
            selector.prepend('<div id="player"></div>')
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
    }
    function onPlayerReady(e){
        e.target.playVideo();
    }
    function onStateChange(e){
//        console.log(e)
        if(e.data == 0){
            container = $(e.target.a).parent()
            container.children("iframe").remove()
            container.children("div.item").show()
            if(typeof container.next() != "undefined"){
                init_play(container.prev());
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
        if(first && IS_HOST){
            //init_play($('#queue').children().last(), false);
            first = false;
        }
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

    //Load youtube iframe API
    if(IS_HOST){
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    function onYouTubeIframeAPIReady() {
        $("#queue_wrapper").append('<input type="submit" value="play" id="play" />')
        $('#play').bind('click', function(){ init_play($('#queue').children().last()); })
    }
</script>
</div>
<?php require('search.php') ?>
</body>
</html>