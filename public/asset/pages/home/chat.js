var Chat = {
    chatId: false,
    textEditors: [],
    user: {
        name: $("li>a.dropdown-toggle.profile-pic b").html(),
        id: $("li>a.dropdown-toggle.profile-pic b").attr('data-id'),
        online: $("li>a.dropdown-toggle.profile-pic b").attr('data-online')
    },
    userStatus: {
        1: {text: 'Hemen Dönecek', icon: "fa fa-clock-o"},
        2: {text: 'Çevrimiçi', icon: "fa fa-check-circle"},
        3: {text: 'Meşgul', icon: "fa fa-minus-circle"},
    },
    lastSendNumber: 1,
};

Chat.setStatus = function (onlineStatus) {
    var elem = $("li.user-status-menu ul > li[data-id='" + onlineStatus + "']")
    var id = elem.attr('data-id');
    $("li.user-status-menu ul li").show();
    elem.hide();
    var icon = elem.find('i').clone(true);
    icon.addClass('s' + onlineStatus).addClass('user-status-icon');
    $("li.user-status-menu > a > span").html();
    $("li.user-status-menu > a > span").html(icon);
};

Chat.onResizeChatScreen = function (id) {
    var screen = $(".chat-screen[data-id='" + id + "']");
    screen.height($(window).height() - ($("footer").height() + 30));
    screen.find(".r-panel-body").height(screen.height() - 70);
    if (screen.hasClass('history-chat') || screen.hasClass('watch-chat')) {
        screen.find(".grid").height(screen.find(".r-panel-body").height() - 77);
        screen.find(".grid.grid2").height(screen.find(".r-panel-body").height() - 107);
    } else {
        screen.find(".grid").height(screen.find(".r-panel-body").height());
        screen.find(".grid.grid2").height(screen.find(".r-panel-body").height() - 30);
    }
    setTimeout(function () {
        screen.find(".send-bar iframe").attr("style", "height:" + (screen.find(".send-bar").height() - screen.find("ul.wysihtml5-toolbar").height()) + "px !important");
    }, 50);
};

Chat.open = function (id) {
    $(".min-message-container .min-message[data-id='" + id + "']").trigger('click');
};

Chat.addChat = function (row, open) {
    if ($(".min-message-container .min-message[data-id='" + row.id + "']").length < 1) {
        var minimizeChat = $("#Clones .min-message.clone").clone(true);
        minimizeChat.find('.text').html(row.data.NameSurname);
        minimizeChat.removeClass('clone');
        minimizeChat.attr('data-id', row.id);

        $(".min-message-container").append(minimizeChat);

        var chatScreen = $("#Clones .chat-screen.current-chat.clone").clone(true);
        chatScreen.find(".rpanel-title .client-name").html(row.data.NameSurname);
        chatScreen.removeClass('clone');
        chatScreen.attr('data-id', row.id);
        chatScreen.attr('data-clientid', row.data.id);
        chatScreen.find('#accordion').attr('id', "accordion" + row.id)
        $(".chat-screen-container").append(chatScreen);

        Chat.setClientInfo(row.id, row.data);

        this.initPlugins(row.id);
    }
    if (open) {
        this.open(row.id);
        $(".chat-screen[data-id='" + row.id + "'] .text-editor").data('wysihtml5').editor.focus();
    }
};

Chat.addHistoryChat = function (row) {
    if ($(".chat-screen-container .chat-screen[data-id=" + row.id + "]").length < 1) {
        var minimizeChat = $("#Clones .min-message.clone").clone(true);
        minimizeChat.find('.text').html(row.data.NameSurname);
        minimizeChat.removeClass('clone');
        minimizeChat.addClass('disabled');
        minimizeChat.attr('data-id', row.id);

        $(".min-message-container").append(minimizeChat);

        var chatScreen = $("#Clones .chat-screen.history-chat.clone").clone(true);
        chatScreen.find(".rpanel-title .client-name").html(row.data.NameSurname);
        chatScreen.removeClass('clone');
        chatScreen.addClass('disabled');
        chatScreen.attr('data-id', row.id);
        chatScreen.attr('data-clientid', row.data.id);
        chatScreen.find('#accordion').attr('id', "accordion" + row.id);
        chatScreen.find('.default-active').addClass('active');
        $(".chat-screen-container").append(chatScreen);

        Chat.setClientInfo(row.id, row.data);
        Chat.setHistoryClientInfo(row);
    }
    this.open(row.id);
};

Chat.addWatchChat = function (row) {
    if ($(".chat-screen-container .chat-screen[data-id=" + row.id + "]").length < 1) {
        var minimizeChat = $("#Clones .min-message.clone").clone(true);
        minimizeChat.find('.text').html(row.data.NameSurname);
        minimizeChat.removeClass('clone');
        minimizeChat.addClass('disabled');
        minimizeChat.attr('data-id', row.id);

        $(".min-message-container").append(minimizeChat);

        var chatScreen = $("#Clones .chat-screen.watch-chat.clone").clone(true);
        chatScreen.find(".rpanel-title .client-name").html(row.data.NameSurname);
        chatScreen.removeClass('clone');
        chatScreen.addClass('disabled');
        chatScreen.attr('data-id', row.id);
        chatScreen.attr('data-clientid', row.data.id);
        chatScreen.find('#accordion').attr('id', "accordion" + row.id);
        chatScreen.find('.default-active').addClass('active');

        $(".chat-screen-container").append(chatScreen);

        Chat.setClientInfo(row.id, row.data);
    }
    this.open(row.id);
};

Chat.setChatToHistory = function (id) {
    var chat = $(".chat-screen-container .chat-screen[data-id='" + id + "']");
    chat.addClass('history-chat');
    chat.find('.chat-container').removeClass('col-md-4').addClass('col-md-12');
    chat.find('.prepared-messages-container').remove();
};

Chat.setHistoryClientInfo = function (visit) {
    Chat.addClientInfoRow(visit.id, 'Görüşme Başlangıç Tarihi', timestampToDate(visit.created_at));
    Chat.addClientInfoRow(visit.id, 'Görüşme Bitiş Tarihi', timestampToDate(visit.closed_at));
    Chat.addClientInfoRow(visit.id, 'Görüşme Süresi', secondToTime(visit.chattime));
    if (!visit.point) {
        visit.point = 'N/A'
    } else {
        visit.point = visit.point + '/5';
    }
    Chat.addClientInfoRow(visit.id, 'Görüşmedeki Temsilciler', visit.username);
    Chat.addClientInfoRow(visit.id, 'Görüşme Puanı', visit.point);
    if (visit.active === '2') {
        visit.closedusername = 'Ziyaretçi';
    }
    Chat.addClientInfoRow(visit.id, 'Görüşmeyi Sonlandıran', visit.closedusername);
};

Chat.setClientInfo = function (id, data) {
    var texts = {Email: 'E-Posta Adresi', NameSurname: 'Adı Soyadı', UserName: 'Kullanıcı Adı', ipAddress: 'IP Adresi'};
    for (var key in data) {
        if (texts[key]) {
            this.addClientInfoRow(id, texts[key], data[key]);
        } else {
            switch (key) {
                case 'connectionTime':
                    this.addClientInfoRow(id, 'Bağlantı Tarihi', data[key].day + '/' + data[key].month + '/' + data[key].year + ' ' + data[key].hour + ':' + data[key].minute);
                    break;
                case 'device':
                    this.addClientInfoRow(id, 'İşletim Sistemi', data[key]['os']);
                    this.addClientInfoRow(id, 'Tarayıcı', data[key]['browser']);
                    break;
                case 'location':
                    this.addClientInfoRow(id, 'Ülke', data[key]['country']);
                    this.addClientInfoRow(id, 'Şehir', data[key]['city']);
                    break;
            }
        }
    }
    if (data.FacebookId) {
        this.addClientInfoRow(id, 'Giriş Türü', '<i class="fa fa-facebook facebook-loggedin-client-icon"></i>');
    } else {
        this.addClientInfoRow(id, 'Giriş Türü', 'Normal');
    }
};

Chat.addClientInfoRow = function (id, name, value) {
    var elem = $("#Clones .client-detail-row.clone").clone(true);
    elem.removeClass('clone');
    elem.find('.name').html(name);
    if (typeof value === 'undefined' || !value)
        value = 'N/A';
    elem.find('.value').html(value);
    $(".chat-screen-container .chat-screen[data-id='" + id + "'] .client-infos").append(elem);
};

Chat.addDisableChatInfo = function (row) {
    Chat.addClientInfoRow(row.id, 'Chat Bitiş Tarihi', timestampToDate(row.closed_at));
};

Chat.showChatScreen = function (id) {
    this.clearPulse(id);
    this.resetUnreadMessageCount(id);
    var chatScreen = $(".chat-screen[data-id='" + id + "']");
    var minMessage = $(".min-message[data-id='" + id + "']");

    $(".min-message").removeClass('active');
    $(".chat-screen").removeClass('shw-rside');

    Chat.onResizeChatScreen(id);
    minMessage.addClass('active');
    minMessage.removeClass('has-message');
    chatScreen.addClass('shw-rside');
    Chat.setScrollbar(id);
    if ($(".chat-screen[data-id='" + id + "'] .text-editor").length > 0) {
        $(".chat-screen[data-id='" + id + "'] .text-editor").data('wysihtml5').editor.focus();
    }
};

Chat.showLeftChatScreen = function () {
    if ($(".min-message-container .min-message").length > 0) {
        if ($(".min-message-container .min-message.active").length > 0) {
            var index = $(".min-message-container .min-message.active").index();
            if (index === 0) {
                var id = $(".min-message-container .min-message:last-child").attr('data-id');
                Chat.showChatScreen(id);
            } else {
                index--;
                var id = $(".min-message-container .min-message:eq(" + index + ")").attr('data-id');
                Chat.showChatScreen(id);
            }
        } else {
            Chat.showFirstChatScreen();
        }
    }
};

Chat.showRightChatScreen = function () {
    if ($(".min-message-container .min-message").length > 0) {
        if ($(".min-message-container .min-message.active").length > 0) {
            var index = $(".min-message-container .min-message.active").index();
            if (index === $(".min-message-container .min-message").length - 1) {
                var id = $(".min-message-container .min-message:first-child").attr('data-id');
                Chat.showChatScreen(id);
            } else {
                index++;
                var id = $(".min-message-container .min-message:eq(" + index + ")").attr('data-id');
                Chat.showChatScreen(id);
            }
        } else {
            Chat.showLastChatScreen();
        }
    }
};

Chat.showUnreadChatScreen = function () {
    if ($(".min-message-container .min-message.has-message").length > 0) {
        $(".min-message-container .min-message.has-message").first().trigger('click');
    }
};

Chat.showFirstChatScreen = function () {
    if ($(".min-message-container .min-message").length > 0) {
        var id = $(".min-message-container .min-message:first-child").attr('data-id');
        Chat.showChatScreen(id);
    }
};

Chat.showLastChatScreen = function () {
    if ($(".min-message-container .min-message").length > 0) {
        var id = $(".min-message-container .min-message:last-child").attr('data-id');
        Chat.showChatScreen(id);
    }
};

Chat.hideChatScreen = function (id) {
    var chatScreen = $(".chat-screen[data-id='" + id + "']");
    var minMessage = $(".min-message[data-id='" + id + "']");

    minMessage.removeClass('active');
    chatScreen.removeClass('shw-rside');

};

Chat.initPlugins = function (id) {
    $('.chat-screen[data-id="' + id + '"] .text-editor').wysihtml5({
        "lists": false,
        "font-styles": false,
        "image": false,
        "stylesheets": false
    });

    setTimeout(function () {
        Chat.setShortcuts(id);
    }, 500);

    var json = JSON.parse($("#PreparedContents").html());
    $('.chat-screen[data-id="' + id + '"] .prepared-messages').treeview({
        data: json
    });

    $('.chat-screen[data-id="' + id + '"] input.search').on('keyup', function () {
        Chat.searchPreparedContent(id);
    });

    $('.chat-screen[data-id="' + id + '"] .prepared-messages-search-container div.search').on('click', function () {
        Chat.searchPreparedContent(id);
    });

    $('.chat-screen[data-id="' + id + '"] .prepared-messages').on('nodeSelected', function (event, data) {
        if (data.type !== 'head' && !Chat.isDisabled(id)) {
            $(".chat-screen[data-id='" + id + "'] .text-editor").data('wysihtml5').editor.focus();
            $(".chat-screen[data-id='" + id + "'] .text-editor").data('wysihtml5').editor.setValue(data.content);
        }
    });

};

Chat.isDisabled = function (id) {
    if ($(".min-message-container .min-message[data-id='" + id + "']").hasClass('disabled')) {
        return true;
    } else {
        return false;
    }
};

Chat.setShortcuts = function (id) {
    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on("keydown", null, 'return', function (e) {
        e.preventDefault();
        $('.chat-screen[data-id="' + id + '"] .send-bar .message-send').trigger('click');
        return false;
    });


    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on("keydown", null, 'esc', function (e) {
        e.preventDefault();
        var id = $(".min-message-container .min-message.active").attr('data-id');
        Chat.hideChatScreen(id);
        return false;
    });

    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on("keydown", null, 'tab', function (e) {
        e.preventDefault();
        var value = $(".chat-screen[data-id='" + id + "'] .text-editor").data('wysihtml5').editor.getValue().replace(/<(.|\n)*?>/g, '');
        if (value && value.length === 1) {
            node.getShortCuts(id, value);
        }
    });

    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on("keydown", null, 'shift+del', function (e) {
        e.preventDefault();
        $(".chat-screen[data-id='" + id + "'] .text-editor").data('wysihtml5').editor.setValue('');
        return false;
    });

    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on("keydown", null, 'f1', function (e) {
        e.preventDefault();
        Chat.showUnreadChatScreen(id);
        return false;
    });

    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on('keydown', null, 'f2', function (e) {
        e.preventDefault();
        $("#LoginVisitorTable");
        if ($("#LoginVisitorTable").find(".take-client").length > 0) {
            $("#LoginVisitorTable").find(".take-client:first-child")[0].click();
        }
        return false;
    });

    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on('keydown', null, 'f3', function (e) {
        e.preventDefault();
        Chat.showLeftChatScreen();
        return false;
    });

    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on('keydown', null, 'f4', function (e) {
        e.preventDefault();
        Chat.showRightChatScreen();
        return false;
    });

    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on('keydown', null, 'ctrl+down', function (e) {
        e.preventDefault();
        var id = Chat.getCurrentChatId();
        if (id) {
            Chat.closeChat(id);
        }
        return false;
    });

    $('.chat-screen[data-id="' + id + '"] .wysihtml5-sandbox').contents().find('body').on('keydown', null, 'ctrl+up', function (e) {
        e.preventDefault();
        Chat.hideCurrentChat();
        return false;
    });

};

Chat.toggleChatContainer = function (id) {
    $(".chat-screen[data-id='" + id + "'].right-sidebar").slideDown(50).toggleClass("shw-rside");
};

Chat.loadMessages = function (data) {
    if (data.messages.length > 0) {
        for (var i = 0; i < data.messages.length; i++) {
            var message = data.messages[i];
            this.loadMessage(message);
        }
    }
};

Chat.loadMessage = function (message) {
    var elem = $("#Clones .message-container.clone").clone(true);
    elem.removeClass('clone');
    elem.find('.text').html(message.text);
    elem.find('.send-time').html(timestampToDate(message.created_at));
    elem.attr('data-id', message.id);
    if (message.sender === "2") {
        elem.addClass('me');
        elem.find('.sender-name').html(message.username);
        if (message.private === 1) {
            elem.addClass('private-message');
            elem.find('.sender-name').append(' - (Özel Mesaj)');
        } else if (message.username !== this.user.name) {
            elem.addClass('other-user');
        }
    } else if (message.sender === "0") {
        elem.addClass('system');
    } else {
        elem.find('.sender-name').html($(".chat-screen[data-id='" + message.visitid + "'] .client-name").html());
    }
    $(".chat-screen[data-id='" + message.visitid + "'] .messages").append(elem);

    Chat.setScrollbar(message.visitid);
    if (!$(".min-message-container .min-message[data-id='" + message.visitid + "']").hasClass("active") && message.sender != "2") {
        if (message.seen == '0') {
            if (!$(".min-message[data-id='" + message.visitid + "']").hasClass('has-message')) {
                $(".min-message[data-id='" + message.visitid + "']").addClass('has-message');
            }
            Chat.newMessageSound();
            var unread = $(".min-message-container .min-message[data-id='" + message.visitid + "'] .unread-message-count").html();
            if (unread) {
                unread = parseInt(unread);
                unread++;
            } else {
                unread = 1;
            }
            $(".min-message-container .min-message[data-id='" + message.visitid + "'] .unread-message-count").removeClass('hidden').html(unread);
            this.pulse(message.visitid);
        }
    }
};

Chat.newMessageSound = function () {
    var audio = document.getElementById('NewMessageAudio');
    audio.play();
};

Chat.pulse = function (id) {
    $(".min-message[data-id='" + id + "']").pulsate({
        color: '#ff0000',
        reach: 50,
        speed: 700,
        pause: 0,
        glow: true
    });
};

Chat.clearPulse = function (id) {
    $(".min-message[data-id='" + id + "']").pulsate('destroy');
    $(".min-message[data-id='" + id + "']").removeAttr('style');
};

Chat.resetUnreadMessageCount = function (id) {
    $(".min-message[data-id='" + id + "'] .unread-message-count").html('').addClass('hidden');
};

Chat.setScrollbar = function (visitid) {
    if ($(".chat-screen[data-id='" + visitid + "'] .messages").length > 0) {
        $(".chat-screen[data-id='" + visitid + "'] .messages")[0].scrollTop = $(".chat-screen[data-id='" + visitid + "'] .messages")[0].scrollHeight;
    }
};

Chat.getSendingMessage = function (elem) {
    var visitId = elem.closest('.chat-screen').attr('data-id');
    var message = $(".chat-screen[data-id='" + visitId + "'] .text-editor").data('wysihtml5').editor.getValue().trim();
    if (message && !$(".min-message-container .min-message[data-id='" + visitId + "']").hasClass('disabled')) {
        return {visitid: visitId, message: message};
    } else {
        return false;
    }
};

Chat.clearText = function (id) {
    if ($(".chat-screen[data-id='" + id + "'] .text-editor").length > 0) {
        $(".chat-screen[data-id='" + id + "'] .text-editor").data('wysihtml5').editor.setValue('');
        $(".chat-screen[data-id='" + id + "'] .text-editor").data('wysihtml5').editor.focus();
    }
};

Chat.disableChat = function (id) {
    $(".chat-screen[data-id='" + id + "']").addClass('disabled');
    $(".min-message-container .min-message[data-id='" + id + "']").addClass('disabled');
    if (!$(".chat-screen-container .chat-screen[data-id='" + id + "']").hasClass('watch-chat')) {
        this.clearText(id);
        if ($(".chat-screen[data-id='" + id + "'] .text-editor").length > 0) {
            $(".chat-screen[data-id='" + id + "'] .text-editor").data('wysihtml5').editor.composer.disable();
        }
        $(".chat-screen[data-id='" + id + "'] .message-send").attr('disabled', 'disabled');
    }
};

Chat.closeChat = function (id) {
    if ($(".chat-screen[data-id='" + id + "']").hasClass('disabled')) {
        if ($(".chat-screen[data-id='" + id + "']").hasClass('watch-chat')) {
            node.logoutRoom(id);
        }
        $(".chat-screen[data-id='" + id + "'], .min-message[data-id='" + id + "']").remove();
        return true;
    } else {
        var cnf = confirm('Görüşmeyi sonlandırmak istediğinize emin misiniz ?');
        if (cnf) {
            Chat.disableChat(id);
            Chat.closeChat(id);
            destroyChat(id);
            return true;
        } else {
            return false;
        }
    }
};

Chat.closeCurrentChat = function () {
    var id = Chat.getCurrentChatId();
    if (id) {
        Chat.closeChat(id);
    }
};

Chat.hideCurrentChat = function () {
    var id = Chat.getCurrentChatId();
    if (id) {
        Chat.hideChatScreen(id);
    }
};

Chat.getCurrentChatId = function () {
    if ($(".min-message-container .min-message.active").length > 0) {
        var id = $(".min-message-container .min-message.active").attr('data-id');
        return id;
    } else {
        return false;
    }
};

Chat.searchPreparedContent = function (id) {
    var chatScreen = $(".chat-screen[data-id='" + id + "']");

    var pattern = chatScreen.find('input.search').val();
    var options = {
        ignoreCase: true,
        exactMatch: false,
        // revealResults: true
    };
    var results = chatScreen.find('.prepared-messages').treeview('search', [pattern, options]);

    var output = '<p>' + results.length + ' içerik bulundu</p>';
    $.each(results, function (index, result) {
        output += '<p>- ' + result.text + '</p>';
    });
    chatScreen.find('.search-output').html(output);
};

Chat.loadRecentVisits = function (data) {
    $(".chat-screen[data-id='" + data.visitId + "'] .history-messages-container .card").remove();
    if ($(".chat-screen[data-id='" + data.visitId + "']").length > 0) {
        if (data.recentVisits.length > 0) {
            var recentVisits = data.recentVisits;
            for (var i = 0; i < recentVisits.length; i++) {
                this.loadRecentVisit(data.visitId, recentVisits[i]);
            }
        }
    }
};

Chat.loadRecentVisit = function (visitId, recentVisit) {
    var elem = $(".card.clone").clone(true);
    elem.attr('data-id', visitId + '_' + recentVisit.id);
    var headingVisitId = 'HeadingRecentVisit' + visitId + '_' + recentVisit.id;
    var collapseVisitId = 'RecentVisit' + visitId + '_' + recentVisit.id;

    elem.find('.chat-date').html(timestampToDate(recentVisit.created_at));
    elem.find('.chat-user span').html(recentVisit.username);

    elem.removeClass('clone');
    elem.find('.card-header').attr('id', headingVisitId);
    elem.find('.card-header').attr('data-target', '#' + collapseVisitId);
    elem.find('.card-header').attr('aria-controls', collapseVisitId);
    elem.find('.collapse').attr('aria-labelledby', headingVisitId);
    elem.find('.collapse').attr('id', collapseVisitId);
    elem.find('.collapse').attr('data-parent', '#accordion' + visitId);

    $(".chat-screen-container .chat-screen[data-id='" + visitId + "'] .history-messages-container > #accordion" + visitId + " > .row").append(elem);
    this.loadRecentVisitInfos(visitId, recentVisit.id, recentVisit);
};

Chat.loadRecentVisitMessages = function (data) {
    if (data.messages.length > 0) {
        for (var i = 0; i < data.messages.length; i++) {
            var message = data.messages[i];
            this.loadRecentVisitMessage(data.visitId, message);
        }
    }
};

Chat.loadRecentVisitMessage = function (visitId, message) {
    var elem = $("#Clones .message-container.clone").clone(true);
    elem.removeClass('clone');
    elem.find('.text').html(message.text);
    elem.find('.send-time').html(timestampToDate(message.created_at));
    elem.attr('data-id', message.id);
    if (message.sender === "2") {
        elem.addClass('me');
        var username = message.username;
        if (!username) {
            elem.find('.sender-name').html('N/A');
        } else {
            if (message.username !== this.user.name) {
                elem.addClass('other-user');
            }
            elem.find('.sender-name').html(username);
        }
    } else if (message.sender === "0") {
        elem.addClass('system');
    } else {
        var username = $("#RecentVisit" + visitId + "_" + message.visitid + "  .recent-infos .client-detail-row:first-child .value").html();
        elem.find('.sender-name').html(username);
    }
    $(".chat-screen-container .chat-screen .card[data-id='" + visitId + '_' + message.visitid + "'] .recent-messages").append(elem);
};

Chat.loadRecentVisitInfos = function (visitId, id, recentVisit) {
    var data = recentVisit.data;
    var texts = {Email: 'E-Posta Adresi', NameSurname: 'Adı Soyadı', UserName: 'Kullanıcı Adı', ipAddress: 'IP Adresi'};
    for (var key in data) {
        if (texts[key]) {
            this.addRecentVisitInfo(visitId + '_' + id, texts[key], data[key]);
        } else {
            switch (key) {
                case 'connectionTime':
                    this.addRecentVisitInfo(visitId + '_' + id, 'Bağlantı Tarihi', data[key].day + '/' + data[key].month + '/' + data[key].year + ' ' + data[key].hour + ':' + data[key].minute)
                    break;
                case 'device':
                    this.addRecentVisitInfo(visitId + '_' + id, 'İşletim Sistemi', data[key]['os'])
                    this.addRecentVisitInfo(visitId + '_' + id, 'Tarayıcı', data[key]['browser'])
                    break;
                case 'location':
                    this.addRecentVisitInfo(visitId + '_' + id, 'Ülke', data[key]['country'])
                    this.addRecentVisitInfo(visitId + '_' + id, 'Şehir', data[key]['city'])
                    break;
            }
        }
    }
    if (data.FacebookId) {
        this.addRecentVisitInfo(visitId + '_' + id, 'Giriş Türü', '<i class="fa fa-facebook facebook-loggedin-client-icon"></i>');
    } else {
        this.addRecentVisitInfo(visitId + '_' + id, 'Giriş Türü', 'Normal');
    }
    var date = new Date(recentVisit.closed_at);
    this.addRecentVisitInfo(visitId + '_' + id, 'Görüşme Başlangıç Tarihi', timestampToDate(recentVisit.created_at));
    this.addRecentVisitInfo(visitId + '_' + id, 'Görüşme Bitiş Tarihi', timestampToDate(recentVisit.closed_at));
    this.addRecentVisitInfo(visitId + '_' + id, 'Görüşme Süresi', secondToTime(recentVisit.chattime));
    this.addRecentVisitInfo(visitId + '_' + id, 'Görüşmedeki Temsilciler', recentVisit.username);
    if (!recentVisit.point) {
        recentVisit.point = 'N/A';
    } else {
        recentVisit.point += '/5';
    }
    this.addRecentVisitInfo(visitId + '_' + id, 'Görüşme Puanı', recentVisit.point);
    if (recentVisit.active === '2') {
        recentVisit.closedusername = 'Ziyaretçi';
    }
    this.addRecentVisitInfo(visitId + '_' + id, 'Görüşmeyi Sonlandıran', recentVisit.closedusername);
};

Chat.addRecentVisitInfo = function (recentVisitId, name, value) {
    var elem = $("#Clones .client-detail-row.clone").clone(true);
    elem.removeClass('clone');
    elem.find('.name').html(name);
    if (typeof value === 'undefined' || !value)
        value = 'N/A';
    elem.find('.value').html(value);
    $(".chat-screen-container .chat-screen .card[data-id='" + recentVisitId + "'] .recent-infos").append(elem);
};

Chat.showRecentVisitInfos = function (recentVisitId) {
    $(".chat-screen-container .chat-screen .card[data-id='" + recentVisitId + "'] .recent-message-buttons .col-sm-6").removeClass('active');
    $(".chat-screen-container .chat-screen .card[data-id='" + recentVisitId + "'] .recent-message-buttons .col-sm-6.show-recent-infos").addClass('active');
    $(".chat-screen-container .chat-screen .card[data-id='" + recentVisitId + "'] .recent-messages").hide();
    $(".chat-screen-container .chat-screen .card[data-id='" + recentVisitId + "'] .recent-infos").show();
};

Chat.showRecentVisitMessages = function (recentVisitId) {
    $(".chat-screen-container .chat-screen .card[data-id='" + recentVisitId + "'] .recent-message-buttons .col-sm-6").removeClass('active');
    $(".chat-screen-container .chat-screen .card[data-id='" + recentVisitId + "'] .recent-message-buttons .col-sm-6.show-recent-messages").addClass('active');
    $(".chat-screen-container .chat-screen .card[data-id='" + recentVisitId + "'] .recent-infos").hide();
    $(".chat-screen-container .chat-screen .card[data-id='" + recentVisitId + "'] .recent-messages").show();
};

Chat.setLetterShortcuts = function (data) {
    var container = $(".chat-screen-container .chat-screen[data-id='" + data.visitId + "'] .shortcuts-container");
    var html = '';
    for (var i = 0; i < data.contents.length; i++) {
        var content = data.contents[i];
        html += '<li data-id="' + content.number + '">' + content.name + '<code>' + content.number + '</code>' + '<textarea style="display:none">' + content.content + '</textarea></li>';
    }
    container.find('ul').html(html);
    container.show();

    $(".form-group.prepared-messages-search-container input").focus().blur();

};

Chat.sendShortcut = function (number) {
    var chatId = this.getCurrentChatId();
    if (chatId) {
        if ($(".chat-screen-container .chat-screen[data-id='" + chatId + "'] .shortcuts-container").is(":visible")) {
            var elem = $(".chat-screen-container .chat-screen[data-id='" + chatId + "'] .shortcuts-container li[data-id='" + number + "']");
            if (elem.length > 0) {
                elem.click();
            }
        }
    }
};

Chat.addLetterShortcutMessage = function (visitId, shortcutNumber) {
    var message = $(".chat-screen-container .chat-screen[data-id='" + visitId + "'] .shortcuts-container li[data-id='" + shortcutNumber + "']").find("textarea").val();
    this.hideLetterShortcutContainer(visitId);
    $(".chat-screen[data-id='" + visitId + "'] .text-editor").data('wysihtml5').editor.setValue(message);
    $(".chat-screen[data-id='" + visitId + "'] .text-editor").data('wysihtml5').editor.focus();

};

Chat.hideLetterShortcutContainer = function (visitId) {
    var message = $(".chat-screen-container .chat-screen[data-id='" + visitId + "'] .shortcuts-container").hide();
};

Chat.focusTextEditor = function () {
    var id = this.getCurrentChatId();
    if (id) {
        $(".chat-screen[data-id='" + id + "'] .text-editor").data('wysihtml5').editor.focus();
    }
};

Chat.showNextHistoryChat = function (e) {
    var row = $("#HistoryTable tr.active-table-row");
    if (row.length > 0 && $(".history-chat.shw-rside").length > 0) {
        var index = row.index();
        $(".chat-screen-container .chat-screen.disabled.shw-rside span.close-chat").click();
        ++index;
        $("#HistoryTable tr:eq(" + (index + 1) + ") .btn-show-history-chat").click();
        e.preventDefault();
    }
};

Chat.showNextWatchChat = function (e) {
    var row = $("#LoginVisitorTable tr.active-table-row");
    if (row.length > 0 && $(".watch-chat.shw-rside").length > 0) {
        var index = row.index();
        $(".chat-screen-container .chat-screen.disabled.shw-rside span.close-chat").click();
        ++index;
        var elem = $("#LoginVisitorTable tr[role='row']:eq(" + (index + 1) + ")");
        if (elem) {
            row.find('.open-client-detail').click();
            $("#LoginVisitorTable tr").removeClass('active-table-row');
            var id = elem.attr('id');
            elem.find('.open-client-detail').click();
            setTimeout(function () {
                elem.next().find('.watch-chat-button').click();
            }, 50);
            e.preventDefault();
        }
    }
};

Chat.showPrevHistoryChat = function (e) {
    var row = $("#HistoryTable tr.active-table-row");
    if (row.length > 0 && $(".history-chat.shw-rside").length > 0) {
        var index = row.index();
        $(".chat-screen-container .chat-screen.disabled.shw-rside span.close-chat").click();
        $("#HistoryTable tr:eq(" + (index) + ") .btn-show-history-chat").click();
        e.preventDefault();
    }
};

Chat.showPrevWatchChat = function (e) {
    var row = $("#LoginVisitorTable tr.active-table-row");
    if (row.length > 0 && $(".watch-chat.shw-rside").length > 0) {
        var index = row.index();
        $(".chat-screen-container .chat-screen.disabled.shw-rside span.close-chat").click();
        var elem = $("#LoginVisitorTable tr[role='row']:eq(" + (index) + ")");
        if (elem) {
            row.find('.open-client-detail').click();
            $("#LoginVisitorTable tr").removeClass('active-table-row');
            var id = elem.attr('id');
            elem.find('.open-client-detail').click();
            setTimeout(function () {
                elem.next().find('.watch-chat-button').click();
            }, 50);
            e.preventDefault();
        }
    }
};

Chat.hasActiveChat = function () {
    var elem = $(".chat-screen-container .chat-screen");
    if (elem.length > 0 && !elem.hasClass('.history-chat') && !elem.hasClass('disabled')) {
        return true;
    } else {
        return false;
    }
};

Chat.setCurrentTime = function (date) {
    $(".time-countup").each(function (index) {
        var elem = $(".time-countup").eq(index);
        var elem2 = elem.parent().prev().find('i');

        var newDate = new Date(date).getTime() - new Date(elem.attr('data-date')).getTime();

        var string = secondToShortTime(Math.round(newDate / 1000));
        var longDate = secondToTime(Math.round(newDate) / 1000);

        elem.html(string);
        elem2.attr('title', longDate);
    });
};