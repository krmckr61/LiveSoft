$(document).ready(function () {

    if ($("select.select2").length > 0) {
        $(".select2").select2();
    }

    if ($('.sttabs').length > 0) {
        [].slice.call(document.querySelectorAll('.sttabs')).forEach(function (el) {
            new CBPFWTabs(el);
        });
    }

});