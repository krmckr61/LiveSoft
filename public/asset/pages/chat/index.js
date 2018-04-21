$(document).ready(function () {
    $(".static-chat-container .messages").height($(window).height() - ($("nav.navbar").height() + $(".navbar-default.sidebar").height() + $(".bg-title").height()) - 220);
});