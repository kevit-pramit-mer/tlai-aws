/**
 * Created by akshay on 5/12/17.
 */

(function (factory) {
    'use strict';
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("ecosmob.getMusicHoldExtension", {
        _create: function () {
            var self = this;
            $.proxy(self._playAudio, self)();
            $.proxy(self._loadAudio, self)();
        },

        _loadAudio: function () {
            var filename = $('#conferencemaster-cm_moh').val();
            if (filename.indexOf("audio_phrase") == 0 || filename.indexOf("None") == 0) {
                $('#moh_extension_player').parent().hide();
            } else {
                var audios = $("#moh_extension_player");
                audios.load();
                audios.parent().show();
            }
        },
        _playAudio: function () {
            $(document).on('change', '#conferencemaster-cm_moh', function (e) {
                var filename = $('#conferencemaster-cm_moh').val();
                var defaultFileName = $('#conferencemaster-cm_moh option:selected').text();
                var em_id = $('#extension_id').val();

                if (filename.indexOf("audio_phrase") == 0 || filename.indexOf("None") == 0) {
                    $('#moh_extension_player').parent().hide();
                } else {
                    var audios = $("#moh_extension_player");
                    audios.load();
                    audios.parent().show();
                }
                $.post("/conference/conference/get-file-path", {
                    'filename': filename,
                    'defaultFileName': defaultFileName,
                    'em_id': em_id
                }, function (response, status) {
                    var audios = $("#moh_extension_player");
                    var okk = $("#moh_extension_player_src").attr("src", response);
                    audios.load();
                });

            });
        }
    });
}));


$(document).ready(function () {
    $("#conference-master-active-form").getMusicHoldExtension();
});
