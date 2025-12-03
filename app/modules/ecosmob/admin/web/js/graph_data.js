$(document).ready(function () {
    getAllData();
});


// Auto Refresh Code
setInterval(function () {
    getAllData();
}, 30000);

function getAllData() {
    $.ajax({
        url: "/admin/admin/get-data",
        success: function (result) {
            var data = JSON.parse(result);

            var diskHtml = "<div id='disk_space_graph' disk_space_data='" + JSON.stringify(data['disk-space']) + "' style='display: none'></div>";
            $("#disk-space-usage-graph").html(diskHtml);
            $("#disk-space-usage").html('<div id="disk-space-chart" class="dashboard-box chart-height chart-1"></div>');
            diskSpaceUsageGraph();

            var memoryHtml = "<div id='memory_usage_graph' memory_usage_data='" + JSON.stringify(data['memory-usage']) + "' style='display: none'></div>";
            $("#memory-usage-graph").html(memoryHtml);
            $("#memory-usage").html('<div id="memory-usage-chart" class="dashboard-box chart-height chart-1"></div>');
            memoryUsageGraph();

            var cpuUsageHtml = "<div id='cpu_usage_graph' cpu_usage_data='" + JSON.stringify(data['cpu-usage']) + "' style='display: none'></div>";
            $("#cpu-usage-graph").html(cpuUsageHtml);
            $("#cpu-usage").html('<div id="cpu-usage-chart" class="dashboard-box chart-height chart-1"></div>');
            cpuUsageGraph();

            var loadHtml = "<div id='load_average_graph' load_average_data='" + JSON.stringify(data['load-average']) + "' style='display: none'></div>";
            $("#load-average-graph").html(loadHtml);
            $("#load-average").html('<div id="load-average-chart" class="dashboard-box chart-height chart-1"></div>');
            loadAverageGraph();
            $('svg').find('g:first').attr('transform',"translate(136.5,150)");
        }
    });
}

function diskSpaceUsageGraph() {
    "use strict";
    var disk_space = $('#disk_space_graph').attr('disk_space_data');
    var disk_space_values = JSON.parse(disk_space);

    Morris.Line({
        element: 'disk-space-chart',
        data: disk_space_values,
        xkey: 'time',
        ykeys: ['disk_used'],
        // lineColors: ['#087380'],
        labels: ['Disk Used'],
        redraw: true,
        resize: true
    });
}

function memoryUsageGraph() {
    "use strict";
    var memory_usage = $('#memory_usage_graph').attr('memory_usage_data');
    var memory_usage_values = JSON.parse(memory_usage);
    Morris.Line({
        element: 'memory-usage-chart',
        data: memory_usage_values,
        xkey: 'time',
        ykeys: ['memory_used'],
        // lineColors: ['#087380'],
        labels: ['Memory Used'],
        redraw: true,
        resize: true
    });
}

function cpuUsageGraph() {
    "use strict";
    var cpu_usage = $('#cpu_usage_graph').attr('cpu_usage_data');
    var cpu_usage_values = JSON.parse(cpu_usage);
    Morris.Line({
        element: 'cpu-usage-chart',
        data: cpu_usage_values,
        xkey: 'time',
        ykeys: ['cpu_usage', 'cpu_system', 'cpu_nice', 'cpu_iowait'],
        // lineColors: ['#087380', '#8c1313', '#363636', '#669696'],
        labels: ['CPU Usage', 'CPU System', 'CPU Nice', 'CPU IOWait'],
        redraw: true,
        resize: true
    });
}

function loadAverageGraph() {
    "use strict";
    var load_average = $('#load_average_graph').attr('load_average_data');
    var load_values = JSON.parse(load_average);
    Morris.Line({
        element: 'load-average-chart',
        data: load_values,
        xkey: 'time',
        ykeys: ['load1', 'load5', 'load15'],
        lineColors: ['#087380', '#8c1313', '#696969'],
        labels: ['Load 1', 'Load 5', 'Load 15'],
        redraw: true,
        resize: true
    });
}


!function (document, window, $) {
}(document, window, jQuery);
