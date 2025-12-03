<?php

use app\modules\ecosmob\shift\ShiftModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\shift\models\Shift */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field'
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="shift-form" id="shift-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'sft_name', [
                                    'inputOptions' => [
                                       // 'autofocus' => 'autofocus',
                                        'class' => 'form-control',
                                    ],
                                ])->textInput([
                                    'maxlength' => true, 'placeholder' => ($model->getAttributeLabel('sft_name'))
                                ])->label(ShiftModule::t('sft', 'sft_name')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'sft_start_time')->textInput([
                                    'class' => 'from-time-format',
                                    'placeholder' => '00:00:00',
                                    'readonly' => true,
                                    'value' => ($model->isNewRecord ? '00:00:01' : $model->sft_start_time)
                                    //'onkeypress' => 'return isNumberKey(event);',
                                ])->label(ShiftModule::t('sft', 'sft_start_time')); ?>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'sft_end_time')
                                    ->textInput([
                                        'class' => 'to-time-format',
                                        //'id' => 'time-demo2',
                                        'placeholder' => '00:00:00',
                                        'readonly' => true,
                                        'value' => ($model->isNewRecord ? '23:59:59' : $model->sft_end_time)
                                        //'onkeypress' => 'return isNumberKey(event);',
                                    ])
                                    ->label(ShiftModule::t('sft', 'sft_end_time')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(ShiftModule::t('sft', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? ShiftModule::t('sft', 'create') : ShiftModule::t('sft',
                    'update'), [
                    'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                        'btn waves-effect waves-light cyan accent-8'
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
