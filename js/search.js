
function autocomplete(){
    if(current_search) current_search.abort()
    $('#search_queue').hide()
    search_query = $('#search_bar').val();
    
    if(search_query){
        $('#search_message').text('Loading...').show()

        current_search = $.ajax('https://content.googleapis.com/youtube/v3/search', {
            dataType: 'jsonp',
            data: {
                q: search_query,
                type: 'video',
                part: 'id,snippet',
                maxResults: 8,
                fields: 'items(id(videoId),snippet(thumbnails,title))',
                key: apiKey
            }
        }).done(render_page)
    }
    else $('#search_message').text('Type a song to search').show()
}
function render_page(result){
    if(result.items){
        $('#search_queue .item').addClass('old')
        for(i=0; i<result.items.length; i++){
            row = result.items[i]
            if($('#search_queue #'+row.id.videoId).length){
                tr = $('#search_queue #'+row.id.videoId).removeClass('old')
            }
            else{
                row.thumb = get_thumb(row)
                tr = search_item_tmpl(row)
            }
            $('#search_queue').append(tr)
        }
        $('#search_queue .old').remove()
        $('#search_queue .item').on('click', add_video_to_queue)
        $('#search_message').hide()
        $('#search_queue').show()
        current_search = null;
    }
}
function get_thumb(row){
    if(row.snippet.thumbnails.small) return row.snippet.thumbnails.small.url
    else if(row.snippet.thumbnails.default) return row.snippet.thumbnails.default.url
    else return ""
}

function add_video_to_queue(){
    div = $(this)
    body = {videoId: div.attr('id')}
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
    leave_search()
}
    
function leave_search(){
    $('#search_wrapper').hide();
    $('#queue_wrapper').show();
    $('#search_wrapper #search_bar').val('')
    autocomplete()
}

apiKey = 'AIzaSyChJLHkrcdUPknA85oQwUHdVpssEmDzMfI';
search_item_tmpl = _.template($('#__search_item_tmpl').html());
current_search = null;
$("#search_bar").on("input", autocomplete)