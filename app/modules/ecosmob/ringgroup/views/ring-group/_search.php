<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\ringgroup\RingGroupModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\ringgroup\models\RingGroupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?=Yii::t('app','search')?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="ring-group-search" id="ring-group-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'ring-group-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1,
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field',
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <?= $form->field($model,
                                'rg_name',
                                [
                                    'options' => ['class' => 'input-field '],
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                        'placeholder' => RingGroupModule::t('rg', "name"),
                                    ],
                                ]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'number', ['options' => ['class' => 'input-field ']])->textInput( [ 'maxlength' => TRUE, 'onkeypress'=>"return isNumberKey(event)",'placeholder' => $model->getAttributeLabel("number") ] ); ?>
                        </div>
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'rg_type', ['options' => ['class' => 'input-field']])
                                    ->dropDownList(['SIMULTANEOUS' =>  RingGroupModule::t('rg', 'simultaneous'), 'SEQUENTIAL' =>  RingGroupModule::t('rg', 'sequential'),],
                                        ['prompt' => RingGroupModule::t('rg', 'select')])
                                    ->label($model->getAttributeLabel('rg_type')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'rg_status', ['options' => ['class' => 'input-field']])
                                    ->dropDownList(['0' => Yii::t('app','inactive'), '1' => Yii::t('app','active')], ['prompt' =>
                                        RingGroupModule::t('rg', 'select')])
                                    ->label($model->getAttributeLabel('rg_status')); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                            <?= Html::submitButton(RingGroupModule::t('rg', 'search'),
                                [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                ]) ?>
                            <?= Html::a(RingGroupModule::t('rg', 'reset'),
                                [
                                    'index',
                                    'page' => Yii::$app->session->get('page'),
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
