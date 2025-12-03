<?php

use app\modules\ecosmob\jobs\JobsModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\jobs\assets\JobsAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\jobs\models\Job */
/* @var $form yii\widgets\ActiveForm */
/* @var $timeZoneList */
/* @var $campaignList */
/* @var $timeConditionList */

JobsAsset::register($this);
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">
                <div class="job-form"
                     id="job-form">

                    <?php $form = ActiveForm::begin([
                        'class' => 'row',
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field'
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'job_name', [
                                    'inputOptions' => [
                                       // 'autofocus' => 'autofocus',
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => true])->label(JobsModule::t('jobs', 'job_name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?php
                                    $timeZoneList = ArrayHelper::map($timeZoneList, 'tz_id', 'tz_zone');
                                    echo $form->field($model, 'timezone_id', ['options' => ['class' => '']])->dropDownList($timeZoneList, ['prompt' => JobsModule::t('jobs', 'select_tz')])->label(JobsModule::t('jobs', 'tz'));
                                    ?>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?php
                                    $campaignList = ArrayHelper::map($campaignList, 'cmp_id', 'cmp_name');
                                    echo $form->field($model, 'campaign_id', ['options' => ['class' => '']])->dropDownList($campaignList, ['prompt' => JobsModule::t('jobs', 'select_camp')])->label(JobsModule::t('jobs', 'camp'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?php
                                    $timeConditionList = ArrayHelper::map($timeConditionList, 'tc_id', 'tc_name');
                                    echo $form->field($model, 'time_id', ['options' => ['class' => '']])->dropDownList($timeConditionList, ['prompt' => JobsModule::t('jobs', 'select_time_cond')])->label(JobsModule::t('jobs', 'time_cond'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!--  <div class="col s6">
                            <div class="input-field input-right">
                                <? /*= $form->field($model, 'concurrent_calls_limit')->textInput(['maxlength'=>true])->label(JobsModule::t('jobs', 'conc_call_limit')); */ ?>

                            </div>
                        </div>-->
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field input-right">
                                <?= $form->field($model, 'answer_timeout')->textInput(['maxlength' => true])->label(JobsModule::t('jobs', 'ans_timeout')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field input-right">
                                <?= $form->field($model, 'ring_timeout')->textInput(['maxlength' => true])->label(JobsModule::t('jobs', 'ring_timeout')); ?>

                            </div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field input-right">
                                <?php /*= $form->field($model, 'retry_on_no_answer')->textInput(['maxlength' => true])->label(JobsModule::t('jobs', 'retry_on_no_ans')); */ ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field input-right">
                                <?php /*= $form->field($model, 'retry_on_busy')->textInput(['maxlength' => true])->label(JobsModule::t('jobs', 'retry_on_busy')); */ ?>

                            </div>
                        </div>
                    </div>-->
                    <div class="row">
                        <?php
                        if (!$model->isNewRecord) {
                            if ($model->job_status == '0') { ?>
                                <div class="col s12 m6">
                                    <div class="input-field">
                                        <div class="select-wrapper">
                                            <?= $form->field($model, 'activation_status', ['options' => ['class' => '']])
                                                ->dropDownList(['1' => Yii::t('app', 'active'), '0' => Yii::t('app', 'inactive'),],
                                                    ['prompt' => JobsModule::t('jobs', 'select_status')])->label(JobsModule::t('jobs', 'status')); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else { ?>

                            <div class="col s12 m6">
                                <div class="input-field">
                                    <div class="select-wrapper">
                                        <?= $form->field($model, 'activation_status', ['options' => ['class' => '']])
                                            ->dropDownList(['1' => Yii::t('app', 'active'), '0' => Yii::t('app',
                                                'inactive'),], ['prompt' => JobsModule::t('jobs', 'select_status')])->label(JobsModule::t('jobs', 'status')); ?>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                    <div class="hseparator"></div>

                    <div class="col s12 center">
                        <div class="input-field mrg-btn">
                            <?= Html::submitButton($model->isNewRecord ? JobsModule::t('jobs', 'create') : JobsModule::t('jobs', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) { ?>
                                <?= Html::submitButton(JobsModule::t('jobs', 'apply'), [
                                    'class' => 'btn waves-effect waves-light amber darken-4 ml-2',
                                    'name' => 'apply',
                                    'value' => 'update']) ?>
                            <?php } ?>
                            <?= Html::a(JobsModule::t('jobs', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                                ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>
</div>

