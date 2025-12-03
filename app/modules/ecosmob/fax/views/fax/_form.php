<?php

use app\modules\ecosmob\fax\FaxModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\fax\assets\FaxAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\fax\models\Fax */
/* @var $form yii\widgets\ActiveForm */

FaxAsset::register($this);
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
                <div class="fax-form" id="fax-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'fax_name', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('fax_name'))])->label(FaxModule::t('fax', 'fax_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'fax_destination')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('fax_destination'))])->label(FaxModule::t('fax', 'fax_destination')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'fax_extension')->textInput([
                                    'maxlength' => TRUE,
                                    'onkeypress' => "return isNumberKey(event)", 'onpaste' => "return paste(this)",
                                    'placeholder' => ($model->getAttributeLabel('fax_extension'))
                                ])->label(FaxModule::t('fax', 'fax_extension')); ?>

                            </div>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                    <p class="h4"><?php /*= FaxModule::t('fax', 'fax_failure') */?>:</p>
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?php /*= Html::checkbox('fax_failure', $model->fax_failure,
                                                ['uncheck' => 0]) */?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(FaxModule::t('fax', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')], ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? FaxModule::t('fax', 'create') : FaxModule::t('fax', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                    'btn waves-effect waves-light cyan accent-8']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

