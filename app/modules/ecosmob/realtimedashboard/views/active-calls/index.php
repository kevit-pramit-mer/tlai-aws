<?php

use app\assets\AuthAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\ConstantHelper;

/* @var $activeCallsSearchModel */
/* @var $activeCallsDataProvider */

$this->title = 'Active Calls';
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
                    <li class=""><a href="<?= Url::to(['/realtimedashboard/queue-status/index']) ?>">Queue
                            Status</a></li>
                <?php } ?>
                <?php if (in_array('/realtimedashboard/active-calls/index', $permissions)) { ?>
                    <li class="tab"><a class="active" href="<?= Url::to(['/realtimedashboard/active-calls/index']) ?>">Active
                            Calls</a>
                    </li>
                <?php } ?>
                <?php if (in_array('/realtimedashboard/campaign-performance/index', $permissions)) { ?>
                    <li class=""><a href="<?= Url::to(['/realtimedashboard/campaign-performance/index']) ?>">Campaign
                            Performance</a></li>
                <?php } ?>
            </ul>
            <div class="mt-1 tab-content">
                <div id="test4" class="col s12 tab-content-section">
                    <?php Pjax::begin(['id' => 'active-calls', 'timeout' => 0, 'enablePushState' => false]); ?>
                    <div class="row">
                        <div class="col-xl-9 col-md-7 col-xs-12">
                            <div class="row">
                                <div class="col s12">
                                    <div class="profile-contain">
                                        <div class="section section-data-tables">
                                            <div class="row ml-0 mr-0">
                                                <div class="col s12 search-filter">
                                                    <?= $this->render('_search', ['model' => $activeCallsSearchModel]); ?>
                                                </div>
                                                <div class="col s12">
                                                    <div class="card mt-1">
                                                        <div class="card-content realtime-card-content">
                                                            <div class="panel-set col s12">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <div class="d-flex align-items-center">
                                                                            <h6 class="m-0">Active Calls</h6>
                                                                            <div class="table-actions">
                                                                                <div class="d-flex align-items-center gap-1">
                                                                                    <div class="table-action">
                                                                                        <?php echo Html::a('<img src="'.Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg".'"
                                                                                             alt="Icon"/> Export to CSV',
                                                                                            ['active-calls-export'],
                                                                                            [
                                                                                                'id' => 'hov',
                                                                                                'data-pjax' => '0',
                                                                                                //'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                                                                'options' => ['style' => 'color:black']

                                                                                            ])
                                                                                        ?>
                                                                                    </div>
                                                                                    <div class="table-action">
                                                                                        <?php echo Html::a( '<img src="'.Yii::getAlias('@web') . "/theme/assets/images/refresh-icon.svg".'"
                                                                                             alt="Icon"/>','javascript:void(0);', [
                                                                                            'id' => 'hov',
                                                                                            'data-pjax' => '0',
                                                                                            'onclick' => 'activeCallsRef()',
                                                                                        ])
                                                                                        ?>
                                                                                        Auto Refresh in : <p
                                                                                                class="active-calls-refresh-time">
                                                                                            <?= Html::dropDownList('active_calls_refresh_time', $refreshTime,
                                                                                                ConstantHelper::getRefreshTime(), [
                                                                                                    'class' => 'form-control refresh-dropdown',
                                                                                                    'id' => 'active_calls_refresh_time',
                                                                                                    //'data' => ['pjax' => '0'],
                                                                                                    'option'=>['style' => 'width:90px;'],
                                                                                                    'onchange' => 'activeCallsRefreshTime($(this))',
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
                                                                                    'id' => 'active-calls-grid', // TODO : Add Grid Widget ID
                                                                                    'dataProvider' => $activeCallsDataProvider,
                                                                                    'layout' => Yii::$app->layoutHelper->get_layout_without_pager('#active-calls-report-search-form'),
                                                                                    //'showOnEmpty' => true,
                                                                                    'columns' => [
                                                                                        [
                                                                                            'class' => 'yii\grid\SerialColumn'
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'cid_num',
                                                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'enableSorting' => True,
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'dest',
                                                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                $dest = explode('-', $model->dest);
                                                                                                return (count($dest) > 1 ? $dest[1] : $model->dest);
                                                                                            },
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'ip_addr',
                                                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'enableSorting' => True,
                                                                                        ],
                                                                                        /* [
                                                                                             'attribute' => 'initial_ip_addr',
                                                                                             'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                             'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                             'enableSorting' => True,
                                                                                         ],*/
                                                                                        [
                                                                                            'attribute' => 'created_epoch',
                                                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                $first_date = new DateTime(date('Y-m-d H:i:s'));
                                                                                                $second_date = new DateTime(date('Y-m-d H:i:s', $model->created_epoch));
                                                                                                $interval = $first_date->diff($second_date);
                                                                                                return $interval->format('%H:%I:%S');
                                                                                            },
                                                                                            'filter' => false
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'callstate',
                                                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                                            'enableSorting' => True,
                                                                                            'format' => 'raw',
                                                                                            'value' => function ($model) {
                                                                                                if ($model->callstate == 'EARLY') {
                                                                                                    return '<span class="badge bg-early">' . $model->callstate . '</span>';
                                                                                                } elseif ($model->callstate == 'ACTIVE') {
                                                                                                    return '<span class="badge bg-active">' . $model->callstate . '</span>';
                                                                                                } elseif ($model->callstate == 'RINGING') {
                                                                                                    return '<span class="badge bg-ringing">' . $model->callstate . '</span>';
                                                                                                } else {
                                                                                                    return '<span class="badge bg-other">' . $model->callstate . '</span>';
                                                                                                }
                                                                                            },
                                                                                        ],
                                                                                    ],
                                                                                    'tableOptions' => [
                                                                                        'class' => 'display dataTable dtr-inline'
                                                                                    ],
                                                                                ]);
                                                                            } catch (Exception $e) {
                                                                            }
                                                                            ?>
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

    activeCallsRefInt = setInterval(activeCallsRef, $('#active_calls_refresh_time').val() * 1000);
    var dropVal = $('#active_calls_refresh_time').val();
    function activeCallsRefreshTime(input) {
        dropVal = $('#active_calls_refresh_time').val();
        clearInterval(activeCallsRefInt);
        activeCallsRefInt = setInterval(activeCallsRef, input.val() * 1000)
    }

    function activeCallsRef() {
        $.pjax.reload({
            container: "#active-calls",
            async: false,
            data: "Channels[cid_num]=" + $('input[name="Channels[cid_num]"]').val() + "&Channels[dest]=" + $('input[name="Channels[dest]"]').val() + "&Channels[callstate]=" + $('#channels-callstate').val(),
            replace: false
        });
    }
    $(document).on("pjax:success", function (e) {
        $('#active_calls_refresh_time').val(dropVal);
    });
</script>
