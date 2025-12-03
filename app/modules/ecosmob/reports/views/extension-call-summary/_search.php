<?php

use app\modules\ecosmob\extension\models\Extension;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\reports\ReportsModule;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\reports\models\ExtensionCallSummarySearch */
/* @var $form yii\widgets\ActiveForm */

$extensions = Extension::find()->asArray()->all();

?>
<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i>Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="cdr-search"
                     id="cdr-search">

                    <?php $form=ActiveForm::begin([
                        'id'=>'cdr-search-form',
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
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'start_epoch',
                                    ['options'=>['class'=>'col-xs-12 col-md-6']])->textInput(['class'=>'form-control drp datepicker', 'readonly'=>true, 'autocomplete'=>'off', 'placeholder'=>date('Y-m-d')]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'end_epoch',
                                    ['options'=>['class'=>'col-xs-12 col-md-6']])->textInput(['class'=>'form-control drp datepicker', 'readonly'=>true, 'autocomplete'=>'off', 'placeholder'=>date('Y-m-d')]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field col s12">

                                <?= $form->field(
                                    $model,
                                    'extension',
                                    ['options'=>['class'=>'col-xs-12 col-md-6']]
                                )->dropDownList(
                                    ArrayHelper::map($extensions, 'em_extension_number', 'em_extension_name'),
                                    ['prompt'=>'All']
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(Yii::t('app', 'Search'), [
                                'class'=>
                                    'btn waves-effect waves-light amber darken-4'
                            ]) ?>
                            <?= Html::a(Yii::t('app', 'Reset'), [
                                'index',
                                'page'=>
                                    Yii::$app->session->get('page')
                            ],
                                ['class'=>'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
    </li>
</ul>
<style>
    .select-month input {
        display: block !important;
        /*color: #E24E42 !important;*/
        font-size: 17px !important;
    }

    .select-year input {
        display: block !important;
        font-size: 17px !important;
        /*color: #E24E42 !important;*/
    }

    .datepicker-controls .select-month input {
        width: 92px;
    }
</style>






