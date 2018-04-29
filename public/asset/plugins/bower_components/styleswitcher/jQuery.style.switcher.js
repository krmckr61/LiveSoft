$(document).ready(function () {
    if(!getCookie('ls-theme-color')) {
        setCookie('ls-theme-color', 'megna');
    }

    loadThemeColor();

    $("*[theme]").click(function (e) {
        e.preventDefault();
        var currentStyle = $(this).attr('theme');
        setCookie('ls-theme-color', currentStyle, 365);
        loadThemeColor();
    });
});

function loadThemeColor() {
    $("#themecolors li a").removeClass('working');
    $("#themecolors li a[theme='" + getCookie('ls-theme-color') + "']").addClass('working');
    $('#theme').attr({href: getDomain() + 'asset/css/colors/' + getCookie('ls-theme-color') + '.css'});
}