<?php

use app\modules\ecosmob\plan\PlanModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\plan\models\Plan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="plan-form"
                     id="plan-form">

                    <?php $form=ActiveForm::begin([
                        'class'=>'row',
                        'fieldConfig'=>[
                            'options'=>[
                                'class'=>'input-field'
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'pl_name')->textInput(['maxlength'=>true])->label(PlanModule::t('pl',
                                    'pl_name')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_black_list') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_black_list', $model->pl_black_list,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_white_list') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_white_list', $model->pl_white_list,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col s12 m6  p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_universal_forward') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_universal_forward', $model->pl_universal_forward,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_no_ans_forward') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_no_ans_forward', $model->pl_no_ans_forward,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_busy_forward') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_busy_forward', $model->pl_busy_forward,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_timebase_forward') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_timebase_forward', $model->pl_timebase_forward,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_selective_forward') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_selective_forward', $model->pl_selective_forward,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_shift_forward') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_shift_forward', $model->pl_shift_forward,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_unavailable_forward') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_unavailable_forward', $model->pl_unavailable_forward,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_redial') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_redial', $model->pl_redial,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_holiday') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_holiday', $model->pl_holiday, ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_week_off') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_week_off', $model->pl_week_off, ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_bargain') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_bargain', $model->pl_bargain, ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_dnd') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_dnd', $model->pl_dnd, ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_park') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_park', $model->pl_park, ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_transfer') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_transfer', $model->pl_transfer, ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_call_record') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_call_record', $model->pl_call_record,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_caller_id_block') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_caller_id_block', $model->pl_caller_id_block,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_call_return') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_call_return', $model->pl_call_return,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s6">
                                    <p class="h4"><?= PlanModule::t('pl', 'pl_busy_callback') ?>:</p>
                                </div>
                                <div class="col s6">
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('pl_busy_callback', $model->pl_busy_callback,
                                                ['uncheck'=>0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hseparator"></div>
                    <div class="row">
                        <div class="col s12 center">
                            <div class="input-field">
                                <?= Html::submitButton($model->isNewRecord ? PlanModule::t('pl', 'create') : PlanModule::t('pl',
                                    'update'), [
                                    'class'=>$model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                        'btn waves-effect waves-light cyan accent-8'
                                ]) ?>
                                <?php if (!$model->isNewRecord) { ?>
                                    <?= Html::submitButton(PlanModule::t('pl', 'apply'), [
                                        'class'=>'btn waves-effect waves-light amber darken-4 ml-2',
                                        'name'=>'apply',
                                        'value'=>'update'
                                    ]) ?>
                                <?php } ?>
                                <?= Html::a(PlanModule::t('pl', 'cancel'),
                                    ['index', 'page'=>Yii::$app->session->get('page')],
                                    ['class'=>'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
