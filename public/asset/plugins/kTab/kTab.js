$(document).ready(function () {
    $(".heads .bar").on("click", function () {
        var target = $(this).attr('data-target');
        $(".heads .bar").removeClass("active");
        $(".contents .content").removeClass("active");
        $(this).addClass("active");
        $(target).addClass("active")
    });
});