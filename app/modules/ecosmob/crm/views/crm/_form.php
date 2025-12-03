<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\crm\CrmModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\crm\models\LeadGroupMember */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="lead-group-member-form"
                     id="lead-group-member-form">

                    <?php $form=ActiveForm::begin([
                        'class'=>'row',
                        'fieldConfig'=>[
                            'options'=>[
                                'class'=>'input-field'
                            ],
                        ],
                    ]); ?>
                    <!--
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <? /*= $form->field($model, 'ld_id')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'ld_id')); */ ?>

                            </div>
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_first_name')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'firstname')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_last_name')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lastname')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_contact_number')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_contact_number')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_contact_number_2')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_contact_number_2')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_email_id')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_email_id')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_address')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_address')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_alternate_number')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_alternate_number')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_pin_code')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_pin_code')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_permanent_address')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_permanent_address')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'lg_dial_status', ['options'=>['class'=>'']])->dropDownList(['NEW'=>'NEW', 'DONE'=>'DONE', 'NO_ANS'=>'NO ANS', 'BUSY'=>'BUSY', 'UNAVAILABLE'=>'UNAVAILABLE',], ['prompt'=>CrmModule::t('crm', 'select')])->label(CrmModule::t('crm', 'dial_status')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field mrg-btn">
                            <?= Html::submitButton($model->isNewRecord ? CrmModule::t('crm', 'create') : CrmModule::t('crm', 'update'), ['class'=>$model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) { ?>
                                <?= Html::submitButton(CrmModule::t('crm', 'apply'), [
                                    'class'=>'btn waves-effect waves-light amber darken-4 ml-2',
                                    'name'=>'apply',
                                    'value'=>'update']) ?>
                            <?php } ?>
                            <?= Html::a(CrmModule::t('crm', 'cancel'), ['index', 'page'=>Yii::$app->session->get('page')],
                                ['class'=>'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .mrg-btn {
        margin-bottom: 1em
    }
</style>
