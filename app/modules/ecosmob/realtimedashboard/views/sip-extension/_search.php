<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\realtimedashboard\RealTimeDashboardModule;

/* @var $this yii\web\View */
/* @var $model \app\models\SipRegistrations */
/* @var $form yii\widgets\ActiveForm */

?>

<ul class="collapsible collapsible-accordion mb-1" data-collapsible="accordion" style="margin-top: -10px">
    <li class="active">
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <?php $form = ActiveForm::begin([
                    'id' => 'sip-reg-report-search-form',
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
                <div class="sip-reg-report-search" id="sip-reg-report-search">
                    <div class="row align-left">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'sip_user', ['options' => ['class' => 'input-field']])
                                ->textInput(['placeholder' => $model->getAttributeLabel('sip_user')])
                                ->label($model->getAttributeLabel('sip_user')); ?>
                        </div>
                        <div class="col s12 m6 select-wrapper" style="text-align: left !important;">
                                <?= $form->field($model, 'status', ['options' => ['class' => 'input-field']])
                                    ->dropDownList(['Online' => 'Online', 'Away' => 'Away', 'On The Phone' => 'On The Phone', 'Busy' => 'Busy', 'Available' => 'Available'], ['prompt' => 'Select Status'])
                                    ->label($model->getAttributeLabel('status')); ?>
                        </div>
                        <div class="col s12">
                            <div class="input-field">
                                <?= Html::submitButton(RealTimeDashboardModule::t('realtimedashboard', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(RealTimeDashboardModule::t('realtimedashboard', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
