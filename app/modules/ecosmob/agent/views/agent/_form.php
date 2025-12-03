<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\agent\AgentModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\agent\models\Agent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin( [
            'class'       => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field col s12',
                ],
            ],
        ] ); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="agent-form" id="agent-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                <?= $form->field( $model, 'name' )
                                         ->textInput( [ 'maxlength' => TRUE ] )
                                         ->label( $model->getAttributeLabel('name') ); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                <?= $form->field( $model, 'contact' )
                                         ->textInput( [ 'maxlength' => TRUE, 'onkeypress' => 'return isNumberKey(event);' ] )
                                         ->label( $model->getAttributeLabel('contact') ); ?>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                <?= $form->field( $model, 'wrap_up_time' )
                                         ->textInput( [ 'maxlength' => TRUE ] )
                                         ->label( $model->getAttributeLabel('wrap_up_time') ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a( Yii::t( 'app', 'cancel' ),
                    [ 'index', 'page' => Yii::$app->session->get( 'page' ) ],
                    [ 'class' => 'btn waves-effect waves-light bg-gray-200' ] ) ?>
                <?= Html::submitButton( $model->isNewRecord ? Yii::t( 'app', 'create' ) : AgentModule::t( 'agent', 'update' ),
                    [
                        'class' => $model->isNewRecord
                            ? 'btn waves-effect waves-light amber darken-4'
                            :
                            'btn waves-effect waves-light cyan accent-8',
                    ] ) ?>
                <?php if ( ! $model->isNewRecord ) { ?>
                    <?= Html::submitButton( Yii::t( 'app', 'apply' ),
                        [
                            'class' => 'btn waves-effect waves-light amber darken-4',
                            'name'  => 'apply',
                            'value' => 'update',
                        ] ) ?>
                <?php } ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
