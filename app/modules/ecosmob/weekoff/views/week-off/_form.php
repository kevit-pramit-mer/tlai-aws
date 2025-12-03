<?php

use app\modules\ecosmob\weekoff\WeekOffModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConstantHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\weekoff\models\WeekOff */
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
                <div class="week-off-form"
                     id="week-off-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field ">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'wo_day', ['options' => [
                                        'class' => '',
                                    ], 'inputOptions' => ['autofocus' => true]])->dropDownList(
                                        ConstantHelper::getDays(),
                                        ['prompt' => WeekOffModule::t('wo', 'prompt_wo_day')])
                                        ->label(WeekOffModule::t('wo', 'wo_day')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'wo_start_time')->textInput([
                                    'class' => 'form-control from-time-format',
                                    'placeholder' => '00:00:00',
                                    'readonly' => true,
                                    'value' => ($model->isNewRecord ? '00:00:01' : $model->wo_start_time)
                                ])->label(WeekOffModule::t('wo', 'wo_start_time')); ?>
                            </div>
                        </div>

                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'wo_end_time')->textInput([
                                    'class' => 'form-control to-time-format',
                                    'placeholder' => '00:00:00',
                                    'readonly' => true,
                                    'value' => ($model->isNewRecord ? '23:59:59' : $model->wo_end_time)
                                ])->label(WeekOffModule::t('wo', 'wo_end_time')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <div class="input-field">
                    <?= Html::a(WeekOffModule::t('wo', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord ? WeekOffModule::t('wo', 'create') : WeekOffModule::t('wo',
                        'update'), [
                        'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                            'btn waves-effect waves-light cyan accent-8'
                    ]) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>