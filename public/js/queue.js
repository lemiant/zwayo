//- Script loading functions
function exists(identifiers) {
    var n, parts, i;
    if (typeof identifiers === 'string') {
        identifiers = [identifiers];
    }
    for (n = 0; n < identifiers.length; n++) {
        //console.log(identifiers)
        parts = identifiers[n].split('.');
        node = window;
        for (i = 0; i < parts.length; i++) {
            node = node[parts[i]];
            if (typeof node === 'undefined') {
                return false;
            }
        }
    }
    return true;
}
function fallback(identifiers, backup) {
    if (!exists(identifiers)) {
        document.write('<script type="text/javascript" src="' + backup + '"></' + 'script>');
    }
}
function blocked_callback(identifiers, callback, refresh_rate) {
    if (!refresh_rate) {
        refresh_rate = 50;
    }
    return function () {
        if (exists(identifiers)) {
            callback.apply(this, arguments);
        } else {
            var t = this;
            var args = arguments;
            setTimeout(function () { blocked_callback(identifiers, callback).apply(t, args); }, refresh_rate);
        }
    };
}

//- Rendering Functions
last_update = 0;
function get_queue_actions() {
    $.ajax({
        url: "server/get_queue_actions.php",
        dataType: 'json',
        type: 'post',
        data: {last: last_update},
        success: blocked_callback(['templating', 'jQuery.fn.doubletap'], make_queue) //Default 50ms refresh
    });
}
function make_queue() {
    window.queue_item_tmpl = _.template($('#__queue_item_tmpl').html());
    update_queue.apply(this, arguments);
}
function update_queue(js, status) {
    if (status === "success" && js.result === "success") {
        queue = $('#queue');
        for (i = 0; i < js.items.length; i++) {
            apply(js.items[i], queue);
        }
        if (js.items.length > 0) {
            last_update = js.items[js.items.length - 1].id;
        }
    }
    setTimeout(get_queue_actions, 2500);
}
function apply(rule, list) {
    rule.body = $.parseJSON(rule.body);
    if (rule.action === 'add') {
        tr = $(queue_item_tmpl(rule));
        tr.data("videoId", rule.body.videoId);
        tr.doubletap(function (e) { play($(e.currentTarget)); }, function () {}, 400);
        list.prepend(tr);
    } else if (rule.action === 'set_active' && player_div.css('display') === 'none') {
        set_active(rule.body.active_id);
    }
}

function set_active(id) {
    $('div.active').removeClass('active');
    $('div#' + id).addClass('active');
}
function send_set_active(id) {
    $.ajax({
        url: 'server/set_queue_action.php',
        type: 'post',
        data: {action: 'set_active',
                body: {active_id: id}}
    });
    set_active(id);
}

String.prototype.ellipsis_truncate = function (size) {
    if (this.length > size) {
        return this.slice(0, size - 4) + "...";
    }
    return this;
};

get_queue_actions();

//- Youtube functions
//- Load search.js
_.templateSettings = {
  interpolate : /\{\{(.+?)\}\}/g,
  execute : /\{\%(.+?)\%\}/g
};
window.templating = true; //Flag for queue rendering

//- Youtube Functions
window.player_div = $('#player_div');

//Load youtube API async
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

function play(t) {
    place_after(t);
    play_above();
}
function place_after(t) {
    t = $(t);
    while (t.next('#player_div').length === 0) {
        if (t.prevAll('#player_div').length) { //is above
            player_div.before(player_div.next());
        } else if (t.nextAll('#player_div').length) {
            player_div.after(player_div.prev());
        }
    }
}
function play_above() {
    var above = player_div.prev();
    player_div.show();
    videoId = above.data('videoId');
    player.loadVideoById({videoId: videoId, suggestedQuality: 'small'});
    send_set_active(above.attr('id'));
}

function onYouTubeIframeAPIReady() {
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
function onPlayerReady(e) {
    $('#play_button_div').show();
    $('#play').on('click', function () {
        place_after($('#queue .item').not(player_div).last());
        play_above();
    });
    if (player_div.css('display') !== 'none') {
        play_above(); //We tried to play a song without the YoutubeAPI
    }
}
function onPlayerStateChange(e) {
    if (e.data === 0) {
        console.log(player_div.prevAll('.item'));
        if (player_div.prevAll('.item').length >= 2) {
            place_after(player_div.prevAll('.item')[1]);
            play_above();
        } else {
            play($('#queue .item').not('#player_div').last());
        }
    }
}

//- Load search.html
$.ajax('search').success(function (result) {
    $('body').append(result);
    $("#add_music_button").on('click', function () {
        $('#queue_wrapper').hide();
        $('#search_wrapper').show();
    });
});