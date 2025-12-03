<?php

use app\modules\ecosmob\timecondition\TimeConditionModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\ecosmob\timecondition\models\TimeCondition */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <?php $form = ActiveForm::begin(
                    [
                        'id' => 'time-condition-search-form',
                        'class' => 'row',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1,
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field'
                            ],
                        ],
                    ]); ?>
                <div class="row">
                    <div class="col s6">
                        <?= $form->field($model, 'tc_name')->textInput([])->label(TimeConditionModule::t('tc', 'tc_name')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field center">
                        <?= Html::submitButton(
                            TimeConditionModule::t('tc', 'search'),
                            [
                                'class' => 'btn waves-effect waves-light amber darken-4',
                            ]) ?>

                        <?= Html::a(
                            TimeConditionModule::t('tc', 'reset'),
                            [
                                'index',
                                'page' => Yii::$app->session->get('page'),
                            ],
                            ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>

                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </li>
</ul>
