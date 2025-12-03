<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule;
use app\modules\ecosmob\extension\models\Extension;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebook */
/* @var $form yii\widgets\ActiveForm */
/* @var $extension */

$extension = ArrayHelper::map(Extension::find()->select(['em_id', 'CONCAT(em_extension_name, " - ", em_extension_number) as name'])->where(['em_status' => '1'])->asArray()->all(), 'em_id', 'name');
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
                <div class="enterprise-phonebook-form" id="enterprise-phonebook-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'en_first_name')->textInput(['maxlength' => true, 'placeholder' => EnterprisePhonebookModule::t('app', 'en_first_name')])->label(EnterprisePhonebookModule::t('app',
                                    'en_first_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'en_last_name')->textInput(['maxlength' => true, 'placeholder' => EnterprisePhonebookModule::t('app', 'en_last_name')])->label(EnterprisePhonebookModule::t('app',
                                    'en_last_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 clear-both">
                            <div class="input-field">
                                <?php echo $form->field($model, 'en_extension', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                    ->dropDownList($extension, ['prompt' => EnterprisePhonebookModule::t('app', 'prompt_extension')])->label(EnterprisePhonebookModule::t('app', 'en_extension')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'en_mobile')->textInput(['maxlength'=>true, 'placeholder' => EnterprisePhonebookModule::t('app', 'en_mobile'), 'onkeypress'=>"return isNumberKeyWithPlus(event)", 'onpaste' => "return paste(this)"])->label(EnterprisePhonebookModule::t('app',
                                    'en_mobile')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'en_phone')->textInput(['maxlength' => true, 'placeholder' => EnterprisePhonebookModule::t('app', 'en_phone'), 'onkeypress' => "return isNumberKeyWithPlus(event)", 'onpaste' => "return paste(this)"])->label(EnterprisePhonebookModule::t('app',
                                    'en_phone')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'en_email_id')->textInput(['maxlength' => true, 'placeholder' => EnterprisePhonebookModule::t('app', 'en_email_id')])->label(EnterprisePhonebookModule::t('app',
                                    'en_email_id')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6 clear-both">
                            <div class="input-field">
                                <?php echo $form->field($model, 'en_status', ['options' => ['class' => '']])
                                    ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label
                                    (EnterprisePhonebookModule::t('app', 'en_status')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <span style="font-size: 12px;"><?= EnterprisePhonebookModule::t('app', 'note') ?></span>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(Yii::t('app', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? EnterprisePhonebookModule::t('app',
                    'create') : Yii::t('app',
                    'update'), [
                    'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                        'btn waves-effect waves-light cyan accent-8'
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
