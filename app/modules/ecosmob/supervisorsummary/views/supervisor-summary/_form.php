<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\supervisorsummary\models\UsersActivityLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="users-activity-log-form"
                     id="users-activity-log-form">
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
                                <?= $form->field($model, 'user_id')->textInput(['maxlength' => true])->label(Yii::t('app', 'user_id')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'login_time')->textInput(['maxlength' => true])->label(Yii::t('app', 'login_time')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'logout_time')->textInput(['maxlength' => true])->label(Yii::t('app', 'logout_time')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'campaign_name')->textInput(['maxlength' => true])->label(Yii::t('app', 'campaign_name')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'created_at')->textInput(['maxlength' => true])->label(Yii::t('app', 'created_at')); ?>

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
