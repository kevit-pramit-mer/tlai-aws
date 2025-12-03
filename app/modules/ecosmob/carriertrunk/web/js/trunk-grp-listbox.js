/**
 * Created by akshay on 28/7/17.
 */
(function (factory) {
    'use strict';
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("ecosmob.switchInput", {
        _create: function () {
            var self = this;
            //on page load events
            $.proxy(self.left_switch, self)();
            $.proxy(self.right_switch, self)();
            $.proxy(self.up_switch, self)();
            $.proxy(self.down_switch, self)();
        },
        // Left button to move trunks from lstbox2 to lstbox1
        left_switch: function () {
            $(document).on('click', '#btnLeft', function (e) {
                var trunks = [];
                var trunk_list = $("#lstBox3").val().split(',');
                var ind;
                $.each($("#lstBox2 option:selected"), function () {
                    trunks.push($(this).val());
                    ind = trunk_list.indexOf($(this).val());
                    if (ind != -1) {
                        trunk_list.splice(ind, 1); // The second parameter is the number of elements to remove.
                    }
                    $(this).remove().appendTo('#lstBox1');
                });
                trunk_list = trunk_list.join(',');
                $("#lstBox1 option:selected").removeAttr("selected");
                $("#lstBox3").val(trunk_list);
                e.preventDefault();
            });
        }
        ,
        // Right button to move trunks from  lstbox1 to lstbox2
        right_switch: function () {
            $(document).on('click', '#btnRight', function (e) {
                var trunks = [];
                $.each($("#lstBox1 option:selected"), function () {
                    trunks.push($(this).val());
                    $(this).remove().appendTo('#lstBox2');
                });
                var trunk_list = $("#lstBox3").val();
                if (trunk_list) {
                    trunk_list = trunk_list.split(',');
                    trunks = $.merge(trunk_list, trunks);
                }
                trunk_list = trunks.join(',');
                $("#lstBox2 option:selected").removeAttr("selected");
                $("#lstBox3").val(trunk_list);
                e.preventDefault();
            });

        }
        ,
        // Up button to move trunks Priority up
        up_switch: function () {
            $(document).on('click', '#btnUp', function (e) {
                $('#lstBox2 option:selected:first-child').prop("selected", false);
                var before = $('#lstBox2 option:selected:first').prev();
                $('#lstBox2 option:selected').detach().insertBefore(before);

                var trunk_list = [];
                $('#lstBox2 option').each(function () {
                    trunk_list.push($(this).attr('value'));
                });
                trunk_list = trunk_list.join(',');
                $("#lstBox3").val(trunk_list);
                e.preventDefault();
            });

        }
        ,
        // Down button to move trunks Priority Down
        down_switch: function () {
            $(document).on('click', '#btnDown', function (e) {
                $('#lstBox2 option:selected:last-child').prop("selected", false);
                var after = $('#lstBox2 option:selected:last').next();
                $('#lstBox2 option:selected').detach().insertAfter(after);

                var trunk_list = [];
                $('#lstBox2 option').each(function () {
                    trunk_list.push($(this).attr('value'));
                });
                trunk_list = trunk_list.join(',');
                $("#lstBox3").val(trunk_list);
                e.preventDefault();
            });

        },
    });
}));

$(document).ready(function () {
    // Div id of form
    $("#trunk-group-form").switchInput();
});

