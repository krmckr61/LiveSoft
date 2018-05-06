table.init();
var node = new Node();
node.init();

$(document).ready(function () {

    var skyInterval;

    $(".min-message-direction").on('mousedown', function () {
        if ($(this).hasClass('direction-left')) {
            var func = function () {
                $(".min-message-container")[0].scrollLeft -= 50
            };
        } else {
            var func = function () {
                $(".min-message-container")[0].scrollLeft += 50
            };
        }
        skyInterval = setInterval(func, 100);
    });

    $("#UserTable").on('mouseenter', '.user-table-icon', function () {
        node.getCurrentTime();
    });

    $(".min-message-direction").on('mouseup', function () {
        clearInterval(skyInterval);
    });

    $("#LoginVisitorTable").on("click", ".take-client", function () {
        var tr = $(this).closest('tr');
        var row = table.loginClientTable.row(tr);
        table.clearPulse(tr);
        var id = tr.attr('id');
        node.takeClient(id);
    });

    $("#HistoryTable").on("click", '.btn-show-history-chat', function () {
        $("#HistoryTable tr").removeClass('active-table-row');
        $(this).parent().parent().addClass('active-table-row');
        var id = $(this).attr('data-id');
        node.getHistoryChat(id);
    });

    $("#WaitingCount").on("click", function () {
        if ($('table#LoginVisitorTable').find(".take-client").length > 0) {
            if (Chat.user.online == 1) {
                $('table#LoginVisitorTable').find(".take-client:first-child")[0].click();
            }
        }
    });

    $(".min-message-container").on("click", '.min-message', function () {
        if ($(this).hasClass('active')) {
            Chat.hideChatScreen($(this).attr('data-id'));
        } else {
            Chat.showChatScreen($(this).attr('data-id'));
            node.readMessages($(this).attr('data-id'));
        }
    });

    $(".chat-screen-container").on("click", 'span.hide-chat', function () {
        var id = $(this).closest('.chat-screen').attr('data-id');
        Chat.hideChatScreen(id);
    });

    $(".chat-screen-container").on("click", '.close-chat', function () {
        var id = $(this).closest('.chat-screen').attr('data-id');
        var chatScreen = $(".chat-screen-container .chat-screen[data-id='" + id + "']");
        Chat.closeChat(id);
    });

    $(".chat-screen button.message-send").on("click", function () {
        var data = Chat.getSendingMessage($(this));
        if (data) {
            Chat.clearText(data.visitid);
            node.sendMessage(data);
        }
    });

    $("#HistorySearchButton").on("click", function () {
        var dates = table.getSelectedHistoryDates();
        node.getHistory(dates);
    });

    $("li.user-status-menu ul > li a").on("click", function () {
        var id = $(this).parent().attr('data-id');
        if (id) {
            node.setOnlineStatus(id);
        }
    });

    $(".chat-screen-container").on("click", ".chat-screen .card .recent-message-buttons .col-sm-6", function () {
        var recentVisitId = $(this).closest('.card').attr('data-id');
        if ($(this).hasClass('show-recent-messages')) {
            Chat.showRecentVisitMessages(recentVisitId);
        } else {
            Chat.showRecentVisitInfos(recentVisitId);
        }
    });

    $(".chat-screen-container").on("click", ".chat-screen .shortcuts-container ul li", function () {
        var id = $(this).closest('.chat-screen').attr('data-id');
        var shortcutId = $(this).attr('data-id');
        Chat.addLetterShortcutMessage(id, shortcutId);
    });

    $(document).on("keydown", null, 'esc', function () {
        $(".chat-screen-container .chat-screen .close-shortcuts").click();
        Chat.focusTextEditor();
    });

    $(".chat-screen-container").on("click", ".chat-screen .close-shortcuts", function () {
        $(this).parent().hide();
    });

    $(document).on('keydown', function (event) {
        if (event.which !== 32 && !isNaN(String.fromCharCode(event.which))) {
            if ($(".min-message-container .min-message.active").length > 0) {
                Chat.sendShortcut(event.key);
            }
        }
    });

    $(".chat-screen-container").on('click', '.chat-transaction', function () {
        $(this).toggleClass('active');
    });

    $(".chat-screen-container").on('click', '.add-user', function () {
        var visitId = $(this).closest('.chat-screen').attr('data-id');
        node.getUserList(visitId);
    });

    $(".chat-screen-container").on('click', '.logout-user', function () {
        var cnf = confirm('Görüşmeden ayrılmak istediğinize emin misiniz ?');
        if (cnf) {
            var visitId = $(this).closest('.chat-screen').attr('data-id');
            node.logoutUserFromVisit(visitId);
        }
    });

    $("#UserListModal").on('click', '.add-user-to-visit', function () {
        var userId = $(this).attr('data-userid');
        node.addUserToVisit(userId, $("#UserListModal").attr('data-id'));
        table.removeUserFromUserListTable(userId);
    });

    $("#LoginVisitorTable").on('click', '.user-ban', function () {
        var id = $(this).attr('data-id');
        var data = JSON.parse($("#LoginVisitorTable tr#" + id + " .open-client-detail textarea").html());

        $("#UserBanModal .ban-user-details").html($(this).parent().parent().clone(true));
        $("#UserBanModal .ban-user-details .row").prepend('<div class="col-sm-3 text-right">Ziyaretçi Adı :</div><div class="col-sm-9 text-left">' + (data.NameSurname ? data.NameSurname : 'N/A') + '</div><div class="col-sm-3 text-right">E-Posta :</div><div class="col-sm-9 text-left">' + (data.Email ? data.Email : 'N/A') + '</div><div class="col-sm-3 text-right">Kullanıcı Adı :</div><div class="col-sm-9 text-left">' + (data.UserName ? data.UserName : 'N/A') + '</div>');
        $("#UserBanModal").attr('data-id', id).modal('show');
    });

    $("#LoginVisitorTable").on('click', '.private-message', function () {
        $("#PrivateMessageModal").find('input').val('');
        var id = $(this).attr('data-id');
        $("#PrivateMessageModal").attr('data-id', id);
        $("#PrivateMessageModal").modal('show');
    });

    $("#LoginVisitorTable").on('click', '.watch-chat-button', function () {
        var id = $(this).attr('data-id');
        if(id) {
            $("#LoginVisitorTable tr#" + id).addClass('active-table-row');
            node.watchChat(id);
        }
    });

    $(".chat-screen-container").on('click', 'li.private-message', function () {
        $("#PrivateMessageModal").find('input').val('');
        var id = $(this).closest('.chat-screen').attr('data-clientid');
        $("#PrivateMessageModal").attr('data-id', id);
        $("#PrivateMessageModal").modal('show');
    });

    $("#LoginVisitorTable").on('click', '.join-chat', function () {
        var id = $(this).attr('data-id');
        if (confirm('Bu görüşmeye katılmak istediğinize emin misiniz ?')) {
            if($(".watch-chat[data-clientid='" + id + "']").length > 0) {
                $(".watch-chat[data-clientid='" + id + "'] .rpanel-title .close-chat").trigger('click');
                setTimeout(function () {
                    node.joinVisit(id);
                }, 100);
            } else {
                node.joinVisit(id);
            }
        }
    });

    $("#PrivateMessageForm").on('submit', function () {
        var message = $(this).find('input').val();
        message = message.trim();
        if (message) {
            node.sendPrivateMessage($("#PrivateMessageModal").attr('data-id'), message);
            $("#PrivateMessageModal").modal('hide');
        }
    });

    $("#UserBanForm").on('submit', function () {
        var date = $("#UserBanModal input").val();
        if (date) {
            $("#UserBanModal").modal('hide');
            node.banClient($("#UserBanModal").attr('data-id'), date);
        }
    });

    $(document).on('keydown', null, 'down', function (e) {
        if($(".chat-screen-container .chat-screen.shw-rside").hasClass('history-chat')) {
            Chat.showNextHistoryChat(e);
        } else if($(".chat-screen-container .chat-screen.shw-rside").hasClass('watch-chat')) {
            Chat.showNextWatchChat(e);
        }
    });

    $(document).on('keydown', null, 'up', function (e) {
        if($(".chat-screen-container .chat-screen.shw-rside").hasClass('history-chat')) {
            Chat.showPrevHistoryChat(e);
        } else if($(".chat-screen-container .chat-screen.shw-rside").hasClass('watch-chat')) {
            Chat.showPrevWatchChat(e);
        }
    });

    $(".heads .bar-choosing").on('click', function () {
        var container = $(this).parent().parent().parent();
        var target = $(this).attr('data-target');
        $(".heads .bar-choosing").removeClass('active');
        $(this).addClass('active');
        container.find('.bar-choosing-container').hide();
        container.find(target).show();
    });

    $(".mega-dropdown li a, .dropdown-menu.dropdown-user li a").on('click', function (e) {
        if (Chat.hasActiveChat()) {
            e.preventDefault();
            var href = $(this).attr('href');
            confirmation('Uyarı!', href, 'Mevcut görüşmelerinizi sonlandırmadan bu bağlantıya gitmek istediğinize emin misiniz ?', 'warning');
        } else {
            return true;
        }
    });

    setInterval(function () {
        node.getCurrentTime();
    }, 30000);

});

function destroyChat(id) {
    node.destroyChat(id);
}