<?php

use app\modules\ecosmob\parkinglot\models\ParkingLotSearch;
use app\modules\ecosmob\parkinglot\ParkingLotModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ParkingLotSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="parking-lot-search" id="parking-lot-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'parking-lot-search-form',
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
                        <div class="col s12 m6">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('name')), 'class' => 'input-field']) ?>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'park_ext')->textInput(['maxlength' => true, 'onkeypress' => "return isNumberKey(event)", 'placeholder' => ($model->getAttributeLabel('park_ext')), 'class' => 'input-field']) ?>
                        </div>
                        <div class="col s12 mt-1 ">
                            <div class="input-field">
                            <?= Html::submitButton(ParkingLotModule::t('parkinglot', 'search'),
                                [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4',
                                ]) ?>
                            <?= Html::a(ParkingLotModule::t('parkinglot', 'reset'),
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
