/**
 * @param t
 * @param search_form_selector
 */
var change_records_per_page = function (t, search_form_selector) {
    var per_page = $(search_form_selector).find('input[name="per-page"]');
    if (per_page.length) {
        per_page.val(t.val());
    } else {
        $(search_form_selector).append('<input type="hidden" name="per-page" value="' + t.val() + '" >');
    }
    $(search_form_selector).submit();
};

$(document).on('click', '.paginate_button.next', function () {
    $(this).siblings()[0].click()
});

$(document).on('click', '.paginate_button.previous', function () {
    $(this).siblings()[0].click()
});


$('body').on('click', '.id-lang', function (e) {
    // alert('test');
    e.preventDefault();

    $.ajax({
        type: 'GET',
        url: $(this).attr('href'),
        success: function () {
            window.location.reload();
        }
    });
});


$(document).on('click', '#delete', function (event) {
    var url = event.currentTarget.dataset['url'];
    var message = event.currentTarget.dataset['message'];
    console.log(url);
    event.preventDefault();
    swal({
        text: message,
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then(function (isConfirm) {
        if (isConfirm) {
            $.post(url);
        }
        else {
            return false;
        }
    })
});

var path = window.location.href; // because the 'href' property of the DOM element is the absolute path

$('li a').each(function () {
    if (path.indexOf($(this).attr('data-href')) >= 0) {
        $(this).addClass('active');
        $(this).parent().addClass('active');
    }
});
$(document).on('click', '.priority_btns', function () {
    var op = $('#multiselect_to_1 option:selected');

    if (op.length) {
        ($(this).val() == 'Up') ?
            op.first().prev().before(op) :
            op.last().next().after(op);
    }
});

$(document).on('click', '.priority_video_btns', function () {
    var op = $('#multiselect_to_3 option:selected');

    if (op.length) {
        ($(this).val() == 'Up') ?
            op.first().prev().before(op) :
            op.last().next().after(op);
    }
});

/**
 * Match a keyboard pressed character against regex which will only allow alphanumeric and asterik characters
 * @param event
 * @return {Boolean}       true if a pressed character matches regex
 */
function isAlphaNumAstValue(event) {
    var regex = new RegExp("^[a-zA-Z0-9\*\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

function isNumAstValue(event) {
    // $('input[name="outbound_extension[]"]').mask('(000) 000-0000');
    var regex = new RegExp("^[+0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

/** Method isNumberKey()
 *
 * Allow to enter only numeric value in input
 *
 * @param evt object
 * @return boolean
 */
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    var nAgt = navigator.userAgent;
    if ((verOffset = nAgt.indexOf("Firefox")) != -1) {
        return !(charCode > 39 && (charCode < 48 || charCode > 57));
    } else {
        return !(charCode > 31 && (charCode < 48 || charCode > 57));
    }

}

function isExitNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 42 && charCode != 35)
        return false;

    return true;
}



/** Password strength meter **/
$.fn.passwordStrength = function (options) {
    return this.each(function () {
        var that = this;
        that.opts = {};
        that.opts = $.extend({}, $.fn.passwordStrength.defaults, options);

        that.div = $(that.opts.targetDiv);
        that.defaultClass = that.div.attr('class');

        that.percents = (that.opts.classes.length) ? 100 / that.opts.classes.length : 100;

        v = $(this)
            .keyup(function () {
                if (typeof el == "undefined")
                    this.el = $(this);
                var s = getPasswordStrength(this.value);
                var p = this.percents;
                var t = Math.floor(s / p);

                if (100 <= s)
                    t = this.opts.classes.length - 1;

                this.div
                    .removeAttr('class')
                    .addClass(this.defaultClass)
                    .addClass(this.opts.classes[t]);

            });

        /*    .after('<a href="#">Generate Password</a>')
            .next()
            .click(function () {
                $(this).prev().val(randomPassword()).trigger('keyup');
                return false;
            });*/
    });

    function getPasswordStrength(H) {
        var D = (H.length);
        if (D > 5) {
            D = 5
        }
        var F = H.replace(/[0-9]/g, "");
        var G = (H.length - F.length);
        if (G > 3) {
            G = 3
        }
        var A = H.replace(/\W/g, "");
        var C = (H.length - A.length);
        if (C > 3) {
            C = 3
        }
        var B = H.replace(/[A-Z]/g, "");
        var I = (H.length - B.length);
        if (I > 3) {
            I = 3
        }
        var E = ((D * 10) - 20) + (G * 10) + (C * 15) + (I * 10);
        if (E < 0) {
            E = 0
        }
        if (E > 100) {
            E = 100
        }
        return E
    }

    function randomPassword() {
        var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$_+";
        var size = 10;
        var i = 1;
        var ret = ""
        while (i <= size) {
            $max = chars.length - 1;
            $num = Math.floor(Math.random() * $max);
            $temp = chars.substr($num, 1);
            ret += $temp;
            i++;
        }
        return ret;
    }

};
function checkCount(totalData, errorMessage) {
    if (totalData <= 0) {
        alert(errorMessage);
        return false;
    }
}

$.fn.passwordStrength.defaults = {
    classes: Array('is10', 'is20', 'is30', 'is40', 'is50', 'is60', 'is70', 'is80', 'is90', 'is100'),
    targetDiv: '#passwordStrengthDiv',
    cache: {}
}
$(document).ready(function () {
    $('select').select2({width: "100%"});
    $('.multiselect').select2('destroy');
    $('#change-records-per-page').select2('destroy');
    $('#change-records-per-page').formSelect();
    $('input[type="password"]').passwordStrength();
    $('input[type="password"]').trigger('keyup');
    $('#web_password').passwordStrength({targetDiv: '#passwordStrengthDiv'});
    $('#web_password').trigger('keyup');
    $('#sip_password').passwordStrength({targetDiv: '#passwordStrengthDiv1'});
    $('#sip_password').trigger('keyup');

    $('#vm_password').passwordStrength({targetDiv: '#passwordStrengthDiv2'});
    $('#vm_password').trigger('keyup');

});
