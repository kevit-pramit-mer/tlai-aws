// For Audio Codecs
(function (factory) {
    'use strict';
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("ecosmob.assignAudioCodecs", {
        _create: function () {
            var self = this;
            $.proxy(self._leftAssign, self)();
            $.proxy(self._rightAssign, self)();
            $.proxy(self._upAssign, self)();
            $.proxy(self._downAssign, self)();
        },

        _leftAssign: function () {
            var self = this;
            $(document).on('click', '#btnLeftAudio', function (e) {

                var audioCodecs = [];
                var audioCodecsList = $('#selectedAudioCodecs').val().split(',');
                var ind;
                $.each($('#assignedAudioCodecs option:selected'), function () {
                    audioCodecs.push($(this).val());
                    ind = audioCodecsList.indexOf($(this).val());
                    if (ind !== -1) {
                        audioCodecsList.splice(ind, 1); // The second parameter is the number of elements to remove.
                    }
                    $(this).remove().appendTo('#availableAudioCodecs');
                });
                audioCodecsList = audioCodecsList.join(',');
                $('#selectedAudioCodecs option:selected').removeAttr("selected");
                $('#selectedAudioCodecs').val(audioCodecsList);
                e.preventDefault();
            });
        },

        _rightAssign: function () {
            var self = this;
            $(document).on('click', ('#btnRightAudio', self.elements), function (e) {
                $(document).on('click', '#btnRightAudio', function (e) {
                    var audioCodecs = [];
                    $.each($("#availableAudioCodecs option:selected"), function () {
                        audioCodecs.push($(this).val());
                        $(this).remove().appendTo('#assignedAudioCodecs');
                    });
                    var audioCodecsList = $("#selectedAudioCodecs").val();
                    if (audioCodecsList) {
                        audioCodecsList = audioCodecsList.split(',');
                        audioCodecs = $.merge(audioCodecsList, audioCodecs);
                    }
                    audioCodecsList = audioCodecs.join(',');
                    $("#assignedAudioCodecs option:selected").removeAttr("selected");
                    $("#selectedAudioCodecs").val(audioCodecsList);
                    e.preventDefault();
                });
            });
        },

        _upAssign: function () {

            $(document).on('click', '#btnUpAudio', function (e) {
                $('#assignedAudioCodecs option:selected:first-child').prop("selected", false);
                var before = $('#assignedAudioCodecs option:selected:first').prev();
                $('#assignedAudioCodecs option:selected').detach().insertBefore(before);

                var audioCodecsList = [];
                $('#assignedAudioCodecs option').each(function () {
                    audioCodecsList.push($(this).attr('value'));
                });
                audioCodecsList = audioCodecsList.join(',');
                $("#selectedAudioCodecs").val(audioCodecsList);
                e.preventDefault();
            });
        },

        _downAssign: function () {

            $(document).on('click', '#btnDownAudio', function (e) {
                $('#assignedAudioCodecs option:selected:last-child').prop("selected", false);
                var after = $('#assignedAudioCodecs option:selected:last').next();
                $('#assignedAudioCodecs option:selected').detach().insertAfter(after);

                var audioCodecsList = [];
                $('#assignedAudioCodecs option').each(function () {
                    audioCodecsList.push($(this).attr('value'));
                });
                audioCodecsList = audioCodecsList.join(',');
                $("#selectedAudioCodecs").val(audioCodecsList);
                e.preventDefault();
            });
        }
    });
}));

// For Video Codecs
(function (factory) {
    'use strict';
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("ecosmob.assignVideoCodecs", {
        _create: function () {
            var self = this;
            $.proxy(self._leftAssign, self)();
            $.proxy(self._rightAssign, self)();
            $.proxy(self._upAssign, self)();
            $.proxy(self._downAssign, self)();
        },

        _leftAssign: function () {

            $(document).on('click', '#btnLeftVideo', function (e) {
                var videoCodecs = [];
                var videoCodecsList = $("#selectedVideoCodecs").val().split(',');
                var ind;
                $.each($("#assignedVideoCodecs option:selected"), function () {
                    videoCodecs.push($(this).val());
                    ind = videoCodecsList.indexOf($(this).val());
                    if (ind != -1) {
                        videoCodecsList.splice(ind, 1); // The second parameter is the number of elements to remove.
                    }
                    $(this).remove().appendTo('#availableVideoCodecs');
                });
                videoCodecsList = videoCodecsList.join(',');
                $("#availableVideoCodecs option:selected").removeAttr("selected");
                $("#selectedVideoCodecs").val(videoCodecsList);
                e.preventDefault();
            });
        },

        _rightAssign: function () {
            $(document).on('click', '#btnRightVideo', function (e) {
                var videoCodecs = [];
                $.each($("#availableVideoCodecs option:selected"), function () {
                    videoCodecs.push($(this).val());
                    $(this).remove().appendTo('#assignedVideoCodecs');
                });
                var videoCodecsList = $("#selectedVideoCodecs").val();
                if (videoCodecsList) {
                    videoCodecsList = videoCodecsList.split(',');
                    videoCodecs = $.merge(videoCodecsList, videoCodecs);
                }
                videoCodecsList = videoCodecs.join(',');
                $("#assignedVideoCodecs option:selected").removeAttr("selected");
                $("#selectedVideoCodecs").val(videoCodecsList);
                e.preventDefault();
            });
        },

        _upAssign: function () {

            $(document).on('click', '#btnUpVideo', function (e) {
                $('#assignedVideoCodecs option:selected:first-child').prop("selected", false);
                var before = $('#assignedVideoCodecs option:selected:first').prev();
                $('#assignedVideoCodecs option:selected').detach().insertBefore(before);

                var videoCodecsList = [];
                $('#assignedVideoCodecs option').each(function () {
                    videoCodecsList.push($(this).attr('value'));
                });
                videoCodecsList = videoCodecsList.join(',');
                $("#selectedVideoCodecs").val(videoCodecsList);
                e.preventDefault();
            });
        },

        _downAssign: function () {

            $(document).on('click', '#btnDownVideo', function (e) {
                $('#assignedVideoCodecs option:selected:last-child').prop("selected", false);
                var after = $('#assignedVideoCodecs option:selected:last').next();
                $('#assignedVideoCodecs option:selected').detach().insertAfter(after);

                var videoCodecsList = [];
                $('#assignedVideoCodecs option').each(function () {
                    videoCodecsList.push($(this).attr('value'));
                });
                videoCodecsList = videoCodecsList.join(',');
                $("#selectedVideoCodecs").val(videoCodecsList);
                e.preventDefault();
            });
        }
    });
}));


$(document).ready(function () {

    $("#trunk-master-form").assignAudioCodecs();
    $("#trunk-master-form").assignVideoCodecs();
});

