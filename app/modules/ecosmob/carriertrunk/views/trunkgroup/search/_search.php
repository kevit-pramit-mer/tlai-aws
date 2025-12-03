<?php

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\carriertrunk\models\TrunkGroupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?=Yii::t('app','search')?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="trunk-group-search" id="trunk-group-search">

                    <?php $form = ActiveForm::begin(
                        [
                            'id'          => 'trunkgroup-search-form',
                            'action'      => [ 'index' ],
                            'method'      => 'get',
                            'options'     => [
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
                        <div class="col s12 m6">
                            <?= $form->field( $model, 'trunk_grp_name' )->textInput([ 'placeholder' => ($model->getAttributeLabel('trunk_grp_name'))]); ?>
                        </div>

                        <div class="col s12 m6">
                                <?= $form->field(
                                    $model,
                                    'trunk_grp_status',
                                    [ 'options' => [ 'class' => '' ] ]
                                )->dropDownList(
                                    [
                                        '1' => CarriertrunkModule::t(
                                            'carriertrunk',
                                            'active'
                                        ),
                                        '0' => CarriertrunkModule::t(
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
                                CarriertrunkModule::t( 'carriertrunk', 'search' ),
                                [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                    'id'    => 'search_referesh',
                                ]
                            ) ?>
                            <?= Html::a(
                                CarriertrunkModule::t( 'carriertrunk', 'reset' ),
                                [
                                    'index',
                                    'page' => Yii::$app->session->get( 'page' ),
                                ],
                                [ 'class' => 'btn waves-effect waves-light bg-gray-200 ml-1' ]
                            ) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
