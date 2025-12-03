<?php

use app\modules\ecosmob\blacklistnumberdetails\BlacklistNumberDetailsModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\blacklistnumberdetails\models\CdrSearch */
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
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                       /* 'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field'
                            ],
                        ],*/
                    ]); ?>

                    <div class="row">
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'caller_id_number', ['options' => ['class' => ' input-field']])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('caller_id_number'))]); ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                                <?= $form->field($model, 'start_epoch',
                                    ['options' => ['class' => ' input-field']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('start_epoch')), 'autocomplete' => 'off']); ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                                <?= $form->field($model, 'end_epoch',
                                    ['options' => ['class' => ' input-field']])->textInput(['class' => 'form-control to-date-time-range', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('end_epoch')), 'autocomplete' => 'off']); ?>
                        </div>
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'answer_epoch',
                                    ['options' => ['class' => ' input-field']])->textInput(['class' => 'form-control date-time-picker', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('answer_epoch')), 'autocomplete' => 'off']); ?>
                        </div>
                        <div class="col s12 m6 l4">

                                <?= $form->field(
                                    $model,
                                    'callstatus',
                                    ['options' => ['class' => ' input-field']]
                                )->dropDownList(
                                    [
                                        'failed' => BlacklistNumberDetailsModule::t('cdr', 'failed'),
                                        'completed' => BlacklistNumberDetailsModule::t('cdr', 'completed'),
                                    ],
                                    ['prompt' => BlacklistNumberDetailsModule::t('cdr', "all")]
                                ); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'dialed_number', ['options' => ['class' => ' input-field']])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('dialed_number'))]); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(BlacklistNumberDetailsModule::t('cdr', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(BlacklistNumberDetailsModule::t('cdr', 'reset'), [
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






