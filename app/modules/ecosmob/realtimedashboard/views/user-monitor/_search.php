<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\realtimedashboard\RealTimeDashboardModule;
use app\modules\ecosmob\auth\models\AdminMaster;
use yii\db\Expression;
use app\modules\ecosmob\queue\models\QueueMaster;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\realtimedashboard\models\CampaignPerformance */
/* @var $form yii\widgets\ActiveForm */

$user = ArrayHelper::map(AdminMaster::find()->select(['adm_id', new Expression("CONCAT(adm_firstname, ' ', adm_lastname) AS name")])->where(['adm_is_admin' => 'agent', 'adm_status' => '1'])->asArray()->all(), 'adm_id', 'name');
$campaign = ArrayHelper::map(Campaign::find()->select(['cmp_id', 'cmp_name'])->where(['cmp_status' => 'Active'])->all(), 'cmp_name', 'cmp_name');
$queue = ArrayHelper::map(QueueMaster::find()->select([ 'qm_id', new Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->where(['qm_status' => '1'])->asArray()->all(), 'qm_name', 'qm_name');

?>

<ul class="collapsible collapsible-accordion mb-0" data-collapsible="accordion" style="margin-top: -10px">
    <li class="active">
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <?php $form = ActiveForm::begin([
                    'id' => 'agent-monitor-report-search-form',
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

                <div class="agent-monitor-report-search" id="agent-monitor-report-search">
                    <div class="row align-left">
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper" >
                                <?= $form->field($model, 'user_id', ['options' => ['class' => 'input-field']])->dropDownList($user, ['prompt' => 'Select Agent Name'])->label($model->getAttributeLabel('user_id')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper" >
                                <?= $form->field($model, 'cmp_name', ['options' => ['class' => 'input-field']])->dropDownList($campaign, ['prompt' => 'Select Campaign Name'])->label($model->getAttributeLabel('cmp_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper" >
                                <?= $form->field($model, 'queue', ['options' => ['class' => 'input-field']])->dropDownList($queue, ['prompt' => 'Select Queue Name'])->label($model->getAttributeLabel('queue')); ?>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="input-field">
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
