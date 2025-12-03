<?php

use app\modules\ecosmob\dispositionType\DispositionTypeModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\dispositionType\models\DispositionType */
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
                <div class="disposition-type-form"
                     id="disposition-type-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'ds_type')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('ds_type'))])->label($model->getAttributeLabel('ds_type')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                    <?= Html::a(DispositionTypeModule::t('dispositionType', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord ? DispositionTypeModule::t('dispositionType', 'create') : DispositionTypeModule::t('dispositionType',
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
