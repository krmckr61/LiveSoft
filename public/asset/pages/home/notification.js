var Notification = {
    notificationTableSelector: $("table#NotificationTable"),
    notificationSound: $("audio#NotificationAudio"),
};

Notification.init = function () {
    this.notificationTable = this.notificationTableSelector.DataTable();
};

Notification.loadNotifications = function (notifications) {
    if(notifications.length > 0) {
        for(var i = 0; i < notifications.length; i++) {
            this.addNotification(notifications[i]);
        }
    }
};

Notification.addNotification = function (notification) {
    var tableData = [];
    tableData.push(notification.username);
    tableData.push(notification.text);
    tableData.push(timestampToDate(notification.created_at));

    this.notificationTable.row.add(tableData).node().id = notification.id;
    this.notificationTable.draw(false);
};

Notification.playSound = function () {
    this.notificationSound.play();
};

Notification.init();