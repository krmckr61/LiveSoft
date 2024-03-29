Array.prototype.deleteFromValue = function () {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

function getToken() {
    return $('meta[name=csrf-token]').attr('content');
}

function getDomain() {
    return location.protocol + '//' + window.location.hostname + '/';
}

function getDomainWithoutSlash() {
    return location.protocol + '//' + window.location.hostname;
}

function confirmation(title, url, text, type) {
    if (!type) {
        type = 'warning';
    }

    var confirmButtonClass = "btn-";
    switch (type) {
        case 'success':
            confirmButtonClass += "success";
            break;
        default:
            confirmButtonClass += "danger";
            break;
    }
    if (!text) {
        text = null;
    }
        swal({
            title: title,
            text: text,
            type: type,
            showCancelButton: true,
            confirmButtonClass: confirmButtonClass,
            confirmButtonText: "Tamam",
            cancelButtonText: "İptal"
        },
        function (isConfirm) {
            if (isConfirm) {
                window.location = url;
            }
        });
}

function timestampToDate(timestamp) {

    if (timestamp) {
        if (timestamp.includes('T')) {
            timestamp = timestamp.replace('T', ' ');
        }
        return new moment(timestamp).format('YYYY-MM-DD H:mm');
    }
    else {
        return 'N/A';
    }
}

function connectionTimeToDate(connectionTime) {
    return new moment(connectionTime.year + '-' + connectionTime.month + '-' + connectionTime.day + ' ' + connectionTime.hour + ':' + connectionTime.minute).format('YYYY-MM-DD H:mm');
}

function timestampToIso(timestamp) {
    if (timestamp) {
        return timestamp.split('.')[0].replace('T', ' ');
    } else {
        return 'N/A';
    }
}

function secondToTime(second) {
    var string = '';

    if (second > 3600) {
        var hours = parseInt(second / 3600);
        second %= 3600;
        string = hours + ' sa ';
    }

    if (second > 60) {
        var minutes = parseInt(second / 60);
        second %= 60;
        string += minutes + ' dk ';
    }

    if (second > 1) {
        string += second + ' sn';
    }

    return string;
}


function secondToShortTime(second) {
    var string = '';

    if (second > 3600) {
        var hours = parseInt(second / 3600);
        second %= 3600;
        string = hours + ' sa ';
    }

    if (second > 60) {
        var minutes = parseInt(second / 60);
        second %= 60;
        string += minutes + ' dk ';
    }

    if (string === '') {
        string = 'yaklaşık 1 dk';
    }

    return string;
}

function getBrowser(browserName) {
    var browsers = ['chrome', 'firefox', 'edge', 'explorer', 'safari', 'yandex', 'opera', 'facebook', 'samsung-internet'];
    for (var i = 0; i < browsers.length; i++) {
        if (browserName.toLowerCase().replace(' ', '-').includes(browsers[i])) {
            return browsers[i];
        }
    }

    return false;
}

function getOs(os) {
    var oss = ['windows', 'android', 'mac', 'linux', 'ios', 'wkwebview'];
    for (var i = 0; i < oss.length; i++) {
        if (os.toLowerCase().replace(' ', '-').includes(oss[i])) {
            return oss[i];
        }
    }

    return false;
}

function strLimit(str, limit = 10, suffix = '...') {
    if (str.length > limit) {
        return str.substring(0, limit).trim() + suffix;
    } else {
        return str;
    }
}

function setCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}