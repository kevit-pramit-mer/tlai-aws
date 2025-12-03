<?php

use app\assets\AuthAsset;
use app\modules\ecosmob\queue\models\QueueMaster;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\ConstantHelper;

/* @var $searchModel */
/* @var $dataProvider */

$this->title = 'Queue Status';
$this->params['breadcrumbs'][] = $this->title;
AuthAsset::register($this);
$permissions = $GLOBALS['permissions'];
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
                    <li class=""><a href="<?= Url::to(['/realtimedashboard/user-monitor/index']) ?>">Agent monitor</a>
                    </li>
                <?php } ?>
                <?php if (in_array('/realtimedashboard/queue-status/index', $permissions)) { ?>
                    <li class="tab"><a class="active" href="<?= Url::to(['/realtimedashboard/queue-status/index']) ?>">Queue
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
                <!-- Queue Status :: BEGIN -->
                <div id="test3" class="col s12 tab-content-section">

                    <?php Pjax::begin(['id' => 'queue-status', 'timeout' => 0, 'enablePushState' => false]); ?>
                    <div class="row">
                        <div class="col-xl-9 col-md-7 col-xs-12">
                            <div class="row">
                                <div class="col s12">
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
                                                                            <h6 class="m-0">Queue Status</h6>
                                                                            <div class="table-actions">
                                                                                <div class="d-flex align-items-center gap-1">
                                                                                    <div class="table-action">
                                                                                        <?php echo Html::a('<img src="'.Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg".'"
                                                                                             alt="Icon"/> Export to CSV',
                                                                                            ['export'],
                                                                                            [
                                                                                                'id' => 'hov',
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
                                                                                            'onclick' => 'queueStatusRef()',
                                                                                        ])
                                                                                        ?>
                                                                                        Auto Refresh in : <p
                                                                                                class="queue-status-refresh-time">
                                                                                            <?= Html::dropDownList('queue_status_refresh_time', $refreshTime,
                                                                                                ConstantHelper::getRefreshTime(), [
                                                                                                    'class' => 'form-control refresh-dropdown',
                                                                                                    'id' => 'queue_status_refresh_time',
                                                                                                    'onchange' => 'queueStatusRefreshTime($(this))',
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
                                                                                    'id' => 'queue-status-report-grid-index', // TODO : Add Grid Widget ID
                                                                                    'dataProvider' => $dataProvider,
                                                                                    'layout' => Yii::$app->layoutHelper->get_layout_without_pager('#queue-status-report-search-form'),
                                                                                    //'showOnEmpty' => true,
                                                                                    'columns' => [
                                                                                        [
                                                                                            'class' => 'yii\grid\SerialColumn',
                                                                                            'headerOptions' => ['class' => 'width-10'],
                                                                                            'contentOptions' => ['class' => 'width-10'],
                                                                                        ],

                                                                                        [
                                                                                            'attribute' => 'queue',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->queue) ? QueueMaster::getQueueName($model->queue) : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'total_calls',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->total_calls) ? $model->total_calls : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'calls_in_queue',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->calls_in_queue) ? $model->calls_in_queue : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'abandoned_calls',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->abandoned_calls) ? $model->abandoned_calls : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'logged_in_agents',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->logged_in_agents) ? $model->logged_in_agents : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'avg_call_duration',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return ((!empty($model->avg_call_duration) && $model->avg_call_duration > 0) ? gmdate('H:i:s', $model->avg_call_duration) : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'avg_queue_wait_time',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                return ((!empty($model->avg_queue_wait_time) && $model->avg_queue_wait_time > 0) ? gmdate("H:i:s", $model->avg_queue_wait_time) : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'longest_queue_wait_time',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return ((!empty($model->longest_queue_wait_time) && $model->longest_queue_wait_time > 0) ? gmdate('H:i:s', $model->longest_queue_wait_time) : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'longest_abandoned_calls_wait_time',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                return ((!empty($model->longest_abandoned_calls_wait_time) && $model->longest_abandoned_calls_wait_time > 0) ? gmdate("H:i:s", $model->longest_abandoned_calls_wait_time) : '-');
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
            </div>
        </div>
        <!-- tab section :: END -->
    </div>
</div>

<script>
    queueStatusRefInt = setInterval(queueStatusRef, $('#queue_status_refresh_time').val() * 1000);
    var dropVal = $('#queue_status_refresh_time').val();
    function queueStatusRefreshTime(input) {
        dropVal = $('#queue_status_refresh_time').val();
        clearInterval(queueStatusRefInt);
        queueStatusRefInt = setInterval(queueStatusRef, input.val() * 1000)
    }

    function queueStatusRef() {
        $.pjax.reload({
            container: "#queue-status",
            async: false,
            data: "QueueStatusReport[queue]=" + $('select[name="QueueStatusReport[queue]"]').val(),
            replace: false
        })
    }
    $(document).on("pjax:success", function (e) {
        $('#queue_status_refresh_time').val(dropVal);
    });
</script>
