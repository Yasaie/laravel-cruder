var route = $('meta[name="route"]').attr('content');
var _token = $('meta[name="csrf-token"]').attr('content');


function deleteItem(id) {
    iziToast.question(Object.assign({}, iziToastConst, {
        close: false,
        overlay: true,
        displayMode: 'once',
        message: '<p>آیا مطمئن هستید؟</p><p>این عملیات غیر قابل بازگشت می‌باشد!</p>',
        position: 'center',
        buttons: [
            ['<button><b>بله</b></button>', function (instance, toast) {
                $.ajax({
                    url: route + '/' + id,
                    type: "POST",
                    data: {
                        _method: 'DELETE',
                        _token: _token
                    },
                    success: function (d) {
                        window.location.replace(route);
                    },
                    error: function (d) {
                        iziToast.error(Object.assign({}, iziToastConst, {
                            title: 'خطا',
                            message: d.responseJSON.message,
                        }));
                    }
                });
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            }, true],
            ['<button>خیر</button>', function (instance, toast) {
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            }],
        ],
    }));

}
