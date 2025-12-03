<?php

/* @var $importModel app\modules\ecosmob\blacklist\models\BlackList */

use app\modules\ecosmob\blacklist\BlackListModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\blacklist\assets\BlackListAsset;

BlackListAsset::register($this);
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= BlackListModule::t('bl', 'import') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <?php $form = ActiveForm::begin([
                    'id' => 'blacklist-import-form',
                    'action' => Yii::$app->urlManager->createUrl(['blacklist/black-list/import']),
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
                <div class="black-list-search" id="black-list-search">
                    <div class="row pb-3 mt-3">
                        <div class="col s12 m6">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span><?= BlackListModule::t('bl', 'file') ?></span>
                                    <?= $form->field($importModel,
                                        'importFileUpload',
                                        [
                                            'options' => ['class' => '',],
                                        ])->fileInput(['accept' => '.csv'])->label(false) ?>
                                </div>&nbsp;<span style="color: red;">*</span>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 pb-3 d-flex align-items-center gap-10">
                            <?php echo Html::submitButton(BlackListModule::t('bl',
                                'import'),
                                [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                ]); ?>
                            <?php echo Html::a(BlackListModule::t('bl',
                                'download_sample_file'),
                                ['download-sample-file'],
                                [
                                    'data-pjax' => 0,
                                    'class' => 'btn waves-effect waves-light bg-gray-200',
                            ]); ?>
                        </div>        
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </li>
</ul>
