<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\services\ServicesModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\services\models\Services */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="services-form"
                     id="services-form">

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
                                <?= $form->field($model, 'ser_name', ['options' => ['class' => '']])->dropDownList(['EXTENSION' => 'EXTENSION', 'AUDIO TEXT' => 'AUDIO TEXT', 'QUEUE' => 'QUEUE', 'VOICEMAIL' => 'VOICEMAIL', 'RING GROUP' => 'RING GROUP', 'CONFERENCE' => 'CONFERENCE', 'EXTERNAL' => 'EXTERNAL',], ['prompt' => ServicesModule::t('services', 'select')])->label(ServicesModule::t('services', 'ser_name')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field col s12">
                            <?= Html::submitButton($model->isNewRecord ? ServicesModule::t('services', 'create') : ServicesModule::t('services', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) { ?>
                                <?= Html::submitButton(ServicesModule::t('services', 'apply'), [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                    'name' => 'apply',
                                    'value' => 'update']) ?>
                            <?php } ?>
                            <?= Html::a(ServicesModule::t('services', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                                ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
