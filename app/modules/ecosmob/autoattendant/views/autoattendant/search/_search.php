<?php

use app\modules\ecosmob\autoattendant\AutoAttendantModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\autoattendant\models\AutoAttendantMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="ring-group-search" id="ring-group-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'auto-attendant-master-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1,
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field',
                            ],
                        ],
                    ]); ?>

                    <div class="row align-items-center">
                        <div class="col s12 m6 l4">
                            <div class="mt-0">
                                <?= $form->field($model,
                                    'aam_name',
                                    ['options' => ['class' => 'input-field']])->textInput([
                                    'maxlength' => 20, 'placeholder' => ($model->getAttributeLabel('aam_name'))
                                ]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="mt-0">
                                <?= $form->field($model,
                                    'aam_ext',
                                    ['options' => ['class' => 'input-field']])->textInput([
                                    'type' => 'number'
                                ])->label(AutoAttendantModule::t(
                                    'autoattendant',
                                    'aam_extension'
                                ))->textInput(['placeholder' => ($model->getAttributeLabel('aam_extension'))]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'aam_status',
                                    [
                                        'options' => ['class' => '',],
                                    ])->dropDownList([
                                    '1' => AutoAttendantModule::t('autoattendant', 'active'),
                                    '0' => AutoAttendantModule::t('autoattendant', 'inactive'),
                                ],
                                    ['prompt' => AutoAttendantModule::t('autoattendant', 'select')]) ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field mt-2">
                                <?= $form->field($model,
                                    'aam_language',
                                    [
                                        'options' => ['class' => '',],
                                    ])->dropDownList([
                                    'en' => Yii::t('app', 'english'),
                                    'es' => Yii::t('app', 'spanish')
                                ],
                                    ['prompt' => AutoAttendantModule::t('autoattendant', 'select')]) ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <div class="form-group">
                                    <?= Html::submitButton(AutoAttendantModule::t('autoattendant', 'search'),
                                        [
                                            'class' => 'btn waves-effect waves-light amber darken-4',
                                        ]) ?>
                                    <?= Html::a(AutoAttendantModule::t('autoattendant', 'reset'),
                                        [
                                            'index',
                                            'page' => Yii::$app->session->get('page'),
                                        ],
                                        ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                                </div>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
