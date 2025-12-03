<?php
echo "Here";exit;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\cdr\CdrModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\cdr\models\CdrSearch */
/* @var $form yii\widgets\ActiveForm */
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
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'caller_id_number', ['options'=>['class'=>'col-xs-12 col-md-6']])->textInput(['maxlength'=>true, 'placeholder'=>CdrModule::t('cdr', "caller_id_number")]); ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'start_epoch',
                                    ['options'=>['class'=>'col-xs-12 col-md-6']])->textInput(['class'=>'form-control drp datepicker', 'readonly'=>true, 'autocomplete'=>'off', 'placeholder'=>date('Y-m-d')]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'end_epoch',
                                    ['options'=>['class'=>'col-xs-12 col-md-6']])->textInput(['class'=>'form-control drp datepicker', 'readonly'=>true, 'autocomplete'=>'off', 'placeholder'=>date('Y-m-d')]); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'answer_epoch',
                                    ['options'=>['class'=>'col-xs-12 col-md-6']])->textInput(['class'=>'form-control drp datepicker', 'readonly'=>true, 'autocomplete'=>'off', 'placeholder'=>date('Y-m-d')]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">

                                <?= $form->field(
                                    $model,
                                    'callstatus',
                                    ['options'=>['class'=>'col-xs-12 col-md-6']]
                                )->dropDownList(
                                    [
                                        'failed'=>'failed',
                                        'completed'=>'completed',
                                    ],
                                    ['prompt'=>CdrModule::t('cdr', "all")]
                                ); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'dialed_number', ['options'=>['class'=>'col-xs-12 col-md-6']])->textInput(['maxlength'=>true, 'placeholder'=>CdrModule::t('cdr', "dialed_number")]); ?>

                            </div>
                        </div>
                        <!--<div class="col s6">
                            <div class="input-field col s12">
                                <? /*= $form->field(
                                    $model,
                                    'call_type',
                                    ['options'=>['class'=>'col-xs-12 col-md-6']]
                                )->dropDownList(
                                    [
                                        'onnet'=>'onnet',
                                        'offnet'=>'offnet',
                                    ],
                                    ['prompt'=>CdrModule::t('cdr', "all")]
                                ); */ ?>

                            </div>
                        </div>-->
                    </div>
                    <div class="row">
                        <div class="input-field col s6">

                            <div class="select-wrapper">
                                <?= $form->field($model, 'isfile', ['options'=>['class'=>'']])->dropDownList(['Yes'=>'Yes', 'No'=>'No'], ['prompt'=>Yii::t('app', '-- Select --')]); ?>
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






