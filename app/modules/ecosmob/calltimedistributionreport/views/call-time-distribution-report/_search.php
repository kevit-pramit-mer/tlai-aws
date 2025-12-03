<?php

use app\modules\ecosmob\calltimedistributionreport\CallTimeDistributionReportModule;
use app\modules\ecosmob\queue\models\QueueMaster;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\calltimedistributionreport\models\CallTimeDistributionReportSearch;

/* @var $this yii\web\View */
/* @var $model CallTimeDistributionReportSearch */
/* @var $form yii\widgets\ActiveForm */

$queue = ArrayHelper::map(QueueMaster::find()->select(['qm_id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS name")])->asArray()->all(), 'qm_id', 'name');

?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="call-time-distribution-report-search" id="call-time-distribution-report-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'call-time-distribution-report-search-form',
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

                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'queue_started', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('from'))])->label(CallTimeDistributionReportModule::t('calltimedistributionreport', 'from_date')) ?>
                        </div>

                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'queue_ended', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control to-date-time-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('to'))])->label(CallTimeDistributionReportModule::t('calltimedistributionreport', 'to_date')) ?>
                        </div>

                        <div class="col s12 m6 l4 theme-dropdown-selection">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'qm_id', ['options' => ['class' => 'input-field']])->dropDownList($queue, ['multiple' => 'multiple'])->label(CallTimeDistributionReportModule::t('calltimedistributionreport', 'queue')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(CallTimeDistributionReportModule::t('calltimedistributionreport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(CallTimeDistributionReportModule::t('calltimedistributionreport', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>

<script>
    $(document).ready(function(){
        setInterval(function () {
            if($("#calltimedistributionreportsearch-qm_id").val() == '') {
                $('.field-calltimedistributionreportsearch-qm_id .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').attr('placeholder', 'Select Queue');
                $('.field-calltimedistributionreportsearch-qm_id .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').css('width', 'auto');
                $('.field-calltimedistributionreportsearch-qm_id .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').css('margin', 0);
            }
        }, 100);
    });
    $(document).on("pjax:success", function (e) {
        setInterval(function () {
            if($("#calltimedistributionreportsearch-qm_id").val() == '') {
                $('.select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').attr('placeholder', 'Select Queue');
                $('.select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').css('width', 'auto');
                $('.select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').css('margin', 0);
            }
        }, 100);
    });
</script>