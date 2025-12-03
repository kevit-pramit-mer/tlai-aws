<?php

use app\modules\ecosmob\extension\extensionModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extension\models\ExtensionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header">
            <?= Yii::t('app', 'search') ?>
        </div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="extension-search" id="extension-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'extension-search-form',
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
                            <?= $form->field($model, 'em_extension_name')
                                ->textInput(['maxlength' => true, 'placeholder' => extensionModule::t('app', "extension_name")
                                ])
                                ->label(extensionModule::t('app', 'extension_name')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'em_extension_number')
                                ->textInput(['maxlength' => true, 'onkeypress' => "return isNumberKey(event)", 'placeholder' => extensionModule::t('app', "extension_number")
                                ])
                                ->label(extensionModule::t('app', 'extension_number')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?php echo $form->field($model, 'em_status', ['options' => ['class' => 'input-field']])
                                ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label(extensionModule::t('app', 'status')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 's_id', ['options' => [
                                'class' => 'input-field',
                            ]])->dropDownList(
                                $model->shiftList,
                                ['prompt' => Yii::t('app', 'all')])
                                ->label(extensionModule::t('app', 'shift')); ?>
                        </div>
                    
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(Yii::t('app', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(Yii::t('app', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>    
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
