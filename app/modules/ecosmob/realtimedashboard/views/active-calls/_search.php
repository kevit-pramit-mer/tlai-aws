<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\realtimedashboard\RealTimeDashboardModule;

/* @var $this yii\web\View */
/* @var $model \app\models\Channels */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion mb-0" data-collapsible="accordion" style="margin-top: -10px">
    <li class="active">
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <?php $form = ActiveForm::begin([
                    'id' => 'active-calls-report-search-form',
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
                <div class="active-calls-report-search"
                     id="active-calls-report-search">

                    <div class="row left-align">
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'cid_num', ['options' => ['class' => 'input-field']])
                                ->textInput(['placeholder' => $model->getAttributeLabel('cid_num')])
                                ->label($model->getAttributeLabel('cid_num')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'dest', ['options' => ['class' => 'input-field']])
                                ->textInput(['placeholder' => $model->getAttributeLabel('dest')])
                                ->label($model->getAttributeLabel('dest')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'callstate', ['options' => ['class' => 'input-field']])
                                    ->dropDownList(['Early' => 'Early', 'Active' => 'Active'], ['prompt' => 'Select Status'])
                                    ->label($model->getAttributeLabel('callstate')); ?>
                            </div>
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
