<?php

use app\modules\ecosmob\group\GroupModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\group\models\Group */
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
                <div class="group-form" id="group-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'grp_name', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('grp_name'))]); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'grp_description')->textArea(['maxlength' => TRUE, 'class' => 'materialize-textarea', 'placeholder' => ($model->getAttributeLabel('grp_description'))]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(GroupModule::t('group', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? GroupModule::t('group', 'create') : GroupModule::t('group', 'update'),
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
