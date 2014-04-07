"use strict";
$(document).ready(function () {
    var locked = false;
    function unlock() {
        locked = false;
    }
    function index(target, targetMenu) {
        var last, lastMenu;
        // Find previous values
        if ($("#menuMake").hasClass("active")) {
            last = $("#make");
            lastMenu = $("#menuMake");
        }
        if ($("#menuJoin").hasClass("active")) {
            last = $("#join");
            lastMenu = $("#menuJoin");
        }
        
        // Transition
        // remove
        last.fadeOut(500);
        lastMenu.removeClass("active");
        // add
        target.delay(500).fadeIn(500);
        targetMenu.addClass("active");
    }
    
    function queue(targetMenu) {
        var lastMenu, link, playPause;
        // Find previous values
        if ($("#menuPrevious").hasClass("active")) {
            lastMenu = $("#menuPrevious");
        }
        if ($("#menuPlayPause").hasClass("active")) {
            lastMenu = $("#menuPlayPause");
        }
        if ($("#menuNext").hasClass("active")) {
            lastMenu = $("#menuNext");
        }
        
        if (lastMenu.is(targetMenu)) {// TODO: consider moving to second priority
            playPause = $(".playPause")[0];
            link = playPause.src.split("imgs/nav/");
            if (link[1] === ("play.png")) {
                playPause.src = link[0] + "imgs/nav/pause.png";
            }
            if (link[1] === ("pause.png")) {
                playPause.src = link[0] + "imgs/nav/play.png";
            }
        } else {
            // Transition
            // remove
            lastMenu.removeClass("active");
            // add
            targetMenu.addClass("active");
        }
    }
    
    $("a").click(function (event) {
        if (locked) {
            return false;
        }
        
        var target, targetMenu, type, active;
        
        // Find new values
        switch (event.currentTarget.id) {
        case "linkMake":
            target = $("#make");
            targetMenu = $("#menuMake");
            type = "index";
            break;
        case "home":
            target = $("#make");
            targetMenu = $("#menuMake");
            type = "index";
            break;
        case "linkJoin":
            target = $("#join");
            targetMenu = $("#menuJoin");
            type = "index";
            break;
        case "linkPrevious":
            targetMenu = $("#menuPrevious");
            type = "queue";
            break;
        case "linkPlayPause":
            targetMenu = $("#menuPlayPause");
            type = "queue";
            break;
        case "linkNext":
            targetMenu = $("#menuNext");
            type = "queue";
            break;
        default:
            return false;
        }
        
        active = targetMenu.hasClass("active");
        
        switch (type) {
        case "index":
            if (active) {
                return false;
            }
            index(target, targetMenu);
            break;
        case "queue":
            if (active && !targetMenu.is("#menuPlayPause")) {
                return false;
            }
            queue(targetMenu);
            break;
        default:
            return false;
        }
        locked = true;
        setTimeout(unlock, 750);
        return false;
    });
});