$(document).ready(function () {

    $('.a_tk_ip_cont').css('display', 'none');
    $(document).on('click', 'a.fa-minus-circle', function (e) {
        if ($('div.a_tk_ip').find('a.fa-minus-circle').length === 1) {
            $(".tk_ip_main_cont .add_ss").last().hide();
            $('#tk_ip_main_cont').append('<div class="form-group" id="buttonAdd"> <div class="row"> <div class=" col-sm-3 col-lg-2 col-md-3 main_plus_button"> <div class="col-xs-12 "> <a href="javascript:void(0);"class="btn btn-primary btn-sm add_ss fa fa-plus-circle"onclick="add_tk_ip($(this))"> <i class="entypo-plus"></i> </a> </div> </div> </div> </div>');
        }
    });
});

var add_tk_ip = function (t) {
    $('.tk_ip_main_cont').append($('.a_tk_ip_cont').html());
    t.hide();
};
var rem_tk_ip = function (t) {
    t.closest('.a_tk_ip').remove();
    $(".tk_ip_main_cont .add_ss").last().show();
};
