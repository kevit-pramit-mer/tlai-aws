<?php

use app\modules\ecosmob\phonebook\PhoneBookModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\phonebook\models\Phonebook */
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
                <div class="phonebook-form" id="phonebook-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'ph_first_name')->textInput(['maxlength' => true])->label(PhoneBookModule::t('app',
                                    'ph_first_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'ph_last_name')->textInput(['maxlength' => true])->label(PhoneBookModule::t('app',
                                    'ph_last_name')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'ph_display_name')->textInput(['maxlength' => true])->label(PhoneBookModule::t('app',
                                    'ph_display_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'ph_extension')->textInput(['maxlength'=>true, /*'disabled'=>!$model->isNewRecord*/ 'onkeypress'=>"return isNumberKey(event)"])->label(PhoneBookModule::t('app',
                                    'ph_extension')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'ph_phone_number')->textInput(['maxlength'=>true, 'onkeypress'=>"return isNumberKey(event)"])->label(PhoneBookModule::t('app',
                                    'ph_phone_number')); ?>

                            </div>
                        </div>
                     <!--   <div class="col s12 m6">
                            <div class="input-field">
                                <?php /*= $form->field($model,
                                    'ph_cell_number')->textInput(['maxlength'=>true, 'onkeypress'=>"return isNumberKey(event)"])->label(PhoneBookModule::t('app',
                                    'ph_cell_number')); */?>

                            </div>
                        </div>-->
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'ph_email_id')->textInput(['maxlength' => true])->label(PhoneBookModule::t('app',
                                    'ph_email_id')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(Yii::t('app', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200 ']) ?>
                    <?= Html::submitButton($model->isNewRecord ? PhoneBookModule::t('app',
                        'create') : Yii::t('app',
                        'update'), [
                        'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                            'btn waves-effect waves-light cyan accent-8',
                        'onclick' => "window.parent.postMessage({ type: 'flash', message: 'Flash message from iframe' }, 'http://localhost/uctenant_master/web/index.php?r=extension%2Fextension%2Fdashboard#admin-login')"

                    ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
