<?php

use app\modules\ecosmob\breaks\models\Breaks;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\supervisor\assets\SupervisorAsset;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use app\modules\ecosmob\supervisor\SupervisorModule;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\extension\models\ExtensionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $extensionInformation */
/* @var $refreshInterval */
/* @var $breakReason */

SupervisorAsset::register($this);

$data = BreakReasonMapping::find()->where(['user_id' => Yii::$app->user->identity->adm_id, 'break_status' => 'In'])->count();

$is_in = 0;
if ($data) {
    $is_in = 1;
}

$loginAgent = Yii::$app->user->identity->adm_id;

$dataList = array();
$campaignList = CampaignMappingUser::find()->select('campaign_id')->where(['supervisor_id' => $loginAgent])->asArray()->all();

$ids = implode(",", array_map(function ($a) {
    return implode("~", $a);
}, $campaignList));

$campaignListData = Campaign::find()->select(['cmp_id', 'cmp_name'])
    ->andWhere(new Expression('FIND_IN_SET(cmp_id,"' . $ids . '")'))
    ->andWhere(['cmp_status' => 'Active'])
    ->asArray()->all();

$campaignListType = ArrayHelper::map($campaignListData, 'cmp_id', 'cmp_name');
?>
<script type="text/javascript">
    var extensionNumber = '<?php echo $extensionInformation['em_extension_number']; ?>'
    var extensionPassword = '<?php echo $extensionInformation['em_password']; ?>'
    var extensionName = '<?php echo $extensionInformation['em_extension_name']; ?>'
    var refreshInterval = '<?php echo $refreshInterval; ?>'
    // var ringFile = '<?php //echo Url::to('@web' . '/media/recordings/12121435421212_5a16c955-7a87-9f05-e34c-96bb2bd8b485.wav'); ?>'

</script>
<audio id="audio-container" controls autoplay="autoplay" controlsList="nodownload" playsinline
       style="display: none;"></audio>
<div id="">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div id="card-stats">
                    <div style="float:right;">
                        <?php echo Html::Button(SupervisorModule::t('supervisor', 'break_in'), ['class' => 'btn btn-basic In', 'style' => ($is_in) ? 'display: none' : 'display:inline-block']) ?>
                        <?php echo Html::Button(SupervisorModule::t('supervisor', 'break_out'), ['class' => 'btn btn-basic Out', 'style' => (!$is_in) ? 'display:none' : 'display:inline-block']) ?>
                        <?= Html::a(SupervisorModule::t('supervisor', 'agents'), ['/supervisor/supervisor/login-link/'], ['class' => 'btn btn-basic Agents']) ?>

                    </div>
                    <div class="row">
                        <?php $form = ActiveForm::begin([
                            'class' => 'row',
                            'fieldConfig' => [
                                'options' => [
                                    'class' => 'input-field col s12'
                                ],
                            ],
                        ]);
                        ?>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field col s12" id="campaign-cmp_type">
                                    <div class="select-wrapper">
                                        <?= $form->field($breakReason, 'camp_id', ['options' => ['class' => '']])->dropDownList($campaignListType, ['prompt' => SupervisorModule::t('supervisor', 'prompt_camp1')])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="card animate fadeLeft">
                                <div class="card-content cyan white-text">
                                    <p style="text-align: left;font-size: 20px;font-weight: bold;"><?php echo SupervisorModule::t('supervisor', 'live_q_call'); ?>
                                    </p>
                                    <h4 class="card-stats-number white-text"><i
                                                class="material-icons"
                                                style="float: left;font-size: 35px;">phone</i><span
                                                class="activeCallsCount">0</span></h4>
                                    <p><?php echo SupervisorModule::t('supervisor', 'live_call'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="card animate fadeLeft">
                                <div class="card-content cyan white-text">
                                    <p style="text-align: left;font-size: 20px;font-weight: bold;"><?php echo SupervisorModule::t('supervisor', 'live_session'); ?>
                                    </p>
                                    <h4 class="card-stats-number white-text"><i
                                                class="material-icons"
                                                style="float: left;font-size: 35px;">perm_media</i><span
                                                class="agent_count">0</span></h4>
                                    <span><?= SupervisorModule::t('supervisor', 'agents_logged_in') ?></span><br/>
                                    <span class="supervisor_count">0 <?= SupervisorModule::t('supervisor', 'superwisor_logged_in') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="card animate fadeLeft">
                                <div class="card-content cyan white-text">
                                    <p style="text-align: left;font-size: 20px;font-weight: bold;">
                                        <?php echo SupervisorModule::t('supervisor', 'agent_call_sts'); ?>
                                    </p>
                                    <h4 class="card-stats-number white-text"><i
                                                class="material-icons"
                                                style="float: left;font-size: 35px;">perm_identity</i><span
                                                class="total">0</span>
                                    </h4>

                                    <span class="cmp_type"> 0 <?= SupervisorModule::t('supervisor', 'in_break') ?></span><br/>
                                    <span class="available">0 <?= SupervisorModule::t('supervisor', 'agents_available') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row active-calls">
                    <div class="col-xl-9 col-md-7 col-xs-12">
                        <div class="row">
                            <div class="col s12">
                                <div class="profile-contain">
                                    <div class="section section-data-tables">
                                        <div class="row">
                                            <div class="col s12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <?php Pjax::begin(['id' => 'active_calls_grid', 'timeout' => false, 'enablePushState' => false]); ?>
                                                        <?php Pjax::end(); ?>
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
                    <div class="col-xl-9 col-md-7 col-xs-12">
                        <div class="row">
                            <div class="col s12">
                                <div class="profile-contain">
                                    <div class="section section-data-tables">
                                        <div class="row">
                                            <div class="col s12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <?php Pjax::begin(['id' => 'waiting_member_grid', 'timeout' => false, 'enablePushState' => false]); ?>
                                                        <?php Pjax::end(); ?>
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
                <div class="row active-calls">
                    <div class="col-xl-9 col-md-7 col-xs-12">
                        <div class="row">
                            <div class="col s12">
                                <div class="profile-contain">
                                    <div class="section section-data-tables">
                                        <div class="row">
                                            <div class="col s12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <?php Pjax::begin(['id' => 'active_agent_grid', 'timeout' => false, 'enablePushState' => false]); ?>
                                                        <?php Pjax::end(); ?>
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

<!--<a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a>-->

<div id="modal1" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?php echo SupervisorModule::t('supervisor', 'break_rsn'); ?></h5>
            <span aria-hidden="true" class="close">&times;</span>
        </div>
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'id' => 'supervisor-submit-break-form',
            'action' => ['supervisor/supervisor/submit-break-reason'],
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field'
                ],
            ],
        ]); ?>
        <div class="modal-body">
            <div class="campaign-form" id="break-form">
                <div class="row pl-2 pr-2">
                    <div class="col s12">
                        <div class="input-field" id="week_off_queue">
                            <div class="select-wrapper">
                                <?php
                                $breakList = Breaks::find()->select(['br_id', 'br_reason'])->all();
                                $breakList = ArrayHelper::map($breakList, 'br_id', 'br_reason');
                                ?>
                                <?= $form->field($breakReason, 'break_reason', ['options' => ['class' => '']])->dropDownList($breakList, ['prompt' => SupervisorModule::t('supervisor', 'prompt_break')])->label(SupervisorModule::t('supervisor', 'select_break')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?= Html::submitButton(SupervisorModule::t('supervisor', 'submit'), [
                'class' => 'btn waves-effect waves-light amber darken-4',
                'name' => 'apply',
                'value' => 'update']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        if ($('#campaign-cmp_type').find(':selected').val() == '') {
            $('#active_calls_grid').html('<?=SupervisorModule::t('supervisor', 'no_active_calls_available')?>');
            $('#waiting_member_grid').html('<?=SupervisorModule::t('supervisor', 'no_waiting_members_available')?>');
            $('#active_agent_grid').html('<?=SupervisorModule::t('supervisor', 'no_agents_available')?>');
            //return false;
        }
        //activeCallsList();
        calculateTime();
        $('.modal').modal({dismissible: false});
    });
    $(document).on("click", ".In", function () {
        $('.modal').modal('open');
    });

    $(document).on("click", ".Out", function () {
        $('.Out').hide();
        $('.In').show();
        submitBreakReason('out');
    });

    function submitBreakReason(type) {
        var data = $('#supervisor-submit-break-form').serializeArray();
        data.push({'name': 'type', 'value': type});
        $.ajax({
            async: false,
            data: data,
            type: 'POST',
            url: baseURL + "index.php?r=supervisor/supervisor/submit-break-reason",
            success: function (result) {
                if (type == 'out')
                    window.location.reload();
            }
        });
        $('.In').hide();
        $('.Out').show();
    }

    $(document).on("submit", "#supervisor-submit-break-form", function (e) {
        e.preventDefault();
        submitBreakReason('in');
        document.getElementById("supervisor-submit-break-form").reset();
        $('.modal').modal('close');
    });

    $(document).on('change', '#campaign-cmp_type', function () {
        activeCallsList();
        activeAgentList();
        waitingMemberList();
        if ($(this).find(':selected').val() == '') {
            $(".activeCallsCount").text("0");
            $(".cmp_type").text("0 <?=SupervisorModule::t('supervisor', 'in_break')?>");
            $(".available").text("0 <?=SupervisorModule::t('supervisor', 'agents_available')?>");
            $(".total").text("0");
            $(".agent_count").text("0");
            $(".supervisor_count").text("0 <?=SupervisorModule::t('supervisor', 'superwisor_logged_in')?>");
        } else {
            var cmp_type = $(this).find(':selected').val();
            $.ajax({
                type: 'POST',
                data: "camp_type=" + cmp_type,
                url: baseURL + "index.php?r=supervisor/supervisor/break-count",
                success: function (result) {
                    //console.log(result)
                    let final_data = $.parseJSON(result);
                    $(".activeCallsCount").text(final_data.activeCallsCount);
                    $(".cmp_type").text(final_data.breakCount + " <?=SupervisorModule::t('supervisor', 'in_break')?>");
                    $(".available").text(final_data.agentsAvailableList + " <?=SupervisorModule::t('supervisor', 'agents_available')?>");
                    $(".total").text(final_data.total);
                    $(".agent_count").text(final_data.agentCount);
                    console.log('supervisor_count', final_data.supervisorCount);
                    $(".supervisor_count").text(final_data.supervisorCount + " <?=SupervisorModule::t('supervisor', 'superwisor_logged_in')?>");
                }
            });
        }
    });

    function countvalue() {
        if ($('#breakreasonmapping-camp_id').val() == '') {
            $(".activeCallsCount").text("0");
            $(".cmp_type").text("0 <?=SupervisorModule::t('supervisor', 'in_break')?>");
            $(".available").text("0 <?=SupervisorModule::t('supervisor', 'agents_available')?>");
            $(".total").text("0");
            $(".agent_count").text("0");
            $(".supervisor_count").text("0 <?=SupervisorModule::t('supervisor', 'superwisor_logged_in')?>");
        } else {
            var cmp_type = $('#breakreasonmapping-camp_id').val();
            $.ajax({
                type: 'POST',
                data: "camp_type=" + cmp_type,
                url: baseURL + "index.php?r=supervisor/supervisor/break-count",
                success: function (result) {
                    let final_data = $.parseJSON(result);
                    $(".activeCallsCount").text(final_data.activeCallsCount);
                    $(".cmp_type").text(final_data.breakCount + " <?=SupervisorModule::t('supervisor', 'in_break')?>");
                    $(".available").text(final_data.agentsAvailableList + " <?=SupervisorModule::t('supervisor', 'agents_available')?>");
                    $(".total").text(final_data.total);
                    $(".agent_count").text(final_data.agentCount);
                    $(".supervisor_count").text(final_data.supervisorCount + " <?=SupervisorModule::t('supervisor', 'superwisor_logged_in')?>");

                }
            });
        }
    }

    function calculateTime() {
        $("body .custom_call_agent_time").each(function () {
            if ($(this).html() != "" && $(this).html() != "-") {
                var myTime = "";
                myTime = $(this).html();

                var ss = myTime.split(":");
                var temp = 0;
                temp = parseInt(ss[0]) * 3600;
                temp += parseInt(ss[1]) * 60;
                temp += parseInt(ss[2]);

                var secs = 0;
                secs = Math.round(temp);
                secs = secs + 1;

                var hours = Math.floor(secs / (60 * 60));
                if (hours <= 0)
                    hours = "00";
                else if (hours < 10)
                    hours = "0" + hours;

                var divisor_for_minutes = secs % (60 * 60);
                var minutes = 00;
                if (divisor_for_minutes > 0) {
                    minutes = Math.floor(divisor_for_minutes / 60);
                    if (minutes < 10)
                        minutes = "0" + minutes;
                }
                var divisor_for_seconds = divisor_for_minutes % 60;
                var seconds = Math.ceil(divisor_for_seconds);
                if (seconds <= 0)
                    seconds = "00";
                else if (seconds < 10)
                    seconds = "0" + seconds;
                $(this).html("");
                $(this).html(hours + ":" + minutes + ":" + seconds);
            }
        });

        $("body .custom_queue_joined_time").each(function () {
            if ($(this).html() != "" && $(this).html() != "-") {
                var myTime = "";
                myTime = $(this).html();

                var ss = myTime.split(":");
                var temp = 0;
                temp = parseInt(ss[0]) * 3600;
                temp += parseInt(ss[1]) * 60;
                temp += parseInt(ss[2]);

                var secs = 0;
                secs = Math.round(temp);
                secs = secs + 1;

                var hours = Math.floor(secs / (60 * 60));
                if (hours <= 0)
                    hours = "00";
                else if (hours < 10)
                    hours = "0" + hours;

                var divisor_for_minutes = secs % (60 * 60);
                var minutes = 00;
                if (divisor_for_minutes > 0) {
                    minutes = Math.floor(divisor_for_minutes / 60);
                    if (minutes < 10)
                        minutes = "0" + minutes;
                }
                var divisor_for_seconds = divisor_for_minutes % 60;
                var seconds = Math.ceil(divisor_for_seconds);
                if (seconds <= 0)
                    seconds = "00";
                else if (seconds < 10)
                    seconds = "0" + seconds;
                $(this).html("");
                $(this).html(hours + ":" + minutes + ":" + seconds);
            }
        });
        setTimeout(function () {
            calculateTime();
        }, 1000);
    }

    /* Active Call List */
    function activeCallsList() {

        var temp_camp = "";

        if ($('#campaign-cmp_type').find(':selected').val() == '') {
            $('#active_calls_grid').html('<?=SupervisorModule::t('supervisor', 'no_active_calls_available')?>');
            return false;
        }

        if ($('#campaign-cmp_type').find(':selected').val() != "") {
            temp_camp = "&camp_id=" + $('#campaign-cmp_type').find(':selected').val();
        }

        $.ajax({
            url: baseURL + "index.php?r=supervisor/supervisor/new-active-call-list" + temp_camp,
            type: 'GET',
            success: function (result) {
                $('#active_calls_grid').html('');
                if (result) {
                    $('#active_calls_grid').html(result);
                } else {
                    $('#active_calls_grid').html('<?=SupervisorModule::t('supervisor', 'no_active_calls_available')?>');
                }
                setTimeout(function () {
                    activeCallsList();
                    countvalue();
                    activeAgentList();
                    waitingMemberList();
                    console.log('refreshInterval', refreshInterval);
                }, refreshInterval);

            }
        });
    }

    /* Active Agents List */
    function activeAgentList() {
        var temp_camp = "";
        if ($('#campaign-cmp_type').find(':selected').val() == '') {
            $('#active_agent_grid').html('<?=SupervisorModule::t('supervisor', 'no_agents_available')?>');
            return false;
        }

        if ($('#campaign-cmp_type').find(':selected').val() != "") {
            temp_camp = "&camp_id=" + $('#campaign-cmp_type').find(':selected').val();
        }

        $.ajax({
            url: baseURL + "index.php?r=supervisor/supervisor/active-agent-list" + temp_camp,
            type: 'GET',
            success: function (result) {
                $('#active_agent_grid').html('');
                if (result) {
                    $('#active_agent_grid').html(result);
                } else {
                    $('#active_agent_grid').html('<?=SupervisorModule::t('supervisor', 'no_agents_available')?>');
                }
            }
        });
    }


    /*  Member Waiting List  */

    function waitingMemberList() {
        var temp_camp = "";

        if ($('#campaign-cmp_type').find(':selected').val() == '') {
            $('#waiting_member_grid').html('<?=SupervisorModule::t('supervisor', 'no_waiting_members_available')?>');
            return false;
        }

        if ($('#campaign-cmp_type').find(':selected').val() != "") {
            temp_camp = "&camp_id=" + $('#campaign-cmp_type').find(':selected').val();
        }

        $.ajax({
            url: baseURL + "index.php?r=supervisor/supervisor/waiting-members" + temp_camp,
            type: 'GET',
            success: function (result) {
                $('#waiting_member_grid').html('');
                if (result) {
                    $('#waiting_member_grid').html(result);
                } else {
                    $('#waiting_member_grid').html('<?=SupervisorModule::t('supervisor', 'no_waiting_members_available')?>');
                }
            }
        });
    }

    $(document).on("click", ".close", function () {
        $('.modal').modal('close');
    });
</script>