<?php

use app\assets\AuthAsset;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\ConstantHelper;

/* @var $searchModel */
/* @var $dataProvider */

$this->title = 'Campaign Performance';
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
                    <li class=""><a href="<?= Url::to(['/realtimedashboard/user-monitor/index']) ?>">Agent monitor</a>
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
                    <li class="tab"><a class="active"
                                           href="<?= Url::to(['/realtimedashboard/campaign-performance/index']) ?>">Campaign
                            Performance</a></li>
                <?php } ?>
            </ul>
            <div class="mt-1 tab-content">

                <!-- Campaign Performance :: BEGIN -->
                <div id="test5" class="col s12 tab-content-section">
                    <?php Pjax::begin(['id' => 'campaign-performance', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                                            <h6 class="m-0">Campaign Performance</h6>
                                                                            <div class="table-actions">
                                                                                <div class="d-flex align-items-center gap-1">
                                                                                    <div class="table-action">
                                                                                        <?php echo Html::a('<img src="'.Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg".'"
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
                                                                                            'onclick' => 'campRef()',
                                                                                        ])
                                                                                        ?>
                                                                                        Auto Refresh in : <p
                                                                                                class="campaign-performance-refresh-time">
                                                                                            <?= Html::dropDownList('camp_refresh_time', $refreshTime,
                                                                                                ConstantHelper::getRefreshTime(), [
                                                                                                    'class' => 'form-control refresh-dropdown',
                                                                                                    'id' => 'camp_refresh_time',
                                                                                                    'onchange' => 'campRefreshTime($(this))',
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
                                                                                    'id' => 'campaign-performance-report-grid-index', // TODO : Add Grid Widget ID
                                                                                    'dataProvider' => $dataProvider,
                                                                                    'layout' => Yii::$app->layoutHelper->get_layout_without_pager('#campaign-performance-report-search-form'),
                                                                                    //'showOnEmpty' => true,
                                                                                    'columns' => [
                                                                                        [
                                                                                            'class' => 'yii\grid\SerialColumn',
                                                                                            'headerOptions' => ['class' => 'width-10'],
                                                                                            'contentOptions' => ['class' => 'width-10'],
                                                                                        ],

                                                                                        [
                                                                                            'attribute' => 'cmp_name',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'total_agent_login',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'total_calls',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'live_calls',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],

                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'answered',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],

                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'abandoned',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],

                                                                                        ],
                                                                                        /*[
                                                                                            'attribute' => 'total_leads',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'dial_leads',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'rechurn_leads',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'contacted_leads',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],

                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'noncontacted_leads',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                        ],*/
                                                                                        [
                                                                                            'attribute' => 'avg_call_duration',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->avg_call_duration) ? gmdate("H:i:s", $model->avg_call_duration) : '-');
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'avg_wrap_up_time',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'value' => function ($model) {
                                                                                                return (!empty($model->avg_wrap_up_time) ? gmdate("H:i:s", $model->avg_wrap_up_time) : '-');
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
                <!-- Campaign Performance :: BEGIN -->

            </div>
        </div>
        <!-- tab section :: END -->
    </div>
</div>

<script>
    refInt = setInterval(campRef, $('#camp_refresh_time').val() * 1000);
    var dropVal = $('#camp_refresh_time').val();
    function campRefreshTime(input) {
        dropVal = $('#camp_refresh_time').val();
        clearInterval(refInt);
        refInt = setInterval(campRef, input.val() * 1000)
    }

    function campRef() {
        $.pjax.reload({
            container: "#campaign-performance",
            async: false,
            data: "CampaignPerformance[cmp_id]=" + $('select[name="CampaignPerformance[cmp_id]"]').val(),
            replace: false
        })
    }
    $(document).on("pjax:success", function (e) {
        $('#camp_refresh_time').val(dropVal);
    });
</script>
