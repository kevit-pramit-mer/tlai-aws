<?php

use app\assets\AuthAsset;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use app\modules\ecosmob\realtimedashboard\RealTimeDashboardModule;
use yii\helpers\Url;
use app\components\ConstantHelper;

/* @var $activeCallsSearchModel */
/* @var $activeCallsDataProvider */
/* @var $totalExtension */
/* @var $registerExtension */
/* @var $notRegisterExtension */
/* @var $inCall */
/* @var $available */
/* @var $dnd */
/* @var $away */
/* @var $ringing */
/* @var $sipRegSearchModel */
/* @var $sipRegDataProvider */

$this->title = RealTimeDashboardModule::t('realtimedashboard', 'real_time_dashboard');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = RealTimeDashboardModule::t('admin', 'admin_dashboard');
AuthAsset::register($this);

$refreshTime = 5;
$globalConfig = GlobalConfig::find()->where(['gwc_key' => 'realtime_dashboard_refresh_time'])->one();
if(!empty($globalConfig)){
    if(!empty($globalConfig->gwc_value)){
        $refreshTime = $globalConfig->gwc_value;
    }
}
?>

<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12 main-dashboard-data">
        <!-- tab section :: BEGIN -->
        <div class="col s12 theme-tabs">
            <ul class="tabs d-flex align-items-center">
                <li class="tab"><a class="active" href="<?= Url::to(['/realtimedashboard/sip-extension/index']) ?>">SIP Extension Registration Status</a></li>
                <li class="tab"><a href="#test2">CC User Monitor</a></li>
                <li class="tab"><a href="#test3">Queue Status</a></li>
                <li class="tab"><a href="<?= Url::to(['/realtimedashboard/active-calls/index']) ?>">Active Calls</a></li>
                <li class="las-tab"><a href="#test5">Campaign Performance</a></li>
            </ul>
            <div class="mt-2 tab-content">
                <!-- SIP Extension Registration Status :: BEGIN -->
                <div id="test1" class="col s12 tab-content-section">
                    <div id="card-stats" class="mt-0 panel-set col s12">
                        <!-- Extension Summary Statistics data :: BEGIN -->
                        <div class="call-summary-data">
                            <div class="card animate fadeRight p-5">
                                <div class="panel-heading d-flex align-items-center">
                                    <span>Extension Summary</span>
                                </div>
                                <div class="row statics-data">
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                            <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/total-extentions.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="total_extension"><?= $totalExtension ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Total Extension</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                            <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/registered-extension.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="reg_extension"><?= $registerExtension ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Registered Extension</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                            <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/not-registered.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="not_reg_extension"><?= $notRegisterExtension ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Not Registered</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3" id="in_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/in-call.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="in_call"><?= $inCall ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">In Call</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3" id="available_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                            <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/available.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="available_call"><?= $available ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Available</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3" id="dnd_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                            <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/dnd.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="dnd_call"><?= $dnd ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Do Not Disturb(DND)</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3" id="away_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                            <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/away.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="away_call"><?= $away ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Away</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3" id="ringing_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/ringing.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="ringing_call"><?= $ringing ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Ringing</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Extension Summary Statistics data :: END -->
                    </div>
                    <!-- Table section :: BEGIN -->
                    <div class="panel-set col s12 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h6 class="m-0">SIP Extension Registration Status Listings</h6>
                                    <div class="table-actions">
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg" ?>" alt="Icon" />
                                                <?php echo Html::a('Export to CSV',
                                                    ['sip-reg-export'],
                                                    [
                                                        'id' => 'hov',
                                                        //'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        'data-pjax' => 0,
                                                        'options' => ['style' => 'color:black']

                                                    ])
                                                ?>
                                            </div>
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/refresh-icon.svg" ?>" alt="Icon" />
                                                Auto Refresh in : <p class="sip-reg-refresh-time">
                                                    <?= Html::dropDownList('sip_reg_refresh_time', $refreshTime,
                                                        ConstantHelper::getRefreshTime(), [
                                                            'class' => 'form-control',
                                                            'id' => 'sip_reg_refresh_time',
                                                            'onchange' => 'sipRegRefreshTime($(this))',
                                                        ])?> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php Pjax::begin(['id' => 'sip-reg', 'timeout' => 0, 'enablePushState' => false]); ?>
                            <div class="card-body">

                                <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                    <?php try {
                                        echo GridView::widget([
                                            'id' => 'sip-reg-grid', // TODO : Add Grid Widget ID
                                            'dataProvider' => $sipRegDataProvider,
                                            'filterModel' => $sipRegSearchModel,
                                            'layout' => Yii::$app->layoutHelper->get_layout_without_pager(''),
                                            'filterRowOptions' => ['class' => 'sip-reg-filter'],
                                            'columns' => [
                                                [
                                                    'class' => 'yii\grid\SerialColumn'
                                                ],
                                                [
                                                    'attribute' => 'sip_user',
                                                    'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'enableSorting' => True,
                                                ],
                                                [
                                                    'attribute' => 'network_ip',
                                                    'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'enableSorting' => True,
                                                ],
                                                [
                                                    'attribute' => 'network_port',
                                                    'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'enableSorting' => True,
                                                ],
                                                [
                                                    'attribute' => 'expires',
                                                    'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'enableSorting' => True,
                                                    'value' => function ($model) {
                                                        return date('D M d h:i:s', strtotime(\app\components\CommonHelper::tsToDt(date('Y-m-d H:i:s', $model->expires))));
                                                    },
                                                    'filter' => false
                                                ],
                                                [
                                                    'attribute' => 'status',
                                                    'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'enableSorting' => True,
                                                    'format' => 'raw',
                                                    'value' => function ($model) {
                                                        if (strtoupper($model->status) == 'EARLY') {
                                                            return '<span class="badge bg-early">' . $model->status . '</span>';
                                                        } elseif (strtoupper($model->status) == 'ACTIVE') {
                                                            return '<span class="badge bg-active">' . $model->status . '</span>';
                                                        } elseif (strtoupper($model->status) == 'RINGING') {
                                                            return '<span class="badge bg-ringing">' . $model->status . '</span>';
                                                        } elseif (strtoupper($model->status) == 'AWAY') {
                                                            return '<span class="badge bg-away">' . $model->status . '</span>';
                                                        } elseif (strtoupper($model->status) == 'ONLINE') {
                                                            return '<span class="badge bg-online">' . $model->status . '</span>';
                                                        } else {
                                                            return '<span class="badge bg-other">' . $model->status . '</span>';
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
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                    <!-- Table section :: END -->
                </div>
                <!-- SIP Extension Registration Status :: END -->

                <!-- CC User Monitor :: BEGIN -->
                <div id="test2" class="col s12 tab-content-section">
                    <div id="card-stats" class="mt-0 panel-set col s12">
                        <!-- User Summary Statistics data :: BEGIN -->
                        <div class="call-summary-data">
                            <div class="card animate fadeRight p-5">
                                <div class="panel-heading d-flex align-items-center">
                                    <span>User Summary</span>
                                </div>
                                <div class="row statics-data">
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                            <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/total-extentions.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title">1000</p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Total Logged in User</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                            <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/registered-extension.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title">800</p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Total Available User</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                            <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/not-registered.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title">200</p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Total In-call User</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img  src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/status.svg" ?>" class="statics-icon-img" alt="ICON" />
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title">100</p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Status 4</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- User Summary Statistics data :: END -->
                    </div>
                    <!-- search section :: BEGIN -->
                    <div class="panel-set col s12 mt-2">
                        <div class="card">
                            <div class="card-header boder-bottom0">
                                <h6 class="m-0">Search</h6>
                            </div>
                            <div class="card-body">
                                <div class="col s12 m6 l3">
                                    <div class="input-field">
                                        <label class="control-label" for="username">User Name</label>
                                        <input type="text" id="username" class="mg-t6" name="uname" >
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col s12 m6 l3">
                                    <div class="input-field">
                                        <label class="control-label" for="Campaign">Campaign</label>
                                        <input type="text" id="Campaign" class="mg-t6" name="uname" >
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col s12 m6 l3">
                                    <div class="input-field">
                                        <label class="control-label" for="Queue">Queue</label>
                                        <input type="text" id="Queue" class="mg-t6" name="uname" >
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col s12 m6 l3">
                                    <div class="input-field">
                                        <label class="control-label" for="usertype">User Type</label>
                                        <input type="text" id="usertype" class="mg-t6" name="uname" >
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col s12 pb-1 mt-1">
                                    <button type="button" class="btn waves-effect waves-light amber darken-4 submitfrom">Search</button>
                                    <button class="btn waves-effect waves-light bg-gray-200 ml-1">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- search section :: END -->

                    <!-- Table section :: BEGIN -->
                    <div class="panel-set col s12 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h6 class="m-0">User Listing</h6>
                                    <div class="table-actions">
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg" ?>" alt="Icon" />
                                                Export to CSV
                                            </div>
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/refresh-icon.svg" ?>" alt="Icon" />
                                                Auto Refresh in : <p class="refresh-time">00:05 Sec</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body"></div>
                        </div>
                    </div>
                    <!-- Table section :: END -->
                </div>
                <!-- CC User Monitor :: BEGIN -->

                <!-- Queue Status :: BEGIN -->
                <div id="test3" class="col s12 tab-content-section">
                    <!-- search section :: BEGIN -->
                    <div class="panel-set col s12 mt-2">
                        <div class="card">
                            <div class="card-header boder-bottom0">
                                <h6 class="m-0">Search</h6>
                            </div>
                            <div class="card-body">
                                <div class="col s12 m6 l4">
                                    <div class="input-field">
                                        <label class="control-label" for="username">Queue Name</label>
                                        <input type="text" id="username" class="mg-t6" name="uname" >
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col s12 m6 l4 mt-3 pb-2">
                                    <button type="button" class="btn waves-effect waves-light amber darken-4 submitfrom">Search</button>
                                    <button class="btn waves-effect waves-light bg-gray-200 ml-1">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- search section :: END -->

                    <!-- Table section :: BEGIN -->
                    <div class="panel-set col s12 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h6 class="m-0">Queue Listing</h6>
                                    <div class="table-actions">
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg" ?>" alt="Icon" />
                                                Export to CSV
                                            </div>
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/refresh-icon.svg" ?>" alt="Icon" />
                                                Auto Refresh in : <p class="refresh-time">00:05 Sec</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body"></div>
                        </div>
                    </div>
                    <!-- Table section :: END -->
                </div>
                <!-- Queue Status :: BEGIN -->

                <!-- Active Calls :: BEGIN -->
                <div id="test4" class="col s12 tab-content-section">
                    <!-- Table section :: BEGIN -->
                    <div class="panel-set col s12 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h6 class="m-0">Active Calls Listing</h6>
                                    <div class="table-actions">
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg" ?>" alt="Icon" />
                                                <?php echo Html::a('Export to CSV',
                                                    ['active-calls-export'],
                                                    [
                                                        'id' => 'hov',
                                                        //'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        'data-pjax' => 0,
                                                        'options' => ['style' => 'color:black']

                                                    ])
                                                ?>
                                            </div>
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/refresh-icon.svg" ?>" alt="Icon" />
                                                Auto Refresh in : <p class="active-calls-refresh-time">
                                                    <?= Html::dropDownList('active_calls_refresh_time', $refreshTime,
                                                        ConstantHelper::getRefreshTime(), [
                                                            'class' => 'form-control',
                                                            'id' => 'active_calls_refresh_time',
                                                            'onchange' => 'activeCallsRefreshTime($(this))',
                                                        ])?> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php Pjax::begin(['id' => 'active-calls', 'timeout' => 0, 'enablePushState' => false]); ?>
                            <div class="card-body">

                                <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                    <?php try {
                                        echo GridView::widget([
                                            'id' => 'active-calls-grid', // TODO : Add Grid Widget ID
                                            'dataProvider' => $activeCallsDataProvider,
                                            'filterModel' => $activeCallsSearchModel,
                                            'layout' => Yii::$app->layoutHelper->get_layout_without_pager(''),
                                            'filterRowOptions' => ['class' => 'active-calls-filter'],
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
                                                ],
                                                [
                                                    'attribute' => 'ip_addr',
                                                    'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'enableSorting' => True,
                                                ],
                                                [
                                                    'attribute' => 'initial_ip_addr',
                                                    'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                    'enableSorting' => True,
                                                ],
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
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                    <!-- Table section :: END -->
                </div>
                <!-- Active Calls :: BEGIN -->

                <!-- Campaign Performance :: BEGIN -->
                <div id="test5" class="col s12 tab-content-section">
                    <!-- search section :: BEGIN -->
                    <div class="panel-set col s12 mt-2">
                        <div class="card">
                            <div class="card-header boder-bottom0">
                                <h6 class="m-0">Search</h6>
                            </div>
                            <div class="card-body">
                                <div class="col s12 m6 l4">
                                    <div class="input-field">
                                        <label class="control-label" for="username">Campaign Name</label>
                                        <input type="text" id="username" class="mg-t6" name="uname" >
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col s12 m6 l4 mt-3 pb-2">
                                    <button type="button" class="btn waves-effect waves-light amber darken-4 submitfrom">Search</button>
                                    <button class="btn waves-effect waves-light bg-gray-200 ml-1">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- search section :: END -->

                    <!-- Table section :: BEGIN -->
                    <div class="panel-set col s12 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h6 class="m-0">Campaign Listing</h6>
                                    <div class="table-actions">
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg" ?>" alt="Icon" />
                                                Export to CSV
                                            </div>
                                            <div class="table-action">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/refresh-icon.svg" ?>" alt="Icon" />
                                                Auto Refresh in : <p class="refresh-time">00:05 Sec</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body"></div>
                        </div>
                    </div>
                    <!-- Table section :: END -->
                </div>
                <!-- Campaign Performance :: BEGIN -->
            </div>
        </div>
        <!-- tab section :: END -->
    </div>
</div>

<script>
    $(document).ready(function () {
        $.ajax({
            url: baseURL + "index.php?r=realtimedashboard/real-time-dashboard/get-data",
            success: function (result) {
                let final_data = $.parseJSON(result);
                $('#total_extension').text(final_data.totalExtension);
                $('#reg_extension').text(final_data.registerExtension);
                $('#not_reg_extension').text(final_data.notRegisterExtension);
                if (final_data.inCall > 0) {
                    $('#in_call_div').show();
                    $('#in_call').text(final_data.inCall);
                } else {
                    $('#in_call_div').hide();
                }
                if (final_data.available > 0) {
                    $('#available_call_div').show();
                    $('#available_call').text(final_data.available);
                } else {
                    $('#available_call_div').hide();
                }
                if (final_data.dnd > 0) {
                    $('#dnd_call_div').show();
                    $('#dnd_call').text(final_data.dnd);
                } else {
                    $('#dnd_call_div').hide();
                }
                if (final_data.away > 0) {
                    $('#away_call_div').show();
                    $('#away_call').text(final_data.away);
                } else {
                    $('#away_call_div').hide();
                }
                if (final_data.ringing > 0) {
                    $('#ringing_call_div').show();
                    $('#ringing_call').text(final_data.ringing);
                } else {
                    $('#ringing_call_div').hide();
                }
            }
        });
    });


    sipRegRefInt = setInterval(sipRegRef, $('#sip_reg_refresh_time').val() * 1000);
    function sipRegRefreshTime(input){
        clearInterval(sipRegRefInt);
        sipRegRefInt = setInterval(sipRegRef,  input.val() * 1000)
    }
    function sipRegRef() {
        $.pjax.reload({
            container:"#sip-reg", data: "SipPresence[sip_user]="+$('input[name="SipPresence[sip_user]"]').val()+"&SipPresence[network_ip]="+$('input[name="SipPresence[network_ip]"]').val()+"&SipPresence[network_port]="+$('input[name="SipPresence[network_port]"]').val()+"&SipPresence[status]="+$('input[name="SipPresence[status]"]').val(), replace: false})

        $.ajax({
            url: baseURL + "index.php?r=realtimedashboard/real-time-dashboard/get-data",
            success: function (result) {
                let final_data = $.parseJSON(result);
                $('#total_extension').text(final_data.totalExtension);
                $('#reg_extension').text(final_data.registerExtension);
                $('#not_reg_extension').text(final_data.notRegisterExtension);
                if(final_data.inCall > 0) {
                    $('#in_call_div').show();
                    $('#in_call').text(final_data.inCall);
                }else{
                    $('#in_call_div').hide();
                }
                if(final_data.available > 0) {
                    $('#available_call_div').show();
                    $('#available_call').text(final_data.available);
                }else{
                    $('#available_call_div').hide();
                }
                if(final_data.dnd > 0) {
                    $('#dnd_call_div').show();
                    $('#dnd_call').text(final_data.dnd);
                }else{
                    $('#dnd_call_div').hide();
                }
                if(final_data.away > 0) {
                    $('#away_call_div').show();
                    $('#away_call').text(final_data.away);
                }else{
                    $('#away_call_div').hide();
                }
                if(final_data.ringing > 0) {
                    $('#ringing_call_div').show();
                    $('#ringing_call').text(final_data.ringing);
                }else{
                    $('#ringing_call_div').hide();
                }
            }
        });
    }

    activeCallsRefInt = setInterval(activeCallsRef, $('#active_calls_refresh_time').val() * 1000);
    function activeCallsRefreshTime(input){
        clearInterval(activeCallsRefInt);
        activeCallsRefInt = setInterval(activeCallsRef,  input.val() * 1000)
    }
    function activeCallsRef() {
        $.pjax.reload({
            container:"#active-calls", async: false, data: "Channels[cid_num]="+$('input[name="Channels[cid_num]"]').val()+"&Channels[dest]="+$('input[name="Channels[dest]"]').val()+"&Channels[ip_addr]="+$('input[name="Channels[ip_addr]"]').val()+"&Channels[initial_ip_addr]="+$('input[name="Channels[initial_ip_addr]"]').val()+"&Channels[callstate]="+$('input[name="Channels[callstate]"]').val(), replace: false})
    }



</script>
