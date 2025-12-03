<?php

use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\didmanagement\models\DidManagement;
use app\modules\ecosmob\extension\extensionModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extension\models\Extension */
/* @var $form yii\widgets\ActiveForm */
/* @var $call_setting_model */
/* @var $selectedDid */

$did = DidManagement::find()->select(['did_id', 'did_number'])
    ->andWhere(['IS', 'action_id', null]);
if (!$model->isNewRecord) {
    $did = $did->orWhere(['AND', ['action_id' => '1'], ['action_value' => $model->id]]);
}
$did = $did->asArray()->all();

$did = ArrayHelper::map($did, 'did_id', 'did_number');
?>
<div class="row">
    <div class="col s12 ">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field'
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs mb-2">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="extension-form" id="extension-form">
                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="col s12">
                                <div id="number"
                                     class="input-field <?= (!$model->isNewRecord) ? 'no-padding' : '' ?>">
                                    <?= $form->field($model, 'em_extension_number')
                                        ->textInput(['maxlength' => true, 'onkeypress' => "return isNumberKey(event)", 'onpaste' => "return paste(this)", 'placeholder' => extensionModule::t('app', 'extension_number'),])
                                        ->label(extensionModule::t('app', 'extension_number')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'em_extension_name')
                                    ->textInput(['maxlength' => true,
                                        'placeholder' => extensionModule::t('app', 'extension_name'),
                                    ])
                                    ->label(extensionModule::t('app', 'extension_name')); ?>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?php echo $form->field($model, 'em_status', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                    ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label
                                    (extensionModule::t('app', 'status')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'em_shift_id', ['options' => [
                                    'class' => '',
                                ]])->dropDownList(
                                    $model->shiftList,
                                    ['prompt' => extensionModule::t('app', 'select_shift')])
                                    ->label(extensionModule::t('app', 'shift')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'em_group_id', ['options' => [
                                    'class' => '',
                                ]])->dropDownList(
                                    $model->groupList,
                                    ['prompt' => extensionModule::t('app', 'select_group')])
                                    ->label(extensionModule::t('app', 'group')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">

                                    <?php echo $form->field($model, 'is_phonebook', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                        ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])
                                        ->label(extensionModule::t('app', 'Is Phonebook')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'em_language_id', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(
                                        [1 => Yii::t('app', 'english'), 2 => Yii::t('app', 'spanish')], ['prompt' =>
                                        extensionModule::t('app', 'select_language')])
                                        ->label(extensionModule::t('app', 'language')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'em_timezone_id', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(
                                        $model->timezoneList,
                                        ['prompt' => extensionModule::t('app', 'select_tz')])
                                        ->label(extensionModule::t('app', 'timezone')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 web-password">
                            <?= $form->field($model, 'em_web_password', [
                                'template' => "{label}\n{input}\n<div id=\"passwordStrengthDiv3\" class=\"is0\"></div><br>{hint}\n{error}",
                            ])
                                ->passwordInput(['maxlength' => true, 'id' => 'web_password', 'placeholder' => extensionModule::t('app', 'web_password')
                                ])->label(extensionModule::t('app', 'web_password')); ?>
                            <div class="passowrd-btns">
                                <button type='button'
                                        class='btn waves-effect waves-light amber darken-4 getNewPass'
                                        onclick="generate('web_password',12);"><i class='material-icons'>settings_backup_restore</i>
                                </button>
                                <button type='button'
                                        class='btn waves-effect waves-light amber darken-4 togglePassword web_password'
                                        onclick="togglePassword('web_password');"><i class='material-icons'>visibility_off</i>
                                </button>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'em_email')
                                    ->textInput(['maxlength' => true, 'placeholder' => extensionModule::t('app', 'email'),
                                    ])
                                    ->label(extensionModule::t('app', 'email')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?php echo $form->field($call_setting_model, 'ecs_bypass_media', ['options' => [
                                    'class' => '',
                                ]])->dropDownList(
                                    ['0' => 'No', '1' => 'Bypass', '2' => 'Bypass After Bridge', '3' => 'Proxy Media'])
                                    ->label(extensionModule::t('app', 'Bypass Media')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'external_caller_id')
                                    ->textInput(['maxlength' => true, 'onkeypress' => "return isNumberKey(event)", 'onpaste' => "return paste(this)", 'placeholder' => extensionModule::t('app', 'external_caller_id'),
                                    ])
                                    ->label(extensionModule::t('app', 'external_caller_id')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field multiSelect">
                                <?= $form->field($model, 'did[]', ['options' => ['id' => 'select2-contacted', 'class' => '']])
                                    ->dropDownList($did,
                                        [
                                            'multiple' => 'multiple',
                                            'options' => (!$model->isNewRecord ? $selectedDid : [])
                                        ]
                                    )->label($model->getAttributeLabel('did')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
            <li>
                <div class="collapsible-header">
                    <?= extensionModule::t('app', 'Call Settings'); ?>
                </div>
                <div class="collapsible-body">
                    <div class="form-collapse-body">
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($call_setting_model, 'ecs_max_calls')
                                        ->textInput(['maxlength' => true, 'onkeypress' => "return isNumberKey(event)", 'onpaste' => "return paste(this)", 'placeholder' => extensionModule::t('app', 'simultaneous_external_call')
                                        ])
                                        ->label(extensionModule::t('app', 'simultaneous_external_call')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6">

                                <div class="input-field">
                                    <div class="select-wrapper">
                                        <?php echo $form->field($call_setting_model, 'ecs_forwarding', ['options' => ['class' => '']])
                                            ->dropDownList([0 => extensionModule::t('app', 'disable'), 3 => extensionModule::t('app', 'enable')])->label(extensionModule::t('app', 'ecs_forwarding')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6 web-password">
                                <?= $form->field($model, 'em_password'
                                    , [
                                        'template' => "{label}\n{input}\n<div id=\"passwordStrengthDiv1\" class=\"is0\"></div><br>{hint}\n{error}",
                                    ]
                                )
                                    ->passwordInput(['maxlength' => true, 'id' => 'sip_password', 'placeholder' => extensionModule::t('app', 'sip_password')
                                    ])
                                    ->label(extensionModule::t('app', 'sip_password')); ?>
                                <div class="passowrd-btns">
                                    <button type='button'
                                            class='btn waves-effect waves-light amber darken-4 getNewPass'
                                            onclick="generate('sip_password',12);"><i
                                                class='material-icons'>settings_backup_restore</i>
                                    </button>
                                    <button type='button'
                                            class='btn waves-effect waves-light amber darken-4 togglePassword sip_password'
                                            onclick="togglePassword('sip_password');"><i
                                                class='material-icons'>visibility_off</i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($call_setting_model, 'ecs_ring_timeout')
                                        ->textInput(['maxlength' => true, 'placeholder' => extensionModule::t('app', 'ecs_ring_timeout')
                                        ])
                                        ->label(extensionModule::t('app', 'ecs_ring_timeout')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($call_setting_model, 'ecs_call_timeout')
                                        ->textInput(['maxlength' => true, 'placeholder' => extensionModule::t('app', 'ecs_call_timeout')
                                        ])
                                        ->label(extensionModule::t('app', 'ecs_call_timeout')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($call_setting_model, 'ecs_ob_max_timeout')
                                        ->textInput(['maxlength' => true, 'placeholder' => extensionModule::t('app', 'ecs_ob_max_timeout')
                                        ])
                                        ->label(extensionModule::t('app', 'ecs_ob_max_timeout')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?php echo $form->field($call_setting_model, 'ecs_video_calling', ['options' => ['class' => 'col-xs-12 col-md-6', 'id' => 'ecs_video_calling']])
                                        ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label
                                        (extensionModule::t('app', 'video_calling')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?php echo $form->field($call_setting_model, 'ecs_auto_recording', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(
                                        ['0' => Yii::t('app', 'disable'), '1' => Yii::t('app', 'all'), '2' => Yii::t('app', 'internal'), '3' => Yii::t('app', 'external')])
                                        ->label(extensionModule::t('app', 'extension_auto_recording')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <div class="select-wrapper">

                                        <?php echo $form->field($call_setting_model, 'ecs_dtmf_type', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                            ->dropDownList(['none' => extensionModule::t('app', 'in_band'), 'rfc2833' => 'RFC2833', 'info' => extensionModule::t('app', 'sip_info')])->label(extensionModule::t('app', 'DTMF_type')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section"></div>
                        <div class="row mt-3">
                            <div class="form-group col s12 p-0">

                                <div class="form-group field-groups-group_status">
                                    <div class=" col s4">
                                        <div class="col no-padding">
                                            <label class="control-label"
                                                   for="groups-group_status"><b><?= extensionModule::t('app', 'inactive_audio_codecs') ?></b></label>

                                        </div>
                                        <select name="all_audio_codec[]" id='multiselect_to_0'
                                                class="multiselect form-control"
                                                size="8"
                                                multiple="multiple"
                                                data-right="#multiselect_to_1"
                                                data-sort="false"
                                                data-right-all="#right_All_1"
                                                data-right-selected="#right_Selected_1"
                                                data-left-all="#left_All_1"
                                                data-left-selected="#left_Selected_1">
                                            <?php foreach ($call_setting_model->all_audio_codec as $value) {
                                                echo "<option value='" . $value . "'>" . $value . "</option>";
                                            } ?>
                                        </select>
                                    </div>

                                    <div class="multiselect-btn-pad with-btn col s2 mt-3">
                                        <button type="button" id="right_All_1"
                                                class="btn-margin-bottom btn btn-block multiselect_btn">
                                            <i class="material-icons">fast_forward</i>
                                        </button>
                                        <button type="button" id="right_Selected_1"
                                                class="btn-margin-bottom btn btn-block multiselect_btn">
                                            <i class="material-icons">keyboard_arrow_right</i>
                                        </button>
                                        <button type="button" id="left_Selected_1"
                                                class="btn-margin-bottom btn btn-block multiselect_btn">
                                            <i class="material-icons">keyboard_arrow_left</i>
                                        </button>
                                        <button type="button" id="left_All_1"
                                                class="btn btn-block multiselect_btn">
                                            <i class="material-icons">fast_rewind</i>
                                        </button>
                                    </div>

                                    <div class=" col s4">
                                        <div class="col no-padding">
                                            <label class="control-label"
                                                   for="groups-group_status"><b><?= extensionModule::t('app', 'active_audio_codecs') ?></b></label>
                                        </div>
                                        <select name="orig_codec[]" id="multiselect_to_1"
                                                class="multiselect form-control"
                                                size="8" multiple="multiple">
                                            <?php foreach ($call_setting_model->orig_codec as $value) {
                                                echo "<option value='" . $value . "'>" . $value . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="with-btn col s2 setM-pad">
                                        <button type="button" value="Up"
                                                class="btn-margin-bottom btn btn-block priority_btns">
                                            <i class="material-icons">arrow_upward</i>
                                        </button>

                                        <button type="button" value="Down"
                                                class="btn btn-block priority_btns">
                                            <i class="material-icons">arrow_downward</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section"></div>
                        <div id="video_rec" style="display: none;">

                            <div class="row mt-3">

                                <div class="form-group col s12  p-0">

                                    <div class="form-group field-groups-group_status">
                                        <!--<label class="control-label col s1"
                                for="groups-group_status"><?php /*= $model->getAttributeLabel('orig_codec') */ ?></label>-->
                                        <div class="col s4">
                                            <div class="col no-padding">
                                                <label class="control-label"
                                                       for="groups-group_status"><b><?= extensionModule::t('app', 'inactive_video_codecs') ?></b></label>
                                            </div>
                                            <select name="all_video_codec[]" id='multiselect_to_2'
                                                    class="multiselect form-control"
                                                    size="8"
                                                    multiple="multiple"
                                                    data-right="#multiselect_to_3"
                                                    data-sort="false"
                                                    data-right-all="#right_All_3"
                                                    data-right-selected="#right_Selected_3"
                                                    data-left-all="#left_All_3"
                                                    data-left-selected="#left_Selected_3">
                                                <?php foreach (/*$call_setting_model->all_video_codec*/
                                                    ['VP8', 'H264'] as $value) {
                                                    echo "<option value='" . $value . "'>" . $value . "</option>";
                                                } ?>
                                            </select>
                                        </div>

                                        <div class="multiselect-btn-pad col s2 mt-3">
                                            <button type="button" id="right_All_3"
                                                    class="btn-margin-bottom btn btn-block multiselect_btn">
                                                <i class="material-icons">fast_forward</i>
                                            </button>
                                            <button type="button" id="right_Selected_3"
                                                    class="btn-margin-bottom btn btn-block multiselect_btn">
                                                <i class="material-icons">keyboard_arrow_right</i>
                                            </button>
                                            <button type="button" id="left_Selected_3"
                                                    class="btn-margin-bottom btn btn-block multiselect_btn">
                                                <i class="material-icons">keyboard_arrow_left</i>
                                            </button>
                                            <button type="button" id="left_All_3"
                                                    class="btn btn-block multiselect_btn">
                                                <i class="material-icons">fast_rewind</i>
                                            </button>
                                        </div>

                                        <div class=" col s4">
                                            <div class="col no-padding">
                                                <label class="control-label"
                                                       for="groups-group_status"><b><?= extensionModule::t('app', 'active_video_codecs') ?></b></label>
                                            </div>
                                            <select name="orig_video_codec[]" id="multiselect_to_3"
                                                    class="multiselect form-control"
                                                    size="8" multiple="multiple">
                                                <?php foreach ($call_setting_model->orig_video_codec as $value) {
                                                    echo "<option value='" . $value . "'>" . $value . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <br>
                                        <div class="with-btn col s2 setM-pad">
                                            <button type="button" value="Up"
                                                    class="btn-margin-bottom btn btn-block priority_video_btns">
                                                <i class="material-icons">arrow_upward</i>
                                            </button>

                                            <button type="button" value="Down"
                                                    class="btn btn-block priority_video_btns">
                                                <i class="material-icons">arrow_downward</i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">

                                    <?php echo $form->field($call_setting_model, 'ecs_multiple_registeration', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                        ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label
                                        (extensionModule::t('app', 'multiple_registeration')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">

                                    <?php echo $form->field($call_setting_model, 'ecs_dial_out', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                        ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label
                                        (extensionModule::t('app', 'dial_out')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
            <li>
                <div class="collapsible-header">
                    <?= extensionModule::t('app', 'vm_and_fax_settings'); ?>
                </div>
                <div class="collapsible-body">
                    <div class="form-collapse-body">
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">

                                    <?php echo $form->field($call_setting_model, 'ecs_voicemail', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                        ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label
                                        (extensionModule::t('app', 'voice_mail')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6 web-password">
                                <?= $form->field($call_setting_model, 'ecs_voicemail_password'
                                    , [
                                        'template' => "{label}\n{input}\n<div id=\"passwordStrengthDiv2\" class=\"is0\"></div><br>{hint}\n{error}",
                                    ])
                                    ->passwordInput(['maxlength' => true, 'id' => 'vm_password', 'placeholder' => extensionModule::t('app', 'vm_password')
                                    ])
                                    ->label(extensionModule::t('app', 'vm_password')); ?>
                                <div class="passowrd-btns">
                                    <button type='button'
                                            class='btn waves-effect waves-light amber darken-4 getNewPass'
                                            onclick="generate('vm_password',10);"><i class='material-icons'>settings_backup_restore</i>
                                    </button>
                                    <button type='button'
                                            class='btn waves-effect waves-light amber darken-4 togglePassword vm_password'
                                            onclick="togglePassword('vm_password');"><i
                                                class='material-icons'>visibility_off</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">

                                    <?php echo $form->field($call_setting_model, 'ecs_fax2mail', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                        ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label
                                        (extensionModule::t('app', 'fax')); ?>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <div class="select-wrapper">
                                        <?= $form->field($model,
                                            'em_moh',
                                            [
                                                'options' => [
                                                    'class' => '',
                                                ],
                                            ])
                                            ->dropDownList(AudioManagement::getMohFiles(), ['prompt' => extensionModule::t('app', 'select_moh')])
                                            ->label(extensionModule::t('app', 'em_moh')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </li>
        </ul>

        <?php if (Yii::$app->session->get('isTragofone') == 1) { ?>
            <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
                <li>
                    <div class="collapsible-header">
                        <?= extensionModule::t('app', 'Tragofone Settings'); ?>
                    </div>
                    <div class="collapsible-body">
                        <div class="form-collapse-body">
                            <div class="row">
                                <div class="col s12 m6">
                                    <div class="input-field">
                                        <?php echo $form->field($call_setting_model, 'ecs_im_status', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                            ->dropDownList([0 => Yii::t('app', 'inactive'), 1 => Yii::t('app', 'active')])->label
                                            (extensionModule::t('app', 'im_status')); ?>
                                    </div>
                                </div>
                                <div class="col s12 m6" style="pointer-events: none;">
                                    <div class="input-field">
                                        <?= $form->field($model, 'trago_username')
                                            ->textInput(['readonly' => 'readonly', 'placeholder' => extensionModule::t('app', 'trago_username')])
                                            ->label(extensionModule::t('app', 'trago_username')); ?>
                                        <span style="font-size: 12px;"><?= "e.g." . " < " . "Account Code" . " >" . "< " . "Extension Number" . " >" ?> </span>
                                    </div>
                                </div>
                                <?php if (!$model->isNewRecord) { ?>
                                    <div class="col s12 m6">
                                        <div class="input-field">
                                            <?php echo $form->field($model, 'is_tragofone', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                                ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label
                                                (extensionModule::t('app', 'is_tragofone')); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        <?php } ?>

        <div class="row mt-2">
                <div class="col s12 pb-3 d-flex align-items-center gap-10">
                    <?= Html::a(extensionModule::t('app', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200 ']) ?>
                    <?= Html::submitButton($model->isNewRecord ? extensionModule::t('app', 'create') : extensionModule::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                        'btn waves-effect waves-light cyan accent-8']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>


            <script>
                var is_tragofone = "<?php echo Yii::$app->session->get('isTragofone'); ?>";
                var tenant_code = "<?php echo Yii::$app->session->get('tenant_code'); ?>";

                function randomPassword(field_name, length, only_numbers) {
                    var str1 = '1234567890';
                    var str2 = 'abcdefghijklmnopqrstuvwxyz';
                    var str3 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    var str4 = '$@{$(!}%)*#?&';

                    var pass = "";
                    var chars = "";

                    if (only_numbers) {
                        chars = str1;
                    } else {
                        if (length > 0) {
                            let i = Math.floor(Math.random() * str1.length);
                            pass += str1.charAt(i); // Add 1 numeric
                            length--;
                        }
                        if (length > 0) {
                            let i = Math.floor(Math.random() * str2.length);
                            pass += str2.charAt(i); // Add 1 small character
                            length--;
                        }
                        if (length > 0) {
                            let i = Math.floor(Math.random() * str3.length);
                            pass += str3.charAt(i); // Add 1 capital character
                            length--;
                        }
                        chars = str1 + str2 + str3;
                        if (field_name == 'web_password') {  // for em_password only
                            if (length > 0) {
                                let i = Math.floor(Math.random() * str4.length); // Add 1 special character
                                pass += str4.charAt(i);
                                chars += str4;
                                length--;
                            }
                        }

                        if (field_name == 'sip_password') {  // for em_password only
                            if (length > 0) {
                                let i = Math.floor(Math.random() * str4.length); // Add 1 special character
                                pass += str4.charAt(i);
                                chars += str4;
                                length--;
                            }
                        }
                    }
                    console.log("length: " + length + ':' + field_name + ':');
                    for (var x = 0; x < length; x++) {
                        let i = Math.floor(Math.random() * chars.length);
                        pass += chars.charAt(i);
                    }
                    return shuffle(pass);
                }

                function shuffle(s) {
                    var arr = s.split('');           // Convert String to array
                    var n = arr.length;              // Length of the array

                    for (var i = 0; i < n - 1; ++i) {
                        var j = getRandomInt(n);       // Get random of [0, n-1]

                        var temp = arr[i];             // Swap arr[i] and arr[j]
                        arr[i] = arr[j];
                        arr[j] = temp;
                    }

                    s = arr.join('');                // Convert Array to string
                    return s;                        // Return shuffled string
                }

                function getRandomInt(n) {
                    return Math.floor(Math.random() * n);
                }

                /* function randomPassword(field_name, length, only_numbers) {
                     var chars = (only_numbers == true) ? "1234567890" : (field_name == 'vm_password') ? 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890' : "ABCDEFGHIJKLMNOPabcdefghijklmnopqrstuvwxyz1234567890$@{$(!}%)*#?&";
                     var pass = "";

                     for (var x = 0; x < length; x++) {
                         var i = Math.floor(Math.random() * chars.length);
                         pass += chars.charAt(i);
                     }
                     return pass;
                 }*/

                function generate(field_name, length) {
                    var is_numeric = false;
                    if (field_name == 'vm_password') {
                        is_numeric = true;
                    }
                    $("#" + field_name).val(randomPassword(field_name, length, is_numeric));
                    $("#" + field_name).siblings().addClass('active');
                    $("#" + field_name).trigger('keyup');

                }

                function togglePassword(field_name) {
                    var pwdType = $("#" + field_name).attr("type");
                    var newType = (pwdType === "password") ? "text" : "password";
                    $("#" + field_name).attr("type", newType);
                    var newEye = $('.' + field_name + ' .material-icons').text();
                    $('.' + field_name + ' .material-icons').text(newEye == 'visibility' ? 'visibility_off' : 'visibility');
                }

                function generatePin(field_name, length) {
                    $("#" + field_name).val(randomPassword(field_name, length, true));
                    $("#" + field_name).siblings().addClass('active');
                    $("#" + field_name).trigger('keyup');
                }

                $(document).ready(function () {
                    $('.field-groups-group_status select').formSelect('destroy');
                    $('.field-groups-group_status select').css('display', 'block');
                    $('.field-groups-group_status select').css('height', '200px');
                    $('.field-groups-group_status select').css('border', '1px solid #bdbdbd');


                    $('#em_type').on('change', function () {
                        if ($(this).find(':selected').val() == 'range') {
                            $("#number").hide();
                            $("#range").show();
                        } else {
                            $("#number").show();
                            $("#range").hide();
                        }
                    });
                    var is_new = '<?=$model->isNewRecord ?>';

                    if (is_new) {
                        generate('web_password', 12);
                        generate('sip_password', 12);
                        generate('vm_password', 10)
                        generatePin('feature_code_pin', 8);
                        // generatePin('vm_password', 10);
                    }

                    $('.multiselect').multiselect({
                        sort: false,
                        fireSearch: function (value) {
                            return value.length > 2;
                        }
                    });

                    $('#em_type').trigger('change');

                    setInterval(function () {
                        if ($("#extension-did").val() == '') {
                            $('.field-extension-did .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').attr('placeholder', 'Select DID');
                            $('.field-extension-did .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').css('width', 'auto');
                        }
                    }, 100);
                });

                if (<?= $call_setting_model->ecs_video_calling ?> == 1
                ) {
                    $('#video_rec').show();
                } else {
                    $('#video_rec').hide();
                }

                $('#ecs_video_calling').change(function () {
                    if ($("#ecs_video_calling option:selected").val() == 1) {
                        $('#video_rec').show();
                    } else {
                        $('#video_rec').hide();
                    }
                });

                if (is_tragofone == 1) {
                    $('#extension-em_extension_number').on('keyup', function () {
                        if (this.value.length === 0) {
                            $('.field-extension-trago_username label').removeClass('active');
                            $('#extension-trago_username').val('');
                        } else {
                            $('.field-extension-trago_username label').addClass('active');
                            $('#extension-trago_username').val(tenant_code + $(this).val());
                        }
                    });

                    $('#extension-em_extension_range_from').on('keyup', function () {
                        if (this.value.length === 0) {
                            $('.field-extension-trago_username label').removeClass('active');
                            $('#extension-trago_username').val('');
                        } else {
                            if ($('#extension-em_extension_range_to').val() != '') {
                                if (parseInt($(this).val()) < parseInt($('#extension-em_extension_range_to').val())) {
                                    $('.field-extension-trago_username label').addClass('active');
                                    /*var tragoUse='';
                                    for (var i = $(this).val(); i <= $('#extension-em_extension_range_to').val(); i++){
                                        tragoUse += tenant_code + i+', ';
                                    }
                                    tragoUse = tragoUse.replace(/,\s*$/, "");
                                    $('#extension-trago_username').val(tragoUse);*/
                                    $('#extension-trago_username').val(tenant_code + $(this).val() + ' To ' + tenant_code + $('#extension-em_extension_range_to').val());
                                } else {
                                    $('#extension-trago_username').val('');
                                }
                            }
                        }
                    });

                    $('#extension-em_extension_range_to').on('keyup', function () {
                        if (this.value.length === 0) {
                            $('.field-extension-trago_username label').removeClass('active');
                            $('#extension-trago_username').val('');
                        } else {
                            if ($('#extension-em_extension_range_from').val() != '') {
                                if (parseInt($(this).val()) > parseInt($('#extension-em_extension_range_from').val())) {
                                    $('.field-extension-trago_username label').addClass('active');
                                    /* var tragoUse='';
                                     for (var i = $('#extension-em_extension_range_from').val(); i <= $(this).val(); i++){
                                         tragoUse += tenant_code + i+', ';
                                     }
                                     tragoUse = tragoUse.replace(/,\s*$/, "");
                                     $('#extension-trago_username').val(tragoUse);*/
                                    $('#extension-trago_username').val(tenant_code + $('#extension-em_extension_range_from').val() + ' To ' + tenant_code + $(this).val());
                                } else {
                                    $('#extension-trago_username').val('');
                                }
                            }
                        }
                    })
                }

            </script>
