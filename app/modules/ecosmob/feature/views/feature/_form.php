<?php

use app\modules\ecosmob\feature\FeatureModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\feature\models\Feature */
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
                <div class="feature-form" id="feature-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'feature_name')
                                    ->textInput(['maxlength' => TRUE, 'disabled' => true,'placeholder' => ($model->getAttributeLabel('feature_name'))])
                                    ->label($model->getAttributeLabel('feature_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'feature_code')
                                    ->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('feature_code'))])
                                    ->label($model->getAttributeLabel('feature_code')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'feature_desc')
                                    ->textArea(['maxlength' => TRUE, 'class' => 'materialize-textarea', 'placeholder' => ($model->getAttributeLabel('feature_desc'))])
                                    ->label($model->getAttributeLabel('feature_desc')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(FeatureModule::t('feature', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord ? FeatureModule::t('feature', 'create') : FeatureModule::t('feature', 'update'),
                        [
                            'class' => $model->isNewRecord
                                ? 'btn waves-effect waves-light amber darken-4'
                                :
                                'btn waves-effect waves-light cyan accent-8',
                        ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
