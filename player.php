<html>
    <head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
     var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    //Load youtube iframe API
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '320',
            width: '400',
            //playlist: ,//,,
            events: {
                onReady: onPlayerReady,
                onStateChange: onStateChange
            }
        });
    }
    playlist = ['wXw6znXPfy4','FfBKqaVk2Co']
    function onPlayerReady(e){
        console.log('onPlayerReady')
        player.loadPlaylist([playlist.shift()])
        //e.target.playVideo();
    }
    function onStateChange(e){
        console.log(e.data)
        if(e.data == 0){
            player.loadPlaylist([playlist.shift()])
            /*div = $(e.target.a).parent('div.item')
            div.removeClass('playing')
            div.children("#player").remove()
            div.children().show()
            if(typeof div.prev() != "undefined"){
                init_play(div.prev());
            }*/
        }
    }
    $(document).ready(function(){
        h = $('div#holder')
        t = $('div#c')
    })
    </script>
    </head>
    <body>
        <div id="holder">
    <div id="player">p</div>
        </div>
    <div id="c" style="min-height: 100px; background-color: red;"></div>
    </body>
</html>
