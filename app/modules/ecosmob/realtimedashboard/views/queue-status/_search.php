<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\realtimedashboard\RealTimeDashboardModule;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\realtimedashboard\models\QueueStatusReport */
/* @var $form yii\widgets\ActiveForm */

$queue = ArrayHelper::map(QueueMaster::find()->select([ new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->where(['qm_status' => '1'])->all(), 'qm_name', 'qm_name');

?>

<ul class="collapsible collapsible-accordion mb-0" data-collapsible="accordion" style="margin-top: -10px">
    <li class="active">
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <?php $form = ActiveForm::begin([
                    'id' => 'queue-status-report-search-form',
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

                <div class="queue-status-report-search" id="queue-status-report-search">
                    <div class="row left-align">
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'queue', ['options' => ['class' => 'input-field']])->dropDownList($queue, ['prompt' => 'Select Queue Name'])->label($model->getAttributeLabel('queue')); ?>
                            </div>
                        </div>
                        <div class="col s12 ">
                            <div class="input-field left-align">
                                <?= Html::submitButton(RealTimeDashboardModule::t('realtimedashboard', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(RealTimeDashboardModule::t('realtimedashboard', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
