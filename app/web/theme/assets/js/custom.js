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

$(document).on('pjax:error', function(event, xhr, textStatus, errorThrown, options) {
    console.log(xhr);
    console.log(textStatus);
    console.log(errorThrown);
});
$(document).on('pjax:success', function(event) {
    $('select').select2({width: "100%"})
    $('.multiselect').select2('destroy');
    $('#change-records-per-page').select2('destroy');
    $('#change-records-per-page').formSelect();
    $('.collapsible').collapsible();
    // $('.select-wrapper input.select-dropdown').css('display','block');
    $( ".datepicker" ).each(function( index ) {
        if($( this ).val() != "") { $( this ).datepicker( {"format":'yyyy-mm-dd'} ).datepicker("setDate", $( this ).val()); }
        else { $( this ).datepicker( {"format":'yyyy-mm-dd'} ).datepicker("setDate", new Date()); }
    });
    $('#page-length-option').DataTable({
        "responsive": false,
        paging: false,
        searching: false,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "info": false,
        scrollY: '70vh',
        scrollX: '100%',
        scrollCollapse: true,
        dom: '<"top"i>rt<"bottom"flp><"clear">',
        "oLanguage": {
            "sSearch": hs_custom_search,
            "sZeroRecords" : hs_custom_no_matching_records_found,
        },
        "aaSorting": [],
        columnDefs: [ {
            'targets': [0], /* column index [0,1,2,3]*/
            'orderable': false, /* true or false */
        }],
    });
    M.updateTextFields();
    flatpickr('.date-picker', {
        dateFormat: "Y-m-d",
        enableTime: false,
        shorthandCurrentMonth: true,
        monthSelectorType: 'static',
        plugins: [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            }),
        ],

    });
    flatpickr('.date-time-picker', {
        // put options here if your don't want to add them via data- attributes
        dateFormat: "Y-m-d H:i:S",
        minuteIncrement: 1,
        enableTime: true,
        enableSeconds: true,
        shorthandCurrentMonth: true,
        monthSelectorType: 'static',
        plugins: [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            }),
        ],

    });
    flatpickr('.from-date-time-range', {
        dateFormat: "Y-m-d H:i:S",
        enableTime: true,
        minuteIncrement: 1,
        enableSeconds: true,
        monthSelectorType: 'static',
        maxDate: new Date(),
        "plugins": [
            new rangePlugin({input: ".to-date-time-range"}),
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    flatpickr('.from-date-range', {
        dateFormat: "Y-m-d",
        enableTime: false,
        monthSelectorType: 'static',
        maxDate: new Date(),
        "plugins": [
            new rangePlugin({input: ".to-date-range"}),
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    flatpickr('.from-time, .to-time', {
        dateFormat: "H:i:S",
        enableTime: true,
        noCalendar: true,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    flatpickr('.from-time-format, .to-time-format', {
        dateFormat: "H:i:S",
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        minuteIncrement: 1,
        enableSeconds: true,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    $('.flatpickr-time input.flatpickr-hour, .flatpickr-time input.flatpickr-minute, .flatpickr-time input.flatpickr-second').attr('readonly', true);
})
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

/* $('li a').each(function () {
    if (path.indexOf($(this).attr('data-href')) >= 0) {
        $(this).addClass('active');
        $(this).parent().addClass('active');
    }
}); */
$('li a').each(function () {

    if(path != "")
    {
        if(path.includes("="))
        {
            var temp_path = path.split("=");
            if(Array.isArray(temp_path))
            {
                if(temp_path[1].includes("%2F"))
                {
                    var temp_path1 = temp_path[1].split("%2F");
                    if(Array.isArray(temp_path))
                    {
                        //console.log(temp_path1);
                        if(("cdr" == temp_path1[1]) && ("cdr" == temp_path1[0]) && ($(this).attr('data-href') == "cdr"))
                        {
                            //console.log("1 temp_path1[1]: "+ temp_path1[1] + " temp_path1[0]: " + temp_path1[0]);
                            $(this).addClass('active');
                            $(this).parent().addClass('active');
                            return true;
                        }
                        /* else if(("iptable" == temp_path1[1]) && ("fail2ban" == temp_path1[0]) && ($(this).attr('data-href') == "fail2ban"))
                        {
                            console.log("ABCD");
                            console.log(temp_path1[1]);
                            console.log(temp_path1[0]);
                            //console.log("1 temp_path1[1]: "+ temp_path1[1] + " temp_path1[0]: " + temp_path1[0]);
                            $(this).addClass('active');
                            $(this).parent().addClass('active');

                            //$(".iptable-child").removeClass('active');
                            //$(".fail2ban-child").addClass('active');
                            //$(".fail2ban-child").parent().addClass('active');
                            return true;
                        } */
                        else if(("cdr" == temp_path1[1]) && ("extensionsummaryreport" == temp_path1[0]) && ($(this).attr('data-href') == "extensionsummaryreport"))
                        {
                            //console.log("2 temp_path1[1]: "+ temp_path1[1] + " temp_path1[0]: " + temp_path1[0]);
                            $(this).addClass('active');
                            $(this).parent().addClass('active');
                            return true;
                        }
                        else if((($(this).attr('data-href') == temp_path1[1]) || ($(this).attr('data-href') == temp_path1[0])) && ($(this).attr('data-href') != "cdr") && ($(this).attr('data-href') != "extensionsummaryreport") /*&& ($(this).attr('data-href') != "fail2ban") */ )
                        {
                            //console.log("3 temp_path1[1]: "+ temp_path1[1] + " temp_path1[0]: " + temp_path1[0]);
                            $(this).addClass('active');
                            $(this).parent().addClass('active');
                        }
                    }
                }
            }
        }
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

function isNumberKeyWithPlus(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    var nAgt = navigator.userAgent;


    if(charCode == "43")
        return true

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


function isExitNumberKeyHcustom(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;

    //if (charCode > 31 && (charCode < 48 || charCode > 57))
    if (charCode >= 48 && charCode <= 57)
        return true;

    return false;
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
    $('.datepicker-modal').modal({dismissible: false});
    $('select').select2({width: "100%"});
    $('.multiselect').select2('destroy');
    $('#change-records-per-page').select2('destroy');
    $('#change-records-per-page').formSelect();
    $('input[type="password"]').passwordStrength();
    $('input[type="password"]').trigger('keyup');
    $('#web_password').passwordStrength({targetDiv: '#passwordStrengthDiv3'});
    $('#web_password').trigger('keyup');
    $('#sip_password').passwordStrength({targetDiv: '#passwordStrengthDiv1'});
    $('#sip_password').trigger('keyup');

    $('#vm_password').passwordStrength({targetDiv: '#passwordStrengthDiv2'});
    $('#vm_password').trigger('keyup');

    //var DateField = MaterialDateTimePicker.create($('.datetime'))
    $('.datepicker').datepicker( { "format":'yyyy-mm-dd', autoClose: true});  $('button.btn-flat.datepicker-cancel.waves-effect, button.btn-flat.datepicker-done.waves-effect').remove();
    /* $('.date-time-picker').datetimepicker({
         format: 'YYYY-MM-DD HH:mm'
     });*/

    flatpickr('.date-picker', {
        dateFormat: "Y-m-d",
        enableTime: false,
        shorthandCurrentMonth: true,
        monthSelectorType: 'static',
        plugins: [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            }),
        ],

    });
    flatpickr('.date-time-picker', {
        // put options here if your don't want to add them via data- attributes
        dateFormat: "Y-m-d H:i:S",
        minuteIncrement: 1,
        enableTime: true,
        enableSeconds: true,
        shorthandCurrentMonth: true,
        monthSelectorType: 'static',
        plugins: [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            }),
        ],

    });
    const now = new Date();
    flatpickr('.from-date-time-range', {
        dateFormat: "Y-m-d H:i:S",
        enableTime: true,
        minuteIncrement: 1,
        enableSeconds: true,
        monthSelectorType: 'static',
        maxDate: new Date(),
        //defaultDate: [now.getFullYear()+'-'+now.getMonth()+1+'-'+now.getDate()+' 00:00:00', new Date()],
        "plugins": [
            new rangePlugin({input: ".to-date-time-range"}),
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    flatpickr('.from-date-range', {
        dateFormat: "Y-m-d",
        enableTime: false,
        monthSelectorType: 'static',
        maxDate: new Date(),
        "plugins": [
            new rangePlugin({input: ".to-date-range"}),
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    flatpickr('.from-time, .to-time', {
        dateFormat: "H:i:S",
        enableTime: true,
        noCalendar: true,
        minuteIncrement: 1,
        enableSeconds: true,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    flatpickr('.from-time-format, .to-time-format', {
        dateFormat: "H:i:S",
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        minuteIncrement: 1,
        enableSeconds: true,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    $('.flatpickr-time input.flatpickr-hour, .flatpickr-time input.flatpickr-minute, .flatpickr-time input.flatpickr-second').attr('readonly', true);
});
var MaterialDateTimePicker = {
    control: null,
    dateRange: null,
    pickerOptions: null,
    create: function (element) {
        this.control = element == undefined ? $('#' + localStorage.getItem('element')) : element;
        element = this.control;
        if (this.control.is("input[type='text']")) {
            var defaultDate = new Date();
            element.off('click');
            element.datepicker({
                format: 'yyyy-mm-dd',
                selectMonths: true,
                dismissable: false,
                autoClose: true,
                onClose: function () {
                    element.datepicker('destroy');
                    element.timepicker({
                        timeFormat: 'HH:mm:ss',
                        dismissable: false,
                        autoClose: true,
                        onSelect: function (hr, min) {
                            element.attr('selectedTime', (hr + ":" + min).toString());
                        },
                        onCloseEnd: function () {
                            element.blur();
                        }
                    });
                    $('button.btn-flat.timepicker-close.waves-effect, button.btn-flat.datepicker-done.waves-effect')[0].remove();

                    if (element.val() != "") {
                        element.attr('selectedDate', element.val().toString());
                    } else {
                        element.val(defaultDate.getFullYear().toString() + "-" + (defaultDate.getMonth() + 1).toString() + "-" + defaultDate.getDate().toString())
                        element.attr('selectedDate', element.val().toString());
                    }
                    element.timepicker('open');
                }
            });
            element.unbind('change');
            element.off('change');
            element.on('change', function () {
                if (element.val().indexOf(':') > -1) {
                    element.attr('selectedTime', element.val().toString());
                    var d = new Date(element.attr('selectedDate') + " " + element.attr('selectedTime'));
                    element.val(element.attr('selectedDate') + " " + (d.getHours()<10?'0':'') + d.getHours() + ':' + (d.getMinutes()<10?'0':'') + d.getMinutes());
                    element.timepicker('destroy');
                    element.unbind('click');
                    element.off('click');
                    element.on('click', function (e) {
                        element.val("");
                        element.removeAttr("selectedDate");
                        element.removeAttr("selectedTime");
                        localStorage.setItem('element', element.attr('id'));
                        MaterialDateTimePicker.create.call(element);
                        element.trigger('click');
                    });
                }
            });
            $('button.btn-flat.datepicker-cancel.waves-effect, button.btn-flat.datepicker-done.waves-effect').remove();
            /* this.addCSSRules();*/
            return element;
        } else {
            console.error("The HTML Control provided is not a valid Input Text type.")
        }
    },
    /* addCSSRules: function () {
         $('html > head').append($('<style>div.modal-overlay { pointer-events:none; }</style>'));
     },*/
}

document.addEventListener("DOMContentLoaded", function () {
    M.updateTextFields();
});

$("#extension_iframe").on("load", function() {
    if (localStorage.getItem("toggle") == 1) { // open
        $('.sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
        $('.custom-sidenav-trigger').text('radio_button_checked');
        $('#main').removeClass('main-full');
        $("#extension_iframe").contents().find("#main").removeClass("main-full");
        $('footer').removeClass('footer-full');
    } else { // close
        $('.sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
        $('.custom-sidenav-trigger').text('radio_button_unchecked');
        $('#main').addClass('main-full');
        $("#extension_iframe").contents().find("#main").addClass("main-full");
        $('footer').addClass('footer-full');
    }

});

function paste(obj)
{
    var totalCharacterCount = window.event.clipboardData.getData('text');
    var strValidChars = "0123456789";
    var strChar;
    var FilteredChars = "";
    for (i = 0; i < totalCharacterCount.length; i++) {
        strChar = totalCharacterCount.charAt(i);
        if (strValidChars.indexOf(strChar) != -1) {
            FilteredChars = FilteredChars + strChar;
        }
    }
    obj.value = FilteredChars;
    return false;
}

/*function updateLastInteractionTime() {
    var date = new Date();
    var lastInteractionTime = date.toLocaleTimeString();
    $.ajax({
        async: true,
        data: 'lastInteractionTime='+ lastInteractionTime,
        type: 'GET',
        url: baseURL + "index.php?r=crm/crm/idle-time",
        success: function (result) {
        }
    });
}
if(userType == 'agent') {
    window.addEventListener("click", updateLastInteractionTime);
    window.addEventListener("keypress", updateLastInteractionTime);
}*/

function debounce(func, wait) {
    let timeout;
    return function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, arguments), wait);
    };
}

const updateLastInteractionTime = debounce(function() {
    var date = new Date();
    var lastInteractionTime = date.toLocaleTimeString();
    $.ajax({
        async: true,
        data: 'lastInteractionTime='+ lastInteractionTime,
        type: 'GET',
        url: baseURL + "index.php?r=crm/crm/idle-time",
        success: function (result) {
        }
    });
}, 500); // Adjust the wait time as needed
if(userType == 'agent') {
    window.addEventListener("click", updateLastInteractionTime);
    window.addEventListener("keypress", updateLastInteractionTime);
}

function isValidPhoneNumber(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    var inputLength = evt.target.value.length;

    if (charCode === 8 || charCode === 9 || charCode === 13 || charCode === 27) {
        return true;
    }

    if (inputLength >= 16) {
        return false;
    }

    if ((charCode >= 48 && charCode <= 57) ||
        (charCode === 43 && inputLength === 0)) {
        return true;
    }

    return false;
}
