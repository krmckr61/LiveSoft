var Role = function () {
    this.modal = $("#RoleModal");
    this.add = function () {
        var self = this;
        $.post(getDomain() + 'roles/add', {_token: getToken()}, function (response) {
            if (response['type'] && response['type'] == 'success') {
                response['role']['active'] = '1';
                self.edit(response['role']);
            } else {
                swal("Başarısız!", "Rol ekleme işlemi gerçekleştirilirken hata meydana geldi.", "error")
            }
        });
    };
    this.edit = function (role) {
        this.clearModal();
        var thisRole;
        if (typeof role === 'number') {
            var self = this;
            $.ajaxSetup({async: false});
            $.post(getDomain() + 'roles/get/' + role, {_token: getToken()}, function (response) {
                if (response['type'] && response['type'] == 'success') {
                    thisRole = response['role'];
                } else {
                    swal("Başarısız!", "Rol ekleme/düzenleme işlemi gerçekleştirilirken hata meydana geldi.", "error")
                }
            });
            $.ajaxSetup({async: true});
        } else {
            thisRole = role;
        }

        this.modal.find("form input[name='active'][value='" + thisRole['active'] + "']").iCheck('check');
        this.modal.find("form input[name='display_name']").val(thisRole['display_name']);
        this.modal.find("form input[name='id']").val(thisRole['id']);
        if(!thisRole['status'] || thisRole['status'] == '0') {
            this.modal.find("#Save").attr('onclick', "role.save('add')");
        } else {
            this.modal.find("#Save").attr('onclick', "role.save('edit')");
        }
        this.modal.modal('show');
    };
    this.save = function (type) {
        if(!type) {
            type = 'edit';
        }
        var id = this.modal.find("form input[name='id']").val();
        var displayName = this.modal.find("form input[name='display_name']").val();
        var active = this.modal.find("form input[name='active']:checked").val();
        var self = this;
        $.post(getDomain() + 'roles/update/' + id, {
            _token: getToken(),
            active: active,
            display_name: displayName
        }, function (response) {
            if (response['type'] && response['type'] == 'success') {
                if(type == 'add') {
                    var elem = '<li class="dd-item dd3-item" data-id="' + id + '"><div class="dd-handle dd3-handle"></div><div class="dd3-content"><div class="row"><div class="col-sm-8">' + displayName + '</div><div class="col-sm-4 text-right"><a title="Düzenle" onclick="javascript:role.edit(' + id + ')" class="btn btn-sm btn-info"><i class="fa fa-pencil-square-o"></i></a><a title="Sil" onclick="javascript:role.delete(' + id + ')" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a></div></div></div></li>';
                    $("#nestable > ol").append(elem);
                    self.hideNoRole();
                    $("#nestable").trigger('change');
                } else {
                    $("li.dd-item[data-id='" + id +"'] .dd3-content .col-sm-8").html(displayName);
                }
                self.clearModal();
                self.modal.modal('hide');
                $.toast({
                    heading: 'Başarılı!',
                    text: 'Rol ekleme/düzenleme işlemi başarıyla gerçekleşti.',
                    position: 'top-right',
                    icon: 'success',
                    hideAfter: 3500,
                    stack: 6
                });
            } else if (response['type'] && response['type'] == 'warning') {
                $.toast({
                    heading: 'Başarısız!',
                    text: response['message'],
                    position: 'top-right',
                    icon: 'warning',
                    hideAfter: 3500,
                    stack: 6
                });
            } else {
                swal("Başarısız!", "Rol ekleme/düzenleme işlemi gerçekleştirilirken hata meydana geldi.", "error");
            }
        });
    };
    this.delete = function (id) {
        var cnf = confirm('Bu rolü silerseniz bu rolün altındaki bütün kullanıcıların bu role ait yetkilerini kaldırmış olacaksiniz. Silmek istediğinize emin misiniz ?');
        if(cnf) {
            $.post(getDomain() + 'roles/delete/' + id, {_token: getToken()}, function (response) {
                if (response['type'] && response['type'] == 'success') {
                    $("li[data-id='"+ id +"'").remove();
                    $.toast({
                        heading: 'Başarılı!',
                        text: 'Silme işlemi başarıyla gerçekleşti.',
                        position: 'top-right',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                } else {
                    swal("Başarısız!", "Rol silme işlemi gerçekleştirilirken hata meydana geldi.", "error");
                }
            });
        }
    };
    this.clearModal = function () {
        this.modal.find("form input[type='text']").val('');
        this.modal.find("form input[type='radio']").removeAttr("checked");
    };
    this.showNoRole = function () {
        if ($("#nestable > ol > li").length < 1) {
            $("#NoRole").show();
        }
    };
    this.hideNoRole = function () {
        if ($("#nestable > ol > li").length > 0) {
            $("#NoRole").hide();
        }
    };
};

var role = new Role();

$(document).ready(function () {
    role.showNoRole();
});