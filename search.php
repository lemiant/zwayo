<link type="text/css" href="css/search.css" rel="stylesheet" /> 
<div id="search_wrapper" style="display:none">
<div id="search_container">
    <input id="search_bar" type="text">
</div>
<div id="close_button" onclick="leave_search()">X</div>
    <div id="search_message">Type a song to search</div>
<div id="search_queue" class="queue">
    <script type="text/html" id="__search_item_tmpl">
        <div class="search item" id="{{id.videoId}}">
        <img src="{{thumb}}">
        <p class="song_title">{{snippet.title.ellipsis_truncate(90)}}</p>
        </div>
    </script>
</div>
<script type="text/javascript" src="js/search.js"></script>
</div>
