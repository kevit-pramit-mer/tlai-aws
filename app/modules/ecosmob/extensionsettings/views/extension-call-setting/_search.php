<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\extensionsettings\ExtensionSettingsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionsettings\models\ExtensionCallSettingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i>Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="extension-call-setting-search"
                     id="extension-call-setting-search">

                    <?php $form=ActiveForm::begin([
                        'id'=>'extension-call-setting-search-form',
                        'action'=>['index'],
                        'method'=>'get',
                        'options'=>[
                            'data-pjax'=>1
                        ],
                        'fieldConfig'=>[
                            'options'=>[
                                'class'=>'input-field col s12'
                            ],
                        ],
                    ]); ?>

                    <!--    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <? /*= $form->field($model, 'ecs_id') */ ?>

                                    </div>
                                </div>
                            </div>-->
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field col s6">
                                <?= $form->field($model, 'em_id') ?>
                            </div>
                            <div class="input-field col s6">
                                <?= $form->field($model, 'ecs_max_calls') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field col s6">
                                <?= $form->field($model, 'ecs_ring_timeout') ?>
                            </div>
                            <div class="input-field col s6">
                                <?= $form->field($model, 'ecs_call_timeout') ?>
                            </div>
                        </div>
                    </div>
                    <?php // echo $form->field($model, 'ecs_ob_max_timeout') ?>

                    <?php // echo $form->field($model, 'ecs_auto_recording') ?>

                    <?php // echo $form->field($model, 'ecs_dtmf_type') ?>

                    <?php // echo $form->field($model, 'ecs_video_calling') ?>

                    <?php // echo $form->field($model, 'ecs_bypass_media') ?>

                    <?php // echo $form->field($model, 'ecs_srtp') ?>

                    <?php // echo $form->field($model, 'ecs_force_record') ?>

                    <?php // echo $form->field($model, 'ecs_moh') ?>

                    <?php // echo $form->field($model, 'ecs_audio_codecs') ?>

                    <?php // echo $form->field($model, 'ecs_video_codecs') ?>

                    <?php // echo $form->field($model, 'ecs_dial_out') ?>

                    <?php // echo $form->field($model, 'ecs_forwarding') ?>

                    <?php // echo $form->field($model, 'ecs_voicemail') ?>

                    <?php // echo $form->field($model, 'ecs_voicemail_password') ?>

                    <?php // echo $form->field($model, 'ecs_fax2mail') ?>

                    <?php // echo $form->field($model, 'ecs_feature_code_pin') ?>

                    <?php // echo $form->field($model, 'ecs_multiple_registeration') ?>

                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(ExtensionSettingsModule::t('extensionsettings', 'search'), ['class'=>
                                'btn waves-effect waves-light amber darken-4']) ?>
                            <?= Html::a(ExtensionSettingsModule::t('extensionsettings', 'reset'), ['index', 'page'=>
                                Yii::$app->session->get('page')],
                                ['class'=>'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                        </div>
                    </div>


                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </li>
</ul>
