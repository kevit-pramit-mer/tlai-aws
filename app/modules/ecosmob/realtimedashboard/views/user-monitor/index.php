<?php

use app\assets\AuthAsset;
use app\modules\ecosmob\realtimedashboard\RealTimeDashboardModule;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\ConstantHelper;

/* @var $searchModel */
/* @var $dataProvider */
/* @var $loginUser */
/* @var $availableUser */
/* @var $inCallUser */

$this->title = 'Agent monitor';
$this->params['breadcrumbs'][] = $this->title;
AuthAsset::register($this);
$permissions=$GLOBALS['permissions'];
$refreshTime = ConstantHelper::REALTIME_DASHBOARD_DEFAULT_REFRESH_TIME;
?>

<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12 main-dashboard-data">
        <!-- tab section :: BEGIN -->
        <div class="col s12 theme-tabs">
            <ul class="tabs d-flex align-items-center">
                <?php if (in_array('/realtimedashboard/sip-extension/index', $permissions)) { ?>
                    <li class=""><a href="<?= Url::to(['/realtimedashboard/sip-extension/index']) ?>">SIP Extension
                            Registration Status</a></li>
                <?php } ?>
                <?php if (in_array('/realtimedashboard/user-monitor/index', $permissions)) { ?>
                    <li class="tab"><a class="active" href="<?= Url::to(['/realtimedashboard/user-monitor/index']) ?>">Agent monitor</a>
                    </li>
                <?php } ?>
                <?php if (in_array('/realtimedashboard/queue-status/index', $permissions)) { ?>
                    <li class=""><a href="<?= Url::to(['/realtimedashboard/queue-status/index']) ?>">Queue
                            Status</a></li>
                <?php } ?>
                <?php if (in_array('/realtimedashboard/active-calls/index', $permissions)) { ?>
                    <li class=""><a href="<?= Url::to(['/realtimedashboard/active-calls/index']) ?>">Active Calls</a>
                    </li>
                <?php } ?>
                <?php if (in_array('/realtimedashboard/campaign-performance/index', $permissions)) { ?>
                    <li class=""><a href="<?= Url::to(['/realtimedashboard/campaign-performance/index']) ?>">Campaign
                            Performance</a></li>
                <?php } ?>
            </ul>
            <div class="mt-1 tab-content">

                <!-- CC User Monitor :: BEGIN -->
                <div id="test2" class="col s12 tab-content-section">
                    <div id="card-stats" class="mt-0 panel-set col s12">
                        <!-- Extension Summary Statistics data :: BEGIN -->
                        <div class="call-summary-data">
                            <div class="card animate fadeRight p-5">
                                <div class="panel-heading d-flex align-items-center">
                                    <span>Agent Summary</span>
                                </div>
                                <div class="row statics-data">
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/total-extentions.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title"
                                                       id="login_user"><?= $loginUser ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Total Logged in Agent</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/registered-extension.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title"
                                                       id="available_user"><?= $availableUser ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Total Available Agent</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/on-the-phone.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title"
                                                       id="incall_user"><?= $inCallUser ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Total In-call Agent</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Extension Summary Statistics data :: END -->
                    </div>
                    <?php Pjax::begin(['id' => 'agent-monitor', 'timeout' => 0, 'enablePushState' => false]); ?>
                    <div class="row">
                        <div class="col-xl-9 col-md-7 col-xs-12">
                            <div class="row">
                                <div class="col s12 mt-1">
                                    <div class="profile-contain">
                                        <div class="section section-data-tables">
                                            <div class="row ml-0 mr-0">
                                                <div class="col s12 search-filter">
                                                    <?= $this->render('_search', ['model' => $searchModel]); ?>
                                                </div>
                                                <div class="col s12">
                                                    <div class="card mt-1">
                                                        <div class="card-content realtime-card-content">
                                                            <div class="panel-set col s12">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <div class="d-flex align-items-center">
                                                                            <h6 class="m-0">Agent Monitor</h6>
                                                                            <div class="table-actions">
                                                                                <div class="d-flex align-items-center gap-1">
                                                                                    <div class="table-action">

                                                                                        <?php echo Html::a( '<img src="'.Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg".'"
                                                                                             alt="Icon"/> Export to CSV',
                                                                                            ['export'],
                                                                                            [
                                                                                                'id' => 'hov',
                                                                                                //'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                                                                'data-pjax' => '0',
                                                                                                'options' => ['style' => 'color:black']

                                                                                            ])
                                                                                        ?>
                                                                                    </div>
                                                                                    <div class="table-action">
                                                                                        <?php echo Html::a( '<img src="'.Yii::getAlias('@web') . "/theme/assets/images/refresh-icon.svg".'"
                                                                                             alt="Icon"/>','javascript:void(0);', [
                                                                                                'id' => 'hov',
                                                                                                'data-pjax' => '0',
                                                                                                'onclick' => 'agentRef()',
                                                                                            ])
                                                                                        ?>
                                                                                        Auto Refresh in : <p
                                                                                                class="agent-monitor-refresh-time">
                                                                                            <?= Html::dropDownList('agent_monitor_refresh_time', $refreshTime,
                                                                                                ConstantHelper::getRefreshTime(), [
                                                                                                    'class' => 'form-control refresh-dropdown',
                                                                                                    'id' => 'agent_monitor_refresh_time',
                                                                                                    'onchange' => 'agentMonitorRefreshTime($(this))',
                                                                                                ]) ?> </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="dataTables_wrapper"
                                                                             id="page-length-option_wrapper">
                                                                            <?php try {
                                                                                echo GridView::widget([
                                                                                    'id' => 'agent-monitor-report-grid-index', // TODO : Add Grid Widget ID
                                                                                    'dataProvider' => $dataProvider,
                                                                                    'layout' => Yii::$app->layoutHelper->get_layout_without_pager('#agent-monitor-report-search-form'),
                                                                                    //'showOnEmpty' => true,
                                                                                    'columns' => [
                                                                                        [
                                                                                            'class' => 'yii\grid\SerialColumn',
                                                                                            'headerOptions' => ['class' => 'width-10'],
                                                                                            'contentOptions' => ['class' => 'width-10'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'agent',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'extension_number',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'customer_number',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {

                                                                                                return (!empty($model->customer_number) ? $model->customer_number : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'status',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'format' => 'raw',
                                                                                            'value' => function ($model) {
                                                                                                if (!empty($model->status)) {
                                                                                                    if ($model->status == 'Logged Out') {
                                                                                                        return '<span class="badge bg-not-available" style="display:inherit;">Not Available</span>';
                                                                                                    }else{
                                                                                                        if (!empty($model->status)) {
                                                                                                            if ($model->status == 'Available' && $model->state == 'Waiting') {
                                                                                                                return '<span class="badge bg-available" style="display:inherit;">'.$model->status.'</span>';
                                                                                                            } else if ($model->status == 'Available' && $model->state == 'In a queue call') {
                                                                                                                return '<span class="badge bg-inqueue" style="display:inherit;">In Call</span>';
                                                                                                            } else if ($model->status == 'On Break' && $model->state == 'Waiting') {
                                                                                                                return '<span class="badge bg-break" style="display:inherit;">' . $model->status . '</span>';
                                                                                                            } else {
                                                                                                                return '<span class="badge bg-other" style="display:inherit;">' . $model->status . '</span>';
                                                                                                            }
                                                                                                        } else {
                                                                                                            return '-';
                                                                                                        }
                                                                                                    }
                                                                                                } else {
                                                                                                    return '-';
                                                                                                }
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'cmp_name',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->cmp_name) ? $model->cmp_name : '-');
                                                                                            }

                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'queue',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->queue) ? $model->queue : '-');
                                                                                            }

                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'total_calls',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'total_talk_time',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->total_talk_time) ? gmdate("H:i:s", $model->total_talk_time) : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'avg_call_duration',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->avg_call_duration) ? gmdate("H:i:s", $model->avg_call_duration) : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'total_idle_time',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return gmdate("H:i:s", $model->login_hour - (!empty($model->total_talk_time) ? $model->total_talk_time : 0));
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'avg_wait_time',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->avg_wait_time) ? gmdate("H:i:s", $model->avg_wait_time) : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'login_hour',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->login_hour) ? gmdate("H:i:s", $model->login_hour) : '-');
                                                                                            }

                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'total_break_time',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->total_break_time) ? gmdate("H:i:s", $model->total_break_time) : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'total_breaks',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'header' => RealTimeDashboardModule::t('realtimedashboard', 'force_logout'),
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'format' => 'raw',
                                                                                            'value' => function($model){
                                                                                                return Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
  <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
</svg>',
                                                                                                    ['force-logout', 'id' => $model->user_id], [
                                                                                                        'style' => '',
                                                                                                        'title' => RealTimeDashboardModule::t('realtimedashboard', 'force_logout'),
                                                                                                    ]);
                                                                                            }
                                                                                        ],
                                                                                    ],
                                                                                    'tableOptions' => [
                                                                                        'class' => 'display dataTable dtr-inline'
                                                                                    ],
                                                                                ]);
                                                                            } catch (Exception $e) {

                                                                            } ?>
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
                    <?php Pjax::end(); ?>
                </div>
                <!-- CC User Monitor :: BEGIN -->

            </div>
        </div>
        <!-- tab section :: END -->
    </div>
</div>

<script>
    $(document).ready(function () {
        $.ajax({
            url: baseURL + "index.php?r=realtimedashboard/user-monitor/get-data",
            success: function (result) {
                let final_data = $.parseJSON(result);
                $('#login_user').text(final_data.loginUser);
                $('#available_user').text(final_data.availableUser);
                $('#incall_user').text(final_data.inCallUser);
            }
        });
    });

    refInt = setInterval(agentRef, $('#agent_monitor_refresh_time').val() * 1000);
    var dropVal = $('#agent_monitor_refresh_time').val();
    function agentMonitorRefreshTime(input) {
        dropVal = $('#agent_monitor_refresh_time').val();
        clearInterval(refInt);
        refInt = setInterval(agentRef, input.val() * 1000)
    }

    function agentRef() {
        $.pjax.reload({
            container: "#agent-monitor",
            async: false,
            data: "UserMonitor[user_id]=" + $('select[name="UserMonitor[user_id]"]').val() + "&UserMonitor[cmp_name]=" + $('select[name="UserMonitor[cmp_name]"]').val() + "&UserMonitor[queue]=" + $('select[name="UserMonitor[queue]"]').val(),
            replace: false
        })

        $.ajax({
            url: baseURL + "index.php?r=realtimedashboard/user-monitor/get-data",
            success: function (result) {
                let final_data = $.parseJSON(result);
                $('#login_user').text(final_data.loginUser);
                $('#available_user').text(final_data.availableUser);
                $('#incall_user').text(final_data.inCallUser);
            }
        });
    }
    $(document).on("pjax:success", function (e) {
        $('#agent_monitor_refresh_time').val(dropVal);
    });
</script>
