<?php

use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\autoattendant\AutoAttendantModule;
use app\modules\ecosmob\extension\models\Extension;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\autoattendant\models\AutoAttendantMaster */
/* @var $form yii\widgets\ActiveForm */
/* @var $audioList app\modules\ecosmob\audiomanagement\models\AudioManagement */

$merged_array = AudioManagement::getAudioList();

$extensionLists = Extension::find()->all();
foreach ($extensionLists as &$ext) {
    $ext->em_extension_name = $ext->em_extension_name . '-' . $ext->em_extension_number;
}
$ext = ArrayHelper::map($extensionLists, 'em_extension_number', 'em_extension_name');
?>
<div class="row">
    <div class="col s12 mb-3 m-p-0">
        <?php $form = ActiveForm::begin(
            [
                'id' => 'auto-attendant-master-active-form',
                'fieldConfig' => [
                    'options' => [
                        'class' => 'input-field',
                    ],
                ],
                'class' => 'row',
            ]
        ); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="auto-attendant-master-form" id="auto-attendant-master-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model,
                                'aam_name',
                                [
                                    'inputOptions' => [],
                                ]
                            )->textInput(
                                [
                                    'maxlength' => TRUE,
                                    'placeholder' => ($model->getAttributeLabel('aam_name'))
                                ]
                            )->label($model->getAttributeLabel('aam_name')) ?>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'aam_extension')->textInput(
                                [
                                    'type' => 'number',
                                    'onkeypress' => 'return isNumberKey(event);',
                                    'placeholder' => ($model->getAttributeLabel('aam_extension'))
                                ]
                            )->label($model->getAttributeLabel('aam_extension')) ?>
                        </div>
                        <div class="col s12 m6">
                                <?= $form->field($model, 'aam_timeout')->textInput(
                                    [
                                        'maxlength' => 5,
                                        'placeholder' => ($model->getAttributeLabel('aam_timeout'))
                                    ]
                                )->label($model->getAttributeLabel('aam_timeout')) ?>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'aam_inter_digit_timeout')->textInput(
                                [
                                    'maxlength' => 5,
                                    'placeholder' => ($model->getAttributeLabel('aam_inter_digit_timeout'))
                                ]
                            )->label($model->getAttributeLabel('aam_inter_digit_timeout')) ?>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?php echo $form->field($model, 'aam_greet_long', ['options' => ['class' => 'input-field']])
                                    ->dropDownList($merged_array, ['prompt' => AutoAttendantModule::t('autoattendant', 'select')])
                                    ->label($model->getAttributeLabel('aam_greet_long')) ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'aam_greet_short', ['options' => ['class' => 'input-field']])
                                    ->dropDownList($merged_array, ['prompt' => AutoAttendantModule::t('autoattendant', 'select')])
                                    ->label($model->getAttributeLabel('aam_greet_short')) ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'aam_failure_prompt', ['options' => ['class' => 'input-field']])
                                    ->dropDownList($merged_array, ['prompt' => AutoAttendantModule::t('autoattendant', 'select')])
                                    ->label($model->getAttributeLabel('aam_failure_prompt')) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="transfer_on_failure" style="display: none">
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'aam_transfer_extension_type', ['options' => ['class' => 'input-field', 'id' => 'transfer_extension_type']])
                                    ->dropDownList(['INTERNAL' => AutoAttendantModule::t('autoattendant', 'internal'),
                                        'EXTERNAL' => AutoAttendantModule::t('autoattendant', 'external')],
                                        ['prompt' => AutoAttendantModule::t('autoattendant', 'select_transfer_type')])
                                    ->label(Yii::t('app', 'Transfer Extension Type')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 ">
                            <div id="transfer_internal">
                                <div class="select-wrapper">
                                    <?php
                                    echo $form->field($model, 'aam_transfer_extension', ['options' => ['class' => 'input-field', 'id' => 'select_transfer_internal']])->dropDownList($ext, ['prompt' => AutoAttendantModule::t('autoattendant', 'select_internal')])->label(AutoAttendantModule::t('autoattendant', 'aam_transfer_on_failure'));
                                    ?>
                                </div>
                            </div>
                            <div class="col s12 input-right" id="transfer_external">
                                <?= $form->field($model, 'aam_transfer_extension')->textInput(['maxlength' => true, 'class' => '', 'id' => 'select_transfer_external'])->label(AutoAttendantModule::t('autoattendant', 'aam_transfer_on_failure')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'aam_invalid_sound', ['options' => ['class' => 'input-field']])
                                        ->dropDownList($merged_array, ['prompt' => AutoAttendantModule::t('autoattendant', 'select')])
                                        ->label($model->getAttributeLabel('aam_invalid_sound')) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'aam_exit_sound', ['options' => ['class' => '']])
                                        ->dropDownList($merged_array, ['prompt' => AutoAttendantModule::t('autoattendant', 'select')])
                                        ->label($model->getAttributeLabel('aam_exit_sound')) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field(
                                    $model,
                                    'aam_max_failures')->textInput(
                                    [
                                        'maxlength' => 5,
                                        'placeholder' => ($model->getAttributeLabel('aam_max_failures'))
                                    ]
                                )->label($model->getAttributeLabel('aam_max_failures')) ?>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'aam_max_timeout')->textInput(
                                    [
                                        'maxlength' => 5,
                                        'placeholder' => ($model->getAttributeLabel('aam_max_timeout'))
                                    ]
                                )->label($model->getAttributeLabel('aam_max_timeout')) ?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'aam_digit_len')->textInput(
                                    [
                                        'maxlength' => 2,
                                        'placeholder' => ($model->getAttributeLabel('aam_digit_len'))
                                    ]
                                )->label($model->getAttributeLabel('aam_digit_len')) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 p-0">
                            <div class="col s12 m6 input-field p-0">
                                <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                    <p class=h4> <?= $model->getAttributeLabel('aam_direct_dial') ?>
                                        : </p>
                                    <div class="switch">
                                        <label>
                                            <?= AutoAttendantModule::t('autoattendant', 'off') ?>
                                            <?= Html::activeCheckbox($model, 'aam_direct_dial', ['uncheck' => 0, 'label' => FALSE]) ?>
                                            <span class="lever"></span>
                                            <?= AutoAttendantModule::t('autoattendant', 'on') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6 p-0 input-field">
                                <div class="col s12 p-0">
                                    <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                        <p class=h4> <?= $model->getAttributeLabel('aam_transfer_on_failure') ?>
                                            : </p>
                                        <div class="switch">
                                            <label>
                                                <?= AutoAttendantModule::t('autoattendant', 'off') ?>
                                                <?= Html::activeCheckbox($model, 'aam_transfer_on_failure', ['uncheck' => 0, 'label' => FALSE]) ?>
                                                <span class="lever"></span>
                                                <?= AutoAttendantModule::t('autoattendant', 'on') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'aam_language', ['options' => ['class' => '']])->dropDownList([
                                            'en' => AutoAttendantModule::t('autoattendant', 'english'),
                                            'es' => AutoAttendantModule::t('autoattendant', 'spanish'),
                                        ]
                                    )->label($model->getAttributeLabel('aam_language')); ?>
                                </div>
                            </div>
                        </div>
                        <?php if (!$model->isNewRecord) { ?>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <div class="select-wrapper">
                                        <?= $form->field($model, 'aam_status', ['options' => ['class' => '']])->dropDownList([
                                                '1' => Yii::t('app', 'active'),
                                                '0' => Yii::t('app', 'inactive'),
                                            ]
                                        )->label($model->getAttributeLabel('aam_status')); ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if ($model->isNewRecord) { ?>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div style="color:#55595c"><i>
                                        * <?= AutoAttendantModule::t(
                                            'autoattendant',
                                            'cant_change_extension_number'
                                        ); ?></i>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <div class="input-field">
                    <?= Html::a(Yii::t('app', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton(
                        $model->isNewRecord ? AutoAttendantModule::t(
                            'autoattendant',
                            'create'
                        ) : AutoAttendantModule::t('autoattendant', 'update'),
                        [
                            'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4'
                                : 'btn waves-effect waves-light cyan accent-8',
                        ]
                    ) ?>
                    <?php if (!$model->isNewRecord) {
                        echo Html::submitButton(
                            AutoAttendantModule::t('autoattendant', 'apply'),
                            [
                                'class' => 'btn waves-effect waves-light amber darken-4',
                                'name' => 'apply',
                                'value' => 'update',
                            ]
                        );
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


<script>
    $(document).on("keypress keyup blur", "#autoattendantmaster-aam_timeout, #autoattendantmaster-aam_inter_digit_timeout, #autoattendantmaster-aam_max_failures, #autoattendantmaster-aam_max_timeout, #autoattendantmaster-aam_digit_len", function (e) {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
</script>

