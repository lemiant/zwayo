<div id="search_wrapper" style="display:none">
<style>
    #search_bar{
        width: 93%;
        background-color: #f2f2f2;
        color: black;
        font-family: veranda, sans-serif;
        font-size:15px;
        border: 3px solid #111111;
        border-radius: 20px;
        margin: auto;
        padding: 0 7px;
        position: relative;
        display: block;
        z-index: 1;
    }

    body
    {
        background-color: #333333;
    }
    html, body{
        width: 100%;
        margin: 0;
        padding: 0;
    }
    ul#autocomplete_list{
        margin: -13px auto 0;
        padding: 12px 0 0 0;
        width: 90%;
        background-color: #f2f2f2;
        border-bottom: 1px solid #111111;
        border-left: 1px solid #111111;
        border-right: 1px solid #111111;
        position: relative;
        z-index: 0;
    }
    ul#autocomplete_list li{
        width: 100%;
        border-bottom: 1px solid #111111;
        border-left: 1px solid #111111;
        border-right: 1px solid #111111;
        list-style-type: none;
    }
    ul#autocomplete_list p{
        padding: 4px 4px;
        margin: 0;
    }
    a:link, ul a:hover, ul a:visited, ul a:active{
        text-decoration: none;
        color: black;
    }
    div#search_container{
        height: 25px;
        position: relative;
        width: 90%;
        margin: 10px auto;
        display: block;
    }
    #search_wrapper .queue .item p.song_title{
        color: #cccccc;
        font-size: 13px;
        margin: 3px;
    }
</style>

<div id="search_container">
    <input id="search_bar" type="text">
    <ul id="autocomplete_list"></ul>
</div>
<div style="display: inline-block; position: absolute; top: 10px; right: 3%; background-color: grey; padding: 2px 4px; border: #444 solid 2px; cursor: pointer; font-family: Arial; font-size: 14px;" onclick="leave_search()">X</div>
<div id="search_queue" class="queue"></div>
<!--<div id="guess_wrapper" style="display: none">-->
    <!--<div style="float: left; height: 100%"><a href="#" id="left_arrow"><img src="imgs/left-arrow.png" height="40" /></a></div>-->
    <!--<div style="float: right; height: 100%"><a href="#" id="right_arrow"><img src="imgs/right-arrow.png" height="40" /></a></div>-->
    <!--<a href="#" id="guess_link"><div id="guess"></div></a>-->
<!--</div>-->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
var apiKey = 'AIzaSyChJLHkrcdUPknA85oQwUHdVpssEmDzMfI';
var list = $('#autocomplete_list')
var active_guess = ''
var guesses = []

function update_autocomplete(result, status){
    if(status == 'success'){
        list.empty()
        suggestions = result[1]
        window.s = suggestions
        for(i=0; i<Math.min(3, suggestions.length); i++){
            list.append('<a href="#"><li><p>'+suggestions[i][0]+"</p></li></a>")
        }
        list.children("a").bind("click", set_search)
    }
}

function set_search(){
    console.log($(this).find('p'))
    $('#search_bar').val( $(this).find('p').get(0).innerHTML )
    autocomplete.call($('#search_bar').get(0), false)
}

function update_guess(js){
    console.log('update_guess')
    guesses = js.items
    render_guess(guesses[0])
}
function render_page(result){ //This could be much more efficient (oh well :-)
    window.r = result
    console.log(result)
    console.log('render_result')

    $('#search_queue').empty()
    if(typeof result.items != "undefined"){
        for(i=0; i<result.items.length; i++){
            row = result.items[i]
            if(typeof row.snippet.thumbnails.small != 'undefined')thumb = row.snippet.thumbnails.small.url
            else if(typeof row.snippet.thumbnails.default != 'undefined')thumb = row.snippet.thumbnails.default.url
            else thumb = ""
            $('#search_queue').append('<a href="#">'+
                    '<div class="item" id="'+row.id.videoId+'">'+
                //'<img class="handle"'+
                    '<img src="'+thumb+'">'+
                    '<p class="song_title">'+truncate(row.snippet.title, 90)+'</p>'+
                    '</div>'+
                    '</a>')
        }
        $('#search_queue').children('a').bind('click', add_video_to_queue)
    }
//        active_guess = entry.id.videoId
//        if(thumb == undefined) thumb = entry.snippet.thumbnails.default.url
//        $('#guess').append('<input type="hidden" value="'+entry.id.videoId+'"><img width="250" src="'+thumb+'"><p>'+entry.snippet.title+'</p>')
}
function truncate(str, size){
    if(str.length > size){
        return str.slice(0,size-4)+"..."
    }
    return str
}

function autocomplete(suggest){
    if(suggest == undefined) suggest = true;
    gapi.client.setApiKey(apiKey)
    query = $(this).val();
    console.log(this)
/*     if(query.length >= 4 && suggest){
       $.ajax({
            url: "http://suggestqueries.google.com/complete/search?hl=en&ds=yt&client=youtube&hjson=t&cp=1&q="+encodeURIComponent(query)+"&key="+apiKey+"&format=5&alt=json&callback=?",
            dataType: 'jsonp',
            success: update_autocomplete
        })
    }
    else list.empty()*/
    if(query.length >= 4){
        $('#search_queue').show()
        gapi.client.request({
            path: 'youtube/v3/search',
            params: {
                q: query,
                type: 'video',
                part: 'id,snippet',
                maxResults: 10,
                fields: 'items(id(videoId),snippet(thumbnails,title))'
            },
            callback: render_page
        })
    }
    else{
        console.log('hiding')
        $('#search_queue').hide()
    }
}

function add_video_to_queue(){
    div = $(this).find('div.item')
    body = {}
    body.videoId = div.get(0).id
    console.log($('#queue .item#'+body.videoId))
    if(!$('#queue .item#'+body.videoId).length){
        body.title = div.children('p.song_title').get(0).innerHTML
        body.thumb = div.children('img').get(0).src
        console.log(body)
        $.ajax({
            url: 'server/set_queue_action.php',
            dataType: 'json',
            type: 'post',
            data: {action: 'add',
                body: body}
        })
    }
    else alert('You cannot add the same song twice. Sorry :(')
    leave_search()
}
function leave_search(){
    $('#search_wrapper').hide();
    $('#queue_wrapper').show();
    $('#search_wrapper #search_bar').val('')
    autocomplete.call($('#search_bar').get(0), false)
}

function onLoad(){
    $("#search_bar").bind("input", autocomplete)
    $('#guess_link').bind("click", add_video_to_queue)
    console.log('load')
}

</script>
<script src="https://apis.google.com/js/client.js?onload=onLoad"></script>
</div>
