<?php

use app\modules\ecosmob\carriertrunk\models\TrunkGroup;
use app\modules\ecosmob\dialplan\DialPlanModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\dialplan\models\OutboundDialPlansDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
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
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="outbound-dial-plans-details-form"
                     id="outbound-dial-plans-details-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'odpd_prefix_match_string', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                    ],
                                ])
                                    ->textInput(['maxlength' => TRUE, 'disabled' => $model->odpd_prefix_match_string == ".*", 'placeholder' => ($model->getAttributeLabel('odpd_prefix_match_string'))])
                                    ->label($model->getAttributeLabel('odpd_prefix_match_string')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">

                            <div class="select-wrapper">
                                <?= $form->field($model, 'trunk_grp_id', ['options' => ['class' => '']])
                                    ->dropDownList(TrunkGroup::getAllTrunkGroups(), ['prompt' =>
                                        DialPlanModule::t('dp', 'select')])
                                    ->label($model->getAttributeLabel('trunk_grp_id')); ?>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'odpd_strip_prefix')
                                    ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('odpd_strip_prefix'))])
                                    ->label($model->getAttributeLabel('odpd_strip_prefix')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'odpd_add_prefix')
                                    ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('odpd_add_prefix'))])
                                    ->label($model->getAttributeLabel('odpd_add_prefix')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <div class="input-field">
                    <?= Html::a(DialPlanModule::t('dp', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord ? DialPlanModule::t('dp', 'create') : DialPlanModule::t('dp', 'update'),
                        [
                            'class' => $model->isNewRecord
                                ? 'btn waves-effect waves-light amber darken-4'
                                :
                                'btn waves-effect waves-light cyan accent-8',
                        ]) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
