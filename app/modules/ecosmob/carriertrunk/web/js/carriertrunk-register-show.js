/**
 * Created by milan on 14/7/17.
 */
(function (factory) {
    'use strict';
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("ecosmob.placeControl", {
        _create: function () {
            var self = this;
            // $.proxy(self._registered, self)();

        },
        //  $(document).on('change', '#unconditional_switch', function (e) {
        /*_registered: function () {
            var self = this;
            $(document).on('change', self.unconditional_switch, function () {
                self.unconditional_switch = $('#trunkmaster-trunk_register', this.element);
                self.class_for_hide_row = $('.class_for_hide_row', this.element);
                self.trunkmaster_trunk_username = $('#trunkmaster-trunk_username', this.element);
                self.trunkmaster_trunk_password = $('#trunkmaster-trunk_password', this.element);

                switch (self.unconditional_switch.val()) {
                    case '1':
                        self.class_for_hide_row.css("display", "block");
                        self.trunkmaster_trunk_username.prop("disabled", false);
                        self.trunkmaster_trunk_password.prop("disabled", false);
                        break;
                    case '0':
                        self.class_for_hide_row.css("display", "none");
                        self.trunkmaster_trunk_username.prop("disabled", true);
                        self.trunkmaster_trunk_password.prop("disabled", true);
                        break;
                    default:
                        self.class_for_hide_row.css("display", "none");
                        self.trunkmaster_trunk_username.prop("disabled", true);
                        self.trunkmaster_trunk_password.prop("disabled", true);
                        break;
                }
            })
        }*/


    });
}));

$(document).ready(function () {
    var self = this;
    // self.unconditional_switch = $('#trunkmaster-trunk_register', this.element);
    // self.class_for_hide_row = $('.class_for_hide_row', this.element);
    // self.trunkmaster_trunk_username = $('#trunkmaster-trunk_username', this.element);
    // self.trunkmaster_trunk_password = $('#trunkmaster-trunk_password', this.element);
    // self.class_for_hide_row.css("display", "none");
    self.trunk_ip_version = $('#trunkmaster-trunk_ip_version').val();
    self.mask32 = $('.mask32', this.element);
    self.mask64 = $('.mask64', this.element);
    self.inbound_mask1 = $('.inbound_mask1', this.element);
    self.inbound_mask2 = $('.inbound_mask2', this.element);
    /* Validate ip based on version */
    if (self.trunk_ip_version == 'IPv4') {
        self.mask32.css("display", "block");
        self.mask64.css("display", "none");
        self.inbound_mask1.prop("disabled", false);
        self.inbound_mask2.prop("disabled", true);
    }
    if (self.trunk_ip_version == 'IPv6') {
        self.mask64.css("display", "block");
        self.mask32.css("display", "none");
        self.inbound_mask2.prop("disabled", false);
        self.inbound_mask1.prop("disabled", true);
    }


    $(document).on('change', '#trunkmaster-trunk_ip_version', function () {
        var self = this;
        self.mask32 = $('.mask32', this.element);
        self.mask64 = $('.mask64', this.element);
        self.inbound_mask1 = $('.inbound_mask1', this.element);
        self.inbound_mask2 = $('.inbound_mask2', this.element);
        var trunk_ip_version = $('#trunkmaster-trunk_ip_version').val();
        if (trunk_ip_version == 'IPv4') {
            self.mask32.css("display", "block");
            self.mask64.css("display", "none");
            self.inbound_mask1.prop("disabled", false);
            self.inbound_mask2.prop("disabled", true);
        }
        if (trunk_ip_version == 'IPv6') {
            self.mask32.css("display", "none");
            self.mask64.css("display", "block");
            self.inbound_mask1.prop("disabled", true);
            self.inbound_mask2.prop("disabled", false);
        }
    });

    /*if (self.unconditional_switch.val() == '1') {
        self.class_for_hide_row.css("display", "block");
        self.trunkmaster_trunk_password.prop("disabled", false);
        self.trunkmaster_trunk_username.prop("disabled", false);
    }
    if (self.unconditional_switch.val() == '0' || self.unconditional_switch.val() == '') {
        self.class_for_hide_row.css("display", "none");
        self.trunkmaster_trunk_password.prop("disabled", true);
        self.trunkmaster_trunk_username.prop("disabled", true);
    }*/
    $('.trunk-master-form').placeControl();
});