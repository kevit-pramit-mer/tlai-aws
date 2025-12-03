<?php

use app\modules\ecosmob\iptable\IpTableModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\iptable\models\IpTable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row form-submission">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field',
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="card-content mb-0">
                <div class="ip-table-form" id="ip-table-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'it_source', [
                                'inputOptions' => [
                                   // 'autofocus' => 'autofocus',
                                    'class' => 'form-control',
                                ],
                            ])
                                ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('it_source'))])
                                ->label($model->getAttributeLabel('it_source')); ?>
                        </div>

                        <div class="col s12 m6">
                            <?= $form->field($model, 'it_destination')
                                ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('it_destination'))])
                                ->label($model->getAttributeLabel('it_destination')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'it_port')
                                ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('it_port'))])
                                ->label($model->getAttributeLabel('it_port')); ?>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'it_protocol', ['options' => ['class' => '']])->dropDownList([
                                    'TCP' => $model->getAttributeLabel('it_tcp'),
                                    'UDP' => $model->getAttributeLabel('it_udp'),
                                    'ANY' => $model->getAttributeLabel('it_any'),
                                ],
                                    ['prompt' => Yii::t('app', 'select')])->label($model->getAttributeLabel('it_protocol')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'it_service')
                                ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('it_service'))])
                                ->label($model->getAttributeLabel('it_service')); ?>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'it_action', ['options' => ['class' => '']])->dropDownList([
                                    'Reject' => $model->getAttributeLabel('it_reject'),
                                    'Accept' => $model->getAttributeLabel('it_accept'),
                                ],
                                    ['prompt' => Yii::t('app', 'select')])->label($model->getAttributeLabel('it_action')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 ml-0">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'it_direction', ['options' => ['class' => '']])
                                    ->dropDownList(['Inbound' => $model->getAttributeLabel('it_inbound')
                                        , 'Outbound' => $model->getAttributeLabel('it_outbound'),],
                                        ['prompt' => Yii::t('app', 'select')])
                                    ->label($model->getAttributeLabel('it_direction')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(IpTableModule::t('it', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? IpTableModule::t('it', 'create') : IpTableModule::t('it', 'update'),
                    [
                        'class' => $model->isNewRecord
                            ? 'btn waves-effect waves-light amber darken-4'
                            :
                            'btn waves-effect waves-light cyan accent-8',
                    ]) ?>
                <?php if (!$model->isNewRecord) { ?>
                    <?= Html::submitButton(IpTableModule::t('it', 'apply'),
                        [
                            'class' => 'btn waves-effect waves-light amber darken-4',
                            'name' => 'apply',
                            'value' => 'update',
                        ]) ?>
                <?php } ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
