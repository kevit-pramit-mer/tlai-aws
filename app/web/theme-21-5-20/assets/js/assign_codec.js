(function (factory) {
    'use strict';
    factory(window.jQuery);
}(function ($) {
    'use strict';
    $.widget("ecosmob.assignAgents", {
        _create: function () {
            var self = this;
            $.proxy(self._leftAssign, self)();
            $.proxy(self._rightAssign, self)();
        },

        _leftAssign: function () {
            var self = this;
            $(document).on('click', '#btnLeft', function (e) {
                var agents = [];
                var agentsList = $('#selectedAgents').val().split(',');
                var ind;
                $.each($('#assignedAgents option:selected'), function () {
                    agents.push($(this).val());
                    ind = agentsList.indexOf($(this).val());
                    if (ind !== -1) {
                        agentsList.splice(ind, 1); // The second parameter is the number of elements to remove.
                    }
                    $(this).remove().appendTo('#availableAgents');
                });
                agentsList = agentsList.join(',');
                $('#selectedAgents option:selected').removeAttr("selected");
                $('#selectedAgents').val(agentsList);
                e.preventDefault();
            });
        },

        _rightAssign: function () {
            var self = this;
            $(document).on('click', ('#btnRight', self.elements), function (e) {
                $(document).on('click', '#btnRight', function (e) {
                    var agents = [];
                    $.each($("#availableAgents option:selected"), function () {
                        agents.push($(this).val());
                        $(this).remove().appendTo('#assignedAgents');
                    });
                    var agentsList = $("#selectedAgents").val();
                    if (agentsList) {
                        agentsList = agentsList.split(',');
                        agents = $.merge(agentsList, agents);
                    }
                    agentsList = agents.join(',');
                    $("#assignedAgents option:selected").removeAttr("selected");
                    $("#selectedAgents").val(agentsList);
                    e.preventDefault();
                });
            });
        },
    });
}));

$(document).ready(function () {
    $("#queue-form").assignAgents();
});

