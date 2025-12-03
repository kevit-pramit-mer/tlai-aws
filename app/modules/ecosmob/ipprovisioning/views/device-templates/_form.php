<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\ipprovisioning\models\DeviceTemplates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="device-templates-form"
                     id="device-templates-form">

                    <?php $form = ActiveForm::begin([
                    'class' => 'row',
                    'fieldConfig' => [
                    'options' => [
                    'class' => 'input-field col s12'
                    ],
                    ],
                    ]); ?>

                                                <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'device_templates_id')->textInput(['maxlength' => true])->label(Yii::t('app','device_templates_id')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'template_name')->textarea(['rows' => 6])->label(Yii::t('app','template_name')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'brand_id')->textInput(['maxlength' => true])->label(Yii::t('app','brand_id')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'voipservice_key')->textInput(['maxlength' => true])->label(Yii::t('app','voipservice_key')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'profile_count')->textInput(['maxlength' => true])->label(Yii::t('app','profile_count')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'supported_models_id')->textInput(['maxlength' => true])->label(Yii::t('app','supported_models_id')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'upload_csv')->textInput(['maxlength' => true])->label(Yii::t('app','upload_csv')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'createdAt')->textInput(['maxlength' => true])->label(Yii::t('app','createdAt')); ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'updatedAt')->textInput(['maxlength' => true])->label(Yii::t('app','updatedAt')); ?>

                                    </div>
                                </div>
                            </div>
                                            <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field col s12">
                            <?= Html::submitButton($model->isNewRecord ? 'Create'                            : 'Update', ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                            'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) {?>
                                <?= Html::submitButton(Yii::t('app', 'apply'), [
                                'class' => 'btn waves-effect waves-light amber darken-4 ml-2',
                                'name' => 'apply',
                                'value' => 'update']) ?>
                            <?php }?>
                            <?= Html::a(Yii::t('app', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                            ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
