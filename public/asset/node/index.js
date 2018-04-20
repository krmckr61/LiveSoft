var Node = function () {
    this.socket;
};

Node.prototype.init = function () {
    var id = $('meta[name=representationId]').attr('content');
    if (id) {
        this.socket = io.connect('http://localhost:3000', {query: 'representativeId=' + id});
        this.initSockets();
    } else {
        alert('Bağlantı hatası');
        location.reload();
    }
};

Node.prototype.readMessages = function (id) {
    this.socket.emit('readMessages', id);
};

Node.prototype.initSockets = function () {

    var self = this;

    this.socket.on('loadClients', function (data) {
        if (Object.keys(data).length > 0) {
            $.each(data, function (index, value) {
                table.addClient(value);
            });
        }
    });

    this.socket.on('loadTalkingClients', function (data) {
        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                var row = data[i];
                Chat.addChat(row);
            }
        }
    });

    this.socket.on('loadMessages', function (data) {
        Chat.loadMessages(data);
    });

    this.socket.on('newClient', function (client) {
        table.addClient(client);
    });

    this.socket.on('clientConnect', function (client) {
        table.clientConnect(client);
    });

    this.socket.on('disconnectClient', function (id) {
        table.removeClient(id);
    });

    this.socket.on('takeClient', function (client) {
        table.removeClient(client.id);
        table.addClient(client);
    });

    this.socket.on('talkClient', function (data) {
        Chat.addChat(data, true);
        table.updateClientCounts();
    });

    this.socket.on('getMessage', function (data) {
        Chat.loadMessage(data);
        if ($(".min-message-container .min-message[data-id='" + data.visitid + "']").hasClass("active") && data.sender != "2") {
            self.readMessages(data.visitid);
        }
    });

    this.socket.on('clientDisconnectChat', function (visitId) {
        Chat.disableChat(visitId);
    });

    this.socket.on('chatDestroyed', function (clientId) {
        table.removeClient(clientId);
    });

    this.socket.on('setHistory', function (rows) {
        table.setHistory(rows);
    });

    this.socket.on('takeHistoryChat', function (visit) {
        Chat.addHistoryChat(visit);
    });

    this.socket.on('loadHistoryChatMessages', function (rows) {
        Chat.loadMessages(rows);
    });

    this.socket.on('loadUsers', function (users) {
        table.setUsers(users);
    });

    this.socket.on('userDisconnect', function (id) {
        table.disconnectUser(id);
    });

    this.socket.on('newUser', function (user) {
        table.addUserRow(user);
    });

    this.socket.on('userSetStatus', function (data) {
        table.setOnlineSatus(data);
    });

    this.socket.on('setOnlineStatus', function (status) {
        Chat.setStatus(status);
    });
    
    this.socket.on('loadRecentVisits', function (data) {
        Chat.loadRecentVisits(data);
    });

    this.socket.on('loadRecentVisitMessages', function (data) {
        Chat.loadRecentVisitMessages(data);
    });
    
    this.socket.on('autoTakeClient', function (clientId) {
        self.takeClient(clientId);
    });
    
    this.socket.on('setShortcuts', function (data) {
        if(data.visitId && data.contents.length > 0) {
            Chat.setLetterShortcuts(data);
        }
    });

    this.socket.on('setUserList', function (data) {
        var users = data.users;
        var visitId = data.visitId;
        if(users && users.length > 0) {
            table.setUserList(visitId, users);
        } else {
            $.toast({
                heading: 'Bilgilendirme!',
                text: 'Sohbete eklenebilecek müşteri temsilcisi bulunamadı.',
                position: 'top-right',
                icon: 'warning',
                hideAfter: 3500,
                stack: 6
            });
        }
    });
    
    this.socket.on('leaveVisit', function (visitId) {
        Chat.disableChat(visitId);
        Chat.closeChat(visitId);
    });

    this.socket.on('takeVisit', function (data) {
        self.socket.emit('takeNewVisit', data);
    });

    this.socket.on('showInformation', function (message) {
        $.toast({
            heading: 'Bilgilendirme!',
            text: message,
            position: 'top-right',
            icon: 'warning',
            hideAfter: 3500,
            stack: 6
        });
    });

    this.socket.on('privateMessageSended', function () {
        $.toast({
            heading: 'Bilgilendirme!',
            text: 'Özel mesaj başarıyla gönderildi.',
            position: 'top-right',
            icon: 'success',
            hideAfter: 3500,
            stack: 6
        });
    });

    this.socket.on('disconnectCurrentUsers', function () {
        window.location.replace(getDomain() + 'anotherLogin');
    });

};

Node.prototype.takeClient = function (id) {
    this.socket.emit('takeClient', id);
};

Node.prototype.sendMessage = function (data) {
    this.socket.emit('sendMessage', data);
};

Node.prototype.destroyChat = function (id) {
    this.socket.emit('destroyChat', id);
};

Node.prototype.getHistory = function (dates) {
    this.socket.emit('getHistory', dates);
};

Node.prototype.getHistoryChat = function (id) {
    this.socket.emit('getHistoryChat', id);
};

Node.prototype.setOnlineStatus = function (onlineStatus) {
    this.socket.emit('setOnlineStatus', onlineStatus);
};

Node.prototype.getShortCuts = function (visitId, letter) {
    this.socket.emit('getShortcuts', {visitId: visitId, letter: letter});
};

Node.prototype.getUserList = function (visitId) {
    this.socket.emit('getUserList', visitId);
};

Node.prototype.addUserToVisit = function (userId, visitId) {
    this.socket.emit('addUserToVisit', {userId: userId, visitId: visitId});
};

Node.prototype.logoutUserFromVisit = function (visitId) {
    this.socket.emit('logoutUserFromVisit', visitId);
};

Node.prototype.banClient = function (clientId, date) {
    this.socket.emit('banClient', {clientId: clientId, date: date});
};

Node.prototype.sendPrivateMessage = function (clientId, message) {
    this.socket.emit('sendPrivateMessage', {clientId: clientId, message: message});
};

Node.prototype.joinVisit = function (clientId) {
    this.socket.emit('joinVisit', clientId);
};