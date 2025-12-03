<?php

use app\modules\ecosmob\manageagent\ManageAgentModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\manageagent\models\ManageAgent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">
                <div class="manage-agent-form"
                     id="manage-agent-form">
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
                                <?= $form->field($model, 'adm_firstname')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_firstname')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_lastname')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_lastname')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_username')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_username')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_email')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_email')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_password')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_password')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_password_hash')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_password_hash')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_contact')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_contact')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_is_admin')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_is_admin')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_status', ['options' => ['class' => '']])->dropDownList([1 => '1', 0 => '0', 2 => '2',], ['prompt' => ManageAgentModule::t('manageagent', 'select')])->label(ManageAgentModule::t('manageagent', 'adm_status')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_timezone_id')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_timezone_id')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_last_login')->textInput(['maxlength' => true])->label(ManageAgentModule::t('manageagent', 'adm_last_login')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="hseparator"></div>
                    <div class="col s12 center">
                        <div class="input-field col s12">
                            <?= Html::submitButton($model->isNewRecord ? ManageAgentModule::t('manageagent', 'create') : ManageAgentModule::t('manageagent', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) { ?>
                                <?= Html::submitButton(ManageAgentModule::t('manageagent', 'apply'), [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                    'name' => 'apply',
                                    'value' => 'update']) ?>
                            <?php } ?>
                            <?= Html::a(ManageAgentModule::t('manageagent', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                                ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
