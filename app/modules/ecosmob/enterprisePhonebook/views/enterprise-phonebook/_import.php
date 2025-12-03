<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule;
use app\modules\ecosmob\enterprisePhonebook\assets\EnterprisePhonebookAsset;

/* @var $this yii\web\View */
/* @var $importModel \app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebook */
/* @var $form yii\widgets\ActiveForm */

EnterprisePhonebookAsset::register($this);
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
                <div class="enterprise-phonebook-form" id="enterprise-phonebook-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span><?=
                                        EnterprisePhonebookModule::t('app', 'file') ?>
                                    </span>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                    <?= Html::a(EnterprisePhonebookModule::t('app', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200 mb-3']) ?>
                    <?php echo Html::a(EnterprisePhonebookModule::t('app', 'download_sample_file'),
                        ['download-sample-file'],
                        ['data-pjax' => 0, 'class' => 'btn waves-effect waves-light amber darken-4 mb-3']); ?>
                    <?= Html::submitButton(EnterprisePhonebookModule::t('app', 'import'),
                        ['class' => 'btn waves-effect waves-light amber darken-4 mb-3']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
