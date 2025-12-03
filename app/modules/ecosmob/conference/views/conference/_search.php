<?php

use app\modules\ecosmob\conference\ConferenceModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\conference\models\ConferenceMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header">
            <?= Yii::t('app', 'search') ?>
        </div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="conference-master-search"
                     id="conference-master-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'conference-master-search-form',
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

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'cm_name')->textInput(['maxlength' => TRUE, 'placeholder' => ConferenceModule::t('conference', "cm_name")]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'cm_extension')->textInput(['maxlength' => TRUE, 'onkeypress' => "return isNumberKey(event)", 'placeholder' => ConferenceModule::t('conference', "cm_extension")]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'cm_mod_code')->textInput(['maxlength' => TRUE, 'onkeypress' => "return isNumberKey(event)", 'placeholder' => ConferenceModule::t('conference', "cm_mod_code")]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'cm_part_code')->textInput(['maxlength' => TRUE, 'onkeypress' => "return isNumberKey(event)", 'placeholder' => ConferenceModule::t('conference', "cm_part_code")]) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(ConferenceModule::t('conference', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light amber darken-4',
                                    ]) ?>
                                <?= Html::a(ConferenceModule::t('conference', 'reset'),
                                    [
                                        'index',
                                        'page' =>
                                            Yii::$app->session->get('page'),
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
