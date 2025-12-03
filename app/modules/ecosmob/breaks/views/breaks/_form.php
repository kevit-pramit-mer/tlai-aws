<?php

use app\modules\ecosmob\breaks\BreaksModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\breaks\models\Breaks */
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
                <div class="breaks-form" id="breaks-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'br_reason', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => TRUE, 'placeholder' => $model->getAttributeLabel('br_reason')])->label($model->getAttributeLabel('br_reason')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'br_description')->textInput(['maxlength' => TRUE, 'placeholder' => $model->getAttributeLabel('br_description')])->label($model->getAttributeLabel('br_description')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(BreaksModule::t('breaks', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? BreaksModule::t('breaks', 'create') : BreaksModule::t('breaks', 'update'),
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
