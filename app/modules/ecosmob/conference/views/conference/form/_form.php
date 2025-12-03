<?php

use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\conference\ConferenceModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\conference\models\ConferenceMaster */
/* @var $form yii\widgets\ActiveForm */
/* @var $audioList */
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'id' => 'conference-master-active-form',
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field',
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="conference-master-form" id="conference-master-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'cm_name', [
                                        'inputOptions' => [
                                            'class' => 'form-control',
                                        ],
                                    ])->textInput(['maxlength' => TRUE, 'class' => 'mg-t6', 'placeholder' => ConferenceModule::t('conference', "cm_name")])->label(ConferenceModule::t('conference',
                                    'cm_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'cm_extension')
                                    ->textInput([
                                        'maxlength' => TRUE,
                                        'type' => 'number',
                                        'class' => 'mg-t6',
                                        'placeholder' => ConferenceModule::t('conference', "cm_extension"),
                                        'onkeypress' => "return isNumberKey(event)"
                                    ])
                                    ->label($model->getAttributeLabel('cm_extension')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model,
                                        'cm_language',
                                        [
                                            'options' => [
                                                'class' => '',
                                            ],
                                        ])
                                        ->dropDownList(
                                            ['en' => Yii::t('app', 'english'), 'es' => Yii::t('app', 'spanish')], ['prompt' => Yii::t('app',
                                            'select')])
                                        ->label(ConferenceModule::t('conference', 'cm_language')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model,
                                        'cm_moh',
                                        [
                                            'options' => [
                                                'class' => '',
                                            ],
                                        ])
                                        ->dropDownList(AudioManagement::getMohFiles(), ['prompt' => ConferenceModule::t('conference', 'select_moh')])
                                        ->label(ConferenceModule::t('conference', 'cm_moh')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'cm_max_participant')->textInput([
                                    'maxlength' => TRUE,
                                    'onkeypress' => 'return isNumberKey(event);',
                                    'class' => 'mg-t6',
                                    'placeholder' => ConferenceModule::t('conference', "cm_max_participant")
                                ])->label(ConferenceModule::t('conference',
                                    "cm_max_participant")); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field( $model, 'cm_part_code' )->textInput( [
                                    'maxlength'  => TRUE,
                                    'class'      => 'mg-t6',
                                    'onkeypress' => 'return isNumberKey(event);',
                                    'placeholder' => ConferenceModule::t('conference', "cm_part_code")
                                ])->label(ConferenceModule::t('conference',
                                    "cm_part_code")); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'cm_mod_code')->textInput([
                                    'maxlength' => TRUE,
                                    'onkeypress' => 'return isNumberKey(event);',
                                    'placeholder' => ConferenceModule::t('conference', "cm_mod_code")
                                ])->label(ConferenceModule::t('conference',
                                    "cm_mod_code")); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                    <p class="h4"><?= ConferenceModule::t('conference', 'cm_entry_tone') ?>:</p>
                                    <div class="switch">
                                        <label>
                                            <?= ConferenceModule::t('conference', 'off') ?>
                                            <?= Html::checkbox('cm_entry_tone', $model->cm_entry_tone, ['uncheck' => 0]) ?>
                                            <span class="lever"></span>
                                            <?= ConferenceModule::t('conference', 'on') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                    <p class="h4"><?= ConferenceModule::t('conference', 'cm_exit_tone') ?>:</p>
                                    <div class="switch">
                                        <label>
                                            <?= ConferenceModule::t('conference', 'off') ?>
                                            <?= Html::checkbox('cm_exit_tone', $model->cm_exit_tone, ['uncheck' => 0]) ?>
                                            <span class="lever"></span>
                                            <?= ConferenceModule::t('conference', 'on') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                    <p class="h4"><?= ConferenceModule::t('conference', 'cm_quick_start') ?>:</p>
                                    <div class="switch">
                                        <label>
                                            <?= ConferenceModule::t('conference', 'off') ?>
                                            <?= Html::checkbox('cm_quick_start', $model->cm_quick_start, ['uncheck' => 0]) ?>
                                            <span class="lever"></span>
                                            <?= ConferenceModule::t('conference', 'on') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ( Yii::$app->user->identity->adm_is_admin != 'extension' && ! $model->isNewRecord ) { ?>
                            <div class="col s12 m6 p-0">
                                <div class="input-field">
                                    <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                        <p class="h4"><?= ConferenceModule::t('conference', 'cm_status') ?>:</p>
                                        <div class="switch">
                                            <label>
                                                <?= ConferenceModule::t('conference', 'off') ?>
                                                <?= Html::checkbox('cm_status', $model->cm_status, ['uncheck' => 0]) ?>
                                                <span class="lever"></span>
                                                <?= ConferenceModule::t('conference', 'on') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
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
                        $model->isNewRecord ? ConferenceModule::t(
                            'conference',
                            'create'
                        ) : ConferenceModule::t('conference', 'update'),
                        [
                            'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4'
                                : 'btn waves-effect waves-light cyan accent-8',
                        ]
                    ) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('select').select2();
    });
</script>
