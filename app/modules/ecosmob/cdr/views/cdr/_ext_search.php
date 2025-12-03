<?php

use app\modules\ecosmob\cdr\CdrModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\cdr\models\CdrSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="cdr-search"
                     id="cdr-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'cdr-search-form',
                        'action' => ['extension-cdr'],
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
                            <?= $form->field($model, 'call_id', ['options' => ['class' => ' input-field ']])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('call_id'))]); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'caller_id_number', ['options' => ['class' => ' input-field ']])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('caller_id_number'))]); ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'start_epoch', ['options' => ['class' => 'input-field ']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('start_epoch')), 'autocomplete' => 'off'/*, 'data-id' => 'rangPlugin'*/]); ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'end_epoch',
                                ['options' => ['class' => ' input-field']])->textInput(['class' => 'form-control to-date-time-range', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('end_epoch')), 'autocomplete' => 'off']); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper ">
                                <?= $form->field(
                                    $model,
                                    'callstatus',
                                    ['options' => ['class' => 'input-field']]
                                )->dropDownList(
                                    [
                                        'failed' => CdrModule::t('cdr', 'failed'),
                                        'completed' => CdrModule::t('cdr', 'completed'),
                                    ],
                                    ['prompt' => CdrModule::t('cdr', "all")]
                                ); ?>
                            </div>
                        </div>    
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'dialed_number', ['options' => ['class' => ' input-field']])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('dialed_number'))]); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'isfile', ['options' => ['class' => 'input-field']])->dropDownList
                                (['Yes' => CdrModule::t('cdr', 'yes'), 'No' => CdrModule::t('cdr', 'no')],
                                    ['prompt' => CdrModule::t('cdr', "select")]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'hangup', ['options' => ['class' => ' input-field']])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('hangup'))]); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(CdrModule::t('cdr', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(CdrModule::t('cdr', 'reset'), [
                                    'extension-cdr',
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