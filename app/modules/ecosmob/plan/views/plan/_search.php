<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\plan\PlanModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\plan\models\PlanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?php echo Yii::t( 'app', 'search'); ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="plan-search"
                     id="plan-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'plan-search-form',
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
                        <div class="col s6 mb-2">
                            <?= $form->field($model, 'pl_name') ?>
                        </div>

                        <div class="row">
                            <div class="input-field center">
                                <?= Html::submitButton(PlanModule::t('pl', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(PlanModule::t('pl', 'reset'), [
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
