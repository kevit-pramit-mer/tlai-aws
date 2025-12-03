<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\customerdetails\CustomerDetailsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\customerdetails\models\CampaignMappingUserSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $campaignList */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion"  >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="campaign-mapping-user-search" id="campaign-mapping-user-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'campaign-mapping-user-search-form',
                        'action' => ['index'],
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
                            <div class="select-wrapper">
                                <?= $form->field($model, 'campaign_id', ['options' => ['class' => 'input-field']])->dropDownList($campaignList, ['prompt' => CustomerDetailsModule::t('customerdetails', 'select_camp')])->label(CustomerDetailsModule::t('customerdetails', 'campaign'));
                                ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_first_name')->textInput(['maxlength' => true, 'class' => '', 'placeholder' => CustomerDetailsModule::t('customerdetails', 'f_name')])->label(CustomerDetailsModule::t('customerdetails', 'f_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_last_name')->textInput(['maxlength' => true, 'class' => '', 'placeholder' => CustomerDetailsModule::t('customerdetails', 'l_name')])->label(CustomerDetailsModule::t('customerdetails', 'l_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'lg_contact_number')->textInput(['maxlength' => true, 'class' => '', 'placeholder' => CustomerDetailsModule::t('customerdetails', 'contact_number'), 'onkeypress' => "return isNumberKey(event)", 'onpaste' => "return paste(this)"])->label(CustomerDetailsModule::t('customerdetails', 'contact_number')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(CustomerDetailsModule::t('customerdetails', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(CustomerDetailsModule::t('customerdetails', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
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
