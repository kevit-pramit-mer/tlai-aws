<?php

use app\modules\ecosmob\dispositionType\DispositionTypeModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\dispositionType\models\DispositionTypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header">Search</div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="disposition-type-search"
                     id="disposition-type-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'disposition-type-search-form',
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
                            <div class="select-wrapper">
                                <?= $form->field($model, 'ds_type')->textInput(['placeholder' => ($model->getAttributeLabel('ds_type'))]) ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(DispositionTypeModule::t('dispositionType', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(DispositionTypeModule::t('dispositionType', 'reset'), ['index', 'page' =>
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
