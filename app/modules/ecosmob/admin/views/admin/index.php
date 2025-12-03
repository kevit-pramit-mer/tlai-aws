<?php

use app\modules\ecosmob\admin\AdminModule;
use app\assets\AuthAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\components\ConstantHelper;

/* @var $subAdmin */
/* @var $supervisor */
/* @var $agent */
/* @var $extension */
/* @var $inbound */
/* @var $outbound */
/* @var $blended */
/* @var $form yii\widgets\ActiveForm */


$this->title = AdminModule::t('admin', 'admin_dashboard');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = AdminModule::t('admin', 'admin_dashboard');
AuthAsset::register($this);
$permissions = $GLOBALS['permissions'];
?>
<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12 main-dashboard-data">
        <div class="col s12">
            <div class="row">
                <div class="col s12 m6">

                </div>

                <div class="col s12 m6">
                </div>
            </div>
            <div class="profile-contain">
                <div class="section section-data-tables">
                    <div id="card-stats" class="pt-0">
                        <!-- Call Summary Statistics data :: BEGIN -->
                        <div class="row mt-1 call-summary-data">
                            <div class="col s12 m12 l12">
                                <div class="card animate fadeRight p-5">
                                    <div class="panel-heading d-flex align-items-center">
                                        <span><b>Call Summary Statistics</b></span>
                                        <div class="ml-auto date-picker-range-section">
                                            <span>Interval : </span>
                                            <div class="summary-interval">

                                                <?= Html::dropDownList('time_interval', 1,
                                                    ConstantHelper::getDashboardIntervalTime(), [
                                                        'class' => 'form-control',
                                                        'id' => 'time_interval',
                                                        //'onchange' => 'sipRegRefreshTime($(this))',
                                                    ]) ?>
                                            </div>
                                        </div>
                                        <!-- <select class="filter-selection">
                                            <option>Select Options</option>
                                            <option>Select Options 1</option>
                                            <option>Select Options 2</option>
                                        </select> -->
                                    </div>
                                    <div class="row statics-data">
                                        <div class="col s12 m6 l4">
                                            <div class="card animate fadeLeft">
                                                <div class="card-content">
                                                    <div class="card-stats-title">
                                                        <p class="card-counter-title">Calls Per Second (CPS)</p>
                                                        <h4 class="card-stats-number m-0" id="CPS"><?=$CPS?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6 l4">
                                            <div class="card animate fadeLeft">
                                                <div class="card-content">
                                                    <div class="card-stats-title">
                                                        <p class="card-counter-title">Average Call Duration (ACD)</p>
                                                        <h4 class="card-stats-number m-0" id="ACD"><?=$ACD?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6 l4">
                                            <div class="card animate fadeLeft">
                                                <div class="card-content">
                                                    <div class="card-stats-title">
                                                        <p class="card-counter-title">Answer Seizure Ratio (ASR)</p>
                                                        <h4 class="card-stats-number m-0" ><span id="ASR"><?=$ASR?></span>%</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Call Summary Statistics data :: END -->
                        <?php if (in_array('/user/user/index', $permissions)) { ?>
                        <div class="row">
                            <div class="col s12 m6 l3">
                                <div class="card animate fadeLeft">
                                    <div class="card-content">
                                        <div class="card-stats-title">
                                            <!-- <i class="material-icons">person_outline</i> -->
                                            <p class="card-counter-title"><?= Yii::t('app', 'sub_admins') ?></p>
                                            <h4 class="card-stats-number m-0"><?= $subAdmin ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (in_array('/supervisor/supervisor/index', $permissions)) { ?>
                            <div class="col s12 m6 l3">
                                <div class="card animate fadeLeft">
                                    <div class="card-content ">
                                        <div class="card-stats-title">
                                            <!-- <i class="material-icons">person_outline</i>  -->
                                            <p class="card-counter-title"><?= Yii::t('app', 'supervisors') ?></p>
                                            <h4 class="card-stats-number m-0"><?= $supervisor ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (in_array('/agents/agents/index', $permissions)) { ?>
                            <div class="col s12 m6 l3">
                                <div class="card animate fadeRight">
                                    <div class="card-content ">
                                        <div class="card-stats-title">
                                            <!-- <i class="material-icons">person_outline</i>  -->
                                            <p class="card-counter-title"><?= Yii::t('app', 'agents') ?></p>
                                            <h4 class="card-stats-number m-0"><?= $agent ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (in_array('/extension/extension/index', $permissions)) { ?>
                            <div class="col s12 m6 l3">
                                <div class="card animate fadeRight">
                                    <div class="card-content 0">
                                        <div class="card-stats-title">
                                            <!-- <i class="material-icons">person_outline</i>  -->
                                            <p class="card-counter-title"><?= Yii::t('app', 'extensions') ?></p>
                                            <h4 class="card-stats-number m-0"><?= $extension ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if(in_array('/campaign/campaign/index', $permissions)){ ?>
                            <div class="col s12 m6 l3">
                                <div class="card animate fadeRight">
                                    <div class="card-content ">
                                        <div class="card-stats-title">
                                            <!-- <i class="material-icons">content_copy</i>  -->
                                            <p class="card-counter-title"><?= Yii::t('app', 'inbound_campaigns') ?></p>
                                            <h4 class="card-stats-number m-0"><?= $inbound ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col s12 m6 l3">
                                <div class="card animate fadeRight">
                                    <div class="card-content  ">
                                        <div class="card-stats-title">
                                            <!-- <i class="material-icons">content_copy</i>  -->
                                            <p class="card-counter-title"><?= Yii::t('app', 'outbound_campaigns') ?></p>
                                            <h4 class="card-stats-number m-0"><?= $outbound ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
<!--                            <div class="col s12 m6 l3">-->
<!--                                <div class="card animate fadeRight">-->
<!--                                    <div class="card-content  ">-->
<!--                                        <div class="card-stats-title">-->
<!--                                            <p class="card-counter-title">--><?php //= Yii::t('app', 'blended_campaigns') ?><!--</p>-->
<!--                                            <h4 class="card-stats-number m-0">--><?php //= $blended ?><!--</h4>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="col s12 m6 l3">
                                <div class="card animate fadeRight">
                                    <div class="card-content  ">
                                        <div class="card-stats-title">
                                            <!-- <i class="material-icons">content_copy</i> -->
                                            <p class="card-counter-title"><?= AdminModule::t('admin', 'active_calls') ?></p>
                                            <h4 class="card-stats-number m-0" id="textActive">0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1 server-summary-data d-none">
                            <div class="col s12 m12 l12">
                                <div class="card animate fadeRight p-5">
                                    <div class="panel-heading d-flex align-items-center">
                                        <span><b><?= AdminModule::t('admin', 'active_services') ?></b></span>
                                        <div class="ml-auto date-picker-range-section">
                                            <span>Server : </span>
                                            <div class="input-field">
                                                <?= Html::dropDownList('server_ip', 1,
                                                   ArrayHelper::map(Yii::$app->masterdb->createCommand("SELECT `server_ip` FROM `uc_server_usases` GROUP BY `server_ip`")->queryAll(), 'server_ip', 'server_ip'), [
                                                        'class' => 'form-control',
                                                        'id' => 'server_ip',
                                                    ]) ?>
                                            </div>
                                        </div>
                                    </div>
                                   <!-- <div class="row statics-data">
                                    <div class="col s12 m6 l6">
                                        <div class="card animate fadeRight">
                                            <div class="card-content ">
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title"><?php /*= AdminModule::t('admin', 'server_time') */?></p>
                                                    <span id='s_time'
                                                          class="card-stats-number">2023-04-13 07:09</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m6 l6">
                                        <div class="card animate fadeRight">
                                            <div class="card-content ">
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title"><?php /*= AdminModule::t('admin', 'reboot_time') */?></p>
                                                    <span id='r_time'
                                                          class="card-stats-number">2023-04-06 10:49</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>-->
                                    <div class="chart-section col s12">
                                        <div class="" ng-app="chartapp" ng-controller="MainController">
                                           <!-- <div class="server-statics-data">
                                            <div class="col s12 m6 l4">
                                                <div class="card animate fadeRight  p-5">
                                                    <div class="panel-heading">
                                                        <span><b><?php /*= AdminModule::t('admin', 'hd_usage') */?></b></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="amount-set-data">
                                                            <span style="color:#08b9db" id="hd-usage-count">0%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m6 l4">
                                                <div class="card animate fadeRight  p-5">
                                                    <div class="panel-heading">
                                                        <span><b><?php /*= AdminModule::t('admin', 'cpu_usage') */?></b></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="amount-set-data">
                                                            <span style="color:#fa8792"
                                                                  id="cpu-usage-count">0%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col s12 m6 l4">
                                                <div class="card animate fadeRight  p-5">
                                                    <div class="panel-heading">
                                                        <span><b><?php /*= AdminModule::t('admin', 'mem_usage') */?></b></span>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="amount-set-data">
                                                                        <span style="color:#08b9db"
                                                                              id="mem-usage-count">0%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                                        <div class="padding-set">
                                            <div class="server-statics-data">
                                                <div class="col s12 active calls-indication">
                                                    <div class="card animate fadeRight p-5">
                                                       <!-- <div class="panel-heading">
                                                            <span><b><?php /*= AdminModule::t('admin', 'active_services') */?></b></span>
                                                        </div>-->
                                                        <div class="panel-body">
                                                            <div>
                                                                <div class="input-field center active-service-panel">
                                                                    <div class="d-flex">
                                                                        <div class="active-circle">
                                                                            <i class="material-icons"
                                                                               id="nginx">brightness_1</i>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <button type="button"
                                                                                    class="btn waves-effect waves-light amber darken-4"
                                                                                    disabled><?= AdminModule::t('admin', 'service_1') ?></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex">
                                                                        <div class="active-circle">
                                                                            <i class="material-icons"
                                                                               id="mysql">brightness_1</i>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <button type="button"
                                                                                    style="background-color: #f3a008ad !important;"
                                                                                    class="btn waves-effect waves-light bg-gray-200 ml-2"
                                                                                    disabled><?= AdminModule::t('admin', 'service_2') ?></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex">
                                                                        <div class="active-circle">
                                                                            <i class="material-icons"
                                                                               id="mongo">brightness_1</i>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <button type="button"
                                                                                    class="btn waves-effect waves-light amber darken-4"
                                                                                    disabled
                                                                                    style=" background-color: #FA8792 !important;"><?= AdminModule::t('admin', 'service_3') ?></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex">
                                                                        <div class="active-circle">
                                                                            <i class="material-icons"
                                                                               id="freeswitch">brightness_1</i>
                                                                        </div>
                                                                        <div class="">
                                                                            <button type="button"
                                                                                    class="btn waves-effect waves-light amber darken-4"
                                                                                    disabled
                                                                                    style="background-color: #26c6da !important;"><?= AdminModule::t('admin', 'service_4') ?></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 m12 l12">
                            <div class="card animate fadeRight  p-5">
                                <div class="panel-heading d-flex align-items-center header-btn-opt">
                                    <span><b><?= AdminModule::t('admin', 'concurrent_calls') ?></b></span>
                                    <div class="ml-auto ">
                                        <button type="button"
                                                class="btn waves-effect waves-light amber darken-4 tablink" style="background:#89adcd !important;" onclick="openPage('hour_concurrent_chart_div', $(this))">Hour
                                        </button>
                                        <button type="button"
                                                class="btn waves-effect waves-light amber darken-4 tablink" id="defaultOpen" onclick="openPage('day_concurrent_chart_div', $(this))">Day
                                        </button>
                                        <button type="button" class="d-none"
                                                class="btn waves-effect waves-light amber darken-4 tablink" onclick="openPage('week_concurrent_chart_div', $(this))">Week
                                        </button>
                                        <button type="button"
                                                class="btn waves-effect waves-light amber darken-4 tablink" onclick="openPage('month_concurrent_chart_div', $(this))">Month
                                        </button>
                                    </div>
                                </div>
                                <div class="col s12 m12 l12 tabcontent" id="hour_concurrent_chart_div" data-id="1">
                                    <div id="hour_concurrent_chart"></div>
                                </div>
                                <div class="col s12 m12 l12 tabcontent" id="day_concurrent_chart_div" data-id="0" style="display: none">
                                    <div id="day_concurrent_chart"></div>
                                </div>
                                <div class="col s12 m12 l12 tabcontent" id="week_concurrent_chart_div" data-id="0" style="display: none">
                                    <div id="week_concurrent_chart"></div>
                                </div>
                                <div class="col s12 m12 l12 tabcontent" id="month_concurrent_chart_div" data-id="0" style="display: none">
                                    <div id="month_concurrent_chart"></div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!--<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>-->
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/apexcharts.js' ?>"></script>

<script>
    $(".dash-active").addClass('active')

    $(document).ready(function () {
        var concurrentHourOptions = {
            series: [{
                name: "Count",
                type: "line",
                data: []
            }],
            chart: {
                id: 'realtime',
                height: 350,
                type: 'line',
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 0
            },
            xaxis: {
                type: 'category',
                tickAmount: 10
            },
            yaxis: {
                labels: {
                    formatter: function(val, index) {
                        return val.toString();
                    }
                }
            },
        };
        var concurrentHourChart = new ApexCharts(document.querySelector("#hour_concurrent_chart"), concurrentHourOptions);
        concurrentHourChart.render();
        var concurrentDayOptions = {
            series: [{
                name: "Count",
                type: "line",
                data: []
            }],
            chart: {
                id: 'realtime',
                height: 350,
                type: 'line',
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 0
            },
            xaxis: {
                type: 'category',
                //tickAmount: 11

            },
            yaxis: {
                labels: {
                    formatter: function(val, index) {
                        return val.toString();
                    }
                }
            },
        };
        var concurrentDayChart = new ApexCharts(document.querySelector("#day_concurrent_chart"), concurrentDayOptions);
        concurrentDayChart.render();
        var concurrentWeekOptions = {
            series: [{
                name: "Count",
                type: "line",
                data: []
            }],
            chart: {
                id: 'realtime',
                height: 350,
                type: 'line',
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 0
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    datetimeUTC: false
                }
            },
        };
        var concurrentWeekChart = new ApexCharts(document.querySelector("#week_concurrent_chart"), concurrentWeekOptions);
        concurrentWeekChart.render();
        var concurrentMonthOptions = {
            series: [{
                name: "Count",
                type: "line",
                data: []
            }],
            chart: {
                id: 'realtime',
                height: 350,
                type: 'line',
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 0
            },
            xaxis: {
                type: 'category',
                tickAmount: 3,
                labels: {
                    formatter: function(val) {
                        return parseInt(val)+' '+new Date().toLocaleString('en-us',{month:'short'});
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val, index) {
                        return val.toString();
                    }
                }
            },
        };
        var concurrentMonthChart = new ApexCharts(document.querySelector("#month_concurrent_chart"), concurrentMonthOptions);
        concurrentMonthChart.render();
        openPage('day_concurrent_chart_div', $('#defaultOpen'))
        var timeInterval = $('#time_interval').val();
        var serverIp = $('#server_ip').val();
        var chartapp = angular.module('chartapp', ['n3-pie-chart']);
        chartapp.controller('MainController', function ($scope) {
            $.ajax({
                url: baseURL + "index.php?r=admin/admin/get-data",
                data: {'timeInterval': timeInterval, 'serverIp': serverIp},
                type: 'GET',
                success: function (data) {
                    console.log(data);

                    let load_data = $.parseJSON(data);
                    $("#ASR").html(load_data.ASR);
                    $("#CPS").html(load_data.CPS);
                    $("#ACD").html(load_data.ACD);
                    $scope.options = {
                        thickness: 8,
                        mode: "gauge",
                        total: 100
                    };
                    $scope.data = [{
                        label: "<?= AdminModule::t('admin', 'percentage') ?>",
                        value: load_data.sys_cpu_usage,
                        color: "#FA8792",
                        suffix: "%"
                    }];

                    $scope.hardoptions = {
                        thickness: 8,
                        mode: "gauge",
                        total: 100
                    };
                    $scope.harddata = [{
                        label: "<?= AdminModule::t('admin', 'percentage') ?>",
                        value: load_data.sys_disk_used,
                        color: "#08B9DB",
                        suffix: "%"
                    }];

                    $scope.memoryoptions = {
                        thickness: 8,
                        mode: "gauge",
                        total: 100
                    };
                    $scope.memorydata = [{
                        label: "<?= AdminModule::t('admin', 'percentage') ?>",
                        value: load_data.sys_mem_used,
                        color: "#33CDE1",
                        suffix: "%"
                    }];
                    $scope.$apply();
                    $("#hd-usage-count").text(load_data.sys_disk_used + '%');
                    $("#cpu-usage-count").text(load_data.sys_cpu_usage + '%');
                    $("#mem-usage-count").text(load_data.sys_mem_used + '%');
                    $("#textActive").text(load_data.sys_active_calls);
                    $("#r_time").text(load_data.sys_last_reboot.slice(0, -3));

                    $("#s_time").text(load_data.date.slice(0, -3));

                    if (load_data.sys_nginx_status == 1) {
                        $("#nginx").css("color", "#3dff3d");
                    } else {
                        $("#nginx").css("color", "red");
                    }

                    if (load_data.sys_mysql_status == 1) {
                        $("#mysql").css("color", "#3dff3d");
                    } else {
                        $("#mysql").css("color", "red");
                    }

                    if (load_data.sys_mongo_status == 1) {
                        $("#mongo").css("color", "#3dff3d");
                    } else {
                        $("#mongo").css("color", "red");
                    }

                    if (load_data.sys_freeswitch_status == 1) {
                        $("#freeswitch").css("color", "#3dff3d");
                    } else {
                        $("#freeswitch").css("color", "red");
                    }

                    var concurrent_hour_data = [];
                    var concurrent_day_data = [];
                    var concurrent_week_data = [];
                    var concurrent_month_data = [];

                    $.each(load_data.concurrent_hour_call_count, function (key, value) {
                        concurrent_hour_data.push([value.time, value.count, value.hover])
                    });

                    $.each(load_data.concurrent_day_call_count, function (key, value) {
                        concurrent_day_data.push([value.time, value.count, value.hover])
                    });

                    $.each(load_data.concurrent_week_call_count, function (key, value) {
                        concurrent_week_data.push([new Date(value.time).getDate() + ' ' + new Date(value.time).toLocaleString('en-us',{month:'short', year: 'numeric'}), value.count, value.hover])
                    });

                    $.each(load_data.concurrent_month_call_count, function (key, value) {
                        concurrent_month_data.push([value.time, value.count, value.hover])
                        //concurrent_month_data.push([value.time + ' ' + new Date().toLocaleString('en-us',{month:'short'}), value.count, value.hover])
                    });

                    concurrentHourChart.updateSeries([{
                        data: concurrent_hour_data
                    }]);

                    concurrentDayChart.updateSeries([{
                        data: concurrent_day_data
                    }]);

                    concurrentWeekChart.updateSeries([{
                        data: concurrent_week_data
                    }]);

                    concurrentMonthChart.updateSeries([{
                        data: concurrent_month_data
                    }]);

                    $('svg').find('g:first').attr('transform', "translate(136.5,150)");
                }
            });
            //Function added to appear our guage chart as if it is showing data real time.
            setInterval(function () {
                var timeInterval = $('#time_interval').val();
                var serverIp = $('#server_ip').val();
                $.ajax({
                    url: baseURL + "index.php?r=admin/admin/get-data",
                    data: {'timeInterval': timeInterval, 'serverIp': serverIp},
                    type: 'GET',
                    success: function (result) {
                        let final_data = $.parseJSON(result);
                        $("#ASR").html(final_data.ASR);
                        $("#CPS").html(final_data.CPS);
                        $("#ACD").html(final_data.ACD);
                        $scope.data[0].value = final_data.sys_cpu_usage;
                        $scope.harddata[0].value = final_data.sys_disk_used;
                        $scope.memorydata[0].value = final_data.sys_mem_used;
                        $scope.$apply();
                        $("#hd-usage-count").text(final_data.sys_disk_used + '%');
                        $("#cpu-usage-count").text(final_data.sys_cpu_usage + '%');
                        $("#mem-usage-count").text(final_data.sys_mem_used + '%');
                        $("#textActive").text(final_data.sys_active_calls);
                        $("#r_time").text(final_data.sys_last_reboot.slice(0, -3));
                        $("#s_time").text(final_data.date.slice(0, -3));

                        if (final_data.sys_nginx_status == 1) {
                            $("#nginx").css("color", "#3dff3d");
                        } else {
                            $("#nginx").css("color", "red");
                        }

                        if (final_data.sys_mysql_status == 1) {
                            $("#mysql").css("color", "#3dff3d");
                        } else {
                            $("#mysql").css("color", "red");
                        }

                        if (final_data.sys_mongo_status == 1) {
                            $("#mongo").css("color", "#3dff3d");
                        } else {
                            $("#mongo").css("color", "red");
                        }

                        if (final_data.sys_freeswitch_status == 1) {
                            $("#freeswitch").css("color", "#3dff3d");
                        } else {
                            $("#freeswitch").css("color", "red");
                        }

                        var concurrent_hour_data = [];
                        var concurrent_day_data = [];
                        var concurrent_week_data = [];

                        $.each(final_data.concurrent_hour_call_count, function (key, value) {
                            concurrent_hour_data.push([value.time, value.count, value.hover])
                        });

                        $.each(final_data.concurrent_day_call_count, function (key, value) {
                            concurrent_day_data.push([value.time, value.count, value.hover])
                        });

                        $.each(final_data.concurrent_week_call_count, function (key, value) {
                            concurrent_week_data.push([new Date(value.time).getDate() + ' ' + new Date(value.time).toLocaleString('en-us',{month:'short', year: 'numeric'}), value.count, value.hover])
                        });

                        concurrentHourChart.updateSeries([{
                            data: concurrent_hour_data
                        }]);

                        concurrentDayChart.updateSeries([{
                            data: concurrent_day_data
                        }]);

                        concurrentWeekChart.updateSeries([{
                            data: concurrent_week_data
                        }]);
                    }
                });
            }, 2000);
            $('svg').find('g:first').attr('transform', "translate(136.5,150)");
        });

    });

    function openPage(pageName, param) {
        var i, tabcontent;
        $('.tabcontent').css('display', 'none');
        tabcontent = document.getElementsByClassName("tabcontent");
        $('#'+pageName).css('display', '');
        $('.tablink').each(function(){
            $(this).attr('data-id', 0);
        });
        param.attr('data-id', 1);
        $('.tabcontent').each(function(){
            if($(this).attr('id') != pageName){
                $('#'+$(this).attr('id')).css('display', 'none');
                $('#'+$(this).attr('id')).attr('data-id', 0);
            }
        });
        $('.tablink').each(function(){
            if($(this).attr('data-id') == 1){
                $(this).attr('style', 'background:#89adcd !important');
            }else{
                $(this).attr('style', 'background:#1c84ee !important');
            }
        });
    }

    /*setInterval(function () {
        var timeInterval = $('#time_interval').val();
        $.ajax({
            url: baseURL + "index.php?r=admin/admin/get-data",
            type: 'GET',
            data: {'timeInterval': timeInterval},
            success: function (result) {
                let final_data = $.parseJSON(result);

                var concurrent_hour_data = [];
                var concurrent_day_data = [];
                var concurrent_week_data = [];

                $.each(final_data.concurrent_hour_call_count, function (key, value) {
                    concurrent_hour_data.push([value.time, value.count, value.hover])
                });

                $.each(final_data.concurrent_day_call_count, function (key, value) {
                    concurrent_day_data.push([value.time, value.count, value.hover])
                });

                $.each(final_data.concurrent_week_call_count, function (key, value) {
                    concurrent_week_data.push([new Date(value.time).getDate()+' '+new Date(value.time).toLocaleString('default', { month: 'short' }), value.count, value.hover])
                });

                concurrentHourChart.updateSeries([{
                    data: concurrent_hour_data
                }]);

                concurrentDayChart.updateSeries([{
                    data: concurrent_day_data
                }]);

                concurrentWeekChart.updateSeries([{
                    data: concurrent_week_data
                }]);
            }
        });
    }, 5000);*/

    $('#time_interval').on('change', function(){
        $.ajax({
            url: baseURL + "index.php?r=admin/admin/get-data",
            data: {'timeInterval': $(this).val(), 'serverIp': $('#server_ip').val()},
            type: 'GET',
            success: function (result) {
                let final_data = $.parseJSON(result);
                $("#ASR").html(final_data.ASR);
                $("#CPS").html(final_data.CPS);
                $("#ACD").html(final_data.ACD);
                $("#hd-usage-count").text(final_data.sys_disk_used + '%');
                $("#cpu-usage-count").text(final_data.sys_cpu_usage + '%');
                $("#mem-usage-count").text(final_data.sys_mem_used + '%');
                $("#textActive").text(final_data.sys_active_calls);
                $("#r_time").text(final_data.sys_last_reboot.slice(0, -3));
                $("#s_time").text(final_data.date.slice(0, -3));

                if (final_data.sys_nginx_status == 1) {
                    $("#nginx").css("color", "#3dff3d");
                } else {
                    $("#nginx").css("color", "red");
                }

                if (final_data.sys_mysql_status == 1) {
                    $("#mysql").css("color", "#3dff3d");
                } else {
                    $("#mysql").css("color", "red");
                }

                if (final_data.sys_mongo_status == 1) {
                    $("#mongo").css("color", "#3dff3d");
                } else {
                    $("#mongo").css("color", "red");
                }

                if (final_data.sys_freeswitch_status == 1) {
                    $("#freeswitch").css("color", "#3dff3d");
                } else {
                    $("#freeswitch").css("color", "red");
                }
            }
        });
    });

    $('#server_ip').on('change', function(){
        $.ajax({
            url: baseURL + "index.php?r=admin/admin/get-data",
            data: {'timeInterval': $('#time_interval').val(), 'serverIp': $(this).val()},
            type: 'GET',
            success: function (result) {
                let final_data = $.parseJSON(result);
                $("#ASR").html(final_data.ASR);
                $("#CPS").html(final_data.CPS);
                $("#ACD").html(final_data.ACD);
                $("#hd-usage-count").text(final_data.sys_disk_used + '%');
                $("#cpu-usage-count").text(final_data.sys_cpu_usage + '%');
                $("#mem-usage-count").text(final_data.sys_mem_used + '%');
                $("#textActive").text(final_data.sys_active_calls);
                $("#r_time").text(final_data.sys_last_reboot.slice(0, -3));
                $("#s_time").text(final_data.date.slice(0, -3));

                if (final_data.sys_nginx_status == 1) {
                    $("#nginx").css("color", "#3dff3d");
                } else {
                    $("#nginx").css("color", "red");
                }

                if (final_data.sys_mysql_status == 1) {
                    $("#mysql").css("color", "#3dff3d");
                } else {
                    $("#mysql").css("color", "red");
                }

                if (final_data.sys_mongo_status == 1) {
                    $("#mongo").css("color", "#3dff3d");
                } else {
                    $("#mongo").css("color", "red");
                }

                if (final_data.sys_freeswitch_status == 1) {
                    $("#freeswitch").css("color", "#3dff3d");
                } else {
                    $("#freeswitch").css("color", "red");
                }
            }
        });

    });

</script>
<!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.16/d3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pie-chart/1.0.0/pie-chart.min.js"></script>-->

<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/loader.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/angular.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/d3.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/pie-chart.min.js' ?>"></script>

