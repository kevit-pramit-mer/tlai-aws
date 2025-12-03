<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\supervisorqueuecallback\models\QueueCallback */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="queue-callback-form"
                     id="queue-callback-form">

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
                                            <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true])->label(Yii::t('app','phone_number')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'created_at')->textInput(['maxlength' => true])->label(Yii::t('app','created_at')); ?>

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
