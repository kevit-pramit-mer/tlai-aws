<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\supervisorabandonedcallreport\models\QueueAbandonedCalls */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="queue-abandoned-calls-form"
                     id="queue-abandoned-calls-form">

                    <?php $form = ActiveForm::begin([
                    'class' => 'row',
                    'fieldConfig' => [
                    'options' => [
                    'class' => 'input-field col s12'
                    ],
                    ],
                    ]); ?>

                                                <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'queue_name')->textInput(['maxlength' => true])->label(Yii::t('app','queue_name')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'queue_number')->textInput(['maxlength' => true])->label(Yii::t('app','queue_number')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'caller_id_number')->textInput(['maxlength' => true])->label(Yii::t('app','caller_id_number')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'call_status')->textInput(['maxlength' => true])->label(Yii::t('app','call_status')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'start_time')->textInput(['maxlength' => true])->label(Yii::t('app','start_time')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'end_time')->textInput(['maxlength' => true])->label(Yii::t('app','end_time')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'hold_time')->textInput(['maxlength' => true])->label(Yii::t('app','hold_time')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'max_wait_reached')->textInput(['maxlength' => true])->label(Yii::t('app','max_wait_reached')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'breakaway_digit_dialed')->textInput(['maxlength' => true])->label(Yii::t('app','breakaway_digit_dialed')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'abandoned_time')->textInput(['maxlength' => true])->label(Yii::t('app','abandoned_time')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'abandoned_wait_time')->textInput(['maxlength' => true])->label(Yii::t('app','abandoned_wait_time')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'break_away_wait_time')->textInput(['maxlength' => true])->label(Yii::t('app','break_away_wait_time')); ?>

                                    </div>
                                </div>
                            </div>
                                            <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field col s12">
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create')                            : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                            'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) {?>
                                <?= Html::submitButton(Yii::t('app', 'apply'), [
                                'class' => 'btn waves-effect waves-light amber darken-4',
                                'name' => 'apply',
                                'value' => 'update']) ?>
                            <?php }?>
                            <?= Html::a(Yii::t('app', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                            ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
