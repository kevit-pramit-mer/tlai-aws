<?php

use app\modules\ecosmob\fraudcall\FraudCallModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\fraudcall\models\FraudCallDetectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?php echo FraudCallModule::t('fcd', 'search'); ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="fraud-call-detection-search"
                     id="fraud-call-detection-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'fraud-call-detection-search-form',
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
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'fcd_rule_name')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('fcd_rule_name'))]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'fcd_destination_prefix')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('fcd_destination_prefix'))]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'blocked_by', ['options' => ['class' => '']])
                                    ->dropDownList(['user' => FraudCallModule::t('fcd', 'user'), 'destination' => FraudCallModule::t('fcd', 'destination')],
                                        ['prompt' => Yii::t('app', 'select')])
                                    ->label($model->getAttributeLabel('blocked_by')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(FraudCallModule::t('fcd', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light amber darken-4',
                                    ]) ?>
                                <?= Html::a(FraudCallModule::t('fcd', 'reset'),
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
