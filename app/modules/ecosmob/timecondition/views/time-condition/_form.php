<?php

use app\modules\ecosmob\timecondition\TimeConditionModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConstantHelper;

/* @var $model app\modules\ecosmob\timecondition\models\TimeCondition */

?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">
                <div class="create_time_condition_form" id="create_time_condition_form">
                    <?php $form = ActiveForm::begin(
                        [
                            'id' => 'time-condition-form',
                            'class' => 'row',
                            'fieldConfig' => [
                                'options' => [
                                    'class' => 'input-field'
                                ],
                            ],
                        ]); ?>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'tc_name', [
                                    'inputOptions' => [
                                        //'autofocus' => 'autofocus',
                                        'class' => 'form-control',
                                    ],
                                ])->textInput([
                                    'maxlength' => true,
                                ])->label(TimeConditionModule::t('tc', 'tc_name')) ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'tc_description')->textArea([
                                    'maxlength' => true,
                                    'class' => 'materialize-textarea'
                                ])->label(TimeConditionModule::t('tc', 'tc_description')) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'tc_start_month', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(
                                       ConstantHelper::getMonth(),
                                        ['prompt' => TimeConditionModule::t('tc', 'prompt_start_month')]
                                    )->label(TimeConditionModule::t('tc', 'tc_start_month')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'tc_end_month', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(
                                       ConstantHelper::getMonth(),
                                        ['prompt' => TimeConditionModule::t('tc', 'prompt_end_month')]
                                    )->label(TimeConditionModule::t('tc', 'tc_end_month')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'tc_start_date', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(
                                        ConstantHelper::getDate(),
                                        ['prompt' => TimeConditionModule::t('tc', 'prompt_start_date')]
                                    )->label(TimeConditionModule::t('tc', 'tc_start_date')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'tc_end_date', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(
                                        ConstantHelper::getDate(),
                                        ['prompt' => TimeConditionModule::t('tc', 'prompt_end_date')]
                                    )->label(TimeConditionModule::t('tc', 'tc_end_date')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'tc_start_day', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(
                                        ConstantHelper::getDays(),
                                        ['prompt' => TimeConditionModule::t('tc', 'prompt_start_day')]
                                    )->label(TimeConditionModule::t('tc', 'tc_start_day')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'tc_end_day', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(
                                        ConstantHelper::getDays(),
                                        ['prompt' => TimeConditionModule::t('tc', 'prompt_end_day')]
                                    )->label(TimeConditionModule::t('tc', 'tc_end_day')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'tc_start_time')->textInput([
                                    'class' => 'from-time',
                                    //'id' => 'time-demo',
                                    'placeholder' => '00:00:00',
                                    'readonly' => true
                                ])->label(TimeConditionModule::t('tc', 'tc_start_time')) ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'tc_end_time')->textInput([
                                    'class' => 'to-time',
                                    //'id' => 'time-demo2',
                                    'placeholder' => '00:00:00',
                                    'readonly' => true
                                ])->label(TimeConditionModule::t('tc', 'tc_end_time')) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 center">
                            <div class="input-field">
                                <?= Html::submitButton(
                                    $model->isNewRecord ? TimeConditionModule::t('tc', 'create')
                                        : TimeConditionModule::t('tc', 'update'),
                                    [
                                        'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' : 'btn waves-effect waves-light cyan accent-8',
                                    ]) ?>

                                <?php if (!$model->isNewRecord) { ?>
                                    <?= Html::submitButton(TimeConditionModule::t('tc', 'apply'), [
                                        'class' => 'btn waves-effect waves-light amber darken-4 ml-2',
                                        'name' => 'apply',
                                        'value' => 'update'
                                    ]) ?>
                                <?php } ?>

                                <?= Html::a(
                                    TimeConditionModule::t('tc', 'cancel'),
                                    [
                                        'index',
                                        'page' => Yii::$app->session->get('page'),
                                    ],
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
