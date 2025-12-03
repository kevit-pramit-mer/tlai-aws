<?php

use app\modules\ecosmob\jobs\JobsModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\jobs\models\JobSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $campaignList */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="job-search"
                     id="job-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'job-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field'
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s12 m6 l3">
                            <?= $form->field($model, 'job_name') ?>
                        </div>
                        <div class="col s12 m6 l3 select-dropdown-selection">
                            <!-- div class="input-field col s12">
                                <?php //$form->field($model, 'concurrent_calls_limit') ?>

                            </div -->
                            <div class="select-wrapper">
                                <?= $form->field($model, 'activation_status', ['options' => ['class' => '']])
                                    ->dropDownList(['1' => Yii::t('app', 'active'), '0' => Yii::t('app', 'inactive'),],
                                        ['prompt' => JobsModule::t('jobs', 'select_status')])->label(JobsModule::t('jobs', 'status')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l3">
                            <?= $form->field($model, 'answer_timeout')->textInput(['maxlength' => true])->label(JobsModule::t('jobs', 'ans_timeout')); ?>
                        </div>
                        <div class="col s12 m6 l3">
                            <div class="select-wrapper">
                                <?php
                                echo $form->field($model, 'camp_id', ['options' => ['class' => '']])->dropDownList($campaignList, ['prompt' => JobsModule::t('jobs', 'select_camp')])->label(JobsModule::t('jobs', 'camp'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <div class="select-wrapper">
                                    <php  /* $form->field($model, 'activation_status', ['options'=>['class'=>'']])
                                        ->dropDownList(['1'=> Yii::t('app','active'), '0'=>Yii::t('app','inactive'),],
                                            ['prompt'=>JobsModule::t('jobs', 'select_status')])->label(JobsModule::t('jobs', 'status')); */ ?>
                                </div>
                            </div>
                        </div>

                    </div -->
                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(JobsModule::t('jobs', 'search'), ['class' =>
                                'btn waves-effect waves-light amber darken-4']) ?>
                            <?= Html::a(JobsModule::t('jobs', 'reset'), ['index', 'page' =>
                                Yii::$app->session->get('page')],
                                ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
