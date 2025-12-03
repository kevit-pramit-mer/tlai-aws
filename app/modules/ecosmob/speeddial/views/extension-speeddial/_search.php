<?php

use app\modules\ecosmob\speeddial\SpeeddialModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\speeddial\models\ExtensionSpeeddialSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i>Search</div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="extension-speeddial-search"
                     id="extension-speeddial-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'extension-speeddial-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field col s12'
                            ],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'es_extension') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'es_*0') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'es_*1') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'es_*2') ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(SpeeddialModule::t('app', 'search'), [
                                'class' =>
                                    'btn waves-effect waves-light amber darken-4'
                            ]) ?>
                            <?= Html::a(SpeeddialModule::t('app', 'reset'), [
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
