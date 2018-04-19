$(document).ready(function () {
    $("form").on('submit', function (e) {
        if(!$("select#RoleId").val()) {
            e.preventDefault();
            $.toast({
                heading: 'Başarısız!',
                text: 'Kullanıcının rolünü seçmelisiniz.',
                position: 'top-right',
                icon: 'warning',
                hideAfter: 3500,
                stack: 6
            });
        }
    });
});