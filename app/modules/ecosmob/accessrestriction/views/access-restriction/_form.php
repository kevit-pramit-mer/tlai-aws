<?php

use app\modules\ecosmob\accessrestriction\AccessRestrictionModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\accessrestriction\models\AccessRestriction */
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
                <div class="access-restriction-form" id="access-restriction-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'ar_ipaddress', [
                                    'inputOptions' => [
                                        //'autofocus' => 'autofocus',
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('ar_ipaddress'))])->label(AccessRestrictionModule::t('accessrestriction', 'ar_ipaddress')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'ar_maskbit')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('ar_maskbit'))])->label(AccessRestrictionModule::t('accessrestriction', 'ar_maskbit')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'ar_description')->textInput(['rows' => 6, 'placeholder' => ($model->getAttributeLabel('ar_description'))])->label(AccessRestrictionModule::t('accessrestriction', 'ar_description')); ?>

                            </div>
                        </div>
                        <?php if (!$model->isNewRecord) { ?>
                            <div class="col s12 m6">
                                    <?= $form->field($model, 'ar_status', ['options' => ['class' => 'input-field']])
                                        ->dropDownList(['0' => Yii::t('app', 'inactive'), '1' => Yii::t('app', 'active')], ['prompt' =>
                                            AccessRestrictionModule::t('accessrestriction', 'select')])->label(AccessRestrictionModule::t('accessrestriction', 'ar_status')); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(AccessRestrictionModule::t('accessrestriction', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? AccessRestrictionModule::t('accessrestriction', 'create') : AccessRestrictionModule::t('accessrestriction', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                    'btn waves-effect waves-light cyan accent-8']) ?>
                <?php if (!$model->isNewRecord) { ?>
                    <?= Html::submitButton(AccessRestrictionModule::t('accessrestriction', 'apply'), [
                        'class' => 'btn waves-effect waves-light amber darken-4',
                        'name' => 'apply',
                        'value' => 'update']) ?>
                <?php } ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
