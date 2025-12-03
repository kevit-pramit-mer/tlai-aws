<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule;
use yii\helpers\ArrayHelper;
use app\modules\ecosmob\extension\models\Extension;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebookSearch */
/* @var $form yii\widgets\ActiveForm */

$extension = ArrayHelper::map(Extension::find()->select(['em_id', 'CONCAT(em_extension_name, " - ", em_extension_number) as name'])->where(['em_status' => '1'])->asArray()->all(), 'em_id', 'name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" style="margin-top: -10px">
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body pb-0">
            <div id="input-fields">
                <div class="enterprise-phonebook-search" id="enterprise-phonebook-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'enterprise-phonebook-search-form',
                        'action' => ['view'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field'
                            ],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field ">
                                <?= $form->field($model,
                                    'en_first_name')->textInput(['maxlength' => true, 'placeholder' => EnterprisePhonebookModule::t('app', 'en_first_name')])->label(EnterprisePhonebookModule::t('app',
                                    'en_first_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field ">
                                <?= $form->field($model,
                                    'en_last_name')->textInput(['maxlength' => true, 'placeholder' => EnterprisePhonebookModule::t('app', 'en_last_name')])->label(EnterprisePhonebookModule::t('app',
                                    'en_last_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'en_extension', ['options' => ['class' => 'col-xs-12 col-md-6']])
                                    ->dropDownList($extension, ['prompt' => EnterprisePhonebookModule::t('app', 'prompt_extension')])->label(EnterprisePhonebookModule::t('app', 'en_extension')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field ">
                                <?= $form->field($model,
                                    'en_mobile')->textInput(['maxlength' => true, 'placeholder' => EnterprisePhonebookModule::t('app', 'en_mobile'), 'onkeypress' => 'return isNumberKey(event);',])->label(EnterprisePhonebookModule::t('app',
                                    'en_mobile')); ?>

                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(EnterprisePhonebookModule::t('app', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(EnterprisePhonebookModule::t('app', 'reset'), [
                                    'view',
                                    'page' =>
                                        Yii::$app->session->get('page')
                                ],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
