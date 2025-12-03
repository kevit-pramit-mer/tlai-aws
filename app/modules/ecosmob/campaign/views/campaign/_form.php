<?php

use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\queue\models\QueueMaster;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\campaign\CampaignModule;
use app\modules\ecosmob\campaign\assets\CampaignAsset;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\services\models\Services;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\campaign\models\Campaign */
/* @var $form yii\widgets\ActiveForm */
/* @var $timeZoneList */
/* @var $queueList */
/* @var $leadGroupList */
/* @var $supervisors */
/* @var $agents */
/* @var $availableSupervisorsUpdate */
/* @var $availableAgentsUpdate */
/* @var $dispositionList */
/* @var $scriptList */
/* @var $trunkList */
/* @var $availableSupervisors */
/* @var $availableAgents */

CampaignAsset::register($this);
?>
<?php
$extensionLists = Extension::find()->where(['em_status' => '1'])->all();
foreach ($extensionLists as $ext) {
    $ext->em_extension_name = $ext->em_extension_name . '-' . $ext->em_extension_number;
}

$ext = ArrayHelper::map($extensionLists, 'em_id', 'em_extension_name');
$campQueue = Campaign::find()->select(['cmp_queue_id']);
if(!$model->isNewRecord) {
    $campQueue = $campQueue->andWhere(['!=', 'cmp_id', $model->cmp_id]);
}
$campQueue = $campQueue->asArray()->all();

$queueIds = array_column($campQueue, 'cmp_queue_id');

$campaignQueue = QueueMaster::find()->select(['qm_id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->andWhere(['NOT IN', 'qm_id', $queueIds])->asArray()->all();

?>

<div class="row">
    <div class="col s12">
        <?php $form=ActiveForm::begin([
            'class'=>'row',
            'id'=>'campaign-form-1',
            //'validateOnSubmit' => false,
            'fieldConfig'=>[
                'options'=>[
                    'class'=>'input-field'
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div> 
            <div class="card-content">
                <div class="campaign-form"
                     id="campaign-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'cmp_name', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength'=>true, 'placeholder' => ($model->getAttributeLabel('cmp_name'))])->label(CampaignModule::t('campaign', 'cmp_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                                <div class="select-wrapper">
                                    <?php
                                    $timeZoneList=ArrayHelper::map($timeZoneList, 'tz_id', 'tz_zone');
                                    echo $form->field($model, 'cmp_timezone', ['options'=>['class'=>'input-field']])->dropDownList($timeZoneList, ['prompt'=>CampaignModule::t('campaign', 'select_tz')])->label(CampaignModule::t('campaign', 'tz'));
                                    ?>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field" id="campaign_cmp_type">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'cmp_type', ['options'=>['class'=>'']])->dropDownList(['Inbound'=>CampaignModule::t('campaign', 'inbound'), 'Outbound'=>CampaignModule::t('campaign', 'outbound')
                                        //'Blended'=>CampaignModule::t('campaign', 'blended')
                                       ], ['prompt'=>CampaignModule::t('campaign', 'select_camp_type')])->label(CampaignModule::t('campaign', 'camp_type')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="input-field" id="dialer_type">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'cmp_dialer_type', ['options'=>['class'=>'']])
                                        ->dropDownList(['AUTO'=>CampaignModule::t('campaign', 'auto'),
                                            'PREVIEW'=>CampaignModule::t('campaign', 'preview')
                                              ],
                                            ['prompt'=>CampaignModule::t('campaign', 'select_dial_type'), 'id'=>'dialer_id'])->label(CampaignModule::t('campaign', 'dial_type').' <span style="color: red">*</span>'); ?>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!--Week of Activity Start-->
                    <div class="row" id="week_off_main_type">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'cmp_week_off_type', ['options'=>['class'=>'', 'id'=>'week_off_type']])
                                        ->dropDownList(Services::getCampServices(),
                                            ['prompt'=>CampaignModule::t('campaign', 'select_WO_type')])
                                        ->label(CampaignModule::t('campaign', 'WO_type')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div id="">
                                <?= $form->field($model, 'cmp_week_off_name', ['options' => ['class' => 'mb-2', 'id' => 'select_action_value']])
                                    ->dropDownList([], ['prompt' => Yii::t('app', 'select')])
                                    ->label(CampaignModule::t('campaign', 'WO_name').' <span style="color: red">*</span>');
                                   ?>
                            </div>

                            <?= $form->field($model, 'cmp_week_off_name')
                                ->textInput(['maxlength' => TRUE, 'id' => 'input_action_value'])
                                ->label(CampaignModule::t('campaign', 'WO_name').' <span style="color: red">*</span>'); ?>
                        </div>
                      <!--  <div class="col s12 m6">
                            <div class="input-field" id="week_off_internal">
                                <div class="select-wrapper">
                                    <?php
/*                                    echo $form->field($model, 'cmp_week_off_name', ['options'=>['class'=>'', 'id'=>'select_week_off_internal']])->dropDownList($ext, ['prompt'=>CampaignModule::t('campaign', 'select_internal')])->label(CampaignModule::t('campaign', 'WO_name').' <span style="color: red">*</span>', ['class' => 'weekoff-int']);
                                    */?>
                                </div>
                            </div>
                            <div class="col s12 input-field input-right p-0" id="week_off_external">
                                <?php /*= $form->field($model, 'cmp_week_off_name')->textInput(['maxlength'=>true, 'placeholder' => ($model->getAttributeLabel('cmp_week_off_name')), 'class'=>'', 'id'=>'select_week_off_external'])->label(CampaignModule::t('campaign', 'WO_name').' <span style="color: red">*</span>', ['class' => 'weekoff-ext']); */?>
                            </div>
                            <div class="input-field" id="week_off_queue">
                                <div class="select-wrapper">
                                    <?php
/*                                    $queueList=ArrayHelper::map($queueList, 'qm_id', 'qm_name');
                                    echo $form->field($model, 'cmp_week_off_name', ['options'=>['class'=>'', 'id'=>'select_week_off_queue']])->dropDownList($queueList, ['prompt'=>CampaignModule::t('campaign', 'select_q')])->label(CampaignModule::t('campaign', 'WO_name').' <span style="color: red">*</span>', ['class' => 'weekoff-queue']);
                                    */?>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <!--Week off Activity End-->

                    <!--Holiday of Activity Start-->
                    <div class="row" id="holiday_main_type">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'cmp_holiday_type', ['options'=>['class'=>'', 'id'=>'holiday_type']])
                                        ->dropDownList(Services::getCampServices(), ['prompt'=>CampaignModule::t('campaign', 'select_holiday_type')])
                                        ->label(CampaignModule::t('campaign', 'holiday_type')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div id="">
                                <?= $form->field($model, 'cmp_holiday_name', ['options' => ['class' => 'mb-2', 'id' => 'select_holiday_action_value']])
                                    ->dropDownList([], ['prompt' => Yii::t('app', 'select')])
                                    ->label(CampaignModule::t('campaign', 'holiday_name').' <span style="color: red">*</span>');
                                ?>
                            </div>

                            <?= $form->field($model, 'cmp_holiday_name')
                                ->textInput(['maxlength' => TRUE, 'id' => 'input_holiday_action_value'])
                                ->label(CampaignModule::t('campaign', 'holiday_name').' <span style="color: red">*</span>'); ?>
                        </div>

                        <!--<div class="col s12 m6">
                            <div class="input-field" id="holiday_internal">
                                <div class="select-wrapper">
                                    <?php
/*                                    echo $form->field($model, 'cmp_holiday_name', ['options'=>['class'=>'', 'id'=>'select_holiday_internal']])->dropDownList($ext, ['prompt'=>CampaignModule::t('campaign', 'select_internal')])->label(CampaignModule::t('campaign', 'holiday_name').' <span style="color: red">*</span>', ['class' => 'holiday-int']);
                                    */?>
                                </div>
                            </div>
                            <div class="col s12 input-field input-right p-0" id="holiday_external">
                                <?php /*= $form->field($model, 'cmp_holiday_name')->textInput(['maxlength'=>true, 'placeholder' => ($model->getAttributeLabel('cmp_holiday_name')), 'class'=>'', 'id'=>'select_holiday_external'])->label(CampaignModule::t('campaign', 'holiday_name').' <span style="color: red">*</span>', ['class' => 'holiday-ext']); */?>
                            </div>
                            <div class="input-field" id="holiday_queue">
                                <div class="select-wrapper">
                                    <?php
/*                                    $queueListData=ArrayHelper::map($queueList, 'qm_id', 'qm_name');
                                    echo $form->field($model, 'cmp_holiday_name', ['options'=>['class'=>'', 'id'=>'select_holiday_queue']])->dropDownList($queueListData, ['prompt'=>CampaignModule::t('campaign', 'select_q')])->label(CampaignModule::t('campaign', 'holiday_name').' <span style="color: red">*</span>', ['class' => 'holiday-queue']);
                                    */?>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <!--Holiday off Activity End-->

                    <div class="row" id="inbound-hide">
                        <div class="col s12 m6">
                            <div class="input-field input-right">
                                <?= $form->field($model, 'cmp_caller_id')->textInput(['maxlength'=>true, 'placeholder' => ($model->getAttributeLabel('cmp_caller_id')), 'onkeypress' => 'return isNumberKey(event);'])->label(CampaignModule::t('campaign', 'caller_id').' <span style="color: red">*</span>'); ?>

                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="input-field input-right">
                                <?= $form->field($model, 'cmp_caller_name')->textInput(['maxlength'=>true, 'placeholder' => ($model->getAttributeLabel('cmp_caller_name'))])->label(CampaignModule::t('campaign', 'caller_name').' <span style="color: red">*</span>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?php
                                    $leadGroupList=ArrayHelper::map($leadGroupList, 'ld_id', 'ld_group_name');
                                    echo $form->field($model, 'cmp_lead_group', ['options'=>['class'=>'']])->dropDownList($leadGroupList, ['prompt'=>CampaignModule::t('campaign', 'select_lead_grp')])->label(CampaignModule::t('campaign', 'lead_grp'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field" id="disposition_type">
                                <div class="select-wrapper">
                                    <?php
                                    $dispositionList=ArrayHelper::map($dispositionList, 'ds_id', 'ds_name');
                                    echo $form->field($model, 'cmp_disposition', ['options'=>['class'=>'']])->dropDownList($dispositionList, ['prompt'=>CampaignModule::t('campaign', 'select_despo')])->label(CampaignModule::t('campaign', 'disposition'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?php
                                    $scriptList=ArrayHelper::map($scriptList, 'scr_id', 'scr_name');
                                    echo $form->field($model, 'cmp_script', ['options'=>['class'=>'']])->dropDownList($scriptList, ['prompt'=>CampaignModule::t('campaign', 'select_script')])->label(CampaignModule::t('campaign', 'script'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field" id="week_off_queue">
                                <div class="select-wrapper">
                                    <?php
                                    $campQueueListData=ArrayHelper::map($campaignQueue, 'qm_id', 'qm_name');
                                    echo $form->field($model, 'cmp_queue_id', ['options'=>['class'=>'',]])->dropDownList($campQueueListData, ['prompt'=>CampaignModule::t('campaign', 'select_q')])->label(CampaignModule::t('campaign', 'q').' <span style="color: red">*</span>');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'cmp_status', ['options'=>['class'=>'']])->dropDownList
                                    (['Active'=>Yii::t('app','active'), 'Inactive'=>Yii::t('app','inactive'),], ['prompt'=>CampaignModule::t('campaign', 'select_status')])->label(CampaignModule::t('campaign', 'status')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <?= $form->field($model, 'cmp_description')->textarea(['rows'=>6, 'class'=>'materialize-textarea', 'placeholder' => ($model->getAttributeLabel('cmp_description'))])->label(CampaignModule::t('campaign', 'description')); ?>
                            </div>
                        </div>
                    </div>
                    <!--------- Start Assigned Supervisor  ---------->
                    <div class="row ">
                        <div class="col s12 m6 p-0 align-items-center">
                            <div class="form-group field-groups-group_status required">
                                <h6 class="col s12 agent-heading">
                                    <i class="material-icons">verified_user</i>
                                    <b class="agent-title"><?= CampaignModule::t('campaign', 'assign_supervisor'); ?> <span style="color: red">*</span></b>
                                </h6>
                                <div class="col s5">
                                    <label class="tag tag-pill tag-danger">
                                        <?php echo CampaignModule::t('campaign', 'available') ?></label>

                                    <select id="availableSupervisors"
                                            size="8"
                                            multiple="multiple"
                                            class="multiselect form-control"
                                            data-target="avaliable">

                                        <?php if ($model->isNewRecord) {

                                            foreach ($availableSupervisors as $key=>$value) {
                                                echo "<option value='"
                                                    . $key . "'>" . $value
                                                    . "</option>";
                                            }
                                        } else {
                                            foreach (
                                                $availableSupervisorsUpdate
                                                as $key=>$value
                                            ) {
                                                echo "<option value='"
                                                    . $key . "'>" . $value
                                                    . "</option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col s2 btn_div p-0 multiselect-btn-pad ">
                                <button type="button" id="btnRightSupervisor"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='avaliable'>
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </button>
                                <button type="button" id="btnLeftSuperviosr"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='assigned'>
                                    <i class="material-icons">keyboard_arrow_left</i>
                                </button>
                            </div>
                            <div class="form-group field-groups-group_status required">
                                <div class="col s5">
                                    <label class="tag tag-pill tag-success">
                                        <?php echo CampaignModule::t('campaign', 'assigned') ?>
                                    </label>
                                    <select id="assignedSupervisors"
                                            multiple size="7"
                                            class="multiselect form-control list"
                                            data-target="avaliable">
                                        <?php if ($model->isNewRecord) {

                                        } else {
                                            foreach (
                                                $supervisors as $key=>
                                                $value
                                            ) {
                                                echo "<option value='"
                                                    . $key . "'>" . $value
                                                    . "</option>";
                                            }
                                        } ?>

                                    </select>
                                    <?php
                                    echo $form->field($model, 'supervisors')->hiddenInput(['id'=>'selectedSupervisors'])->label(FALSE)
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!---------  Supervisor Assigned End --------------------------->
                        <!---------  Agents Assigned End --------------------------->

                        <div class="col s12 m6 p-0 align-items-center">
                            <div class="form-group field-groups-group_status required">
                                <h6 class="col s12 agent-heading">
                                    <i class="material-icons">verified_user</i>
                                    <b class="agent-title"><?= CampaignModule::t('campaign', 'assign_agents'); ?> <span style="color: red">*</span></b>
                                </h6>
                                <div class="col s5">
                                    <label class="tag tag-pill tag-danger">
                                        <?php echo CampaignModule::t('campaign', 'available') ?></label>

                                    <select id="availableAgents"
                                            size="8"
                                            multiple="multiple"
                                            class="multiselect form-control"
                                            data-target="avaliable">

                                        <?php
                                        if ($model->isNewRecord) {

                                            foreach ($availableAgents as $key=>$value) {
                                                echo "<option value='"
                                                    . $key . "'>" . $value
                                                    . "</option>";
                                            }
                                        } else {
                                            foreach (
                                                $availableAgentsUpdate
                                                as $key=>$value
                                            ) {
                                                echo "<option value='"
                                                    . $key . "'>" . $value
                                                    . "</option>";
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col s2 btn_div p-0 multiselect-btn-pad">
                                <button type="button" id="btnRight"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='avaliable'>
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </button>
                                <button type="button" id="btnLeft"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='assigned'>
                                    <i class="material-icons">keyboard_arrow_left</i>
                                </button>
                            </div>
                            <div class="form-group field-groups-group_status required">
                                <div class="col s5">
                                    <label class="tag tag-pill tag-success">
                                        <?php echo CampaignModule::t('campaign', 'assigned') ?>
                                    </label>
                                    <select id="assignedAgents"
                                            multiple size="7"
                                            class="multiselect form-control list"
                                            data-target="avaliable">
                                        <?php if ($model->isNewRecord) {

                                        } else {
                                            foreach (
                                                $agents as $key=>
                                                $value
                                            ) {
                                                echo "<option value='"
                                                    . $key . "'>" . $value
                                                    . "</option>";
                                            }
                                        } ?>

                                    </select>
                                    <?php
                                    echo $form->field($model, 'agents')->hiddenInput(['id'=>'selectedAgents'])->label(FALSE)
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!--End Assigned Agents   -->

                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(CampaignModule::t('campaign', 'cancel'), ['index', 'page'=>Yii::$app->session->get('page')],
                    ['class'=>'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? CampaignModule::t('campaign', 'create') : CampaignModule::t('campaign', 'update'), ['class'=>$model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                    'btn waves-effect waves-light cyan accent-8']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">
    /*FOR UNCONDITIONAL HIDE SHOW  START*/

    var weekOffType = "<?= $model->cmp_week_off_type ?>";
    var holidayType = "<?= $model->cmp_holiday_type ?>";
    var weekOffTypeValue = '<?= $model->cmp_week_off_name ?>';
    var holidayActionValue = '<?= $model->cmp_holiday_name ?>';

    $(document).ready(function () {
        $('#dialer_type').trigger('change');
        $('#campaign-cmp_type').trigger('change');
        $('.field-input_action_value').hide();
        $('#select_action_value').hide();
        $('.field-input_holiday_action_value').hide();
        $('#select_holiday_action_value').hide();

        /*$('#dialer_type').find('select').attr('disabled', 'disabled');*/
        if ($('#campaign_cmp_type').find(':selected').val() == '') {
            $('#dialer_type').find('select').attr('disabled', 'disabled');
            $('#dialer_type').hide();
            $('.field-campaign-cmp_queue_id').hide();
            $('#inbound-hide').hide();
        } else {
            $('#dialer_type').find('select').removeAttr('disabled')
        }
        setTimeout(function () {
            $('#week_off_type').trigger('change');
            $('#holiday_type').trigger('change');
            $('#campaign_type').trigger('change');
            changeWeekOffAction(weekOffTypeValue);
            changeHolidayAction(holidayActionValue);
        }, 500);
    });

    /*        document.addEventListener('DOMContentLoaded', function() {
                var elems = document.querySelectorAll('select');
                var instances = M.FormSelect.init(elems);
                for (i = 0; i < instances.length; i++) {
                    instances[i].destroy();
                }
            });*/

    /* Dialer type according to Campaign type hide show start */

    $(document).on('change', '#campaign-cmp_type', function () {
        if ($(this).val() == 'Blended') {
            $("#dialer_id").append(new Option("AUTO", "AUTO"));
            $("#dialer_id").val("AUTO");
            $('#trunk_type').show();
            $('#dialratio').show();
            $('#week_off_main_type').show();
            $('#holiday_main_type').show();
            //$('#disposition_type').show();
            $('#dialer_type').show();
        	$('#inbound-hide').show();

            $("#dialer_id").select2();
            $('#dialer_type').find('select').attr('readOnly');
            $('#dialer_type').find('select').removeAttr('disabled');
            $('#dialer_type').find('select').attr('disabled', 'disabled');

            /*$('#dialer_type').find('select').prop('readonly', true);*/
            $('.field-campaign-cmp_queue_id').show();

        } else if ($(this).val() == 'Inbound') {
            $("#dialer_id").val("");
            $('#trunk_type').hide();
            $('#dialratio').hide();
            $('#inbound-hide').hide();
            $('#week_off_main_type').show();
            $('#holiday_main_type').show();
            //$('#disposition_type').show();
            $("#dialer_id").val("");
            $('#dialer_type').hide();
            $('.field-campaign-cmp_queue_id').show();

        } else if ($(this).val() == 'Outbound') {
            $('#trunk_type').show();
            $('#dialer_id option[value="AUTO"]').remove();
            $('#dialratio').show();
            $('#week_off_main_type').hide();
            $('#holiday_main_type').hide();
            //$('#disposition_type').hide();
        	$('#inbound-hide').show();
            $('#dialer_type').show();
            /*$("#dialer_id").val("");*/
            $("#dialer_id").select2();
            // $('.toSelect2').select2('val','');
            $('#dialer_type').find('select').removeAttr('disabled');
            $('.field-campaign-cmp_queue_id').hide();
        } else {
            $('#week_off_main_type').show();
            $('#holiday_main_type').show();
            //$('#disposition_type').show();
            $('#trunk_type').show();
            $('#inbound-hide').show();
            $('#dialratio').show();
            $('#dialer_type').show();
            $('#dialer_type').find('select').removeAttr('disabled');
            $('.field-campaign-cmp_queue_id').hide();
        }
    });
    /* Dialer type according to Campaign type hide show start */

    /* Week Off type hide show Start */

    $(document).on('change', '#campaign-cmp_week_off_type', function () {
        changeWeekOffAction('');
    });

    function changeWeekOffAction(action_value) {
        var action_id = $('#campaign-cmp_week_off_type').val();

        if (action_id != '') {
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['change-action']); ?>',
                data: {_csrf : csrfToken, 'action_id': action_id, 'action_value': action_value},
                success: function (data) {
                    if (action_id == '6') { // external
                        // show textbox
                        $('.field-input_action_value').show();
                        // remove disabled from textbox
                        $('#input_action_value').removeAttr('disabled');
                        $('#input_action_value').val(action_value);

                        // hide select
                        $('#select_action_value').hide();
                        // add disabled in input
                        $('#campaign-cmp_week_off_name').attr('disabled', 'disabled');


                    } else {
                        $('#input_action_value').attr('disabled', 'disabled');
                        $('#campaign-cmp_week_off_name').removeAttr('disabled');
                        $('#campaign-cmp_week_off_name').select2();
                        $('#campaign-cmp_week_off_name').html(data);
                        $('#campaign-cmp_week_off_name').formSelect();
                        $('#select_action_value').show();
                        $('.field-input_action_value').hide();
                    }
                    $('#campaign-cmp_week_off_name').select2('destroy');
                    $('#campaign-cmp_week_off_name').select2();
                }
            });
        }else{
            $('.field-input_action_value').hide();
            $('#select_action_value').hide();
        }
    }

    /*$(document).on('change', '#week_off_type', function () {

        if(weekOffType == 'INTERNAL'){
            $('#select_week_off_external').val('');
            $('#select_week_off_queue option:selected').prop("selected", false);
        }else if(weekOffType == 'EXTERNAL'){
            $('#select_week_off_internal option:selected').prop("selected", false);
            $('#select_week_off_queue option:selected').prop("selected", false);
        }else if(weekOffType == 'QUEUE') {
            $('#select_week_off_internal option:selected').prop("selected", false);
            $('#select_week_off_external').val('');
        }else{
            $('#select_week_off_internal option:selected').prop("selected", false);
            $('#select_week_off_external').val('');
            $('#select_week_off_queue option:selected').prop("selected", false);
        }

        if ($(this).find(':selected').val() == 'INTERNAL') {

            // select box will appear and remove disable
            $("#week_off_internal").show();
            $('#week_off_internal').find('select').removeAttr('disabled');

            // input box will hide and add disable

            $("#week_off_external").hide();
            $('#week_off_external').find('input').attr('disabled', 'disabled');

            $("#week_off_queue").hide();
            $('#week_off_queue').find('select').attr('disabled', 'disabled');
            $('#select_week_off_internal select').select2();

            $(".weekoff-int").text('Extension Number');

        } else if ($(this).find(':selected').val() == 'EXTERNAL') {

            // input box will appear and remove disable
            $("#week_off_external").show();
            $('#week_off_external').find('input').removeAttr('disabled');

            // select box will hide and add disable
            $("#week_off_internal").hide();
            $('#week_off_internal').find('select').attr('disabled', 'disabled');

            $("#week_off_queue").hide();
            $('#week_off_queue').find('select').attr('disabled', 'disabled');
            $('#select_week_off_external select').select2();

            $(".weekoff-ext").text('External Number');

        } else if ($(this).find(':selected').val() == 'QUEUE') {

            // input box will appear and remove disable
            $("#week_off_queue").show();
            $('#week_off_queue').find('select').removeAttr('disabled');

            // select box will hide and add disable
            $("#week_off_internal").hide();
            $('#week_off_internal').find('select').attr('disabled', 'disabled');

            $("#week_off_external").hide();
            $('#week_off_external').find('select').attr('disabled', 'disabled');
            $('#select_week_off_queue select').select2();

            $(".weekoff-queue").text('Queue Name');
        } else {
            $("#week_off_external").hide();
            $("#week_off_internal").hide();
            $("#week_off_queue").hide();
            $('#week_off_internal').find('select').attr('disabled', 'disabled');
            $('#week_off_external').find('input').attr('disabled', 'disabled');
            $('#week_off_queue').find('select').attr('disabled', 'disabled');

        }

    });*/
    /*  Week of type hide show end */


    /* Holiday type hide show start */
    $(document).on('change', '#campaign-cmp_holiday_type', function () {
        changeHolidayAction('');
    });

    function changeHolidayAction(action_value) {
        var action_id = $('#campaign-cmp_holiday_type').val();

        if (action_id != '') {
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['change-action']); ?>',
                data: {_csrf : csrfToken, 'action_id': action_id, 'action_value': action_value},
                success: function (data) {
                    if (action_id == '6') { // external
                        // show textbox
                        $('.field-input_holiday_action_value').show();
                        // remove disabled from textbox
                        $('#input_holiday_action_value').removeAttr('disabled');
                        $('#input_holiday_action_value').val(action_value);

                        // hide select
                        $('#select_holiday_action_value').hide();
                        // add disabled in input
                        $('#campaign-cmp_holiday_name').attr('disabled', 'disabled');


                    } else {
                        $('#input_holiday_action_value').attr('disabled', 'disabled');
                        $('#campaign-cmp_holiday_name').removeAttr('disabled');
                        $('#campaign-cmp_holiday_name').select2();
                        $('#campaign-cmp_holiday_name').html(data);
                        $('#campaign-cmp_holiday_name').formSelect();
                        $('#select_holiday_action_value').show();
                        $('.field-input_holiday_action_value').hide();
                    }
                    $('#campaign-cmp_holiday_name').select2('destroy');
                    $('#campaign-cmp_holiday_name').select2();
                }
            });
        }else{
            $('.field-input_holiday_action_value').hide();
            $('#select_holiday_action_value').hide();
        }
    }

   /* $(document).on('change', '#holiday_type', function () {

        if(holidayType == 'INTERNAL'){
            $('#select_holiday_external').val('');
            $('#select_holiday_queue option:selected').prop("selected", false);
        }else if(holidayType == 'EXTERNAL'){
            $('#select_holiday_internal option:selected').prop("selected", false);
            $('#select_holiday_queue option:selected').prop("selected", false);
        }else if(holidayType == 'QUEUE') {
            $('#select_holiday_internal option:selected').prop("selected", false);
            $('#select_holiday_external').val('');
        }else{
            $('#select_holiday_internal option:selected').prop("selected", false);
            $('#select_holiday_external').val('');
            $('#select_holiday_queue option:selected').prop("selected", false);
        }

        if ($(this).find(':selected').val() == 'INTERNAL') {

            // select box will appear and remove disable
            $("#holiday_internal").show();
            $('#holiday_internal').find('select').removeAttr('disabled');

            // input box will hide and add disable
            $("#holiday_external").hide();
            $('#holiday_external').find('input').attr('disabled', 'disabled');

            $("#holiday_queue").hide();
            $('#holiday_queue').find('select').attr('disabled', 'disabled');
            $('#select_holiday_internal select').select2();

            $(".holiday-int").text('Extension Number');
        } else if ($(this).find(':selected').val() == 'EXTERNAL') {

            // input box will appear and remove disable
            $("#holiday_external").show();
            $('#holiday_external').find('input').removeAttr('disabled');

            // select box will hide and add disable
            $("#holiday_internal").hide();
            $('#holiday_internal').find('select').attr('disabled', 'disabled');

            $("#holiday_queue").hide();
            $('#holiday_queue').find('select').attr('disabled', 'disabled');
            $('#select_holiday_external select').select2();

            $(".holiday-ext").text('External Number');
        } else if ($(this).find(':selected').val() == 'QUEUE') {

            // input box will appear and remove disable
            $("#holiday_queue").show();
            $('#holiday_queue').find('select').removeAttr('disabled');

            // select box will hide and add disable
            $("#holiday_internal").hide();
            $('#holiday_internal').find('select').attr('disabled', 'disabled');

            $("#holiday_external").hide();
            $('#holiday_external').find('select').attr('disabled', 'disabled');
            $('#select_holiday_queue select').select2();

            $(".holiday-queue").text('Queue Name');
        } else {
            $("#holiday_external").hide();
            $("#holiday_internal").hide();
            $("#holiday_queue").hide();
            $('#holiday_internal').find('select').attr('disabled', 'disabled');
            $('#holiday_external').find('input').attr('disabled', 'disabled');
            $('#holiday_queue').find('select').attr('disabled', 'disabled');

        }
    });*/
    /* Holiday type hide show end */

</script>

<style type="text/css">
    .mrg-btn {
        margin-bottom: 1em
    }
</style>

<script>
    $(document).ready(function () {

        $('.field-groups-group_status select').formSelect('destroy');
        $('.field-groups-group_status select').css('display', 'block');
        $('.field-groups-group_status select').css('height', '200px');
        $('.field-groups-group_status select').css('border', '1px solid #bdbdbd');

    });
</script>
