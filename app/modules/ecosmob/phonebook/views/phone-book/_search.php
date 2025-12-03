<?php

use app\modules\ecosmob\phonebook\PhoneBookModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\phonebook\models\PhoneBookSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="phonebook-search"
                     id="phonebook-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'phonebook-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field'
                            ],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field ">
                                <?= $form->field($model,
                                    'ph_display_name')->textInput(['maxlength' => true])->label(PhoneBookModule::t('app',
                                    'ph_display_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field ">
                                <?= $form->field($model,
                                    'ph_phone_number')->textInput(['maxlength' => true, 'onkeypress' => 'return isNumberKey(event);',])->label(PhoneBookModule::t('app',
                                    'ph_phone_number')); ?>

                            </div>
                        </div>
                        <div class="col s12 mt-1">
                                <?= Html::submitButton(PhoneBookModule::t('app', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(PhoneBookModule::t('app', 'reset'), [
                                    'index',
                                    'page' =>
                                        Yii::$app->session->get('page')
                                ],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
