<?php

use app\modules\ecosmob\audiomanagement\AudioManagementModule;
use app\modules\ecosmob\extension\models\Extension;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\audiomanagement\assets\AudioAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\audiomanagement\models\AudioManagement */
/* @var $form yii\widgets\ActiveForm */

AudioAsset::register($this);
?>
<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'class' => 'input-field'
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>   
            <div class="card-content">
                <div class="audio-management-form"
                     id="audio-management-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'af_name',
                                ['inputOptions' => [
                                    'class' => 'mg-t6',
                                ]
                                ])
                                ->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('af_name'))])
                                ->label($model->getAttributeLabel('af_name')); ?>
                        </div>
                        <div class="col s12 m6">
                                <?= $form->field($model, 'af_description')->textArea([
                                    'rows' => '2',
                                    'maxlength' => TRUE,
                                    'class' => 'materialize-textarea mg-t6',
                                    'placeholder' => ($model->getAttributeLabel('af_description'))
                                ])
                                    ->label($model->getAttributeLabel('af_description')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                                <?= $form->field($model, 'af_type', ['options' => ['class' => 'input-field', 'id' => 'af_type']])
                                    ->dropDownList(['Prompt' => AudioManagementModule::t('am', 'prompt'), 'MOH' =>
                                        AudioManagementModule::t('am', 'MOH'), 'Recording' => AudioManagementModule::t('am', 'recording')],
                                        ['prompt' => AudioManagementModule::t('am', 'select'),])
                                    ->label($model->getAttributeLabel('af_type')); ?>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field af_language">
                                <?= $form->field($model, 'af_language', ['options' => ['class' => 'input-field']])
                                    ->dropDownList(['English' => Yii::t('app', 'english'), 'Spanish' => Yii::t('app', 'spanish'),], ['prompt' => AudioManagementModule::t('am', 'select')])
                                    ->label($model->getAttributeLabel('af_language')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row set-padding-mobile">
                        <div class="col s12 m6 pt-2 mt-2 pb-2 file-upload-section">
                            <div class="file-field input-field" id="inputfile_">
                                <div class="btn">
                                    <span><?= AudioManagementModule::t('am', 'file') ?></span>
                                    <?= $form->field($model, 'af_file',
                                        ['options' => ['class' => '']]
                                    )->fileInput(['accept' => '.mp3, .wav'])->label(false); ?>
                                </div>&nbsp;<span style="color: red;">*</span>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text"
                                           value="<?php if (!$model->isNewRecord) {
                                               $data=explode('/', $model->af_file);
                                               echo array_reverse($data)[0];
                                           } ?>">
                                </div>
                                <span class="new badge red ml-0 mt-1"
                                      data-badge-caption="<?= AudioManagementModule::t('am', 'only_mp3_wav_file_supported') ?>">
                                        </span>
                            </div>

                        </div>
                        <div class="col s12 m6">
                            <div class="file-field input-field mt-2 mb-3" id="ext_" style="display: none">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'af_extension', ['options' => ['class' => '', 'id' => 'af_type']])
                                        ->dropDownList(Extension::getExtensionNumbersList(),
                                            ['prompt' => AudioManagementModule::t('am', 'select')])
                                        ->label($model->getAttributeLabel('af_extension')); ?>
                                </div>
                            </div>
                        </div>
                        <?php if (!$model->isNewRecord) {
                            $extension = pathinfo($model->af_file, PATHINFO_EXTENSION);

                            if ($model->af_type == 'Recording') {
                                $sourcePath = Url::to('@web' . '/media/recordings/' . $model->af_file);
                            } else {
                                $sourcePath = Url::to('@web' . '/media/audio-libraries/' . $model->af_file);
                            }
                            if ($extension == "mp3") {
                                $type = 'audio/mpeg';
                            } else {
                                $type = 'audio/wav';
                            }
                            ?>
                            <div class="col s12">
                                <audio controls style="position: relative;">
                                    <source src="<?= $sourcePath ?>" type="<?= $type ?>">
                                </audio>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <div class="input-field">
                    <?= Html::a(Yii::t('app', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'create') :
                        Yii::t('app', 'update'),
                        [
                            'id' => 'submitbtn',
                            'class' => $model->isNewRecord
                                ? 'btn waves-effect waves-light amber darken-4'
                                :
                                'btn waves-effect waves-light amber darken-4'
                        ]) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<input type="hidden" id="isNewRecord" value=" <?= $model->isNewRecord ?>">

<script>
    $(document).ready(function () {
        checkType();
    });
    $(document).on('change', '#audiomanagement-af_type', function () {
        checkType();
    });

    function checkType() {
        if ($('#audiomanagement-af_type').val() == 'Recording') {
            if ($('#isNewRecord').val() == 1) {
                $('#submitbtn').text('<?= AudioManagementModule::t('am', 'call_and_create'); ?>');
            }
            $('#inputfile_').slideUp();
            $('#ext_').slideDown();
        } else {
            if ($('#isNewRecord').val() == 1) {
                $('#submitbtn').text('<?= Yii::t('app', 'create'); ?>');
            }
            $('#inputfile_').slideDown();
            $('#ext_').slideUp();
        }
    }
</script>
