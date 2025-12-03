<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\activecalls\models\ActiveCalls */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="active-calls-form"
                     id="active-calls-form">

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
                                            <?= $form->field($model, 'caller_id')->textInput(['maxlength' => true])->label(Yii::t('app','caller_id')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'did')->textInput(['maxlength' => true])->label(Yii::t('app','did')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'destination_number')->textInput(['maxlength' => true])->label(Yii::t('app','destination_number')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'uuid')->textInput(['maxlength' => true])->label(Yii::t('app','uuid')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'status')->textInput(['maxlength' => true])->label(Yii::t('app','status')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'queue')->textInput(['maxlength' => true])->label(Yii::t('app','queue')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'agent')->textInput(['maxlength' => true])->label(Yii::t('app','agent')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'call_queue_time')->textInput(['maxlength' => true])->label(Yii::t('app','call_queue_time')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'call_start_time')->textInput(['maxlength' => true])->label(Yii::t('app','call_start_time')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'call_agent_time')->textInput(['maxlength' => true])->label(Yii::t('app','call_agent_time')); ?>

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
