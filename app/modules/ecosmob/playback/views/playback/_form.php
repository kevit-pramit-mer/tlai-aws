<?php

use app\modules\ecosmob\playback\PlaybackModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\playback\models\Playback */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">

                <div class="playback-form"
                     id="playback-form">

                    <?php $form = ActiveForm::begin([
                        'class' => 'row',
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field',
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'pb_name')->textInput(['maxlength' => TRUE])->label($model->getAttributeLabel('pb_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'pb_language', ['options' => ['class' => '']])->dropDownList([
                                    'English' => Yii::t('app', 'english'),
                                    'Spanish' => Yii::t('app', 'spanish'),
                                ], ['prompt' => PlaybackModule::t('pb', 'select')])->label($model->getAttributeLabel('pb_language')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="file-field input-field">
                                <div class="col s12">
                                    <span class="new badge red"
                                            data-badge-caption="<?= PlaybackModule::t('pb', 'file_validation'); ?>"></span>
                                </div>
                                <div class="btn">
                                    <span><?= PlaybackModule::t('pb', 'file') ?></span>
                                    <?= $form->field($model,
                                        'pb_file',
                                        [
                                            'options' => ['class' => '',],
                                        ])->fileInput(['accept' => '.mp3, .wav'])->label(false) ?>
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="hseparator"></div>
                    <div class="row">
                        <div class="col s12 center">
                            <div class="input-field">
                                <?= Html::submitButton($model->isNewRecord ? PlaybackModule::t('pb', 'create') : PlaybackModule::t('pb', 'update'),
                                    [
                                        'class' => $model->isNewRecord
                                            ? 'btn waves-effect waves-light amber darken-4'
                                            :
                                            'btn waves-effect waves-light cyan accent-8',
                                    ]) ?>
                                <?= Html::a(PlaybackModule::t('pb', 'cancel'),
                                    ['index', 'page' => Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                            </div>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
<style>
    .help-block {
        text-transform: capitalize;
        position: absolute;
        top: 50px;
    }
    .file-field {
        margin-top: 37px;
    }
    @media  screen and (max-width: 1366px) {
        .help-block {
            text-transform: capitalize;
            position: absolute;
            top: 62px;
        }
        .file-field {
            margin-top: 37px;
        }
    }
</style>
