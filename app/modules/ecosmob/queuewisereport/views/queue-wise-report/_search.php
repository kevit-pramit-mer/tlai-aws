<?php

use app\modules\ecosmob\queuewisereport\QueueWiseReportModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\queue\models\QueueMaster;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\queuewisereport\models\QueueWiseReportSearch */
/* @var $form yii\widgets\ActiveForm */

$queue = ArrayHelper::map(QueueMaster::find()->select(['qm_id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS name")])->asArray()->all(), 'qm_id', 'name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="queue-wise-report-search" id="queue-wise-report-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'queue-wise-report-search-form',
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
                            <?= $form->field($model, 'queue_started', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(QueueWiseReportModule::t('queuewisereport', 'From Date')) ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'queue_ended', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(QueueWiseReportModule::t('queuewisereport', 'To Date')) ?>
                        </div>
                        <div class="col s12 m6 l4 input-field">
                            <?php echo $form->field($model, 'qm_id', ['options' => ['class' => '', 'id' => 'select_week_off_internal']])->dropDownList($queue, ['prompt' => QueueWiseReportModule::t('queuewisereport', 'select_queue')])->label(QueueWiseReportModule::t('queuewisereport', 'queue')); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(QueueWiseReportModule::t('queuewisereport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(QueueWiseReportModule::t('queuewisereport', 'reset'), ['index', 'page' =>
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
