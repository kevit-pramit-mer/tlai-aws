<?php

use app\modules\ecosmob\agents\AgentsModule;
use app\modules\ecosmob\breaks\models\Breaks;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\extension\models\ExtensionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $totalCalls */
/* @var $totalTalkTimeMinute */
/* @var $query */
/* @var $breakReason */

/*$this->title=Yii::t('app', 'Extensions');*/
$data = BreakReasonMapping::find()->where(['user_id' => Yii::$app->user->identity->adm_id, 'break_status' => 'In'])->count();
$is_in = 0;
if ($data) {
    $is_in = 1;
} ?>

<div id="">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div id="card-stats">
                    <div class="row">
                        <div style="float:right;">
                            <?php echo Html::Button(AgentsModule::t('agents', 'break_in'), ['class' => 'btn btn-basic In', 'style' => ($is_in) ? 'display: none' : 'display:inline-block']) ?>
                            <?php echo Html::Button(AgentsModule::t('agents', 'break_out'), ['class' => 'btn btn-basic Out', 'style' => (!$is_in) ? 'display:none' : 'display:inline-block']) ?>
                        </div>
                    </div>
                    <!--        <div class="row">
                        <?php /*$form=ActiveForm::begin([
                            'class'=>'row',
                            'fieldConfig'=>[
                                'options'=>[
                                    'class'=>'input-field col s12'
                                ],
                            ],
                        ]); */ ?>
                        <div class="row">
                            <div class="col s6">
                                <div class="input-field col s12" id="campaign-cmp_type">
                                    <div class="select-wrapper">
                                        <? /*= $form->field($model, 'adm_firstname', ['options'=>['class'=>'']])->dropDownList($campaignListType, ['prompt'=>Yii::t('app', '-- Select Campaign1 --')])->label(Yii::t('app', 'Select Campaign1')); */ ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php /*ActiveForm::end(); */ ?>
                    </div>-->

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="card animate fadeLeft">
                                <div class="card-content cyan white-text">
                                    <p style="text-align: left;font-size: 20px;font-weight: bold;"><?php echo AgentsModule::t('agents', 'ttl_call') ?>
                                    </p>
                                    <h4 class="card-stats-number white-text"><i
                                                class="material-icons"
                                                style="float: left;font-size: 35px;">phone</i><span><?= $totalCalls; ?></span>
                                    </h4>

                                </div>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="card animate fadeLeft">
                                <div class="card-content cyan white-text">
                                    <p style="text-align: left;font-size: 20px;font-weight: bold;"><?php echo AgentsModule::t('agents', 'ttl_talk_call') ?>
                                    </p>
                                    <h4 class="card-stats-number white-text"><i
                                                class="material-icons"
                                                style="float: left;font-size: 35px;">access_time</i><span><?= $totalTalkTimeMinute; ?></span>
                                    </h4>
                                </div>

                            </div>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="card animate fadeLeft">
                                <div class="card-content cyan white-text">
                                    <p style="text-align: left;font-size: 20px;font-weight: bold;"><?php echo AgentsModule::t('agents', 'break_time') ?>
                                    </p>
                                    <h4 class="card-stats-number white-text"><i
                                                class="material-icons"
                                                style="float: left;font-size: 35px;">free_breakfast</i><span><?= $query; ?></span>
                                    </h4>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a>-->

<div id="modal1" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?php echo AgentsModule::t('agents', 'break_reason') ?></h5>
            <span aria-hidden="true" class="close">&times;</span>
        </div>
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'id' => 'submit-break-form',
            //'action'=>['supervisor/supervisor/submit-break-reason'],
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field col s12'
                ],
            ],
        ]); ?>
        <div class="modal-body">
            <div class="campaign-form" id="break-form">
                <div class="row pl-2 pr-2">
                    <div class="col s12">
                        <div class="input-field col s12" id="week_off_queue">
                            <div class="select-wrapper">
                                <?php
                                $breakList = Breaks::find()->select(['br_id', 'br_reason'])->all();
                                $breakList = ArrayHelper::map($breakList, 'br_id', 'br_reason');
                                echo $form->field($breakReason, 'break_reason', ['options' => ['class' => '']])->dropDownList($breakList, ['prompt' => AgentsModule::t('agents', 'prompt_break')])->label(AgentsModule::t('agents', 'select_break')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton(AgentsModule::t('agents', 'submit'), [
                    'class' => 'btn waves-effect waves-light amber darken-4',
                    'name' => 'apply',
                    'value' => 'update']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <style>

        .card-content.cyan.white-text {
            min-height: 145px !important;
        }

        label {
            font-size: 1rem;
            /*color: #4a4a4a;*/
        }

    </style>

    <script type="text/javascript">
        $(document).ready(function () {
            // submitBreakReason();
            //$('.modal').modal();
            $('.modal').modal()[0].M_Modal.options.dismissible = false;
        });
        $(document).on("click", ".In", function () {
            $('.modal').modal('open');
        });

        $(document).on("click", ".Out", function () {
            $('.Out').hide();
            $('.In').show();
            submitBreakReason('out');
        });

        function submitBreakReason(type) {
            var data = $('#submit-break-form').serializeArray();
            data.push({'name': 'type', 'value': type});

            $.ajax({
                async: false,
                data: data,
                type: 'POST',
                url: baseURL + "index.php?r=supervisor/supervisor/submit-break-reason",
                success: function (result) {
                    if (type == 'out') {
                        window.location.reload();
                    }
                }
            });
            $('.In').hide();
            $('.Out').show();
        }

        $(document).on("submit", "#submit-break-form", function (e) {
            e.preventDefault();
            submitBreakReason('in');
            document.getElementById("submit-break-form").reset();
            $('.modal').modal('close');
        });
        $(document).on("click", ".close", function () {
            $('.modal').modal('close');
        });
    </script>

