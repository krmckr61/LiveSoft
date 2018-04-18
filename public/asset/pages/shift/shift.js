var Shift = function () {
    this.url = '/shifts/';
    this.shift = $('#Shift');
    this.saveButton = $('#Save');
    this.userLink = $(".user-link");
    this.modal = $("#ShiftModal");
    this.offUsersModal = $("#OffUsersModal");
    this.removeButton = this.modal.find('.removeButton');
    this.shiftObj = null;
    this.downloadButton = $("#Download");
    this.loader = $(".preloader");
};

Shift.prototype.showLoader = function () {
    this.loader.show();
};

Shift.prototype.hideLoader = function () {
    this.loader.hide();
};

Shift.prototype.setListeners = function () {
    var $this = this;
    $(document).ready(function () {

        $this.userLink.on("click", function () {
            if ($(this).find('input:checked').length > 0) {
                $(this).find('input').iCheck('uncheck');
            } else {
                $(this).find('input').iCheck('check');
            }
        });

        $this.modal.find('form').on('submit', function () {
            $this.save();
        });

        $this.removeButton.on("click", function () {
            $this.deleteShift();
        });

        $this.shift.on('click', '.fc-day-header.fc-widget-header', function () {
            var date = $(this).attr('data-date');
            if (date) {
                $this.showOffUsers(date);
            } else {
                $.toast({
                    heading: 'Başarısız!',
                    text: 'Günün izinli personellerini görebilmek için hafta görünümünden günü seçmelisiniz.',
                    position: 'top-right',
                    icon: 'warning',
                    hideAfter: 3500,
                    stack: 6
                });
            }
        });

        $this.downloadButton.on('click', function () {
            Loader.show();
            $("#DownloadAsImage").remove();
            html2canvas(document.getElementById('Shift')).then(function(canvas) {
                var img    = canvas.toDataURL("image/png");
                $("body").append('<a href="' + img + '" id="DownloadAsImage" download></a>');
                setTimeout(function () {
                    Loader.hide();
                    document.getElementById("DownloadAsImage").click();
                }, 300);
            });
        });

    });
};

Shift.prototype.showOffUsers = function (date) {
    var $this = this;
    this.showLoader();
    $this.offUsersModal.find('ul').html('');
    $.post(this.url + 'getOffUsers/' + this.getRoleId(), {_token: getToken(), date: date}, function (response) {
        $this.offUsersModal.find('.modal-title span.date').html(date);
        if (response['type'] === 'success') {
            if (Object.keys(response['offUsers']).length > 0) {
                Object.keys(response['offUsers']).forEach(function (key) {
                    var user = response['offUsers'][key];
                    $this.offUsersModal.find('ul').append('<li>' + user.name + '</li>');
                });
            } else {
                $this.offUsersModal.find('ul').append('<li>İzinli kullanıcı mevcut değil.</li>');
            }
            $this.hideLoader();
            $this.offUsersModal.modal('show');
        }
    });
};

Shift.prototype.getRoleId = function () {
    return this.modal.find('textarea[name=roleid]').val();
};

Shift.prototype.addShift = function (date) {
    var arr = date.split(' ');
    var arr2 = arr[0].split('-');
    var d = new Date(arr2[2] + '-' + arr2[1] + '-' + arr2[0]);
    var today = new Date();

    if (d.getTime() > today.getTime()) {
        var $this = this;
        this.showLoader();
        $.post(this.url + 'addShift', {_token: getToken()}, function (response) {
            $this.hideLoader();
            if (response && response['type']) {
                if (response['type'] === 'success') {
                    if (date) {
                        response.shift.startdate = date;
                    }
                    $this.initModal(response.shift);

                } else {
                    swal("Başarısız!", "Mesai ekleme/düzenleme işlemi gerçekleştirilirken hata meydana geldi.", "error");
                }
            } else {
                swal("Başarısız!", "Mesai ekleme/düzenleme işlemi gerçekleştirilirken hata meydana geldi.", "error");
            }
        });
    } else {
        $.toast({
            heading: 'Başarısız!',
            text: 'Sadece ileri tarihlere mesai ekleyebilirsiniz.',
            position: 'top-right',
            icon: 'warning',
            hideAfter: 3500,
            stack: 6
        });
    }
};

Shift.prototype.deleteShift = function () {
    var $this = this;
    var cnf = confirm('Bu mesaiyi silmek istediğinize emin misiniz ?');
    if (cnf) {
        this.showLoader();
        var shiftId = this.modal.find("input[name=id]").val();
        $.post(this.url + 'deleteShift', {_token: getToken(), id: shiftId}, function (response) {
            $this.hideLoader();
            if (response && response.type) {
                if (response['type'] === 'success') {
                    $.toast({
                        heading: 'Başarılı!',
                        text: 'Silme işlemi başarıyla gerçekleşti',
                        position: 'top-right',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                    $this.afterDelete(response.id);
                } else {
                    swal("Başarısız!", "Mesai silme işlemi gerçekleştirilirken hata meydana geldi.", "error");
                }
            } else {
                swal("Başarısız!", "Mesai silme işlemi gerçekleştirilirken hata meydana geldi.", "error");
            }
        })
    }
};

Shift.prototype.afterDelete = function (id) {
    this.modal.modal('hide');
    this.shiftObj.fullCalendar('removeEvents', id);
};

Shift.prototype.save = function () {

    var $this = this;
    var userIds = this.modal.find("select").val();
    var id = this.modal.find("input[name='id']").val();
    var startDate = this.modal.find("input[name='startdate']").val();
    var workTime = this.modal.find("input[name='worktime']").val();
    var breakTime = this.modal.find("input[name='breaktime']").val();
    var roleid = this.modal.find("textarea[name='roleid']").val();

    if (userIds && id && startDate && workTime && breakTime && roleid) {
        this.showLoader();
        $.post(this.url + 'saveShift/' + id, {
            _token: getToken(),
            startdate: startDate,
            worktime: workTime,
            breaktime: breakTime,
            userIds: userIds,
            roleid: roleid
        }, function (response) {
            $this.hideLoader();
            if (response) {
                if (response['type'] === 'success') {
                    $.toast({
                        heading: 'Başarılı!',
                        text: 'Ekleme/Düzenleme işlemi başarıyla gerçekleşti',
                        position: 'top-right',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                    $this.afterSave(response.shift);
                } else {
                    $.toast({
                        heading: 'Başarısız!',
                        text: response.message,
                        position: 'top-right',
                        icon: 'warning',
                        hideAfter: 3500,
                        stack: 6
                    });
                }
            }
        });
    } else {
        return false;
    }
};

Shift.prototype.initModal = function (shift, users) {
    this.clearModal();
    if (users) {
        this.removeButton.show();
        this.modal.find("input[name='startdate']").val(this.timestampToDate(shift.startdate));
        this.modal.find("select").val(users).trigger('change');
        this.modal.find("input[name='worktime']").val(shift.worktime);
        this.modal.find("input[name='breaktime']").val(shift.breaktime);
    } else {
        this.modal.find("input[name='startdate']").val(shift.startdate);
    }

    if(shift.cantEdit) {
        $(".edit-action").hide();
    } else {
        $(".edit-action").show();
    }
    this.modal.find("input[name='id']").val(shift.id);
    this.modal.modal('show');
};

Shift.prototype.afterSave = function (shift) {
    this.addEvent(shift);
    this.clearCheckedUsers();
    this.clearModal();
    this.modal.modal('hide');
};

Shift.prototype.addEvent = function (shift) {
    this.shiftObj.fullCalendar('removeEvents', shift.id);
    this.shiftObj.fullCalendar('renderEvent', shift, true);
};

Shift.prototype.clearModal = function () {
    this.removeButton.hide();
    this.modal.find('input[type=hidden]').val('');
    this.modal.find("select").val('').trigger('change');

    var breakTime = this.modal.find("input[name='breaktime']");
    var workTime = this.modal.find("input[name='worktime']");

    breakTime.val(breakTime.attr('data-value'));
    workTime.val(workTime.attr('data-value'));
};

Shift.prototype.clearCheckedUsers = function () {
    this.userLink.find('input:checked').iCheck('uncheck');
};

Shift.prototype.getCheckedUsers = function () {
    var arr = [];
    for (var i = 0; i < $(".shift-users input:checked").length; i++) {
        arr.push($(".shift-users input:checked").eq(i).attr('value'));
    }
    return arr;
};

Shift.prototype.hasCheckedUser = function () {
    if (this.userLink.find('input:checked').length > 0) {
        return true;
    } else {
        return false;
    }
};

Shift.prototype.initForms = function () {
    var $this = this;

    this.modal.find('input[name=startdate]').datetimepicker({
        "format": 'DD-MM-YYYY H:mm',
        minDate: moment()
    });

    var defaultEvents = JSON.parse($("#Events").val());

    this.shiftObj = this.shift.fullCalendar({
        lang: 'tr',
        events: defaultEvents,
        displayEventTime: false,
        header: {
            right: 'next',
            center: 'today',
            left: 'prev'
        },
        defaultView: 'basicWeek',
        eventClick: function (event) {
            $this.edit(event.id);
        },
        dayClick: function (moment) {
            var date = moment.format('DD-MM-YYYY 00:00');
            $this.addShift(date);
        }
    });
};

Shift.prototype.edit = function (id) {
    var $this = this;
    this.showLoader();
    $.post(this.url + 'getShift/' + id, {_token: getToken()}, function (response) {
        $this.hideLoader();
        $this.initModal(response.shift, response.users);
    });
};

Shift.prototype.init = function () {
    this.initForms();
    this.setListeners();
};

Shift.prototype.timestampToDate = function (timestamp) {
    var arr = timestamp.split(' ');
    var date = arr[0].split('-');
    return date[2] + '-' + date[1] + '-' + date[0] + ' ' + arr[1];
};

var shift = new Shift();
shift.init();