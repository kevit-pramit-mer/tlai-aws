<?php

use app\assets\AuthAsset;
use app\models\SipPresence;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\ConstantHelper;

/* @var $totalExtension */
/* @var $registerExtension */
/* @var $notRegisterExtension */
/* @var $inCall */
/* @var $available */
/* @var $dnd */
/* @var $online */
/* @var $away */
/* @var $ringing */
/* @var $sipRegSearchModel */
/* @var $sipRegDataProvider */
/* @var $onThePhone */
/* @var $busy */

$this->title = 'SIP Extension Registration Status';
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
                    <li class="tab"><a class="active" href="<?= Url::to(['/realtimedashboard/sip-extension/index']) ?>">SIP
                            Extension
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
                    <li class=""><a href="<?= Url::to(['/realtimedashboard/campaign-performance/index']) ?>">Campaign
                            Performance</a></li>
                <?php } ?>
            </ul>
            <div class="mt-1 tab-content">
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
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/total-extentions.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title"
                                                       id="total_extension"><?= $totalExtension ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Total Extension</h4>
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
                                                       id="reg_extension"><?= $registerExtension ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Registered Extension</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/not-registered.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title"
                                                       id="not_reg_extension"><?= $notRegisterExtension ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Not Registered</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3 d-none" id="in_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/online-user.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="in_call"><?= $online ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Online</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3 d-none" id="available_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/available.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title"
                                                       id="available_call"><?= $available ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Available</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3 d-none" id="dnd_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/dnd.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="dnd_call"><?= $dnd ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Do Not Disturb(DND)</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3 d-none" id="away_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/away.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="away_call"><?= $away ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Away</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3 d-none" id="ringing_call_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/ringing.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="ringing_call"><?= $ringing ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Ringing</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3 d-none" id="on_the_phone_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/on-the-phone.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="on_the_phone"><?= $onThePhone ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">On The Phone</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s12 m4 l3 d-none" id="busy_div">
                                        <div class="card animate fadeLeft">
                                            <div class="card-content">
                                                <img src=" <?= Yii::getAlias('@web') . "/theme/assets/images/real-time-dashboard/on-the-phone.svg" ?>"
                                                     class="statics-icon-img" alt="ICON"/>
                                                <div class="card-stats-title">
                                                    <p class="card-counter-title" id="busy"><?= $busy ?></p>
                                                    <h4 class="card-stats-number m-0" id="CPS">Busy</h4>
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
                    <?php Pjax::begin(['id' => 'sip-reg', 'timeout' => 0, 'enablePushState' => false]); ?>
                    <div class="row">
                        <div class="col-xl-9 col-md-7 col-xs-12">
                            <div class="row">
                                <div class="col s12 mt-1">
                                    <div class="profile-contain">
                                        <div class="section section-data-tables">
                                            <div class="row ml-0 mr-0">
                                                <div class="col s12 search-filter">
                                                    <?= $this->render('_search', ['model' => $sipRegSearchModel]); ?>
                                                </div>
                                                <div class="col s12">
                                                    <div class="card mt-0">
                                                        <div class="card-content realtime-card-content">
                                                            <div class="panel-set col s12 mt-1">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <div class="d-flex align-items-center">
                                                                            <h6 class="m-0">SIP Extension Registration
                                                                                Status</h6>
                                                                            <div class="table-actions">
                                                                                <div class="d-flex align-items-center gap-1">
                                                                                    <div class="table-action">
                                                                                        <?php echo Html::a('<img src="'.Yii::getAlias('@web') . "/theme/assets/images/expert-icon.svg".'"
                                                                                             alt="Icon"/> Export to CSV',
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
                                                                                        <?php echo Html::a( '<img src="'.Yii::getAlias('@web') . "/theme/assets/images/refresh-icon.svg".'"
                                                                                             alt="Icon"/>','javascript:void(0);', [
                                                                                            'id' => 'hov',
                                                                                            'data-pjax' => '0',
                                                                                            'onclick' => 'sipRegRef()',
                                                                                        ])
                                                                                        ?>
                                                                                        Auto Refresh in : <p
                                                                                                class="sip-reg-refresh-time">
                                                                                            <?= Html::dropDownList('sip_reg_refresh_time', $refreshTime,
                                                                                                ConstantHelper::getRefreshTime(), [
                                                                                                    'class' => 'form-control refresh-dropdown',
                                                                                                    'id' => 'sip_reg_refresh_time',
                                                                                                    'onchange' => 'sipRegRefreshTime($(this))',
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
                                                                                    'id' => 'sip-reg-grid', // TODO : Add Grid Widget ID
                                                                                    'dataProvider' => $sipRegDataProvider,
                                                                                    'layout' => Yii::$app->layoutHelper->get_layout_without_pager('#sip-reg-report-search-form'),
                                                                                    'showOnEmpty' => true,
                                                                                    'columns' => [
                                                                                        [
                                                                                            'class' => 'yii\grid\SerialColumn'
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'sip_user',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'network_ip',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function($model){
                                                                                                $networkIP = $model->network_ip;
                                                                                                $userAgent = explode('::', $model->user_agent);
                                                                                                if(isset($userAgent[1])){
                                                                                                    if(str_contains($userAgent[1], 'client_ip')){
                                                                                                        $ip = explode('=', $userAgent[1]);
                                                                                                        if(isset($ip[1])){
                                                                                                            $networkIP = $ip[1];
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                                return $networkIP;
                                                                                            }
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'network_port',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'user_agent',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                $userAgent = explode('::', $model->user_agent);
                                                                                                if(isset($userAgent[1])) {
                                                                                                    $str = strtolower($userAgent[0]);
                                                                                                    $search = strtolower('SIP.js');
                                                                                                    if (preg_match("~\b" . strtolower($search) . "\b~", strtolower($str))) {
                                                                                                        return 'WebRTC Client';
                                                                                                    } else {
                                                                                                        return $userAgent[0];
                                                                                                    }
                                                                                                }else{
                                                                                                    return $model->user_agent;
                                                                                                }
                                                                                            },
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'expires',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'value' => function ($model) {
                                                                                                return date('D M d h:i:s', strtotime(\app\components\CommonHelper::tsToDt(date('Y-m-d H:i:s', $model->expires))));
                                                                                            },
                                                                                            'filter' => false
                                                                                        ],
                                                                                        [
                                                                                            'attribute' => 'status',
                                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                                            'enableSorting' => True,
                                                                                            'format' => 'raw',
                                                                                            'value' => function ($model) {
                                                                                                $getStatus = SipPresence::getStatus($model->sip_host, $model->sip_user);
                                                                                                $status = '';
                                                                                                if (!empty($getStatus)) {
                                                                                                    $status = $getStatus->status;
                                                                                                }else{
                                                                                                    $available = Yii::$app->fscoredb->createCommand("SELECT `sip_registrations`.sip_user as sip_user FROM `sip_registrations`  WHERE `sip_registrations`.`sip_host`='".$_SERVER['HTTP_HOST']."' and sip_user not in (select sip_user from sip_presence where sip_host = sip_host and sip_user = ".$model->sip_user.")")->queryOne();
                                                                                                    if(!empty($available)){
                                                                                                        $status = 'Available';
                                                                                                    }
                                                                                                }
                                                                                                if(!empty($status)) {
                                                                                                    $color = "badge bg-" . strtolower(str_replace(" ", "-", $status));
                                                                                                    return '<span class="' . $color . '">' . ucfirst($status) . '</span>';
                                                                                                }else{
                                                                                                    return '-';
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
                    <!-- Table section :: END -->
                    <?php Pjax::end(); ?>
                </div>
                <!-- SIP Extension Registration Status :: END -->
            </div>
        </div>
        <!-- tab section :: END -->
    </div>
</div>

<script>
    $(document).ready(function () {
        $.ajax({
            url: baseURL + "index.php?r=realtimedashboard/sip-extension/get-data",
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
                if (final_data.onThePhone > 0) {
                    $('#on_the_phone_div').show();
                    $('#on_the_phone').text(final_data.onThePhone);
                } else {
                    $('#on_the_phone_div').hide();
                }
                if (final_data.busy > 0) {
                    $('#busy_div').show();
                    $('#busy').text(final_data.busy);
                } else {
                    $('#busy_div').hide();
                }
            }
        });
    });


    sipRegRefInt = setInterval(sipRegRef, $('#sip_reg_refresh_time').val() * 1000);
    var dropVal = $('#sip_reg_refresh_time').val();
    function sipRegRefreshTime(input) {
        dropVal = $('#sip_reg_refresh_time').val();
        clearInterval(sipRegRefInt);
        sipRegRefInt = setInterval(sipRegRef, input.val() * 1000)
    }

    function sipRegRef() {
        $.pjax.reload({
            container: "#sip-reg",
            async: false,
            data: "SipRegistrations[sip_user]=" + $('input[name="SipRegistrations[sip_user]"]').val() + "&SipRegistrations[status]=" + $('#sipregistrations-status').val(),
            replace: false
        })

        $.ajax({
            url: baseURL + "index.php?r=realtimedashboard/sip-extension/get-data",
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
                if (final_data.onThePhone > 0) {
                    $('#on_the_phone_div').show();
                    $('#on_the_phone').text(final_data.onThePhone);
                } else {
                    $('#on_the_phone_div').hide();
                }
                if (final_data.busy > 0) {
                    $('#busy_div').show();
                    $('#busy').text(final_data.busy);
                } else {
                    $('#busy_div').hide();
                }
            }
        });
    }
    $(document).on("pjax:success", function (e) {
        $('#sip_reg_refresh_time').val(dropVal);
    });

</script>
