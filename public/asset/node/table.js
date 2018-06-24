var table = {};

table.init = function () {
    this.clientTableSelector = $('table#VisitorTable');
    this.clientTable = this.clientTableSelector.DataTable({});
    this.loginClientTableSelector = $('table#LoginVisitorTable');
    this.loginClientTable = this.loginClientTableSelector.DataTable({
        order: [[1, 'asc']],
        columns: [{orderable: false}, {orderable: true}, {orderable: true}, {orderable: true}, {orderable: true}, {orderable: true}, {orderable: true}, {orderable: true}]
    });
    this.historyTableSelector = $("table#HistoryTable");
    this.historyTable = this.historyTableSelector.DataTable();
    this.userBanModalSelector = $("#UserBanModal");
    this.userBanPicker = this.userBanModalSelector.find('input').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
    });
    this.userBanPicker.val(new moment().format('YYYY-MM-DD HH:mm'));
    this.historyDateRangePickerSelector = $('#HistoryDateRangePicker');
    this.historyDateRangePicker = this.historyDateRangePickerSelector.daterangepicker({
        timePicker: true,
        locale: {
            format: 'YYYY-MM-DD HH:mm'
        },
        startDate: moment().format('YYYY-MM-DD 00:00'),
        endDate: moment().add(1, 'days').format('YYYY-MM-DD 00:00'),
        timePickerIncrement: 30,
        timePicker24Hour: true,
        timePickerSeconds: false,
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse'
    });
    this.userTableSelector = $("table#UserTable");
    this.userTable = this.userTableSelector.DataTable();
    this.userListTableSelector = $("#UserListModal table");
    this.userListTable = this.userListTableSelector.DataTable();
    this.audio = new Audio('/sounds/client2.mp3');
    this.visitorCount = 0;
    this.liveCount = 0;
    this.setListeners();
    this.setShortcuts();
    this.initResponsive();
    this.audio = new Audio('/sounds/client2.mp3');
};

table.initResponsive = function () {
    $(".content-wrap").height($(window).height() - $(".navbar-default").height() - $("footer").height() - 190);
};

table.clearClientTable = function () {
    this.clientTable.clear().draw(false);
    this.updateClientCounts();
};

table.clearLoginClientTable = function () {
    this.loginClientTable.clear().draw(false);
    this.updateClientCounts();
};

table.addClient = function (client) {
    if (client.status !== 0) {
        return this.addConnectClient(client);
    } else {
        this.clientTable.row("#" + client.id).remove().draw();
        var tableData = [];
        if (client.data.banned) {
            tableData.push('<i class="fa fa-ban" title="Engellemiş kullanıcı"></i>');
        } else {
            tableData.push('<i class="fa fa-user"></i>');
        }

        if(client.data.connectionTime.hour < 10) {
            client.data.connectionTime.hour = '0' + client.data.connectionTime.hour;
        }
        if(client.data.connectionTime.minute < 10) {
            client.data.connectionTime.minute = '0' + client.data.connectionTime.minute;
        }


        tableData.push(client.data.location && client.data.location.countryCode ? '<img src="/asset/image/flags/' + client.data.location.countryCode.toLowerCase() + '.png">' : 'N/A');
        tableData.push(client.data.location && client.data.location.city ? client.data.location.city : 'N/A');
        tableData.push(client.data.connectionTime.hour + ':' + client.data.connectionTime.minute);
        var os = getOs(client.data.device.os);
        if (os) {
            tableData.push('<img src="/asset/image/' + os + '.png" title="' + client.data.device.os + '">');
        } else {
            tableData.push(client.data.device.os ? client.data.device.os : 'N/A');
        }

        var browser = getBrowser(client.data.device.browser);
        if (browser) {
            tableData.push('<img src="/asset/image/' + browser + '.png" title="' + client.data.device.browser + '">');
        } else {
            tableData.push(client.data.device.browser ? client.data.device.browser : 'N/A');
        }
        this.clientTable.row.add(tableData).node().id = client.id;
        this.clientTable.draw(false);
    }

    this.updateClientCounts();
};

table.setHistory = function (rows) {
    this.historyTable.clear().draw(false);
    if (rows.length > 0) {
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            table.addHistoryRow(row);
        }
    }
};

table.addHistoryRow = function (row) {
    var tableData = [];
    tableData.push(row.data.UserName);
    tableData.push(row.data.NameSurname);
    tableData.push(row.data.Email);
    tableData.push(row.data.location.countryCode);
    tableData.push(timestampToDate(row.created_at));
    tableData.push(timestampToDate(row.closed_at));
    tableData.push(secondToTime(row.chattime));
    tableData.push(row.username);
    tableData.push('<button class="btn btn-success btn-show-history-chat" data-id="' + row.id + '"><i class="fa fa-info-circle"></i></button>');

    this.historyTable.row.add(tableData).node().id = row.visitorid;
    this.historyTable.draw(false);

};

table.addUserRow = function (user) {
    this.userTable.row("#" + user.id).remove().draw();
    var tableData = [];
    if (user.onlinestatus == '1') {
        tableData.push('<i class="fa fa-check-circle user-table-icon s1" data-date="' + user.created_at + '"></i>');
    } else if (user.onlinestatus == '2') {
        tableData.push('<i class="fa fa-clock-o user-table-icon s2" data-date="' + user.created_at + '"></i>');
    } else {
        tableData.push('<i class="fa fa-minus-circle user-table-icon s3" data-date="' + user.created_at + '"></i>');
    }
    tableData.push('<div class="time-countup" data-date="' + user.created_at + '"></div>');
    tableData.push(user.id);
    tableData.push(user.name);
    this.userTable.row.add(tableData).node().id = user.id;
    this.userTable.draw(false);
    node.getCurrentTime();
};

table.setOnlineSatus = function (data) {
    var elem = this.userTableSelector.find('tr#' + data.userId + ' i');
    elem.removeClass('s1 s2 s3 fa-check-circle fa-clock-o fa-minus-circle');
    if (data.onlineStatus == 1) {
        elem.addClass('fa-check-circle s1');
    } else if (data.onlineStatus == 2) {
        elem.addClass('fa-clock-o s2');
    } else {
        elem.addClass('fa-minus-circle s3');
    }

    elem.parent().next().find('.time-countup').attr('data-date', new moment(data.created_at).format('YYYY-MM-DDTHH:mm:ss'));
    elem.attr('data-date', new moment(data.created_at).format('YYYY-MM-DDTHH:mm:ss'));

    Chat.setCurrentTime(data.created_at);
};

table.addConnectClient = function (client) {
    this.loginClientTable.row("#" + client.id).remove().draw();
    var tableData = [];
    tableData.push('<button class="btn btn-circle btn-success open-client-detail" data-id="' + client.id + '"><textarea class="hidden">' + JSON.stringify(client.data) + '</textarea><i class="fa fa-plus"></i></button>');
    if (client.status === 1) {
        tableData.push('<span class="hidden">1</span><i class="fa fa-spinner"></i>');
    } else if (client.status === 2) {
        tableData.push('<span class="hidden">2</span><i class="fa fa-comments-o"></i>');
    }

    tableData.push(client.data.UserName ? client.data.UserName : 'N/A');
    tableData.push(client.data.NameSurname ? client.data.NameSurname : 'N/A');
    tableData.push(client.data.Email ? client.data.Email : 'N/A');
    tableData.push(client.data.location ? '<img src="/asset/image/flags/' + client.data.location.countryCode.toLowerCase() + '.png">' : 'N/A');

    if(client.data.connectionTime.hour < 10) {
        client.data.connectionTime.hour = '0' + client.data.connectionTime.hour;
    }
    if(client.data.connectionTime.minute < 10) {
        client.data.connectionTime.minute = '0' + client.data.connectionTime.minute;
    }

    tableData.push(client.data.connectionTime.hour + ':' + client.data.connectionTime.minute);
    if (client.status === 2) {
        var userString = '';
        for (var i = 0; i < client.users.length; i++) {
            var user = client.users[i];
            if (i !== 0) {
                userString += ', ';
            }
            userString += user.name;
        }
        tableData.push(userString);
    } else {
        tableData.push('<button class="btn btn-success take-client"><i class="fa fa-check-circle"></i></button>');
    }
    this.loginClientTable.row.add(tableData).node().id = client.id;
    this.loginClientTable.draw(false);

    if (client.status === 1) {
        try {
            this.audio.play();
        } catch (e) {
            console.log(e);
        }
        this.pulseRow(this.loginClientTableSelector.find('#' + client.id));
    }

    this.updateClientCounts();
};

table.updateClientCounts = function () {
    this.liveCount = ($("#LiveClient span.total-count").html() ? $("#LiveClient span.total-count").html() : "0");
    this.visitorCount = ($("#VisitorClient span.total-count").html() ? $("#VisitorClient span.total-count").html() : "0")
    $("#LiveBar span").html(this.liveCount);
    $("#VisitorBar span").html(this.visitorCount);

    var waitingClientCount = $('table#LoginVisitorTable .take-client').length;
    if (waitingClientCount > 0) {
        $("#WaitingCount span").html(waitingClientCount);
        $("#WaitingCount").removeAttr('disabled').removeClass('btn-default').addClass('btn-success');
    } else {
        $("#WaitingCount span").html(waitingClientCount);
        $("#WaitingCount").attr('disabled', 'disabled').removeClass('btn-success').addClass('btn-default');
    }
};

table.pulseRow = function (row) {
    row.pulsate({
        color: '#ff0000', reach: 20,
        speed: 700,
        pause: 0,
        glow: true
    });
};

table.clearPulse = function (row) {
    row.pulsate('destroy');
    row.removeAttr('style');
};

table.clientConnect = function (client) {
    var id = client.id;
    this.removeClient(id);
    this.addClient(client);
    this.updateClientCounts();
};

table.removeClient = function (id) {
    this.clientTable.row("#" + id).remove().draw();
    this.loginClientTable.row("#" + id).remove().draw();
    this.updateClientCounts();
};

table.setListeners = function () {
    var self = this;
    $("#LoginVisitorTable").on("click", ".open-client-detail", function () {
        var tr = $(this).closest('tr');
        var row = table.loginClientTable.row(tr);
        if (row.child.isShown()) {
            $(this).find("i").removeClass('fa-minus').addClass('fa-plus');
            row.child.hide();
            tr.removeClass('shown');
        } else {
            $("#LoginVisitorTable .open-client-detail i.fa.fa-minus").parent().click();
            $(this).find("i").removeClass('fa-plus').addClass('fa-minus');
            var data = self.getChildData($(this).attr('data-id'), $(this).find('textarea').html());
            row.child(data).show();
            tr.addClass('shown');
        }
    });
};

table.getChildData = function (id, data) {
    data = JSON.parse(data);

    var clone = $("#Clones .table-child-data").clone(true);
    clone.removeClass('clone');
    clone.find('.country').html(data.location ? '<img src="/asset/image/flags/' + data.location.countryCode.toLowerCase() + '.png">' : 'N/A');
    clone.find('.city').html(data.location ? data.location.city : 'N/A');
    clone.find('.browser').html(data.device.browser && getBrowser(data.device.browser) ? '<img src="/asset/image/' + getBrowser(data.device.browser) + '.png">' : 'N/A');
    clone.find('.os').html(data.device.os && getOs(data.device.os) ? '<img src="/asset/image/' + getOs(data.device.os) + '.png">' : 'N/A');
    clone.find('.connectionTime').html(connectionTimeToDate(data.connectionTime));
    clone.find('.loginType').html(data.FacebookId ? '<i class="fa fa-facebook facebook-loggedin-client-icon"></i>' : 'Normal');
    clone.find('button.user-ban').attr('data-id', id);
    clone.find('button.join-chat').attr('data-id', id);
    clone.find('button.private-message').attr('data-id', id);
    clone.find('button.watch-chat-button').attr('data-id', id);

    return clone;
};

table.setShortcuts = function () {
    var self = this;
    $(document).on('keydown', null, 'f2', function (e) {
        e.preventDefault();
        if (self.loginClientTableSelector.find(".take-client").length > 0) {
            self.loginClientTableSelector.find(".take-client:first-child")[0].click();
        }
        return false;
    });

    $(document).on('keydown', null, 'f1', function (e) {
        e.preventDefault();
        Chat.showUnreadChatScreen();
        return false;
    });

    $(document).on('keydown', null, 'f3', function (e) {
        e.preventDefault();
        Chat.showLeftChatScreen();
        return false;
    });

    $(document).on('keydown', null, 'f4', function (e) {
        e.preventDefault();
        Chat.showRightChatScreen();
        return false;
    });

    $(document).on('keydown', null, 'ctrl+down', function (e) {
        e.preventDefault();
        var id = Chat.getCurrentChatId();
        if (id) {
            Chat.closeChat(id);
        }
        return false;
    });

    $(document).on('keydown', null, 'ctrl+up', function (e) {
        e.preventDefault();
        Chat.hideCurrentChat();
        return false;
    });
};

table.getSelectedHistoryDates = function () {
    return {
        startDate: this.dateToTimeStamp(this.historyDateRangePickerSelector.data('daterangepicker').startDate),
        endDate: this.dateToTimeStamp(this.historyDateRangePickerSelector.data('daterangepicker').endDate)
    }
};

table.dateToTimeStamp = function (date) {
    date = date.toDate();
    var dd = date.getDate();
    var mm = date.getMonth() + 1;
    var yyyy = date.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    return yyyy.toString() + '-' + mm.toString() + '-' + dd.toString() + ' ' + date.getHours() + ':' + date.getMinutes();
};

table.getTodayTimestamp = function () {
    var date = new Date();
    var dd = date.getDate();
    var mm = date.getMonth() + 1;
    var yyyy = date.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }

    return yyyy.toString() + '-' + mm.toString() + '-' + dd.toString() + ' 00:00';
};

table.setUsers = function (users) {
    if (users.length > 0) {
        for (var i = 0; i < users.length; i++) {
            var user = users[i];
            table.addUserRow(user);
        }
    }
};

table.clearUserTable = function () {
    this.userTable.clear().draw(false);
};

table.disconnectUser = function (id) {
    this.userTable.row("#" + id).remove().draw();
};

table.setUserList = function (visitId, users) {
    $("#UserListModal").attr('data-id', visitId);
    this.userListTable.clear().draw(false);
    for (var i = 0; i < users.length; i++) {
        var user = users[i];
        this.userListTable.row("#" + user.id).remove().draw();
        var tableData = [];
        if (user.onlinestatus == '1') {
            tableData.push('<i class="fa fa-check-circle user-table-icon s1" data-date="' + user.created_at + '"></i>');
        } else if (user.onlinestatus == '2') {
            tableData.push('<i class="fa fa-clock-o user-table-icon s2" data-date="' + user.created_at + '"></i>');
        } else {
            tableData.push('<i class="fa fa-minus-circle user-table-icon s3" data-date="' + user.created_at + '"></i>');
        }
        tableData.push('<div class="time-countup" data-date="' + user.created_at + '"></div>');
        tableData.push(user.name);
        tableData.push('<button type="button" class="btn btn-success add-user-to-visit" data-userid="' + user.id + '"><i class="fa fa-user-plus"></i></button>');
        this.userListTable.row.add(tableData).node().id = user.id;
        this.userListTable.draw(false);
    }
    $("#UserListModal").modal('show');
};

table.removeUserFromUserListTable = function (userId) {
    this.userListTable.row("#" + userId).remove().draw();
};