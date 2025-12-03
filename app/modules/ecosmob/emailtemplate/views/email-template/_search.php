<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\emailtemplate\EmailTemplateModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\emailtemplate\models\EmailTemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-template-search"
     id="email-template-search">
    
    <?php $form = ActiveForm::begin( [
        'id'      => 'email-template-search-form',
        'action'  => [ 'index' ],
        'method'  => 'get',
        'options' => [
            'data-pjax' => 1,
        ],
    ] ); ?>
    <div class="card-accordions">
        <div id="accordion" role="tablist" aria-multiselectable="true">
            <div class="card">
                <div class="card-header card-custom">
                    <a class="card-title" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        <h6 class="mb-0"><span class="fa fa-search"></span>
                            <?= 'Search' ?>
                        </h6></a>
                </div>
                <div id="collapseOne" class="collapse">
                    <div class="card-block">
                        <div class="row">
                            <div class="form-group">
                                <?= $form->field( $model, 'key', [ 'options' => [ 'class' => 'col-xs-12 col-md-6' ] ] )->textInput( [
                                    'maxlength'   => TRUE,
                                    'placeholder' => EmailTemplateModule::t( 'emailtemplate',
                                        'key' ),
                                ] ) ?>
                                <?= $form->field( $model, 'subject', [ 'options' => [ 'class' => 'col-xs-12 col-md-6' ] ] )->textInput( [
                                    'maxlength'   => TRUE,
                                    'placeholder' => EmailTemplateModule::t( 'emailtemplate',
                                        'subject' ),
                                ] ) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-offset-5 col-md-offset-5 col-xs-offset-2">
                                <?= Html::submitButton( EmailTemplateModule::t( 'emailtemplate',
                    'search' ),
                                    [
                                        'class' =>
                                            'btn btn-primary btn-round-left',
                                    ] ) ?>
                                <?= Html::a( EmailTemplateModule::t( 'emailtemplate',
                    'reset' ),
                                    [
                                        'index',
                                        'page' =>
                                            Yii::$app->session->get( 'page' ),
                                    ],
                                    [ 'class' => 'btn btn-danger btn-round-right' ] ) ?>
                            </div>
                        </div>
                        
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
