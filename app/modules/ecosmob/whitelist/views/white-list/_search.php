<?php

use app\modules\ecosmob\whitelist\WhiteListModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\whitelist\models\WhiteListSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="white-list-search" id="white-list-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'white-list-search-form',
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
                            <?= $form->field($model, 'wl_number')->textInput( [ 'placeholder' => ($model->getAttributeLabel('wl_number')) ] ) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <?= Html::submitButton(WhiteListModule::t('wl', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(WhiteListModule::t('wl', 'reset'), [
                                    'index',
                                    'page' =>
                                        Yii::$app->session->get('page')
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
