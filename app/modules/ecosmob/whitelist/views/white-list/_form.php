<?php

use app\modules\ecosmob\whitelist\WhiteListModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\whitelist\models\WhiteList */
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
                <div class="white-list-form" id="white-list-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'wl_number', [
                                        'inputOptions' => [
                                            'class' => 'form-control',
                                        ],
                                    ])->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('wl_number')), 'onkeypress' => "return isNumberKey(event)", 'onpaste' => "return paste(this)"])->label(WhiteListModule::t('wl',
                                    'wl_number')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'wl_description')
                                    ->textArea(['maxlength' => TRUE, 'placeholder' => WhiteListModule::t('wl',
                                        'wl_description'), 'class' => 'materialize-textarea'])
                                    ->label(WhiteListModule::t('wl',
                                        'wl_description')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(WhiteListModule::t('wl', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord
                        ? WhiteListModule::t('wl', 'create')
                        : WhiteListModule::t('wl',
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
