<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\callrecordings\CallRecordingsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callrecordings\models\CallRecordings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="call-recordings-form"
                     id="call-recordings-form">

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
                                            <?= $form->field($model, 'cr_name')->textInput(['maxlength' => true])->label(CallRecordingsModule::t('app','cr_name')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'cr_files')->textInput(['maxlength' => true])->label(CallRecordingsModule::t('app','cr_files')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'cr_date')->textInput(['maxlength' => true])->label(CallRecordingsModule::t('app','cr_date')); ?>

                                    </div>
                                </div>
                            </div>
                                            <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field col s12">
                            <?= Html::submitButton($model->isNewRecord ? CallRecordingsModule::t('app', 'create')                            : CallRecordingsModule::t('app', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                            'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) {?>
                                <?= Html::submitButton(CallRecordingsModule::t('app', 'apply'), [
                                'class' => 'btn waves-effect waves-light amber darken-4',
                                'name' => 'apply',
                                'value' => 'update']) ?>
                            <?php }?>
                            <?= Html::a(CallRecordingsModule::t('app', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                            ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
