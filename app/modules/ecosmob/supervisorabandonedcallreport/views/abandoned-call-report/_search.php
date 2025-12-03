<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\queue\models\QueueMaster;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\supervisorabandonedcallreport\models\QueueAbandonedCallsSearch */
/* @var $form yii\widgets\ActiveForm */

$queue = ArrayHelper::map(QueueMaster::find()->select(['qm_id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->where(['qm_status' => '1'])->all(), 'qm_name', 'qm_name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="queue-abandoned-calls-search" id="queue-abandoned-calls-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'queue-abandoned-calls-search-form',
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
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'queue_name', ['options' => ['class' => '']])->dropDownList($queue, ['prompt' => Yii::t('app', 'prompt_queue')])->label(Yii::t('app', 'queue_name')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'queue_number', ['options'=>['class'=>'input-field']])->label(Yii::t('app', 'queue_number'))->textInput( [ 'placeholder' => Yii::t('app', 'callee_id') ] )->label(Yii::t('app', 'callee_id')); ?>
                        </div>
                    
                        <div class="col s12 m6 l4 calender-icon">
                                <?= $form->field($model, 'start_time', ['options'=>['class'=>'input-field']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'autocomplete' => 'off'])->label(Yii::t('app', 'from_date')) ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                                <?= $form->field($model, 'end_time', ['options'=>['class'=>'input-field']])->textInput(['class' => 'form-control to-date-time-range', 'readonly'
                                => true, 'autocomplete' => 'off'])->label(Yii::t('app', 'to_date')) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field ">
                                <?= Html::submitButton(Yii::t('app', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(Yii::t('app', 'reset'), ['index', 'page' =>
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
