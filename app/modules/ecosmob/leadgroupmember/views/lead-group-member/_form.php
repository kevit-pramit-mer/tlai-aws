<?php

use app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\leadgroupmember\models\LeadGroupMember */
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
                <div class="lead-group-member-form" id="lead-group-member-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'lg_first_name')->textInput(['maxlength' => true])->label(LeadGroupMemberModule::t('lead-group-member',
                                    'lg_first_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'lg_last_name')->textInput(['maxlength' => true])->label(LeadGroupMemberModule::t('lead-group-member',
                                    'lg_last_name')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'lg_email_id')->textInput(['maxlength' => true])->label(LeadGroupMemberModule::t('lead-group-member',
                                    'lg_email_id')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'lg_contact_number')->textInput(['maxlength' => true, 'onkeypress' => 'return isNumberPlusKey(event);'])->label(LeadGroupMemberModule::t('lead-group-member',
                                    'lg_contact_number')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'lg_contact_number_2')->textInput(['maxlength' => true,'onkeypress' => 'return isNumberPlusKey(event);'])->label(LeadGroupMemberModule::t('lead-group-member',
                                    'lg_contact_number_2')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'lg_alternate_number')->textInput(['maxlength' => true, 'onkeypress' => 'return isNumberPlusKey(event);'])->label(LeadGroupMemberModule::t('lead-group-member',
                                    'lg_alternate_number')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'lg_pin_code')->textInput(['maxlength' => true])->label(LeadGroupMemberModule::t('lead-group-member',
                                    'lg_pin_code')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'lg_permanent_address')->textarea(['rows' => 6, 'class' => 'materialize-textarea', 'maxlength' => true])->label(LeadGroupMemberModule::t('lead-group-member',
                                    'lg_permanent_address')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'lg_address')->textarea(['rows' => 6, 'class' => 'materialize-textarea', 'maxlength' => true])->label(LeadGroupMemberModule::t('lead-group-member',
                                    'lg_address')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(LeadGroupMemberModule::t('lead-group-member', 'cancel'),
                    ['index', 'ld_id' => $model->ld_id, 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? LeadGroupMemberModule::t('lead-group-member',
                    'create') : LeadGroupMemberModule::t('lead-group-member',
                    'update'), [
                    'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                        'btn waves-effect waves-light cyan accent-8'
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    function isNumberPlusKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        var isFirefox = navigator.userAgent.indexOf("Firefox") !== -1;

        // Allow numbers (0-9) and the + key
        if (isFirefox) {
            return (charCode >= 48 && charCode <= 57) || charCode === 43;
        } else {
            return (charCode >= 48 && charCode <= 57) || charCode === 43;
        }
    }
</script>
