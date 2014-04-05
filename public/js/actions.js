"use strict";
$(document).ready(function () {
    $('.party_name, .button').click(function (e) {
        e.stopPropagation();
    });
    $('.submit').click(function (event) {
        $(this).closest('form').submit();
    });
    $("a").click(function (event) {
        var last, lastMenu, target, targetMenu;
        
        // Find new values
        switch (event.target.id) {
        case "linkMake":
            target = $("#make");
            targetMenu = $("#menuMake");
            break;
        case "linkJoin":
            target = $("#join");
            targetMenu = $("#menuJoin");
            break;
        default:
            return false;
        }
        
        if (targetMenu.hasClass("active")) {
            return false;
        }
        
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
        
        return false;
    });
});