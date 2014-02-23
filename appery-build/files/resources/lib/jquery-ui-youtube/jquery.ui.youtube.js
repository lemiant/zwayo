(function () {
    var isYoutubeApiInitialized = false,
        INVALID_VIDEO_ID = 2;

    // Next event listeners are used to generate jQuery events for iframe
    function onPlayerReady(event) {
        var player = event.target, iframe = $(player.getIframe());
        iframe.trigger("ready");
        var targetVideoId = player.getVideoData().video_id;
        if (targetVideoId === "" || targetVideoId === null || targetVideoId === undefined) {
            if (iframe.data("has-autoplay") == true) {
                iframe.trigger("youtubeError", [INVALID_VIDEO_ID]);
            }
        }

        if (iframe.is(":not(:visible)") && iframe.data("has-autoplay")) {
            player.mute();
        }
    }

    function onPlayerStateChange(event) {
        var iframe = event.target.getIframe();

        var eventNames = {
            "-1": "youtubeunstarted",
            "0": "youtubeended",
            "1": "youtubeplaying",
            "2": "youtubepaused",
            "3": "youtubebuffering",
            "5": "youtubevideocued"
        };
        if (event.data in eventNames) {
            $(iframe).trigger(eventNames["" + event.data]);
        }
        $(iframe).trigger("youtubeStateChange", [event.data]);
    }

    function onPlayerPlaybackQualityChange(event) {
        var iframe = event.target.getIframe();
        $(iframe).trigger("youtubePlaybackQualityChange", [event.data]);
    }

    function onPlayerPlaybackRateChange(event) {
        var iframe = event.target.getIframe();
        $(iframe).trigger("youtubePlaybackRateChange", [event.data]);
    }

    function onPlayerError(event) {
        var iframe = event.target.getIframe();
        $(iframe).trigger("youtubeError", [event.data]);
    }

    function onPlayerApiChange(event) {
        var iframe = event.target.getIframe();
        $(iframe).trigger("youtubeApiChange");
    }

    // Create Player object and add event listeners to Youtube iframe.
    function registerYoutubeComponentNow(elementId) {
        var player_options = {
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange,
                'onPlaybackQualityChange': onPlayerPlaybackQualityChange,
                'onPlaybackRateChange': onPlayerPlaybackRateChange,
                'onError': onPlayerError,
                'onApiChange': onPlayerApiChange
            }
        };

        var playerObject = new YT.Player(elementId, player_options);
        return $("#" + elementId).data("youtube-player-object", playerObject);
    }

    // List of Youtube IFrames which are going to be initialized when Youtube API is ready.
    Appery.__uninitializedYoutubeComponents = [];

    // This global function is called by Youtube API when it's ready.
    window.onYouTubeIframeAPIReady = function () {
        isYoutubeApiInitialized = true;

        var idsList = Appery.__uninitializedYoutubeComponents;
        for (var i = 0; i < idsList.length; i++) {
            registerYoutubeComponentNow(idsList[i]);
        }

        Appery.__uninitializedYoutubeComponents = [];
    };

    Appery.registerYoutubeComponent = function (elementId) {
        if (isYoutubeApiInitialized) {
            registerYoutubeComponentNow(elementId);
        }
        else {
            Appery.__uninitializedYoutubeComponents.push(elementId);
        }
    };

})();
