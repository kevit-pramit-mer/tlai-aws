<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\activecalls\models\ActiveCallsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
    <li>
        <div class="collapsible-header">Search</div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="active-calls-search" id="active-calls-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'active-calls-search-form',
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
                            <div class="input-field">
                                    <?= $form->field($model, 'active_id') ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                    <?= $form->field($model, 'caller_id') ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                    <?= $form->field($model, 'did') ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                    <?= $form->field($model, 'destination_number') ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                    <?= $form->field($model, 'uuid') ?>

                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' =>
                                'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(Yii::t('app', 'Reset'), ['index', 'page' =>
                                Yii::$app->session->get('page')],
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