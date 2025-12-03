<?php

use app\modules\ecosmob\leadgroup\LeadgroupModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\leadgroup\models\LeadgroupMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field'
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="leadgroup-master-form"
                     id="leadgroup-master-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'ld_group_name', [
                                        'inputOptions' => [
                                            'class' => 'form-control',
                                        ],
                                    ])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('ld_group_name'))])->label($model->getAttributeLabel('ld_group_name')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <div class="input-field">
                    <?= Html::a(LeadgroupModule::t('leadgroup', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord ? LeadgroupModule::t('leadgroup', 'create') : LeadgroupModule::t('leadgroup',
                        'update'), [
                        'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                            'btn waves-effect waves-light cyan accent-8'
                    ]) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
