<?php

use app\modules\ecosmob\script\ScriptModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\script\assets\ScriptAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\script\models\Script */
/* @var $form yii\widgets\ActiveForm */

ScriptAsset::register($this);
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
                <div class="script-form"
                     id="script-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'scr_name', [
                                    'inputOptions' => [
                                        //'autofocus' => 'autofocus',
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('scr_name'))])->label(ScriptModule::t('script', 'scr_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field input-right">
                                <?= $form->field($model, 'scr_description')->textarea(['rows' => 6, 'placeholder' => ($model->getAttributeLabel('scr_description')), 'class' => 'materialize-textarea'])->label(ScriptModule::t('script', 'description')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(ScriptModule::t('script', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? ScriptModule::t('script', 'create') : ScriptModule::t('script', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                    'btn waves-effect waves-light cyan accent-8']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
