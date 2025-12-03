<?php

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?php echo Yii::t('app', 'search'); ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="trunk-search" id="trunk-search">

                    <?php $form = ActiveForm::begin(
                        [
                            'id' => 'trunk-search-form',
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
                        ]
                    ); ?>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'trunk_name')->textInput([ 'placeholder' => ($model->getAttributeLabel('trunk_name'))]); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'trunk_ip')->textInput([ 'placeholder' => ($model->getAttributeLabel('trunk_ip'))]); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field(
                                $model,
                                'trunk_ip_type',
                                ['options' => ['class' => 'input-field']]
                            )->dropDownList(
                                [
                                    'PRIVATE' => CarriertrunkModule::t(
                                        'carriertrunk',
                                        'private'
                                    ),
                                    'PUBLIC' => CarriertrunkModule::t(
                                        'carriertrunk',
                                        'public'
                                    ),
                                ],
                                [
                                    'prompt' => CarriertrunkModule::t(
                                        'carriertrunk',
                                        'select'
                                    ),
                                ]
                            ); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field(
                                $model,
                                'trunk_status',
                                ['options' => ['class' => 'input-field']]
                            )->dropDownList(
                                [
                                    'Y' => CarriertrunkModule::t(
                                        'carriertrunk',
                                        'active'
                                    ),
                                    'N' => CarriertrunkModule::t(
                                        'carriertrunk',
                                        'inactive'
                                    ),
                                ],
                                [
                                    'prompt' => CarriertrunkModule::t(
                                        'carriertrunk',
                                        'select'
                                    ),
                                ]
                            ); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(
                                    CarriertrunkModule::t(
                                        'carriertrunk',
                                        'search'
                                    ),
                                    [
                                        'class' => 'btn waves-effect waves-light amber darken-4',
                                        'id' => 'search_refresh',
                                    ]
                                ) ?>
                                <?= Html::a(
                                    CarriertrunkModule::t(
                                        'carriertrunk',
                                        'reset'
                                    ),
                                    [
                                        'index',
                                        'page' => Yii::$app->session->get('page'),
                                    ],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']
                                ) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>

