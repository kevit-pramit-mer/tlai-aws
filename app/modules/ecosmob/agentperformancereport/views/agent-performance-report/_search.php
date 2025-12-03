<?php

use app\modules\ecosmob\admin\models\AdminMaster;
use app\modules\ecosmob\agentperformancereport\AgentPerformanceReportModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingAgents;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\queue\models\QueueMaster;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\agentperformancereport\models\AgentPerformanceReportSearch */
/* @var $form yii\widgets\ActiveForm */


$arr = ['agent', 'supervisor'];

$campaignList = [];
$agentsList = [];
$supervisorCamp = CampaignMappingUser::find()
    //->where(['supervisor_id' => Yii::$app->user->id])
    ->all();
foreach ($supervisorCamp as $supervisorCamps) {
    $campaignList[] = $supervisorCamps['campaign_id'];
}

$agentsCamp = CampaignMappingAgents::find()
    ->where(['campaign_id' => $campaignList])
    ->all();

foreach ($agentsCamp as $agentsCamps) {
    $agentsList[] = $agentsCamps['agent_id'];
}

$agentName = ArrayHelper::map(AdminMaster::find()->where(['adm_is_admin' => $arr])->andWhere(['adm_id' => $agentsList])->all(), 'adm_id', function ($model) {
    return $model['adm_firstname'] . ' ' . $model['adm_lastname'];
});

$campaignList = Campaign::find()->where(['cmp_id' => $campaignList])->andWhere(['cmp_status' => 'Active'])->all();

$campaign = ArrayHelper::map($campaignList, 'cmp_id', 'cmp_name');

$queue = ArrayHelper::map(QueueMaster::find()->select(['qm_id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->where(['qm_status' => '1'])->all(), 'qm_id', 'qm_name');

?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="agent-performance-report-search" id="agent-performance-report-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'agent-performance-report-search-form',
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
                            <?= $form->field($model, 'from', ['options' => ['class' => 'input-field ']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(AgentPerformanceReportModule::t('agentperformancereport', 'from_date')) ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'to', ['options' => ['class' => 'input-field ']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(AgentPerformanceReportModule::t('agentperformancereport', 'to_date')) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'agent_id', ['options' => ['class' => 'input-field']])->dropDownList($agentName, ['prompt' => AgentPerformanceReportModule::t('agentperformancereport', 'prompt_agent_name')])->label(AgentPerformanceReportModule::t('agentperformancereport', 'agent_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'camp_name', ['options' => ['class' => 'input-field']])->dropDownList($campaign, ['prompt' => AgentPerformanceReportModule::t('agentperformancereport', 'prompt_camp')])->label(AgentPerformanceReportModule::t('agentperformancereport', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'queue', ['options' => ['class' => 'input-field']])->dropDownList($queue, ['prompt' => AgentPerformanceReportModule::t('agentperformancereport', 'prompt_queue')])->label(AgentPerformanceReportModule::t('agentperformancereport', 'queue')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(AgentPerformanceReportModule::t('agentperformancereport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(AgentPerformanceReportModule::t('agentperformancereport', 'reset'), ['index', 'page' =>
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
