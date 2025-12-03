<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\systemcode\models\FeatureMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="feature-master-form"
                     id="feature-master-form">

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
                                <?= $form->field($model, 'feature_name')->textInput(['maxlength' => true])->label(Yii::t('app', 'feature_name')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'feature_code')->textInput(['maxlength' => true])->label(Yii::t('app', 'feature_code')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'feature_desc')->textInput(['maxlength' => true])->label(Yii::t('app', 'feature_desc')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field col s12">
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) { ?>
                                <?= Html::submitButton(Yii::t('app', 'apply'), [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                    'name' => 'apply',
                                    'value' => 'update']) ?>
                            <?php } ?>
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
