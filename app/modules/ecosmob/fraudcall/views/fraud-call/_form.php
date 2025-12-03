<?php

use app\modules\ecosmob\fraudcall\FraudCallModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\fraudcall\models\FraudCallDetection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
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
                <div class="fraud-call-detection-form" id="fraud-call-detection-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'fcd_rule_name', [
                                    'inputOptions' => [
                                        //'autofocus' => 'autofocus',
                                        'class' => 'form-control',
                                    ],
                                ])
                                    ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('fcd_rule_name'))])
                                    ->label($model->getAttributeLabel('fcd_rule_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'fcd_destination_prefix')
                                    ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('fcd_destination_prefix')), 'onkeypress'=>"return isNumberKeyWithPlus(event)", 'onpaste' => "return paste(this)"])
                                    ->label($model->getAttributeLabel('fcd_destination_prefix')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'fcd_call_duration')
                                    ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('fcd_call_duration'))])
                                    ->label($model->getAttributeLabel('fcd_call_duration')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'fcd_call_period')
                                    ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('fcd_call_period'))])
                                    ->label($model->getAttributeLabel('fcd_call_period')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'fcd_notify_email')
                                    ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('fcd_notify_email'))])
                                    ->label($model->getAttributeLabel('fcd_notify_email')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field mt-0">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'blocked_by', ['options' => ['class' => '']])
                                        ->dropDownList(['user' => FraudCallModule::t('fcd', 'user'), 'destination' => FraudCallModule::t('fcd', 'destination')],
                                            ['prompt' => Yii::t('app', 'select')])
                                        ->label($model->getAttributeLabel('blocked_by')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(FraudCallModule::t('fcd', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord
                    ? FraudCallModule::t('fcd', 'create')
                    : FraudCallModule::t('fcd',
                        'update'),
                    [
                        'class' => $model->isNewRecord
                            ? 'btn waves-effect waves-light amber darken-4'
                            :
                            'btn waves-effect waves-light cyan accent-8',
                    ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
