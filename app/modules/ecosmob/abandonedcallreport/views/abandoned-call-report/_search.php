<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\queue\models\QueueMaster;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\abandonedcallreport\models\QueueAbandonedCallsSearch */
/* @var $form yii\widgets\ActiveForm */

$queue = ArrayHelper::map(QueueMaster::find()->select(['qm_id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->where(['qm_status' => '1'])->all(), 'qm_name', 'qm_name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" style="margin-top: -10px">
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="queue-abandoned-calls-search"
                     id="queue-abandoned-calls-search">

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
                            <?php /*= $form->field($model, 'queue_name', ['options'=>['class'=>'col-xs-12 col-md-3 input-field']]) */?>
                            <?= $form->field($model, 'queue_name', ['options' => ['class' => '']])->dropDownList($queue, ['prompt' => Yii::t('app', 'prompt_queue')]); ?>
                        </div>
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'queue_number', ['options'=>['class'=>'col-xs-12 col-md-3 input-field']])->textInput(['placeholder' => $model->getAttributeLabel('queue_number')]) ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'start_time', ['options'=>['class'=>'input-field']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'autocomplete' => 'off']) ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'end_time', ['options'=>['class'=>'input-field']])->textInput(['class' => 'form-control to-date-time-range', 'readonly'
                                => true, 'autocomplete' => 'off']) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
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
