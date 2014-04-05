"use strict";
$(document).ready(function () {
    $('.party_name, .button').click(function (e) {
        e.stopPropagation();
    });
    $('.submit').click(function (event) {
        $(this).closest('form').submit();
    });
});