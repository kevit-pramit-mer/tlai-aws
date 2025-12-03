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
                                <?=$form->field($crm, 'lg_first_name')->textInput(['maxlength'=>true, 'placeholder' => CrmModule::t('crm', 'firstname')])->label(CrmModule::t('crm', 'firstname')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?=$form->field($crm, 'lg_last_name')->textInput(['maxlength'=>true, 'placeholder' => CrmModule::t('crm', 'lastname')])->label(CrmModule::t('crm', 'lastname')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?=$form->field($crm, 'lg_contact_number')->textInput(['maxlength'=>true, 'placeholder' => CrmModule::t('crm', 'lg_contact_number')])->label(CrmModule::t('crm', 'lg_contact_number')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?=$form->field($crm, 'lg_contact_number_2')->textInput(['maxlength'=>true, 'placeholder' => CrmModule::t('crm', 'lg_contact_number_2')])->label(CrmModule::t('crm', 'lg_contact_number_2')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?=$form->field($crm, 'lg_email_id')->textInput(['maxlength'=>true, 'placeholder' => CrmModule::t('crm', 'lg_email_id')])->label(CrmModule::t('crm', 'lg_email_id')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?=$form->field($crm, 'lg_address')->textInput(['maxlength'=>true, 'placeholder' => CrmModule::t('crm', 'lg_address')])->label(CrmModule::t('crm', 'lg_address')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?=$form->field($crm, 'lg_alternate_number')->textInput(['maxlength'=>true, 'placeholder' => CrmModule::t('crm', 'lg_alternate_number')])->label(CrmModule::t('crm', 'lg_alternate_number')); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?=$form->field($crm, 'lg_pin_code')->textInput(['maxlength'=>true, 'placeholder' => CrmModule::t('crm', 'lg_pin_code')])->label(CrmModule::t('crm', 'lg_pin_code')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?=$form->field($crm, 'lg_permanent_address')->textInput(['maxlength'=>true, 'placeholder' => CrmModule::t('crm', 'lg_permanent_address')])->label(CrmModule::t('crm', 'lg_permanent_address')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field col s12">
                                <?= $form->field($leadCommentMapping, 'comment')->textarea(['rows'=>6, 'class'=>'materialize-textarea', 'placeholder' => CrmModule::t('crm', 'comments')])->label(CrmModule::t('crm', 'comments')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field col s12 mrg-btn">
                            <?php if (!$crm->isNewRecord) { ?>
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
<script type="text/javascript">
    $(document).ready(function() {
        M.updateTextFields();
    });

</script>
