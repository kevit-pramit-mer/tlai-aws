<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\extensionforwarding\ExtensionForwardingModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionforwarding\models\ExtensionForwardingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i>Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="extension-forwarding-search"
                     id="extension-forwarding-search">

                    <?php $form=ActiveForm::begin([
                        'id'=>'extension-forwarding-search-form',
                        'action'=>['index'],
                        'method'=>'get',
                        'options'=>[
                            'data-pjax'=>1
                        ],
                        'fieldConfig'=>[
                            'options'=>[
                                'class'=>'input-field col s12'
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ef_extension') ?>

                            </div>
                        </div>
                        <!--<div class="col s6">
                            <div class="input-field col s12">
                                <?/*= $form->field($model, 'ef_unconditional_type') */?>

                            </div>
                        </div>-->
                    </div>
                    <!--<div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?/*= $form->field($model, 'ef_unconditional_num') */?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?/*= $form->field($model, 'ef_holiday_type') */?>

                            </div>
                        </div>
                    </div>-->
                    <?php // echo $form->field($model, 'ef_holiday') ?>

                    <?php // echo $form->field($model, 'ef_holiday_num') ?>

                    <?php // echo $form->field($model, 'ef_weekoff_type') ?>

                    <?php // echo $form->field($model, 'ef_weekoff') ?>

                    <?php // echo $form->field($model, 'ef_weekoff_num') ?>

                    <?php // echo $form->field($model, 'ef_shift_type') ?>

                    <?php // echo $form->field($model, 'ef_shift') ?>

                    <?php // echo $form->field($model, 'ef_shift_num') ?>

                    <?php // echo $form->field($model, 'ef_universal_type') ?>

                    <?php // echo $form->field($model, 'ef_universal_num') ?>

                    <?php // echo $form->field($model, 'ef_no_answer_type') ?>

                    <?php // echo $form->field($model, 'ef_no_answer_num') ?>

                    <?php // echo $form->field($model, 'ef_busy_type') ?>

                    <?php // echo $form->field($model, 'ef_busy_num') ?>

                    <?php // echo $form->field($model, 'ef_unavailable_type') ?>

                    <?php // echo $form->field($model, 'ef_unavailable_num') ?>


                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(ExtensionForwardingModule::t('extensionforwarding', 'search'), ['class'=>
                                'btn waves-effect waves-light amber darken-4']) ?>
                            <?= Html::a(ExtensionForwardingModule::t('extensionforwarding', 'reset'), ['index', 'page'=>
                                Yii::$app->session->get('page')],
                                ['class'=>'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                        </div>
                    </div>


                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </li>
</ul>
