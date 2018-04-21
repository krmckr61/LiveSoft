var Report = function () {
    this.url = '/reports/';
    this.users = [];
    this.dateRange = null;
    this.getReportButton = $("#GetReport");
    this.checkboxes = {all: $("input.check-all-users"), user: $("input.user-checkbox")};
    this.dateRangeHtml = $("#DateRange");
    this.downloadButton = $("#Download");
    this.dateRangeObj = null;
    this.init();
};

Report.prototype.setListeners = function () {
    var $this = this;
    $(document).ready(function () {

        //check all
        $this.checkboxes.all.on('ifChanged', function () {
            if ($(this).prop('checked') === true) {
                $this.checkAllUsers();
            } else {
                $this.checkOutAllUsers();
            }
        });

        //check user
        $this.checkboxes.user.on('ifChanged', function () {
            if ($(this).prop('checked') === true) {
                $this.addUser($(this).attr('value'));
            } else {
                $this.removeUser($(this).attr('value'));
            }
        });

        //on date change
        $this.dateRangeHtml.on('change', function () {
            $this.setDateRange();
        });

        //on get report click
        $this.getReportButton.on('click', function () {
            $this.getReport();
        });

        $this.downloadButton.on('click', function () {
            $this.reportToExcell();
        });

    });
};

Report.prototype.setDateRange = function () {
    this.dateRange = this.dateRangeHtml.val();
};

Report.prototype.addUser = function (id) {
    if (!this.hasUser(id)) {
        this.users.push(id);
    }
};

Report.prototype.removeUser = function (id) {
    if (this.hasUser(id)) {
        this.users.deleteFromValue(id);
    }
};

Report.prototype.hasUser = function (id) {
    if (this.users.indexOf(id) > -1) {
        return true;
    } else {
        return false;
    }
};

Report.prototype.checkAllUsers = function () {
    this.checkboxes.user.iCheck('check');
};

Report.prototype.checkOutAllUsers = function () {
    this.checkboxes.user.iCheck('unCheck');
};

Report.prototype.getReport = function () {
    //validation
    if (this.users.length > 0 && this.dateRange !== null) {
        Loader.show();
        var $this = this;
        $.post(this.url + 'getReport', {
            _token: getToken(),
            users: this.users,
            dateRange: this.dateRange
        }, function (response) {
            Loader.hide();
            $this.initReportTable(response);
        });
    } else {
        $.toast({
            heading: 'Başarısız!',
            text: 'Lütfen rapor almak istediğiniz tarihi ve kullanıcıları seçiniz.',
            position: 'top-right',
            icon: 'warning',
            hideAfter: 3500,
            stack: 6
        });
    }
};

Report.prototype.initReportTable = function (data) {
    $("#ReportTable tbody").html('');
    this.addReportRow('Tarih Aralığı', data.dateRange + ' <b>(' + data.daysCount + ' Gün)</b>')
    var userString = this.getUserString(data.users);
    this.addReportRow('Kullanıcılar', userString);
    this.addReportRow('Toplam Mesai Süresi', data.totalShiftTime);
    this.addReportRow('Toplam Mola Süresi', data.totalShiftBreakTime);
    this.addReportRow('Toplam Çalışılan Süre', data.totalWorkTime);
    this.addReportRow('Yapılan Mola Süresi', data.totalOfflineTime);
    this.addReportRow('Yapılan Mola Adeti', data.totalOfflineCount + ' adet');
    this.addReportRow('Toplam Görüşme Adeti', data.totalTakenClientCount + ' adet');
    this.addReportRow('Toplam Görüşme Süresi', data.takenClientTime);
    this.addReportRow('Ortalama Görüşme Süresi', data.avgClientTime);
    this.addReportRow('Görüşme Puanı Ortalaması', data.avgVisitPoint ? parseFloat(data.avgVisitPoint).toFixed(2) + ' / 5' : 'N/A');

    this.initBlockedWords(data.faultyMessages);
    this.initLowScoreVisits(data.lowScoreVisits);
    $(".report-detail").show();
    $(".no-report").hide();
};

Report.prototype.initBlockedWords = function (faultyMessages) {
    $("#BlockedWords tbody").html('');
    if (faultyMessages.length > 0) {
        for (var i = 0; i < faultyMessages.length; i++) {
            this.addBlockedWordRow(faultyMessages[i]['name'], faultyMessages[i]['text'], faultyMessages[i]['created_at']);
        }
    }
};

Report.prototype.initLowScoreVisits = function (lowScoreVisits) {
    $("#LowScoreVisits tbody").html('');
    if(lowScoreVisits.length > 0) {
        for(var i = 0; i < lowScoreVisits.length; i++) {
            var visit = lowScoreVisits[i];
            this.addLowScoreVisitRow(visit.username, visit.point, visit.id);
        }
    }
};

Report.prototype.addReportRow = function (name, value) {
    var elem = $("table.detail-hidden tr.clone").clone(true);
    elem.removeClass('clone');
    elem.find('td.name').html(name);
    elem.find('td.value').html(value);
    $("#ReportTable tbody").append(elem);
};

Report.prototype.addBlockedWordRow = function (name, value, created_at) {
    var elem = $("table.blocked-hidden tr.clone").clone(true);
    elem.removeClass('clone');
    elem.find('td.name').html(name);
    elem.find('td.value').html(value);
    elem.find('td.value2').html(created_at);
    $("#BlockedWords tbody").append(elem);
};

Report.prototype.addLowScoreVisitRow = function (userName, point, visitId) {
    var elem = $("table.lowscore-hidden tr.clone").clone(true);
    elem.removeClass('clone');
    elem.find('td.username').html(userName);
    elem.find('td.point').html(point);
    elem.find('td.visitid').html('<a href="/chats/' + visitId + '" target="_blank">@' + visitId + '</a>');
    $("#LowScoreVisits tbody").append(elem);
};

Report.prototype.reportToExcell = function () {
    var uri = 'data:application/vnd.ms-excel;base64,'
        ,
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
        , base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        }
        , format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            })
        }
    return function () {
        var name = new moment().format('YYYY-MM-DD');
        var html = document.getElementById('ReportTable').innerHTML + document.getElementById('BlockedWords').innerHTML + document.getElementById('LowScoreVisits').innerHTML;
        var ctx = {worksheet: name, table: html}
        window.location.href = uri + base64(format(template, ctx))
    }
}();

Report.prototype.getUserString = function (users) {
    var userString = '';
    if (users.length > 0) {
        for (var i = 0; i < users.length; i++) {
            if (i > 0) {
                userString += ', ';
            }
            userString += users[i].name;
        }
    }
    return userString;
};

Report.prototype.initForm = function () {
    this.dateRangeObj = this.dateRangeHtml.daterangepicker({
        timePicker: true,
        locale: {
            format: 'YYYY-MM-DD HH:mm'
        },
        timePicker24Hour: true,
        startDate: moment().format('YYYY-MM-DD'),
        endDate: moment().add(1, 'days').format('YYYY-MM-DD'),
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        maxDate: moment().add(1, 'days')
    });
    this.setDateRange();
};

Report.prototype.init = function () {
    this.initForm();
    this.setListeners();
};

var rep = new Report();