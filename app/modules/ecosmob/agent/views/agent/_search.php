<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\agent\models\AgentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
    <li>
        <div class="collapsible-header"><?=Yii::t('app','search')?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="agent-search" id="agent-search">
                    <?php $form = ActiveForm::begin( [
                        'id'          => 'agent-search-form',
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
                    ] ); ?>

                    <div class="row">
                        <div class="col s12 m6 input-field">
                            <?= $form->field( $model, 'name' ) ?>
                        </div>
                        <div class="col s12 m6 input-field">
                            <?= $form->field( $model, 'system' ) ?>
                        </div>
                        <div class="col s12 m6 input-field">
                            <?= $form->field( $model, 'uuid' ) ?>
                        </div>
                        <div class="col s12 m6 input-field">
                            <?= $form->field( $model, 'type' ) ?>
                        </div>
                        <div class="col s12 m6 input-field">
                            <?= $form->field( $model, 'status' ) ?>
                        </div>
                        <div class="col s12 m6 input-field">
                            <?= $form->field( $model, 'state' ) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton( Yii::t('app', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light green darken-1',
                                    ] ) ?>
                                <?= Html::a( Yii::t('app', 'reset'),
                                    [
                                        'index',
                                        'page' =>
                                            Yii::$app->session->get( 'page' ),
                                    ],
                                    [ 'class' => 'btn waves-effect waves-light bg-gray-200 ml-1' ] ) ?>
                            </div>
                        </div>
                    </div>    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
