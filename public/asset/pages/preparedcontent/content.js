var Content = function () {
    this.modal = $("#ContentModal");
    this.add = function () {
        var self = this;
        $.post(getDomain() + 'preparedContents/add', {_token: getToken()}, function (response) {
            if (response['type'] && response['type'] == 'success') {
                response['content']['active'] = '1';
                self.edit(response['content']);
            } else {
                swal("Başarısız!", "İçerik ekleme işlemi gerçekleştirilirken hata meydana geldi.", "error")
            }
        });
    };
    this.edit = function (content) {
        this.clearModal();
        var thisContent;
        if (typeof content === 'number') {
            var self = this;
            $.ajaxSetup({async: false});
            $.post(getDomain() + 'preparedContents/get/' + content, {_token: getToken()}, function (response) {
                if (response['type'] && response['type'] == 'success') {
                    thisContent = response['content'];
                } else {
                    swal("Başarısız!", "Rol ekleme/düzenleme işlemi gerçekleştirilirken hata meydana geldi.", "error")
                }
            });
            $.ajaxSetup({async: true});
        } else {
            thisContent = content;
        }

        this.modal.find("form input[name='active'][value='" + thisContent['active'] + "']").iCheck('check');
        this.modal.find("form input[name='type'][value='" + thisContent['type'] + "']").iCheck('check');
        this.modal.find("form input[name='name']").val(thisContent['name']);
        this.modal.find("form input[name='id']").val(thisContent['id']);
        this.modal.find("form input[name='letter']").val(thisContent['letter']);
        this.modal.find("form input[name='number']").val(thisContent['number']);
        if (thisContent['type'] != 'head') {
            setTimeout(function () {
                CKEDITOR.instances['Text'].setData(thisContent['content']);
            }, 100);
        }
        if (!thisContent['status'] || thisContent['status'] == '0') {
            this.modal.find("#Save").attr('onclick', "content.save('add')");
        } else {
            this.modal.find("#Save").attr('onclick', "content.save('edit')");
        }
        this.modal.modal('show');
    };
    this.save = function (typ) {
        if (!typ) {
            typ = 'edit';
        }
        var id = this.modal.find("form input[name='id']").val();
        var name = this.modal.find("form input[name='name']").val();
        var type = this.modal.find("form input[name='type']:checked").val();
        var active = this.modal.find("form input[name='active']:checked").val();
        var content = CKEDITOR.instances['Text'].getData();
        var letter = this.modal.find("form input[name='letter']").val();
        var number = this.modal.find("form input[name='number']").val();
        var self = this;
        $.post(getDomain() + 'preparedContents/update/' + id, {
            _token: getToken(),
            active: active,
            type: type,
            name: name,
            content: content,
            letter:letter,
            number:number
        }, function (response) {
            if (response['type'] && response['type'] == 'success') {
                if (typ == 'add') {
                    var elem = '<li class="dd-item dd3-item" data-id="' + id + '"><div class="dd-handle dd3-handle"></div><div class="dd3-content"><div class="row"><div class="col-sm-8">' + name;
                    if(letter) {
                        elem += '<code>(' + letter + ' + ' + number + ')</code>';
                    }
                    elem += '</div><div class="col-sm-4 text-right"><a title="Düzenle" onclick="javascript:content.edit(' + id + ')" class="btn btn-sm btn-info"><i class="fa fa-pencil-square-o"></i></a><a title="Sil" onclick="javascript:content.delete(' + id + ')" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a></div></div></div></li>';
                    $("#nestable > ol").append(elem);
                    self.hideNoContent();
                    $("#nestable").trigger('change');
                } else {
                    $("li.dd-item[data-id='" + id + "'] .dd3-content .col-sm-8").html(name);
                }
                self.clearModal();
                self.modal.modal('hide');
                $.toast({
                    heading: 'Başarılı!',
                    text: 'İçerik ekleme/düzenleme işlemi başarıyla gerçekleşti.',
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
                swal("Başarısız!", "İçerik ekleme/düzenleme işlemi gerçekleştirilirken hata meydana geldi.", "error");
            }
        });
    };
    this.delete = function (id) {
        var cnf = confirm('Bu içeriği Silmek istediğinize emin misiniz ?');
        if (cnf) {
            $.post(getDomain() + 'preparedContents/delete/' + id, {_token: getToken()}, function (response) {
                if (response['type'] && response['type'] == 'success') {
                    var subContents = $("li[data-id='" + id + "'").find(' > ol.dd-list');
                    if(subContents.length > 0) {
                        $("div#nestable > ol").append(subContents.html());
                    }
                    $("li[data-id='" + id + "'").remove();
                    $.toast({
                        heading: 'Başarılı!',
                        text: 'Silme işlemi başarıyla gerçekleşti.',
                        position: 'top-right',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                } else {
                    swal("Başarısız!", "İçerik silme işlemi gerçekleştirilirken hata meydana geldi.", "error");
                }
            });
        }
    };
    this.clearModal = function () {
        this.modal.find("form input[type='text']").val('');
        this.modal.find("form input[type='radio']").removeAttr("checked");
        CKEDITOR.instances['Text'].setData('');
    };
    this.showNoContent = function () {
        if ($("#nestable > ol > li").length < 1) {
            $("button[type=submit]").hide();
            $("#NoContent").show();
        }
    };
    this.hideNoContent = function () {
        if ($("#nestable > ol > li").length > 0) {
            $("button[type=submit]").show();
            $("#NoContent").hide();
        }
    };
    this.initModal = function () {
        var self = this;
        this.modal.find(".type-radio").on("ifChecked", function () {
            var val = $(this).val();
            if (val == 'head') {
                self.modal.find('.form-group.content').hide();
            } else {
                self.modal.find('.form-group.content').show();
            }
        });
    };
};

var content = new Content();

$(document).ready(function () {
    content.showNoContent();
    content.initModal();
});