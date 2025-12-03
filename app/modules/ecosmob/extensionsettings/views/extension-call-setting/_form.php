<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\extensionsettings\ExtensionSettingsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionsettings\models\ExtensionCallSetting */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    .mrg-btn{
        margin-bottom: 1em;
    }
</style>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="extension-call-setting-form"
                     id="extension-call-setting-form">

                    <?php $form=ActiveForm::begin([
                        'class'=>'row',
                        'fieldConfig'=>[
                            'options'=>[
                                'class'=>'input-field col s12'
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'em_id')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'email')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_max_calls')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'max_call')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_ring_timeout')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'ring_timeout')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_call_timeout')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'call_timeout')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_ob_max_timeout')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'ob_max_timeout')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_auto_recording', ['options'=>['class'=>'']])->dropDownList(['0', '1', '2', '3',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'auto_record')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_dtmf_type', ['options'=>['class'=>'']])->dropDownList(['rfc2833'=>'Rfc2833', 'info'=>'Info', 'none'=>'None',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_dtmf_type')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_bypass_media', ['options'=>['class'=>'']])->dropDownList(['0', '1', '2', '3',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_bypass_media')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_srtp', ['options'=>['class'=>'']])->dropDownList(['0', '1',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'Srtp')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_force_record', ['options'=>['class'=>'']])->dropDownList(['0', '1',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_force_record')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_moh')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'Moh')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_audio_codecs')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_audio_codecs')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_video_codecs')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_video_codecs')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_dial_out', ['options'=>['class'=>'']])->dropDownList(['0', '1',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_dial_out')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_forwarding', ['options'=>['class'=>'']])->dropDownList(['0', '1', '2',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_forwarding')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_voicemail', ['options'=>['class'=>'']])->dropDownList(['0', '1',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_voicemail')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_voicemail_password')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_voicemail_password')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_fax2mail', ['options'=>['class'=>'']])->dropDownList(['0', '1',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_fax2mail')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_feature_code_pin')->textInput(['maxlength'=>true])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_feature_code_pin')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ecs_multiple_registeration', ['options'=>['class'=>'']])->dropDownList([1=>'1', 0=>'0',], ['prompt'=>ExtensionSettingsModule::t('extensionsettings', 'select')])->label(ExtensionSettingsModule::t('extensionsettings', 'ecs_multiple_registeration')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field col s12 mrg-btn">
                            <?= Html::submitButton($model->isNewRecord ? ExtensionSettingsModule::t('extensionsettings', 'create') : ExtensionSettingsModule::t('extensionsettings', 'update'), ['class'=>$model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) { ?>
                                <?= Html::submitButton(ExtensionSettingsModule::t('extensionsettings', 'apply'), [
                                    'class'=>'btn waves-effect waves-light amber darken-4',
                                    'name'=>'apply',
                                    'value'=>'update']) ?>
                            <?php } ?>
                            <?= Html::a(ExtensionSettingsModule::t('extensionsettings', 'cancel'), ['index', 'page'=>Yii::$app->session->get('page')],
                                ['class'=>'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                    </div>
            </div>
        </div>
    </div>
</div>
