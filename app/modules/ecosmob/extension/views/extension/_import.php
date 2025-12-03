<?php

use app\modules\ecosmob\extension\assets\ExtensionAsset;
use app\modules\ecosmob\extension\extensionModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $importModel app\modules\ecosmob\didmanagement\models\DidManagement */
/* @var $form yii\widgets\ActiveForm */

ExtensionAsset::register($this);
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field col s12',
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="didmaster-form" id="didmaster-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="file-field input-field set-ext-import" >
                                <div class="btn">
                                    <span><?= extensionModule::t('app', 'importFileUpload') ?></span>
                                    <?= $form->field($importModel,
                                        'importFileUpload',
                                        [
                                            'options' => [
                                                'class' => '',
                                            ],
                                        ])->fileInput(['accept' => '.csv'])->label(FALSE) ?>
                                </div>&nbsp;<span style="color: red;">*</span>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <?= extensionModule::t('app', 'download_sample_csv_file') ?>
                            <div class="input-field col s12 no-padding">
                                <?php echo Html::a(extensionModule::t('app', 'download_sample_basic_file'),
                                    ['download-basic-file'],
                                    [
                                        'data-pjax' => 0,
                                        'class' => 'btn waves-effect waves-light amber darken-4',
                                    ]); ?>

                                <?php echo Html::a(extensionModule::t('app', 'download_advanced_basic_file'),
                                    ['download-advanced-file'],
                                    [
                                        'data-pjax' => 0,
                                        'class' => 'btn waves-effect waves-light amber darken-4 ml-2',
                                        'title' => extensionModule::t('app', 'audio_video_codec_tooltip'),
                                    ]); ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="input-field col s12">
                            <b><?= Yii::t('app', 'Please select below mandatory fields') ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <?= $form->field($importModel, 'em_shift_id', ['options' => [
                                'class' => '',
                            ]])->dropDownList(
                                $importModel->shiftList,
                                ['prompt' => extensionModule::t('app', 'select_shift')])
                                ->label(extensionModule::t('app', 'shift')); ?>
                        </div>
                        <div class="input-field col s12 m6">
                            <?= $form->field($importModel, 'em_group_id', ['options' => [
                                'class' => '',
                            ]])->dropDownList(
                                $importModel->groupList,
                                ['prompt' => extensionModule::t('app', 'select_group')])
                                ->label(extensionModule::t('app', 'group')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <!--                        <div class="input-field col s12 m6">-->
                        <!--                            --><?php //= $form->field($importModel, 'em_plan_id', ['options' => [
                        //                                'class' => '',
                        //                            ]])->dropDownList(
                        //                                $importModel->planList,
                        //                                ['prompt' => extensionModule::t('app', 'select_plan')])
                        //                                ->label(extensionModule::t('app', 'plan')); ?>
                        <!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(extensionModule::t('app', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton(extensionModule::t('app', 'import'),
                    [
                        'class' => 'btn waves-effect waves-light amber darken-4',
                    ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
