<?php

use app\modules\ecosmob\callcampaign\CallCampaignModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callcampaign\models\CallCampaignSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i>Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="call-campaign-search"
                     id="call-campaign-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'call-campaign-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field col s12'
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'cmp_name') ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'cmp_status', ['options' => ['class' => '']])->dropDownList([
                                    'Active' => 'Active',
                                    'Inactive' => 'Inactive',
                                    'ALL' => 'ALL',
                                ], [
                                    'prompt' => CallCampaignModule::t('app', 'select')
                                ])->label(CallCampaignModule::t('app', 'cmp_status')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'cmp_disposition',
                                        ['options' => ['class' => '']])->dropDownList([
                                        'ALL' => 'ALL',
                                        'OFF' => 'OFF',
                                        'ON' => 'ON',
                                    ], [
                                        'prompt' => CallCampaignModule::t('app', 'select')
                                    ])->label(CallCampaignModule::t('app', 'cmp_disposition')); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(CallCampaignModule::t('app', 'search'), [
                                'class' =>
                                    'btn waves-effect waves-light amber darken-4'
                            ]) ?>
                            <?= Html::a(CallCampaignModule::t('app', 'reset'), [
                                'index',
                                'page' =>
                                    Yii::$app->session->get('page')
                            ],
                                ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                        </div>
                    </div>


                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </li>
</ul>
