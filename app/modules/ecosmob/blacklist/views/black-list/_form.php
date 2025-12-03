<?php

use app\modules\ecosmob\blacklist\BlackListModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\blacklist\models\BlackList */
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
                <div class="black-list-form" id="black-list-form">
                    <div class="row ">
                        <div class="col s12 m6">
                            <?= $form->field($model,
                                'bl_number', [
                                    'inputOptions' => [
                                        //'autofocus' => 'autofocus',
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('bl_number')), 'onkeypress' => "return isNumberKey(event)", 'onpaste' => "return paste(this)"])->label(BlackListModule::t('bl',
                                'bl_number')); ?>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'bl_type', ['options' => [
                                'class' => 'input-field'
                            ]])->dropDownList([
                                'IN' => BlackListModule::t('bl', 'in'),
                                'OUT' => BlackListModule::t('bl', 'out'),
                                'BOTH' => BlackListModule::t('bl', 'both'),
                            ], ['prompt' => BlackListModule::t('bl',
                                'select')])->label(BlackListModule::t('bl', 'bl_type')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'bl_reason')->textArea(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('bl_reason')), 'class' => 'materialize-textarea'])->label(BlackListModule::t('bl',
                                    'bl_reason')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(BlackListModule::t('bl',
                    'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? BlackListModule::t('bl',
                    'create') : BlackListModule::t('bl',
                    'update'), [
                    'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                        'btn waves-effect waves-light cyan accent-8'
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
