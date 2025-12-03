// Generate a password string
function randString(id) {
    var dataSet = $(id).attr('data-character-set').split(',');
    var text = '';
    var possible = '';

    if ($.inArray('a-z', dataSet) >= 0) {
        possible = 'abcdefghijklmnopqrstuvwxyz';
        for (var i = 0; i < 3; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
    }
    if ($.inArray('A-Z', dataSet) >= 0) {
        possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for (var i = 0; i < 3; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
    }
    if ($.inArray('0-9', dataSet) >= 0) {
        possible = '0123456789';
        for (var i = 0; i < 3; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
    }
    if ($.inArray('#', dataSet) >= 0) {
        possible = '%&*$#@';
        for (var i = 0; i < 3; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
    }
    if ($.inArray('@', dataSet) >= 0) {
        possible = '_';
        text += possible.charAt(Math.floor(Math.random() * possible.length));

        possible = 'abcdefghijklmnopqrstuvwxyz';
        for (var i = 0; i < 2; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
    }
    return text.split('').sort(function () {
        return 0.5 - Math.random()
    }).join('');
}

$('.getNewPass').click(function () {
    var field = $(this).closest('div').find('input[rel="gp"]');
    field.val(randString(field));
    $(this).parent().parent().find('input').trigger('keyup');
});

$('.togglePassword').click(function () {
    var field = $(this).closest('div').find('input[rel="gp"]');

    if (field.attr('type') === 'text') {
        field.attr('type', 'password');
    } else {
        field.attr('type', 'text');
    }
});

window.onload = function () {
    $('.getNewPass').click();
}
