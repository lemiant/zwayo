/*
 * JS for musicQueueScreen generated by Appery.io
 */

Appery.getProjectGUID = function() {
    return 'a616adf4-1952-491a-84e9-9c07b1a4d37f';
};

function navigateTo(outcome, useAjax) {
    Appery.navigateTo(outcome, useAjax);
}

// Deprecated


function adjustContentHeight() {
    Appery.adjustContentHeightWithPadding();
}

function adjustContentHeightWithPadding(_page) {
    Appery.adjustContentHeightWithPadding(_page);
}

function setDetailContent(pageUrl) {
    Appery.setDetailContent(pageUrl);
}

Appery.AppPages = [{
    "name": "searchScreen",
    "location": "searchScreen.html"
}, {
    "name": "musicQueueScreen",
    "location": "musicQueueScreen.html"
}, {
    "name": "findPartyScreen",
    "location": "findPartyScreen.html"
}];

musicQueueScreen_js = function(runBeforeShow) {

    /* Object & array with components "name-to-id" mapping */
    var n2id_buf = {
        'mobilecarousel_1': 'musicQueueScreen_mobilecarousel_1',
        'mobilecarouselitem_2': 'musicQueueScreen_mobilecarouselitem_2',
        'mobilecarouselitem_3': 'musicQueueScreen_mobilecarouselitem_3',
        'mobilecarouselitem_4': 'musicQueueScreen_mobilecarouselitem_4',
        'mobilecarouselitem_5': 'musicQueueScreen_mobilecarouselitem_5',
        'mobilecarouselitem_6': 'musicQueueScreen_mobilecarouselitem_6',
        'mobilecarouselitem_7': 'musicQueueScreen_mobilecarouselitem_7',
        'mobilecarouselitem_8': 'musicQueueScreen_mobilecarouselitem_8'
    };

    if ("n2id" in window && window.n2id !== undefined) {
        $.extend(n2id, n2id_buf);
    } else {
        window.n2id = n2id_buf;
    }

    if (navigator.userAgent.indexOf("IEMobile") != -1) {
        // Fixing issue https://github.com/jquery/jquery-mobile/issues/5424 on Windows Phone
        $("div[data-role=footer]").css("bottom", "-36px");
    }

    Appery.CurrentScreen = 'musicQueueScreen';

    /*
     * Nonvisual components
     */
    var datasources = [];

    /*
     * Events and handlers
     */

    // Before Show
    var musicQueueScreen_beforeshow = function() {
            Appery.CurrentScreen = "musicQueueScreen";
            for (var idx = 0; idx < datasources.length; idx++) {
                datasources[idx].__setupDisplay();
            }
        };

    // On Load
    var musicQueueScreen_onLoad = function() {
            musicQueueScreen_elementsExtraJS();

            // TODO fire device events only if necessary (with JS logic)
            musicQueueScreen_deviceEvents();
            musicQueueScreen_windowEvents();
            musicQueueScreen_elementsEvents();
        };

    // screen window events
    var musicQueueScreen_windowEvents = function() {

            $('#musicQueueScreen').bind('pageshow orientationchange', function() {
                var _page = this;
                adjustContentHeightWithPadding(_page);
            });

        };

    // device events
    var musicQueueScreen_deviceEvents = function() {
            document.addEventListener("deviceready", function() {

            });
        };

    // screen elements extra js
    var musicQueueScreen_elementsExtraJS = function() {
            // screen (musicQueueScreen) extra code

            /* mobilecarousel_1*/
            var mobilecarousel_1_options = {
                indicatorsListClass: "ui-carousel-indicators",
                showIndicator: true,
                showTitle: true,
                titleBuildIn: true,
                titleIsText: true,
                animationDuration: 250,
                useLegacyAnimation: true,
                enabled: true,
            }
            Appery.__registerComponent('mobilecarousel_1', new Appery.ApperyMobileCarouselComponent("musicQueueScreen_mobilecarousel_1", mobilecarousel_1_options));

            $("#musicQueueScreen_mobilecarouselitem_2").attr("reRender", "mobilecarousel_1");

            $("#musicQueueScreen_mobilecarouselitem_3").attr("reRender", "mobilecarousel_1");

            $("#musicQueueScreen_mobilecarouselitem_4").attr("reRender", "mobilecarousel_1");

            $("#musicQueueScreen_mobilecarouselitem_5").attr("reRender", "mobilecarousel_1");

            $("#musicQueueScreen_mobilecarouselitem_6").attr("reRender", "mobilecarousel_1");

            $("#musicQueueScreen_mobilecarouselitem_7").attr("reRender", "mobilecarousel_1");

            $("#musicQueueScreen_mobilecarouselitem_8").attr("reRender", "mobilecarousel_1");

        };

    // screen elements handler
    var musicQueueScreen_elementsEvents = function() {
            $(document).on("click", "a :input,a a,a fieldset label", function(event) {
                event.stopPropagation();
            });

        };

    $(document).off("pagebeforeshow", "#musicQueueScreen").on("pagebeforeshow", "#musicQueueScreen", function(event, ui) {
        musicQueueScreen_beforeshow();
    });

    if (runBeforeShow) {
        musicQueueScreen_beforeshow();
    } else {
        musicQueueScreen_onLoad();
    }
};

$(document).off("pagecreate", "#musicQueueScreen").on("pagecreate", "#musicQueueScreen", function(event, ui) {
    Appery.processSelectMenu($(this));
    musicQueueScreen_js();
});