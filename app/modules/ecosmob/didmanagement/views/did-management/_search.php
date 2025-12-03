<?php

use app\modules\ecosmob\didmanagement\DidManagementModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\didmanagement\models\DidManagementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="didmaster-search" id="didmaster-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'didmanagement-search-form',
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
                        <div class="col s12 m6">
                            <div class="input-field mt-1">
                            <?= $form->field( $model,
                                'did_number',
                                [
                                     'options' => [ 'class' => 'input-field' ],
                                    'inputOptions' => [
                                        'class'     => 'form-control',
                                    ],
                                ] )->textInput([ 'placeholder' => ($model->getAttributeLabel('did_number'))])
                                ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field mt-0">
                                <?= $form->field( $model, 'did_status', [ 'options' => [ 'class' => 'col-xl-12 col-md-6' ] ] )
                                         ->dropDownList( [ '1' => Yii::t('app','active'), '0' => Yii::t('app','inactive') ],
                                             [ 'prompt' => DidManagementModule::t('did','select') ] )
                                         ->label( $model->getAttributeLabel( 'did_status' ) );
                                ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(DidManagementModule::t('did', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light amber darken-4',
                                    ]) ?>
                                <?= Html::a(DidManagementModule::t('did', 'reset'),
                                    [
                                        'index',
                                        'page' =>
                                            Yii::$app->session->get('page'),
                                    ],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </li>
</ul>
