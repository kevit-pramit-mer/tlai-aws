/**
 * Created by akshay on 15/9/17.
 */

$(document).ready(function () {
    if (window.location.pathname === '/admin/admin/update-profile') {
        $('#updateProfile').addClass('active-class');
    }
    if (window.location.pathname === '/admin/admin/change-password') {
        $('#changePassword').addClass('active-class');
    }

    $.mask.definitions['#'] = $.mask.definitions['9'];
    $.mask.definitions['+'] = null;
    $.mask.definitions['9'] = null;
    $.mask.definitions['8'] = null;
    $('#adminmaster-adm_contact').mask('(+98) 9## ### ## ##');
});
