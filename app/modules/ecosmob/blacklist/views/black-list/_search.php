<?php

use app\modules\ecosmob\blacklist\BlackListModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\blacklist\models\BlackListSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="black-list-search" id="black-list-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'black-list-search-form',
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
                        <div class="col s12 m6 l4">
                            <?= $form->field($model,
                                'bl_number')->textInput(['placeholder' => ($model->getAttributeLabel('bl_number'))])->label(BlackListModule::t('bl', 'bl_number')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'bl_type', ['options' => [
                                'class' => 'input-field'
                            ]])->dropDownList([
                                'IN' => BlackListModule::t('bl', 'in'),
                                'OUT' => BlackListModule::t('bl', 'out'),
                                'BOTH' => BlackListModule::t('bl', 'both'),
                            ], ['prompt' => BlackListModule::t('bl', 'select_type')])->label(BlackListModule::t('bl', 'bl_type')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model,
                                'bl_reason')->textInput(['placeholder' => ($model->getAttributeLabel('bl_reason'))])->label(BlackListModule::t('bl',
                                'bl_reason')); ?>
                        </div>
                    
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(BlackListModule::t('bl',
                                    'search'), [
                                    'class' => 'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(BlackListModule::t('bl',
                                    'reset'), [
                                    'index',
                                    'page' => Yii::$app->session->get('page')
                                ], ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
