<?php

use app\modules\ecosmob\campaign\CampaignModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\campaign\models\CampaignSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $timeZoneList */
/* @var $dispositionList */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="campaign-search"
                     id="campaign-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'campaign-search-form',
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
                            <?= $form->field($model, 'cmp_name')->textInput(['placeholder' => ($model->getAttributeLabel('cmp_name'))]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <div id="campaign-cmp_type">
                                <div class="select-wrapper input-field">
                                    <?= $form->field($model, 'cmp_type', ['options' => ['class' => '']])->dropDownList(['Inbound' => CampaignModule::t('campaign', 'inbound'), 'Outbound' => CampaignModule::t('campaign', 'outbound'), 'Blended' => CampaignModule::t('campaign', 'blended')], ['prompt' => CampaignModule::t('campaign', 'select_camp_type')])->label(CampaignModule::t('campaign', 'camp_type')); ?>

                                </div>
                            </div>
                        </div>
                        <!--<div class="col s12 m6 l4">
                            <?php /*= $form->field($model, 'cmp_caller_id')->textInput(['placeholder' => ($model->getAttributeLabel('cmp_caller_id'))]) */?>
                        </div>-->
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper input-field">
                                <?= $form->field($model, 'cmp_timezone', ['options' => ['class' => '']])->dropDownList($timeZoneList, ['prompt' => CampaignModule::t('campaign', 'select_tz')])->label(CampaignModule::t('campaign', 'tz'));
                                ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper input-field">
                                <?= $form->field($model, 'cmp_disp', ['options' => ['class' => '']])->dropDownList($dispositionList, ['prompt' => CampaignModule::t('campaign', 'select_despo')])->label(CampaignModule::t('campaign', 'disposition')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper input-field">
                                <?= $form->field($model, 'cmp_status', ['options' => ['class' => '']])->dropDownList
                                (['Active' => Yii::t('app', 'active'), 'Inactive' => Yii::t('app', 'inactive'),], ['prompt' => CampaignModule::t('campaign',
                                    'select_status')])->label(CampaignModule::t('campaign', 'status')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(CampaignModule::t('campaign', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(CampaignModule::t('campaign', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
