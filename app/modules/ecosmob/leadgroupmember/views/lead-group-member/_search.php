<?php

use app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\leadgroupmember\models\LeadGroupMemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" style="margin: 10px">
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="lead-group-member-search"
                     id="lead-group-member-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'lead-group-member-search-form',
                        'action' => ['index', 'ld_id' => isset($_GET['ld_id']) ? $_GET['ld_id'] : ''],
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
                            <?= $form->field($model, 'lg_first_name')->textInput(['placeholder' => LeadGroupMemberModule::t('lead-group-member',
                                'lg_first_name')])->label(LeadGroupMemberModule::t('lead-group-member',
                                'lg_first_name')) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model,
                                'lg_last_name')->textInput(['placeholder' => LeadGroupMemberModule::t('lead-group-member',
                                'lg_last_name')])->label(LeadGroupMemberModule::t('lead-group-member',
                                'lg_last_name')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model,
                                'lg_email_id')->textInput(['maxlength' => true, 'placeholder' => LeadGroupMemberModule::t('lead-group-member',
                                'lg_email_id')]); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'lg_contact_number')->textInput(['maxlength' => true, 'placeholder' => LeadGroupMemberModule::t('lead-group-member',
                                'lg_contact_number'), 'onkeypress' => 'return isNumberKey(event);'])->label(LeadGroupMemberModule::t('lead-group-member',
                                'lg_contact_number')) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(LeadGroupMemberModule::t('lead-group-member', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(LeadGroupMemberModule::t('lead-group-member', 'reset'), [
                                    'index',
                                    'ld_id' => isset($_GET['ld_id']) ? $_GET['ld_id'] : '',
                                    'page' =>
                                        Yii::$app->session->get('page')
                                ],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>   
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </li>
</ul>
