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
                                'class'=>'input-field col s12'
                            ],
                        ],
                    ]);
                    ?>

                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($progresiveTypeData, 'lg_first_name')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'firstname')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($progresiveTypeData, 'lg_last_name')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lastname')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($progresiveTypeData, 'lg_contact_number')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_contact_number')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($progresiveTypeData, 'lg_contact_number_2')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_contact_number_2')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($progresiveTypeData, 'lg_email_id')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_email_id')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($progresiveTypeData, 'lg_address')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_address')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($progresiveTypeData, 'lg_alternate_number')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_alternate_number')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($progresiveTypeData, 'lg_pin_code')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_pin_code')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($progresiveTypeData, 'lg_permanent_address')->textInput(['maxlength'=>true])->label(CrmModule::t('crm', 'lg_permanent_address')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field col s12">
                                <?= $form->field($leadCommentMapping, 'comment')->textarea(['rows'=>6, 'class'=>'materialize-textarea'])->label(CrmModule::t('crm', 'comment')); ?>
                                <input type="hidden" id="id" value=" <?= $leadCommentMapping->id; ?>"
                                       name="LeadCommentMapping[id]">
                                <!-- <input type="hidden" id="lg_id" value=" <? /*= $crmList->lg_id; */ ?>" name="LeadCommentMapping[lead_id]">-->

                                <?php
                                /*echo $form->field(
                                    $leadCommentMapping,
                                    'id'
                                )->hiddenInput(['id'=>'id'])->label(
                                    FALSE
                                );

                                echo $form->field(
                                    $crmList,
                                    'lg_id'
                                )->hiddenInput(['id'=>'lg_id'])->label(
                                    FALSE
                                );*/
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="hseparator"></div>
                    <div class="col s12 center">
                        <div class="input-field col s12 mrg-btn">
                            <?= Html::submitButton(CrmModule::t('crm', 'update'), [
                                'class'=>'btn waves-effect waves-light amber darken-4',
                                'name'=>'Update',
                                'value'=>'update']) ?>
                            <?php echo Html::Button(CrmModule::t('crm', 'cancel'), ['class'=>'btn waves-effect waves-light amber darken-4 cancel']) ?>
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
<script type="text/javascript">
    $(document).ready(function () {
        M.updateTextFields();
    });

</script>
