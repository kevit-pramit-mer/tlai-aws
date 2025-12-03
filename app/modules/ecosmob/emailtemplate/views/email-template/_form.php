<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\emailtemplate\EmailTemplateModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\emailtemplate\models\EmailTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-template-form"
     id="email-template-form">
    
    <?php $form = ActiveForm::begin( [
        'id' => 'email-template-active-form',
    ] ); ?>

    <div class="row">
        <div class="form-group">
            <?= $form->field( $model, 'key', [ 'options' => [ 'class' => 'col-xs-12 col-md-6' ] ] )->textInput( [
                'maxlength'   => TRUE,
                'placeholder' => EmailTemplateModule::t( 'emailtemplate',
                    "key" ),
                'disabled'    => ! $model->isNewRecord,
            ] )->label( EmailTemplateModule::t( 'emailtemplate',
                    'key' )
                        . ' <span><i class="icon fa fa-question-circle fa-lg" data-toggle= "popover"  data-trigger = "hover" data-content = "'
                        . EmailTemplateModule::t( 'emailtemplate',
                    'key_label' ) . '" ></i></span>' ); ?>
            
            <?= $form->field( $model, 'subject', [ 'options' => [ 'class' => 'col-xs-12 col-md-6' ] ] )->textInput( [
                'maxlength'   => TRUE,
                'placeholder' => EmailTemplateModule::t( 'emailtemplate',
                    "subject" ),
            ] )->label( EmailTemplateModule::t( 'emailtemplate',
                    'subject' )
                        . ' <span><i class="icon fa fa-question-circle fa-lg" data-toggle= "popover"  data-trigger = "hover" data-content = "'
                        . EmailTemplateModule::t( 'emailtemplate',
                    'subject_label' ) . '" ></i></span>' ); ?>

        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <?= $form->field( $model, 'content', [ 'options' => [ 'class' => 'col-xs-12 col-md-12' ] ] )->widget( TinyMce::className(),
                [
                    'options'       => [ 'rows' => 18 ],
                    'language'      => 'en',
                    'clientOptions' => [
                        'plugins' => [
                            "advlist autolink lists link charmap print preview anchor",
                            "searchreplace visualblocks code fullscreen",
                            "insertdatetime media table contextmenu paste",
                        ],
                        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                    ],
                ] )->label( EmailTemplateModule::t( 'emailtemplate',
                    'content' )
                            . ' <span><i class="icon fa fa-question-circle fa-lg" data-toggle= "popover"  data-trigger = "hover" data-content = "'
                            . EmailTemplateModule::t( 'emailtemplate',
                    'content_label' ) . '" ></i></span>' ); ?>

        </div>
    </div>
    <div class="hseparator"></div>
    <div class="row">
        <div class="form-group col-sm-offset-5 col-md-offset-5 col-xs-offset-2">
            <?= Html::submitButton( $model->isNewRecord ? EmailTemplateModule::t( 'emailtemplate',
                    'create' ) : EmailTemplateModule::t( 'emailtemplate',
                    'update' ),
                [
                    'class' => 'btn btn-primary btn-round-left',
                ] ) ?>
            <?php if ( ! $model->isNewRecord ) { ?>
                <?= Html::submitButton( EmailTemplateModule::t( 'emailtemplate',
                    'apply' ),
                    [
                        'class' => 'btn btn-success',
                        'name'  => 'apply',
                        'value' => 'update',
                    ] ) ?>
            <?php } ?>
            <?= Html::a( EmailTemplateModule::t( 'emailtemplate',
                    'cancel' ),
                [ 'index', 'page' => Yii::$app->session->get( 'page' ) ],
                [ 'class' => 'btn btn-danger btn-round-right' ] ) ?>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
