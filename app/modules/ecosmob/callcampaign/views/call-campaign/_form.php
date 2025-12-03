<?php

use app\modules\ecosmob\callcampaign\CallCampaignModule;
use app\modules\ecosmob\callcampaign\models\CallCampaign;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callcampaign\models\CallCampaign */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="call-campaign-form"
                     id="call-campaign-form">

                    <?php $form = ActiveForm::begin([
                        'class' => 'row',
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field'
                            ],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'cmp_name')->textInput(['maxlength' => true])->label(CallCampaignModule::t('app', 'cmp_name')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'cmp_type', ['options' => ['class' => '']])->dropDownList([
                                    'Inbound' => 'Inbound',
                                    'Outbound' => 'Outbound',
                                    ' Blended' => ' Blended',
                                ], ['prompt' => CallCampaignModule::t('app', 'select')])->label(CallCampaignModule::t('app', 'cmp_type')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'cmp_caller_id')->textInput(['maxlength' => true])->label(CallCampaignModule::t('app',
                                    'cmp_caller_id')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'cmp_timezone', ['options' => ['class' => '']])->dropDownList(CallCampaign::getTimeZone()
                                    , ['prompt' => CallCampaignModule::t('app', 'select')])->label(CallCampaignModule::t('app', 'cmp_timezone')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'cmp_status', ['options' => ['class' => '']])->dropDownList([
                                        'Active' => 'Active',
                                        'Inactive' => 'Inactive',
                                        'ALL' => 'ALL',
                                    ], ['prompt' => CallCampaignModule::t('app', 'select')])->label(CallCampaignModule::t('app', 'cmp_status')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'cmp_disposition',
                                    ['options' => ['class' => '']])->dropDownList([
                                    'ALL' => 'ALL',
                                    'OFF' => 'OFF',
                                    'ON' => 'ON',
                                ], ['prompt' => CallCampaignModule::t('app', 'select')])->label(CallCampaignModule::t('app', 'cmp_disposition')); ?>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 center">
                            <div class="input-field">
                                <?= Html::submitButton($model->isNewRecord ? CallCampaignModule::t('app', 'Create') : CallCampaignModule::t('app',
                                    'Update'), [
                                    'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                        'btn waves-effect waves-light cyan accent-8'
                                ]) ?>
                                <?php if (!$model->isNewRecord) { ?>
                                    <?= Html::submitButton(CallCampaignModule::t('app', 'apply'), [
                                        'class' => 'btn waves-effect waves-light amber darken-4 ml-2',
                                        'name' => 'apply',
                                        'value' => 'update'
                                    ]) ?>
                                <?php } ?>
                                <?= Html::a(CallCampaignModule::t('app', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
