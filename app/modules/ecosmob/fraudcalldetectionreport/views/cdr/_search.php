<?php

use app\modules\ecosmob\fraudcalldetectionreport\FraudCallDetectionReportModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\fraudcalldetectionreport\models\CdrSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="cdr-search" id="cdr-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'cdr-search-form',
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
                        <div class="col s12 m6 calender-icon">
                            <?= $form->field($model, 'start_epoch',
                                ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('start_epoch'))]); ?>
                        </div>
                        <div class="col s12 m6 calender-icon">
                            <?= $form->field($model, 'end_epoch',
                                ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control to-date-time-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('end_epoch'))]); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(FraudCallDetectionReportModule::t('cdr', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(FraudCallDetectionReportModule::t('cdr', 'reset'), [
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
        </div>
    </li>
</ul>
<style>
    .select-month input {
        display: block !important;
        /*color: #E24E42 !important;*/
        font-size: 17px !important;
    }

    .select-year input {
        display: block !important;
        font-size: 17px !important;
        /*color: #E24E42 !important;*/
    }

    .datepicker-controls .select-month input {
        width: 92px;
    }
</style>






