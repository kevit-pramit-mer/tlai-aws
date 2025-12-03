<?php

/* @var $importModel app\modules\ecosmob\whitelist\models\WhiteList */

use app\modules\ecosmob\whitelist\WhiteListModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\whitelist\assets\WhiteListAsset;

WhiteListAsset::register($this);
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= WhiteListModule::t('wl', 'import') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="white-list-search" id="white-list-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'whitelist-import-form',
                        'action' => Yii::$app->urlManager->createUrl(['whitelist/white-list/import']),
                        'options' => [
                            'class' => '',
                            'autocomplete' => 'off',
                            'enctype' => 'multipart/form-data',
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field',
                            ],
                        ],
                    ]); ?>

                    <div class="row pb-3 mt-3">
                        <div class="col s12 m6">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span><?= WhiteListModule::t('wl', 'file') ?></span>
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

                        <div class="input-field col s12 m6">
                            <?php echo Html::submitButton(WhiteListModule::t('wl',
                                'import'),
                                [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                ]); ?>
                            <?php echo Html::a(WhiteListModule::t('wl',
                                'download_sample_file'),
                                ['download-sample-file'],
                                [
                                    'data-pjax' => 0,
                                    'class' => 'btn waves-effect waves-light bg-gray-200 ml-1',
                                ]); ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
