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
    return 'http://' + window.location.hostname + '/';
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
            var split = timestamp.split('T');
        } else {
            var split = timestamp.split(' ');
        }

        var date = split[0].split('-');
        var time = split[1].split('.')[0];
        time = time.split(':');
        time = time[0] + ':' + time[1];

        var day = date[2];
        var year = date[0];
        var month = date[1];
        return day + '/' + month + '/' + year + ' ' + time;

    }
    else {
        return 'N/A';
    }
}

function timestampToIso(timestamp) {
    if(timestamp) {
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
        string = hours + ' saat ';
    }

    if (second > 60) {
        var minutes = parseInt(second / 60);
        second %= 60;
        string += minutes + ' dakika ';
    }

    if (second > 1) {
        string += second + ' saniye';
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

    if(string === '') {
        string = 'yaklaşık 1 dk';
    }

    return string;
}

function getBrowser(browserName) {
    var browsers = ['chrome', 'firefox', 'edge', 'explorer', 'safari', 'yandex', 'opera'];
    for (var i = 0; i < browsers.length; i++) {
        if (browserName.toLowerCase().includes(browsers[i])) {
            return browsers[i];
        }
    }

    return false;
}

function getOs(os) {
    var oss = ['windows', 'android', 'mac', 'linux'];
    for (var i = 0; i < oss.length; i++) {
        if (os.toLowerCase().includes(oss[i])) {
            return oss[i];
        }
    }

    return false;
}