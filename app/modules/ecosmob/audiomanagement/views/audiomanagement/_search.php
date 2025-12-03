<?php

use app\modules\ecosmob\audiomanagement\AudioManagementModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\audiomanagement\models\AudioManagement */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="prompt-list-search"
                     id="prompt-list-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'audio-management-search-form',
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
                                <?= $form->field($model, 'af_name',
                                    ['inputOptions' => [
                                        'class' => 'form-control',
                                        'tabindex' => '1']
                                    ])->textInput([ 'placeholder' => ($model->getAttributeLabel('af_name'))])
                                ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'af_type', ['options' => ['class' => 'input-field']])
                                ->dropDownList(['Prompt' => AudioManagementModule::t('am', 'prompt'), 'MOH' =>
                                    AudioManagementModule::t('am', 'MOH'), 'Recording' =>
                                    AudioManagementModule::t('am', 'recording')],
                                    ['prompt' => Yii::t('app', 'select')])
                                ->label($model->getAttributeLabel('af_type')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'af_language', ['options' => ['class' => 'input-field']])
                                ->dropDownList(['English' => Yii::t('app', 'english'), 'Spanish' => Yii::t('app', 'spanish'),], ['prompt' =>
                                    Yii::t('app', 'select')])
                                ->label($model->getAttributeLabel('af_language')); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(AudioManagementModule::t('am', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(AudioManagementModule::t('am', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
