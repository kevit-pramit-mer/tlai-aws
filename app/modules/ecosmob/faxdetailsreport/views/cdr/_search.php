<?php

use app\modules\ecosmob\faxdetailsreport\FaxDetailsReportModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\faxdetailsreport\models\CdrSearch */
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
                                ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('art_epoch')), 'autocomplete' => 'off']); ?>
                        </div>
                        <div class="col s12 m6 calender-icon">
                            <?= $form->field($model, 'end_epoch',
                                ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control to-date-time-range', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('end_epoch')), 'autocomplete' => 'off']); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(FaxDetailsReportModule::t('cdr', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(FaxDetailsReportModule::t('cdr', 'reset'), [
                                    'index',
                                    'page' =>
                                        Yii::$app->session->get('page')
                                ],
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






