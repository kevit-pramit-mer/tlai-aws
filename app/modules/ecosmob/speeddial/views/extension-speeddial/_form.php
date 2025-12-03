<?php

use app\modules\ecosmob\speeddial\SpeeddialModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\speeddial\models\ExtensionSpeeddial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12 speeddial-form-data">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field'
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="extension-speeddial-form" id="extension-speeddial-form">
                    <!--        <div class="row">
                        <div class="col s6">
                            <div class="input-field ">
                                <? /*= $form->field($model,
                                    'es_extension')
                                    ->textInput(['maxlength' => true])
                                    ->label($model->getAttributeLabel('es_extension')); */ ?>
                            </div>
                        </div>
                    </div>-->
                    <div class="col s12 m6 p-0 margin-top-15">
                        <!--<div class="input-field col s6">
                            <i class="material-icons prefix">filter_0</i>
                            <input id="icon_prefix" type="text" class="validate">
                            <label for="icon_prefix">First Name</label>
                        </div>-->
                        <table>
                            <tbody>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_0', ['template' => '<i class="material-icons prefix">filter_0</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_0')])
                                        ->label($model->getAttributeLabel('es_0')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_1', ['template' => '<i class="material-icons prefix">filter_1</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_1')])
                                        ->label($model->getAttributeLabel('es_1')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_2', ['template' => '<i class="material-icons prefix">filter_2</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_2')])
                                        ->label($model->getAttributeLabel('es_2')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_3', ['template' => '<i class="material-icons prefix">filter_3</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_3')])
                                        ->label($model->getAttributeLabel('es_3')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_4', ['template' => '<i class="material-icons prefix">filter_4</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_4')])
                                        ->label($model->getAttributeLabel('es_4')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_5', ['template' => '<i class="material-icons prefix">filter_5</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_5')])
                                        ->label($model->getAttributeLabel('es_5')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_6', ['template' => '<i class="material-icons prefix">filter_6</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_6')])
                                        ->label($model->getAttributeLabel('es_6')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_7', ['template' => '<i class="material-icons prefix">filter_7</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_7')])
                                        ->label($model->getAttributeLabel('es_7')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_8', ['template' => '<i class="material-icons prefix">filter_8</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_8')])
                                        ->label($model->getAttributeLabel('es_8')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><?= $form->field($model, 'es_9', ['template' => '<i class="material-icons prefix">filter_9</i>{input}{label}'])
                                        ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('es_9')])
                                        ->label($model->getAttributeLabel('es_9')); ?>
                                </td>
                            </tr>
                            <!--
                            <tr>
                                <td class=""><i class="material-icons prefix">filter_1</i></td>
                                <td class=""><? /*= $form->field($model,
                                        'es_1')
                                        ->textInput(['maxlength' => true])
                                        ->label($model->getAttributeLabel('es_1')); */ ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><i class="material-icons prefix">filter_2</i></td>
                                <td class=""><? /*= $form->field($model,
                                        'es_2')
                                        ->textInput(['maxlength' => true])
                                        ->label($model->getAttributeLabel('es_2')); */ ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><i class="material-icons prefix">filter_3</i></td>
                                <td class=""><? /*= $form->field($model,
                                        'es_3')
                                        ->textInput(['maxlength' => true])
                                        ->label($model->getAttributeLabel('es_3')); */ ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><i class="material-icons prefix">filter_4</i></td>
                                <td class=""><? /*= $form->field($model,
                                        'es_4')
                                        ->textInput(['maxlength' => true])
                                        ->label($model->getAttributeLabel('es_4')); */ ?>
                                </td>
                            </tr>
                            <tr>

                                <td class=""><i class="material-icons prefix">filter_5</i></td>
                                <td class=""><? /*= $form->field($model,
                                        'es_5')
                                        ->textInput(['maxlength' => true])
                                        ->label($model->getAttributeLabel('es_5')); */ ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><i class="material-icons prefix">filter_6</i></td>
                                <td class=""><? /*= $form->field($model,
                                        'es_6')
                                        ->textInput(['maxlength' => true])
                                        ->label($model->getAttributeLabel('es_6')); */ ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><i class="material-icons prefix">filter_7</i></td>
                                <td class=""><? /*= $form->field($model,
                                        'es_7')
                                        ->textInput(['maxlength' => true])
                                        ->label($model->getAttributeLabel('es_7')); */ ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><i class="material-icons prefix">filter_8</i></td>
                                <td class=""><? /*= $form->field($model,
                                        'es_8')
                                        ->textInput(['maxlength' => true])
                                        ->label($model->getAttributeLabel('es_8')); */ ?>
                                </td>
                            </tr>
                            <tr>
                                <td class=""><i class="material-icons prefix">filter_9</i></td>
                                <td class=""><? /*= $form->field($model,
                                        'es_9')
                                        ->textInput(['maxlength' => true])
                                        ->label($model->getAttributeLabel('es_9')); */ ?>
                                </td>
                            </tr>
-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?php /*= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app',
                    'Update'), [
                    'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                        'btn waves-effect waves-light cyan accent-8'
                ])*/ ?>
                <?= Html::a(SpeeddialModule::t('app', 'cancel'),
                    ['/extension/extension/dashboard'],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
        
                <?php if (!$model->isNewRecord) { ?>
                    <?= Html::submitButton(SpeeddialModule::t('app', 'apply'), [
                        'class' => 'btn waves-effect waves-light amber darken-4',
                        'name' => 'apply',
                        'value' => 'update'
                    ]) ?>
                <?php } ?>
            </div>        
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
